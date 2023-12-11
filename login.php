<?php
    //if form submitted
    session_start();
    $b=False; // boolean
    if (isset($_SESSION['username'])) {
        $b=True; // used so the logged in user doesn't get sent back to this page, until logged out
    }

    if (isset($_POST['submitLogin'])) { // if user submitted details
        if (!isset($_POST['username'], $_POST['password'])) { // check if fields are empty
            exit('Please fill both the username and password fields!'); // stop running check, error message (empty fields)
        }

        require_once("php/loggingIn.php");
        
    }

    if (isset($_POST['submitSignUp'])) { // if user submitted details
        if (!isset($_POST['email'], $_POST['fName'], $_POST['lName'], $_POST['username'], $_POST['password'])) { // check if fields are empty
            exit('Please fill the email, username, and password fields.'); // stop running check, error message (empty fields)
        }
    
        // go to the signingUp script
        require_once("php/signingUp.php");
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Petopia</title>
        <link href="css/loginmk4.css" rel="stylesheet" type="text/css">
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
    
    </head>
<body>
    <header>
        <!--
        [NAVIGATION/HEADER]
    -->
    <header>
        <!--Logo-->
        <div class="logo-container"><a href="index.php"><img src="assets/logo.png" alt=""></a></div>

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
            <!--Shoppiung Basket Button-->
            <a href="" class="basket-link">
                <div class='basket-button bx bx-basket'></div>
            </a>
            <!--Login Button-->
            <a href="login.php" class="login-link">
                <div class="login-button">Login</div>
            </a>
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
                <li><a href="advice.php">Advice</a></li>
                <li><a href="about.php">About Us</a></li>
                <li><a href="contact.php">Contact</a></li>
                <div class="mobile-bottom-nav">
                    <?php
                        if ($b==true) {
                            //Log out button
                            //Shopping Basket Button
                            echo '<a href="basket.php" class="basket-link"><div class="basket-button bx bx-basket"></div></a>';
                            echo '<div class="login-button"><a href="php/signOut.php"">Log Out</a></div>';
                        }else{
                            //Login Button
                            echo '<div class="login-button"><a href="login.php">Login</a></div>';
                        } 
                    ?>
                </div>
            </ul>
        </nav>
        <!--Mobile Hamburger-->
        <div id="hamburger-button" class='bx bx-menu'></div>
    </header>
    <!--
        [HEADER/NAVIGATION END]
    -->

    </header>
    
    <div class="maincontent">
        <div class="discount-cat-image">
                <img src="assets\minimaldognobackground..png" alt="">
            </div>

    <!-- Left Pane (Login Form) -->
    <section class="main-content">
        <div class="Login-box">
            <div class="max-w-md p-8">
                <h2 class="login-text">Login</h2>
                <form id="loginForm" name="loginForm" method="post" action="login.php">
                    <div class="mb-4">
                        <label for="username" class="username-label">Username</label>
                        <input type="text" id="username" name="username" class="username-input" placeholder="username" required/> <!--username-->
                    </div>
                    <div class="mb-6">
                        <label for="password" class="password-label">Password</label>
                        <input type="password" id="password" name="password" class="password-input" placeholder="**********" required/> <!--password-->
                    </div>
                    <button type="submit" class="login-submit">Login</button>
                    <input type="hidden" name="submitLogin" value="TRUE" />
                </form>
            </div>
        </div>

    <!-- Right Pane (Sign Up Form) -->
        <div class="SignUp-box">
            <div class="max-w-md p-8">
                <h2 class="login-text">Sign Up</h2>
                <form id="signUpForm" name="signUpForm" method="post" action="login.php">
                    <div class="mb-4">
                        <label for="fname" class="fname-label">First Name</label>
                        <input type="text" id="fName" name="fName" class="username-input" placeholder="John" required/> <!--firstname-->
                        
                        <label for="lname" class="fname-label">Last Name</label>
                        <input type="text" id="lName" name="lName" class="username-input" placeholder="Smith" required/> <!--lastname-->

                        <label for="email" class="email-label">Email</label>
                        <input type="text" id="email" name="email" class="username-input" placeholder="example@email.com" required/> <!--email-->

                        <label for="pNumber" class="pnumber-label">Phone Number</label>
                        <input type="tel" id="pNumber" name="pNumber" class="username-input" 

                        placeholder="07599671811" pattern="[0-9]{11}" required/> <!--class name should be changed to reflect field-->


                        <label for="username" class="username-label">Username</label>
                        <input type="text" id="username" name="username" class="username-input" placeholder="username" required/> <!--username-->
                    </div>
                    <div class="mb-6">
                        <label for="password" class="password-label">Password</label>
                        <input type="password" id="password" name="password" class="password-input" placeholder="**********" required/> <!--password-->
                    </div>
                    <button type="submit" class="login-submit">Sign Up</button>
                    <input type="hidden" name="submitSignUp" value="TRUE" />
                </form>
            </div>
        </div>
    </section>
</body>
</html>