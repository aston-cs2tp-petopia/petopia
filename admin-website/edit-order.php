<?php
session_start();
require_once('../php/connectdb.php');
$isAdmin = include('../php/isAdmin.php');
require_once('../admin-website/php/adminCheckRedirect.php');
require_once('../php/alerts.php'); // Include alerts.php for displaying messages

$orderId = $_GET['Orders_ID'] ?? null;

// Fetch the current order details before POST processing to get original quantities
$stmt = $db->prepare("SELECT * FROM orders WHERE Orders_ID = ?");
$stmt->execute([$orderId]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

$stmtDetails = $db->prepare("SELECT od.*, p.Name, p.Num_In_Stock FROM ordersdetails od JOIN product p ON od.Product_ID = p.Product_ID WHERE od.Orders_ID = ?");
$stmtDetails->execute([$orderId]);
$orderDetails = $stmtDetails->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_order'], $_POST['status'], $_POST['orderDetails'])) {
    // Check if the order status is "Cancelled" or "Returned"
    if ($order['Order_Status'] === 'Cancelled' || $order['Order_Status'] === 'Returned') {
        jsAlert('This order cannot be edited as it is already cancelled or returned.', false, 3000); // Display error message
    } else {
        $status = $_POST['status'];
        $submittedOrderDetails = $_POST['orderDetails']; 

        $db->beginTransaction();
        $totalAmount = 0;
        try {
            foreach ($submittedOrderDetails as $productId => $details) {
                $newQuantity = (int)$details['quantity'];
                $originalQuantity = 0;

                // Find original quantity from previously fetched order details
                foreach ($orderDetails as $detail) {
                    if ($detail['Product_ID'] == $productId) {
                        $originalQuantity = $detail['Quantity'];
                        break;
                    }
                }

                // Calculate stock adjustment
                $stockAdjustment = $newQuantity - $originalQuantity;

                $productQuery = $db->prepare("SELECT Num_In_Stock, Price FROM product WHERE Product_ID = ?");
                $productQuery->execute([$productId]);
                $product = $productQuery->fetch(PDO::FETCH_ASSOC);

                if ($product) {
                    $subtotal = $newQuantity * $product['Price'];
                    $totalAmount += $subtotal;

                    // Update ordersdetails table
                    $updateOrderDetail = $db->prepare("UPDATE ordersdetails SET Quantity = ?, Subtotal = ? WHERE Orders_ID = ? AND Product_ID = ?");
                    $updateOrderDetail->execute([$newQuantity, $subtotal, $orderId, $productId]);

                    // Adjust stock if the order status is cancelled or returned
                    if ($status === 'Cancelled' || $status === 'Returned') {
                        $newStock = $product['Num_In_Stock'] + $originalQuantity; // Add original quantity back to stock
                    } else {
                        // Adjust stock for other statuses
                        $newStock = $product['Num_In_Stock'] - $stockAdjustment;
                    }

                    $updateStock = $db->prepare("UPDATE product SET Num_In_Stock = ? WHERE Product_ID = ?");
                    $updateStock->execute([$newStock, $productId]);
                }
            }

            // Update the order's total amount and status
            $updateOrder = $db->prepare("UPDATE orders SET Total_Amount = ?, Order_Status = ? WHERE Orders_ID = ?");
            $updateOrder->execute([$totalAmount, $status, $orderId]);

            $db->commit();
            jsAlert('Order updated successfully.', true, 3000); // Display success message
        } catch (Exception $e) {
            $db->rollBack();
            jsAlert('Error updating order: ' . $e->getMessage(), false, 3000); // Display error message
        }

        // Refresh order details after updates
        $stmtDetails->execute([$orderId]);
        $orderDetails = $stmtDetails->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Order</title>
    <!-- Add CSS links -->
    <style>
        .product-detail {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<h2>Edit Order #<?php echo htmlspecialchars($orderId); ?></h2>
<form action="edit-order.php?Orders_ID=<?php echo htmlspecialchars($orderId); ?>" method="post">
    <label for="status">Order Status:</label>
    <select id="status" name="status">
        <option value="Processing" <?php if ($order['Order_Status'] == 'Processing') echo 'selected'; ?>>Processing</option>
        <option value="Shipped" <?php if ($order['Order_Status'] == 'Shipped') echo 'selected'; ?>>Shipped</option>
        <option value="Delivered" <?php if ($order['Order_Status'] == 'Delivered') echo 'selected'; ?>>Delivered</option>
        <option value="Cancelled" <?php if ($order['Order_Status'] == 'Cancelled') echo 'selected'; ?>>Cancelled</option>
        <option value="Returning" <?php if ($order['Order_Status'] == 'Returning') echo 'selected'; ?>>Returning</option>
        <option value="Returned" <?php if ($order['Order_Status'] == 'Returned') echo 'selected'; ?>>Returned</option>
    </select>
    <div>
        <h3>Order Details:</h3>
        <?php foreach ($orderDetails as $detail): ?>
        <div class="product-detail">
            <img src="../assets/ProductImages/ImageID_<?php echo $detail['Product_ID']; ?>.jpeg" alt="Product Image" style="width:50px;">
            <p>Product Name: <?php echo htmlspecialchars($detail['Name']); ?></p>
            <?php
            // Query to get the current stock for the product
            $stockQuery = $db->prepare("SELECT Num_In_Stock FROM product WHERE Product_ID = ?");
            $stockQuery->execute([$detail['Product_ID']]);
            $currentStock = $stockQuery->fetchColumn();
            ?>
            <p>Current Stock: <?php echo htmlspecialchars($currentStock); ?></p>
            <label for="quantity_<?php echo $detail['Product_ID']; ?>">Quantity:</label>
            <input type="number" id="quantity_<?php echo $detail['Product_ID']; ?>" name="orderDetails[<?php echo $detail['Product_ID']; ?>][quantity]" value="<?php echo $detail['Quantity']; ?>" min="0" max="<?php echo $currentStock; ?>">
        </div>
        <?php endforeach; ?>
    </div>

    <input type="submit" name="update_order" value="Update Order">
</form>
</body>
</html>
