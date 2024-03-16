<?php
require_once('php/mainLogCheck.php');

//Check if the user is logged in
if (!$b) {
    header("Location: login.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Petopia</title>
    <link href="css/checkout.css" rel="stylesheet" type="text/css">

    <!--[Google Fonts]-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!--Nunito Font-->
    <link
        href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,700;1,800&family=Work+Sans:wght@700;800&display=swap"
        rel="stylesheet">

    <!--Box Icons-->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!--
        [Navigation & Footer]
    -->
    <script src="templates/navigationTemplate.js"></script>
    <link href="css/navigation.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="css/footer.css">

    <!--Flickity-->
    <!--CSS Templates-->
    <link rel="stylesheet" href="https://unpkg.com/flickity@2/dist/flickity.min.css">
    <link rel="stylesheet" href="templates/hero-banner.css">
</head>

<body>

<header></header>
    
    <main>
        <!--Hero Banner-->
        <section class="hero-banner">
            <!--Hero Banner Image-->
            <div class="hero-banner-image"><img src="assets/Homepage/hero-banner2.jpg" alt=""></div>

            <!--Hero Banner Text Container-->
            <div class="hero-banner-left">

                <div class="hero-banner-content">
                    <h2>Checkout</h2>
                    <p></p>
                </div>
            </div>
        </section>
        <!--Basket Container-->
        <section class="basket-container">
            <!--Basket Container-->
            <div class="top-container">
                <h3>Your basket</h3>
            </div>
            <!--Basket Table-->
            <div class="basket-php-container">
                <?php
                    require_once('php/basket_template.php');
                ?>
            </div>
        </section>
            
        
    </main>
</body>
    
    <footer>
        &copy; 2023 Petopia
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var removeButtons = document.querySelectorAll('.remove-basket');

            removeButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    var productId = this.closest('.basket-row').dataset.productId;

                    // Make an AJAX request to remove the item
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', 'remove_from_basket.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                    xhr.onreadystatechange = function () {
                        if (xhr.readyState == 4 && xhr.status == 200) {
                            // Refresh the page or update the UI as needed
                            location.reload();
                        } else if (xhr.readyState == 4 && xhr.status != 200) {
                            alert('Error removing item from the basket.');
                        }
                    };

                    xhr.send('productId=' + encodeURIComponent(productId));
                });
            });
        });
    </script>

</body>
</html>