<?php
    session_start();
    require_once('../php/connectdb.php');

    $isAdmin=include('../php/isAdmin.php');
    
    if(!$isAdmin || !isset($_SESSION['username'])){
        header("Location: ../index.php");
        exit();
        echo    'being redirected';
    }

    $customerID=$_GET["customerID"];
    echo "$customerID";

    $query=$db->prepare("SELECT `FROM `customer` WHERE `Customer_ID` = '$customerID';");

    $query->execute();

    header("Location: adminCustomer.php");
        exit();
?>