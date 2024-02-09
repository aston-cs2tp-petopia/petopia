<?php
    require_once('php/mainLogCheck.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Petopia</title>
    <link href="css/contact.css" rel="stylesheet" type="text/css">
    <link href="css/navigation.css" rel="stylesheet" type="text/css">
    <link href="css/footer.css" rel="stylesheet" type="text/css">

    <!--[Google Fonts]-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!--Nunito Font-->
    <link
        href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,700;1,800&family=Work+Sans:wght@700;800&display=swap"
        rel="stylesheet">

    <!--Box Icons-->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!--Flickity-->
    <!--CSS-->
    <link rel="stylesheet" href="https://unpkg.com/flickity@2/dist/flickity.min.css">
    <!--JS-->
    <script src="https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js"></script>
    <script>"contact.js/scripts"</script>

</head>

<body>
    <header>
        <!--Logo-->
        <div class="logo-container" href="index.php"><img src="assets/logo.png" alt=""></div>

        <!--Middle Navigation-->
        <nav class="desktop-nav">
            <ul class="desktop-nav-ul">
                <li><a href="index.php">Home</a></li>
                <!--Dropdown-->
                <li class="dropdown">
                    <a href="#">Pets v</a>
                    <ul class="dropdown-menu">
                        <li class="dropdown-li"><a href="products.php">Cats</a></li>
                        <li class="dropdown-li"><a href="products.php">Dogs</a></li>
                    </ul>
                </li>
                <!--Dropdown-->
                <li class="dropdown">
                    <a href="#">Shop v</a>
                    <ul class="dropdown-menu">
                        <li class="dropdown-li"><a href="products.php">Toys</a></li>
                        <li class="dropdown-li"><a href="products.php">Grooming</a></li>
                        <li><a href="products.php">Treats</a></li>
                    </ul>
                </li>
                <li><a href="advice.php">Advice</a></li>
                <li><a href="about.php">About Us</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </nav>

        <!--Right Navigation-->
        <div class="right-nav">
            <!--Login Button-->
            <?php
                if ($b==true) {
                    //Log out button
                    //Shoppiung Basket Button
                    echo '<a href="basket.php" class="basket-link"><div class="basket-button bx bx-basket"></div></a>';
                    echo '<div class="login-button"><a href="php/signOut.php"">Log Out</a></div>';
                }else{
                    //Login Button
                    echo '<div class="login-button"><a href="login.php">Login</a></div>';
                }        
            ?>
        </div>

        <!--Mobile-Background-->
        <div class="mobile-background"></div>
        <!--Mobile Navigation-->
        <nav class="mobile-nav">
            <div class="mobile-nav-top">
                <div class="mobile-logo"><img src="assets/logo.png" alt=""></div>
                <p class="close-menu-button" draggable="false">X</p>
            </div>
            <ul>
                <li><a href="index.php">Home</a></li>
                <!--Dropdown-->
                <li class="dropdown">
                    <a href="#">Pets v</a>
                    <ul class="dropdown-menu-mobile">
                        <li class="dropdown-li"><a href="products.php">Cats</a></li>
                        <li class="dropdown-li"><a href="products.php">Dogs</a></li>
                    </ul>
                </li>
                <!--Dropdown-->
                <li class="dropdown">
                    <a href="#">Shop v</a>
                    <ul class="dropdown-menu-mobile">
                        <li class="dropdown-li"><a href="products.php">Cats</a></li>
                        <li class="dropdown-li"><a href="products.php">Dogs</a></li>
                    </ul>
                </li>
                <li><a href="advice.html">Advice</a></li>
                <li><a href="about.php">About Us</a></li>
                <li><a href="contact.php">Contact</a></li>
                <div class="mobile-bottom-nav">
                    <!--Login Button-->
                    <?php
                        //Login Button
                        if ($b==true) {
                            //Log out button
                            //Shoppiung Basket Button
                            echo '<a href="basket.php" class="basket-link"><div class="basket-button bx bx-basket"></div></a>';
                            echo '<div class="login-button"><a href="php/signOut.php"">Log Out</a></div>';
                        }else{
                            //Login Button
                            echo '<div class="login-button"><a href="login.php">Login</a></div>';
                        }       
                    ?>
                    <!--Shoppiung Basket Button-->
                    <a href="" class="basket-link">
                        <div class='basket-button bx bx-basket'></div>
                    </a>
                </div>
            </ul>
        </nav>
        <!--Mobile Hamburger-->
        <div id="hamburger-button" class='bx bx-menu'></div>
    </header>

    <main>

        <section class="hero-banner">

            <div class="hero-banner-image"><img src="assets/Homepage/hero-banner2.jpg" alt=""></div>

            <div class="hero-banner-left">

                <div class="hero-banner-content">
                    <h2>Contact us</h2>
                    <p>Get in touch with our team</p>
                </div>
            </div>
        </section>

        <section class="contact-container">
            <div class="contact-bottom">
                <!--Left Box-->
                <form>
                    <h1> Contact Us </h1>
                    <p>Give us a message</p>
                    <p>Name*</p>
                    <input type="text" name="name" id="name" placeholder="Mark Dee..." required />
                    <p>Email*</p>
                    <input type="email" name="email" id="email" placeholder="info.exmaple@.com" required />
                    <p></p>
                    <label> Your Message*</br> </br>
                        <textarea name="txtAr" rows="10" cols="40" id="text" placeholder="Message..."> </textarea>
                    </label>
                    <p></p>
                    <input type="submit" value="Send Now" id="submit" required onclick="checkForm()" />
                </form>

                <!--Right Box-->
                <div class="box">
                    <img src="assets/Contactpage/phone.png" width="200" height="200">
                    <p></p>

                    <div class="image-container"><img src="assets/Contactpage/phone2.png" width="40" height="40"></div>
                    <div class="image-container"><img src="assets/Contactpage/mail.png" width="40" height="40"></div>
                    <div class="image-container"><img src="assets/Contactpage/location.png" width="40" height="40">
                    </div>
                    <p></p>
                    <a href="https://uk.linkedin.com/"><img src="assets/Contactpage/linkedin.jpg" width="40"
                            height="40"></a>
                    <a href="https://www.facebook.com/login/"><img src="assets/Contactpage/facebook.png" width="40"
                            height="40"></a>
                    <a href="https://twitter.com/login"><img src="assets/Contactpage/twitter.jpg" width="40"
                            height="40"></a>
                </div>
            </div>

        </section>
    </main>

    <footer>
        <p>Copyright © Petopia 2023</p>
    </footer>


</body>