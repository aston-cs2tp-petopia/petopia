<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Petopia</title>

        <!--[Google Fonts]-->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link
            href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,700;1,800&family=Work+Sans:wght@700;800&display=swap"
            rel="stylesheet">

        <!--Box Icons-->
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

        <!--Flickity-->
        <link rel="stylesheet" href="https://unpkg.com/flickity@2/dist/flickity.min.css">
        <script src="https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js"></script>

        <!--
            [Navigation & Footer]
        -->
        <script src="../admin-website/jScript/navigationTemplate.js"></script>
        <link href="../css/navigation.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="../css/footer.css">


        <!--CSS-->
        <link href="css/admin-form-template.css" rel="stylesheet" type="text/css">

        <!--CSS Templates-->
        <link rel="stylesheet" href="../templates/hero-banner.css">

    </head>
<body>
<header></header>
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

<section class="admin-form-section admin-first-section">
    <h2>Edit Order #<?php echo htmlspecialchars($orderId); ?></h2>
    <h3 class="admin-heading">Edit and Update Order Details</h3>
    <a class="go-back-link" href="../admin-website/order-management.php" class="back-button">Go Back to Order Management</a>
    <form action="edit-order.php?Orders_ID=<?php echo htmlspecialchars($orderId); ?>" method="post">
        <div class="input-container-big">
            <label for="status">Order Status:</label>
            <select id="status" name="status">
                <option value="Processing" <?php if ($order['Order_Status'] == 'Processing') echo 'selected'; ?>>Processing</option>
                <option value="Shipped" <?php if ($order['Order_Status'] == 'Shipped') echo 'selected'; ?>>Shipped</option>
                <option value="Delivered" <?php if ($order['Order_Status'] == 'Delivered') echo 'selected'; ?>>Delivered</option>
                <option value="Cancelled" <?php if ($order['Order_Status'] == 'Cancelled') echo 'selected'; ?>>Cancelled</option>
                <option value="Returning" <?php if ($order['Order_Status'] == 'Returning') echo 'selected'; ?>>Returning</option>
                <option value="Returned" <?php if ($order['Order_Status'] == 'Returned') echo 'selected'; ?>>Returned</option>
            </select>
        </div>
            <h5>Order Details:</h5>
            <?php foreach ($orderDetails as $detail): ?>
            <div class="product-detail">
                <div class="img-container">
                    <img src="../assets/ProductImages/ImageID_<?php echo $detail['Product_ID']; ?>.jpeg" alt="Product Image">
                </div>
                <?php
                    // Query to get the current stock for the product
                    $stockQuery = $db->prepare("SELECT Num_In_Stock FROM product WHERE Product_ID = ?");
                    $stockQuery->execute([$detail['Product_ID']]);
                    $currentStock = $stockQuery->fetchColumn();
                ?>
                <div class="flex-column">
                    <p>Product Name: <?php echo htmlspecialchars($detail['Name']); ?></p>
                    <p>Current Stock: <?php echo htmlspecialchars($currentStock); ?></p>
                    <div class="input-container">
                        <label for="quantity_<?php echo $detail['Product_ID']; ?>">Quantity:</label>
                        <!-- Adjusted to consider total stock -->
                        <input class="small-quantity-input" type="number" id="quantity_<?php echo $detail['Product_ID']; ?>" name="orderDetails[<?php echo $detail['Product_ID']; ?>][quantity]" value="<?php echo $detail['Quantity']; ?>" min="0" max="<?php echo $currentStock + $detail['Quantity']; ?>">
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <input class="submit-btn" type="submit" name="update_order" value="Update Order">
    </form>
</section>

</body>
</html>
