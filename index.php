<?php
 require_once('php/mainLogCheck.php');
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
    <script src="scripts/navigation.js"></script>
    <link href="css/navigation.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="css/footer.css">


    <!--CSS-->
    <link href="css/index.css" rel="stylesheet" type="text/css">

</head>

<body>
    <!--
        [NAVIGATION/HEADER]
    -->
    <header>
        <!--Logo-->
        <div class="logo-container"><a href="#"><img src="assets/logo.png" alt=""></a></div>

        <!--Middle Navigation-->
        <nav class="desktop-nav">
            <ul class="desktop-nav-ul">
                <li><a href="index.php">Home</a></li>
                <!--Dropdown-->
                <li class="dropdown">
                    <a href="#">Pets v</a>
                    <ul class="dropdown-menu">
                        <li class="dropdown-li"><a href="itemTable.php">Cats</a></li>
                        <li class="dropdown-li"><a href="itemTable.php">Dogs</a></li>
                    </ul>
                </li>
                <!--Dropdown-->
                <li class="dropdown">
                    <a href="#">Shop v</a>
                    <ul class="dropdown-menu">
                        <li class="dropdown-li"><a href="itemTable.php">Toys</a></li>
                        <li class="dropdown-li"><a href="itemTable.php">Grooming</a></li>
                        <li><a href="itemTable.php">Treats</a></li>
                    </ul>
                </li>
                <li><a href="testSignOut.php">Advice</a></li>
                <li><a href="about.php">About Us</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </nav>

        <!--Right Navigation-->
        <div class="right-nav">
            <!--Shoppiung Basket Button-->
            <a href="" class="basket-link">
                <div class='basket-button bx bx-basket'></div>
            </a>
            <!--Login Button-->
            <?php
                if ($b==true) {
                    //Log out button
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
                        <li class="dropdown-li"><a href="itemTable.php">Cats</a></li>
                        <li class="dropdown-li"><a href="itemTable.php">Dogs</a></li>
                    </ul>
                </li>
                <!--Dropdown-->
                <li class="dropdown">
                    <a href="#">Shop v</a>
                    <ul class="dropdown-menu-mobile">
                        <li class="dropdown-li"><a href="itemTable.php">Cats</a></li>
                        <li class="dropdown-li"><a href="itemTable.php">Dogs</a></li>
                    </ul>
                </li>
                <li><a href="advice.html">Advice</a></li>
                <li><a href="about.php">About Us</a></li>
                <li><a href="contact.php">Contact</a></li>
                <div class="mobile-bottom-nav">
                    <!--Login Button-->
                    <?php
                        if ($b==true) {
                            //Log out button
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
    <!--
        [HEADER/NAVIGATION END]
    -->

    <main>
        <!--Hero Banner-->
        <section class="hero-banner">
            <!--Hero Banner Image-->
            <div class="hero-banner-image"><img src="assets/Homepage/hero-banner2.jpg" alt=""></div>

            <!--Hero Banner Text Container-->
            <div class="hero-banner-left">

                <div class="hero-banner-content">
                    <h2>Best friend with happy time</h2>
                    <p> The weather is amazing today and the dogs will have fun in that good weathger</p>
                    <!--Hero Banner Button-->
                    <div class="hero-banner-button">Learn more</div>
                </div>
            </div>
        </section>

        <!--Quick Access Section-->
        <section class="quick-access-container">
            <div class="quick-access-content">
                <div class="quick-access qa-info" style="background-color: #163741;">
                    <div class='bx bxs-timer'></div>
                    <p>Quick Access</p>
                </div>

                <div class="quick-access qa-triangle"></div>

                <div class="quick-access qa-border">
                    <div class='bx bxs-dog'></div>
                    <a href="">Browse Dogs</a>
                </div>

                <div class="quick-access qa-border">
                    <div class='bx bxs-cat'></div>
                    <a href="">Browse Cats</a>
                </div>

                <div class="quick-access qa-border">
                    <div class='bx bxs-help-circle'></div>
                    <a href="">Visit Advice</a>
                </div>

                <div class="quick-access">
                    <div class='bx bxs-group'></div>
                    <a href="">Visit About Us</a>
                </div>
            </div>
        </section>

        <!--Why Choose Us-->
        <section class="discount-container">
            <!--Left Content-->
            <div class="discount-left-content">
                <h2>Why Choose Us?</h2>
                <h3>Discover the Unique Petopia Experience for All Your Pet Needs</h3>
                <div class="percentage-containers">
                    <div class="percentage-container">
                        <h4>50% OFF</h4>
                        <p>On Selected Pet Supplies</p>
                    </div>

                    <div class="percentage-container">
                        <h4>50% OFF</h4>
                        <p>On Selected Pet Breeds</p>
                    </div>
                </div>
            </div>

            <!--Right Image-->
            <div class="discount-cat-image">
                <img src="assets/Homepage/cat-image.jpg" alt="">
            </div>
        </section>

        <!--Testimonials Section-->
        <section class="testimonials-container">
            <div class="t-heading-container">
                <h2>Testimonials</h2>
                <h3>Our Satisfied Customers</h3>
                <p>See what our delighted customers have to say about their extraordinary experiences with us!</p>
            </div>

            <!--Flickity Carousel-->
            <div class="main-carousel">
                <!--Testimonial 1-->
                <div class="carousel-cell">
                    <div class="cell-background">
                        <!--Stars-->
                        <div class="stars">
                            <div class='bx bxs-star'></div>
                            <div class='bx bxs-star'></div>
                            <div class='bx bxs-star'></div>
                            <div class='bx bxs-star'></div>
                            <div class='bx bxs-star'></div>
                        </div>

                        <!--Quote-->
                        <p class="testimonial-quote">"Petopia is a pet lover's paradise! Their caring staff and
                            top-notch
                            products make it my go-to
                            for all things pet. From premium food to adorable toys, Petopia has it all. My furry friend
                            gives it two paws up!"</p>
                        <!--Profile-->
                        <div class="testimonial-profile">
                            <div class='bx bxs-user-circle'></div>
                            <div class="testimonial-details">
                                <h3>John Doe</h3>
                                <h4>Age: 53</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <!--Testimonial 2-->
                <div class="carousel-cell">
                    <div class="cell-background">
                        <!--Stars-->
                        <div class="stars">
                            <div class='bx bxs-star'></div>
                            <div class='bx bxs-star'></div>
                            <div class='bx bxs-star'></div>
                            <div class='bx bxs-star'></div>
                            <div class='bx bxs-star'></div>
                        </div>
                        <!--Quote-->
                        <p class="testimonial-quote">"Petopia is a game-changer for pet parents like me! The team's
                            genuine love for animals reflects in every aspect of their service. With a fantastic
                            selection of quality products, Petopia is where my pet's happiness begins."</p>
                        <!--Profile-->
                        <div class="testimonial-profile">
                            <div class='bx bxs-user-circle'></div>
                            <div class="testimonial-details">
                                <h3>Jane Doe</h3>
                                <h4>Age: 33</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <!--Testimonial 3-->
                <div class="carousel-cell">
                    <div class="cell-background">
                        <!--Stars-->
                        <div class="stars">
                            <div class='bx bxs-star'></div>
                            <div class='bx bxs-star'></div>
                            <div class='bx bxs-star'></div>
                            <div class='bx bxs-star'></div>
                            <div class='bx bxs-star'></div>
                        </div>
                        <!--Quote-->
                        <p class="testimonial-quote">"Petopia is my pet's happy place! The personalized attention and
                            fantastic product range make it a standout choice. From tasty treats to cozy beds, Petopia
                            has elevated our pet-parenting experience."</p>
                        <!--Profile-->
                        <div class="testimonial-profile">
                            <div class='bx bxs-user-circle'></div>
                            <div class="testimonial-details">
                                <h3>Hamza Smith</h3>
                                <h4>Age: 24</h4>
                            </div>
                        </div>
                    </div>
                </div>



            </div>
        </section>
    </main>

    <!--Footer-->
    <footer>
        <p>Copyright © Petopia 2023</p>
    </footer>

    <!--Flickity-->
    <script src="scripts//Homepage/index.js"></script>
</body>

</html>