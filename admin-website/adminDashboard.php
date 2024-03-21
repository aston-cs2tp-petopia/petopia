<?php
    session_start();
    require_once('../php/connectdb.php');

    $isAdmin=include('../php/isAdmin.php');
    // echo "$isAdmin"

    if(!$isAdmin || !isset($_SESSION['username'])){
        header("Location: ../index.php");
        exit();
        echo'being redirected';
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <!--[Google Fonts]-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!--Nunito Font-->
    
    <link
        href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,700;1,800&family=Work+Sans:wght@700;800&display=swap"
        rel="stylesheet">

    <!--Box Icons-->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!--CSS-->
    <link rel="stylesheet" href="css/admin-dashboard.css">
</head>

<body>
    <!--Navigation-->
    <nav class="admin-nav">

    </nav>

    <p><a href="adminCustomer.php">adminCustomer</a></p>
    <p><a href="adminProducts.php">adminProduct</a></p>

</body>

</html>