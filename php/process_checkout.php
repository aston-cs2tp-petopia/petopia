<?php
session_start();
require_once('connectdb.php');
require_once('alerts.php');

if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];

try {
    $db->beginTransaction();

    // Grab user data
    $custStmt = $db->prepare("SELECT Customer_ID FROM customer WHERE Username = ?");
    $custStmt->execute([$username]);
    $customerID = $custStmt->fetchColumn();

    if (!$customerID) {
        throw new Exception("Customer not found.");
    }

    // Grab basket items for total
    $basketStmt = $db->prepare("SELECT Product_ID, Quantity, Subtotal FROM basket WHERE Customer_ID = ?");
    $basketStmt->execute([$customerID]);
    $basketItems = $basketStmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$basketItems) {
        throw new Exception("Basket is empty.");
    }

    $totalAmount = array_sum(array_column($basketItems, "Subtotal"));

    // Add to orders with Order_Status 'Processing'
    $ordersStmt = $db->prepare("INSERT INTO orders (Customer_ID, Order_Date, Total_Amount, Order_Status) VALUES (?, NOW(), ?, 'Processing')");
    $ordersStmt->execute([$customerID, $totalAmount]);
    $ordersID = $db->lastInsertId();

    // Update stock and order details
    foreach ($basketItems as $item) {
        $ordersDetailsStmt = $db->prepare("INSERT INTO ordersdetails (Orders_ID, Product_ID, Quantity, Subtotal) VALUES (?, ?, ?, ?)");
        $ordersDetailsStmt->execute([$ordersID, $item['Product_ID'], $item['Quantity'], $item['Subtotal']]);

        $updateProductStmt = $db->prepare("UPDATE product SET Num_In_Stock = Num_In_Stock - ? WHERE Product_ID = ?");
        $updateProductStmt->execute([$item['Quantity'], $item['Product_ID']]);
    }

    // Clear basket
    $clearBasketStmt = $db->prepare("DELETE FROM basket WHERE Customer_ID = ?");
    $clearBasketStmt->execute([$customerID]);

    $db->commit();

    jsAlert('Order has been successfully placed.', true, 3000); // If you wish to show an alert
} catch (Exception $e) {
    $db->rollBack();
    jsAlert('Error processing your order: ' . $e->getMessage(), false, 3000); // Display error using jsAlert
}

header("Location: ../orderconfirm.php?orderId=" . $ordersID); // Redirect
exit;
?>
