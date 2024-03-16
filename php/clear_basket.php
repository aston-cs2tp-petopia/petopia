<?php
session_start();
require_once('connectdb.php');
require_once('mainLogCheck.php');

if ($b) {
    $username = $_SESSION["username"];

    //Grab customer ID
    $custIdStmt = $db->prepare("SELECT Customer_ID FROM customer WHERE Username = ?");
    $custIdStmt->execute([$username]);
    $customerId = $custIdStmt->fetchColumn();

    //Clear basket
    $clearStmt = $db->prepare("DELETE FROM basket WHERE Customer_ID = ?");
    $clearStmt->execute([$customerId]);
}

header("Location: ../basket.php");
exit();
?>
