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

    // Fetch customer ID
    $custStmt = $db->prepare("SELECT Customer_ID FROM customer WHERE Username = ?");
    $custStmt->execute([$username]);
    $customerID = $custStmt->fetchColumn();

    if (!$customerID) {
        throw new Exception("Customer not found.");
    }

    // Fetch basket items for subtotal calculation
    $basketStmt = $db->prepare("SELECT Product_ID, Quantity, Subtotal FROM basket WHERE Customer_ID = ?");
    $basketStmt->execute([$customerID]);
    $basketItems = $basketStmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$basketItems) {
        throw new Exception("Basket is empty.");
    }

    $totalAmount = array_sum(array_column($basketItems, "Subtotal"));

    // Insert into orders
    $ordersStmt = $db->prepare("INSERT INTO orders (Customer_ID, Order_Date, Total_Amount) VALUES (?, NOW(), ?)");
    $ordersStmt->execute([$customerID, $totalAmount]);
    $ordersID = $db->lastInsertId();

    // Insert into ordersdetails and update product stock
    foreach ($basketItems as $item) {
        $ordersDetailsStmt = $db->prepare("INSERT INTO ordersdetails (Orders_ID, Product_ID, Quantity, Subtotal) VALUES (?, ?, ?, ?)");
        $ordersDetailsStmt->execute([$ordersID, $item['Product_ID'], $item['Quantity'], $item['Subtotal']]);

        $updateProductStmt = $db->prepare("UPDATE product SET Num_In_Stock = Num_In_Stock - ? WHERE Product_ID = ?");
        $updateProductStmt->execute([$item['Quantity'], $item['Product_ID']]);
    }

    // Clear user basket
    $clearBasketStmt = $db->prepare("DELETE FROM basket WHERE Customer_ID = ?");
    $clearBasketStmt->execute([$customerID]);

    $db->commit();

    // Success message
    $_SESSION['alert_message'] = ["message" => "Order placed successfully.", "success" => true];
} catch (Exception $e) {
    $db->rollBack();
    $_SESSION['alert_message'] = ["message" => "Error placing order: " . $e->getMessage(), "success" => false];
}

header("Location: ../orderconfirm.php"); // Redirect to a success page
exit;
?>
