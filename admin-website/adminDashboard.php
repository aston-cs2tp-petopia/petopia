<?php
    require_once('../php/connectdb.php');
    $isAdmin=include('../php/isAdmin.php');
    require_once('php/adminCheck.php');
?>

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
    <script src="navigationTemplate.js"></script>
    <link href="../css/navigation.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="../css/footer.css">


    <!--CSS-->
    <link href="css/admin-dashboard.css" rel="stylesheet" type="text/css">

    <!--CSS Templates-->
    <link rel="stylesheet" href="../templates/hero-banner.css">

</head>


<body>
    <!--Navigation-->
    <header></header>

    <!--Dashboard-->
    <section class="admin-dashboard-section">
        <!--Title Etc...-->
        <h2 class="update-account-h2">Admin Dashboard</h2>
        <h3 class="update-account-h3">Manage your dashboard settings below:</h3>
        <h4 class="update-account-h4">Modify your dashboard settings and access various management tools:</h4>
        <div class="admin-dash-buttons">
            <a href="../admin-website/order-management.php" class="admin-dash-button"><div>Order Management</div></a>
            <a href="../admin-website/customer-management.php" class="admin-dash-button"><div>Customer Management</div></a>
            <a href="#" class="admin-dash-button"><div>Inventory Management System</div></a>
            <a href="#" class="admin-dash-button"><div>Customer Contact Forms</div></a>
            <a href="#" class="admin-dash-button"><div>Admin Management</div></a>
        </div>
    </section>
</body>

</html>