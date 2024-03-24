<?php
session_start();
require_once('../php/connectdb.php');
$isAdmin = include('../php/isAdmin.php');

if (!$isAdmin) {
    // Redirect non-admin users to a suitable page
    header("Location: ../index.php");
    exit;
}

if (isset($_GET['Orders_ID']) && !empty($_GET['Orders_ID'])) {
    $orderID = $_GET['Orders_ID'];

    // Check if the order exists
    $stmt = $db->prepare("SELECT * FROM orders WHERE Orders_ID = :orderID");
    $stmt->bindParam(':orderID', $orderID);
    $stmt->execute();
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($order) {
        // Delete the order and related details
        $stmtDeleteDetails = $db->prepare("DELETE FROM ordersdetails WHERE Orders_ID = :orderID");
        $stmtDeleteDetails->bindParam(':orderID', $orderID);
        $stmtDeleteDetails->execute();

        $stmtDeleteOrder = $db->prepare("DELETE FROM orders WHERE Orders_ID = :orderID");
        $stmtDeleteOrder->bindParam(':orderID', $orderID);
        $stmtDeleteOrder->execute();

        header("Location: ../admin-website/order-management.php?success=1");
        exit;
    } else {
        header("Location: ../admin-website/order-management.php?success=1");
        exit;
    }
} else {
    header("Location: ../admin-website/order-management.php?success=1");
    exit;
}
?>
