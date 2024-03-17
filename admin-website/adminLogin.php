<?php
    session_start(); // Start the session at the beginning of the script
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
?>

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
    <!-- <script src="templates/navigationTemplate.js"></script>
    <link href="css/navigation.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="css/footer.css"> -->

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

                <div class="input-container">
                    <!--Hidden Input (Identifys an Admin)-->
                    <input type="" id="userType" name="userType" class="userType"
                        placeholder="userType" value="admin" required />
                </div>

                <!--Login Button-->
                <button type="submit" class="login-btn">Login</button>
                <input type="hidden" name="submit-login" value="TRUE" />

            </form>
        </div>
    </section>
    
    <script>
    window.onload = function() {
        <?php
        if (isset($_SESSION['error_message'])) {
            echo "alert('" . addslashes($_SESSION['error_message']) . "');";
            unset($_SESSION['error_message']); // Clear the message so it doesn't persist
        }
        ?>
    };
</script>
</body>
</html>