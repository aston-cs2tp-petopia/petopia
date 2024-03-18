<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Petopia</title>
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

    <!--CSS Templates-->
    <link href="css/login.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="templates/hero-banner.css">
    <!--JS-->
    <script src="scripts/login.js"></script>

</head>
<body>
     <!--
        [NAVIGATION/HEADER]
    -->
    <header></header>
    <!--
        [HEADER/NAVIGATION END]
    -->

    <?php
    session_start(); // Start the session at the beginning of the script
    require_once('php/alerts.php');
    $ex=null;
    $b=False; // boolean
    
    if (isset($_SESSION['username'])) {
        $b=True; // user is logged in
    }

    if (isset($_POST['submit-login'])) {
        // Use the correct form input names: login-username and login-password
        if (!isset($_POST['login-username'], $_POST['login-password'])) {
            exit('Please fill both the username and password fields!');
        }
        require_once("php/loggingIn.php");
    }

    if (isset($_POST['submit-signup'])) { // if user submitted details
        if (!isset($_POST['signup-email'], $_POST['signup-firstname'], $_POST['signup-lastname'], $_POST['signup-username'], $_POST['signup-password'], $_POST['signup-number'])) { // check if fields are empty
            exit('Please fill the email, username, and password fields.'); // stop running check, error message (empty fields)
        }
    
        // go to the signingUp script
        require_once("php/signingUp.php");
    }

    if (isset($_SESSION['error_message'])) {
        $errorMessage = $_SESSION['error_message'];
        jsAlert($errorMessage, false, 10000);
        unset($_SESSION['error_message']);
    }
?>

    <!--Login Section-->
    <section class="login-section">
        <!--Login Container-->
        <div class="login-container">
            <!--Petopia Logo-->
            <div class="login-logo-image">
                <img src="assets/logo.png" alt="">
            </div>
            <h2 class="login-title">Login</h2>
            <!--Login Form-->
            <form id="login-form" action="login.php" method="post" name="login-form">
                <!--Username Input Container-->
                <div class="input-container">
                    <!--Username Input-->
                    <input type="text" id="login-username" name="login-username" class="username-input" placeholder="username"
                        required />
                    <i class='bx bxs-user'></i>
                </div>
                <!--Password Input Container-->
                <div class="input-container">
                    <!--Password Input-->
                    <input type="password" id="login-password" name="login-password" class="password-input"
                        placeholder="password" required />
                    <i class='bx bxs-lock-alt'></i>
                </div>
                <!--Login Button-->
                <button type="submit" class="login-btn">Login</button>
                <input type="hidden" name="submit-login" value="TRUE" />

            </form>
            <p class="register-bottom">Don't have an account? <strong class="register-button">Register</strong></p>
        </div>

        <!--Sign Up Container-->
        <div class="signup-container hide">
            <!--Petopia Logo-->
            <div class="signup-logo-image">
                <img src="assets/logo.png" alt="">
            </div>
            <h2 class="signup-title">Sign Up</h2>
            <!--Sign Up Form-->
            <form id="signup-form" action="login.php" method="post" name="signup-form" onsubmit="return validateSignupData()">
                <!--First Name Input Container-->
                <div class="input-container">
                    <!--First Name Input-->
                    <input type="text" id="signup-firstname" name="signup-firstname" class="firstname-input"
                        placeholder="First Name" required />
                    <i class='bx bx-user'></i>
                </div>

                <!--Last Name Input Container-->
                <div class="input-container">
                    <!--Last Name Input-->
                    <input type="text" id="signup-lastname" name="signup-lastname" class="lastname-input"
                        placeholder="Last Name" required />
                    <i class='bx bx-user'></i>
                </div>

                <?php
                    if($ex != null){
                        echo ("ERROR");
                    }
                ?>
                <!--Email Input Container-->
                <div class="input-container">
                    <!--Email Input-->
                    <input type="text" id="signup-email" name="signup-email" class="email-input" placeholder="Email"
                        required />
                    <i class='bx bxs-envelope'></i>
                </div>

                <!--Mobile Number Input Container-->
                <div class="input-container">
                    <!--Mobile Number Input-->
                    <input type="text" id="signup-number" name="signup-number" class="number-input" placeholder="Phone Number"
                        required />
                    <i class='bx bxs-phone'></i>
                </div>

                <!--Home Address Input Container-->
                <div class="input-container">
                    <!--Home Address Input-->
                    <input type="text" id="signup-homeAddress" name="signup-homeAddress" class="homeAddress-input" 
                    placeholder="Home Address"required />
                    <i class='bx bxs-home'></i>
                </div>

                <div class="input-container">
                    <!--Postcode Input-->
                    <input type="text" id="signup-postcode" name="signup-postcode" class="postcode-input" 
                    placeholder="Postcode"required />
                    <i class='bx bxs-map-pin'></i>
                </div>

                <!--Username Input Container-->
                <div class="input-container">
                    <!--Username Input-->
                    <input type="text" id="signup-username" name="signup-username" class="username-input"
                        placeholder="username" required />
                    <i class='bx bxs-user'></i>
                </div>
                <!--Password Input Container-->
                <div class="input-container">
                    <!--Password Input-->
                    <input type="password" id="signup-password" name="signup-password" class="password-input"
                        placeholder="password" required />
                    <i class='bx bxs-lock-alt'></i>
                </div>
                <!--Login Button-->
                <button type="submit" class="signup-btn">Sign Up</button>
                <input type="hidden" name="submit-signup" value="TRUE" />
            </form>
            <p class="login-bottom">Already have an account? <strong class="register-button">Login</strong></p>
        </div>
    </section>
    <!--Footer-->
    <footer>
        <p>Copyright © Petopia 2023</p>
    </footer>
</body>
</html>