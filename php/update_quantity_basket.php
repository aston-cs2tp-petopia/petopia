<?php
session_start();
require_once('connectdb.php');
require_once('mainLogCheck.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['productID'], $_POST['quantity'])) {
    $productId = intval($_POST['productID']);
    $quantity = intval($_POST['quantity']);
    $username = $_SESSION['username'];

    //Grab customer ID
    $custIdStmt = $db->prepare("SELECT Customer_ID FROM customer WHERE Username = ?");
    $custIdStmt->execute([$username]);
    $customerId = $custIdStmt->fetchColumn();

    if ($quantity > 0) {
        //Calculate new subtotal
        $productStmt = $db->prepare("SELECT Price FROM product WHERE Product_ID = ?");
        $productStmt->execute([$productId]);
        $product = $productStmt->fetch();
        $newSubtotal = $product['Price'] * $quantity;

        //Update subtotal + quantity
        $updateStmt = $db->prepare("UPDATE basket SET Quantity = ?, Subtotal = ? WHERE Customer_ID = ? AND Product_ID = ?");
        $updateStmt->execute([$quantity, $newSubtotal, $customerId, $productId]);
    } else {
        //Removes item from basket if quantity is == 0
        $deleteStmt = $db->prepare("DELETE FROM basket WHERE Customer_ID = ? AND Product_ID = ?");
        $deleteStmt->execute([$customerId, $productId]);
    }
    
    header("Location: ../basket.php"); //Refreshes page
} else {
    header("Location: ../basket.php");
}
?>
