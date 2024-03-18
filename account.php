<?php
require_once "php/mainLogCheck.php";

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
    <link href="css/account.css" rel="stylesheet" type="text/css">

    <!--[Google Fonts]-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!--Nunito Font-->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,700;1,800&family=Work+Sans:wght@700;800&display=swap"
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
    <link rel="stylesheet" href="https://unpkg.com/flickity@2/dist/flickity.min.css">
    <link rel="stylesheet" href="templates/hero-banner.css">

    <!--JS-->
    <script src="https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js"></script>

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
                    <h2>Account</h2>
                    <p>Personal Profile</p>
                </div>
            </div>
        </section>

<?php
require_once('php/connectdb.php');
require_once('php/validateSignup.php');
require_once('php/alerts.php');

$username = $_SESSION['username'] ?? '';

//Array for userdata
$userData = [];

if ($username) {
    //Grab user data from db
    $stmt = $db->prepare("SELECT First_Name, Last_Name, Contact_Email AS email, Phone_Number AS phone, Home_Address AS address, Postcode, Password FROM customer WHERE Username = ?");
    $stmt->execute([$username]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);
}

if (isset($_POST['submit-update']) && !empty($_SESSION['username'])) {
    $currentPassword = $_POST['current-password'] ?? '';
    $newPassword = $_POST['new-password'] ?? '';

    //Verifies password
    if (!password_verify($currentPassword, $userData['Password'])) {
        //Displays error
        jsAlert('• Incorrect password', false, 10000);
    } else {
        $firstName = filter_input(INPUT_POST, 'update-first-name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $lastName = filter_input(INPUT_POST, 'update-last-name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'update-email', FILTER_SANITIZE_EMAIL);
        $phone = filter_input(INPUT_POST, 'update-phone', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $address = filter_input(INPUT_POST, 'update-address', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $postcode = filter_input(INPUT_POST, 'update-postcode', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        //Checks if inputs are valid
        $errors = validateSignupData($firstName, $lastName, $email, $phone, $username);

        if (empty($errors)) {
            $updateStmt = $db->prepare("UPDATE customer SET First_Name = ?, Last_Name = ?, Contact_Email = ?, Phone_Number = ?, Home_Address = ?, Postcode = ? WHERE Username = ?");
            if ($updateStmt->execute([$firstName, $lastName, $email, $phone, $address, $postcode, $username])) {
                $updatedSuccessfully = true;
            }

            if (!empty($newPassword)) {
                if (strlen($newPassword) >= 8 && preg_match('/[A-Za-z]/', $newPassword) && preg_match('/[0-9]/', $newPassword) && preg_match('/[!@#$%^&*()-_+=]/', $newPassword)) {
                    $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                    $updatePasswordStmt = $db->prepare("UPDATE customer SET Password = ? WHERE Username = ?");
                    if ($updatePasswordStmt->execute([$hashedNewPassword, $username])) {
                        jsAlert('Account and password updated successfully.', true, 4000);
                        $updatedSuccessfully = true;
                    }
                } else {
                    jsAlert('New password must be at least 8 characters long and include a number.', false, 10000);
                }
            } elseif ($updatedSuccessfully) {
                jsAlert('Account updated successfully.', true, 4000);
            }
        } else {
            $errorMessage = implode("<br>", $errors);
            jsAlert($errorMessage, false, 10000);
        }
    }
}
?>

<section class="account-settings-section">
            <!--Left Box-->
            <div class="update-account">

                <!--Title Etc...-->
                <h2 class="update-account-h2">Account Settings</h2>
                <h3 class="update-account-h3">Update your account details below:</h3>
                <h4 class="update-account-h4">Modify your information as needed. Make sure to review and save your changes before
                    submitting!
                </h4>

                <!--Update Account Form-->
                <form id="update-form" action="account.php" method="post" name="update-form">
                    <!--First Name Input Container-->
                    <div class="input-container">
                        <label for="update-first-name">First Name<span style="color: red;">*</span></label>
                        <input type="text" maxLength="30" id="update-first-name" name="update-first-name" class="name-input"
                            placeholder="First Name" value="<?= htmlspecialchars($userData['First_Name'] ?? '') ?>" required />
                        <i class='bx bxs-user'></i>
                    </div>
                    <!--Last Name Input Container-->
                    <div class="input-container">
                        <label for="update-last-name">Last Name<span style="color: red;">*</span></label>
                        <input type="text" maxLength="30" id="update-last-name" name="update-last-name" class="name-input"
                            placeholder="Last Name" value="<?= htmlspecialchars($userData['Last_Name'] ?? '') ?>" required />
                        <i class='bx bxs-user'></i>
                    </div>
                    <!--Email Input Container-->
                    <div class="input-container">
                        <label for="update-email">Email<span style="color: red;">*</span></label>
                        <input type="email" maxLength="60" id="update-email" name="update-email" class="email-input"
                            placeholder="Email" value="<?= htmlspecialchars($userData['email'] ?? '') ?>" required />
                        <i class='bx bxs-envelope'></i>
                    </div>
                    <!--Phone Number Input Container-->
                    <div class="input-container">
                        <label for="update-phone">Phone Number</label>
                        <input type="tel" id="update-phone" name="update-phone" class="phone-input"
                            placeholder="Phone Number" maxLength="11" value="<?= htmlspecialchars($userData['phone'] ?? '') ?>" />
                        <i class='bx bxs-phone'></i>
                    </div>
                    <!--Home Address Input Container-->
                    <div class="input-container">
                        <label for="update-address">Home Address</label>
                        <input type="text" maxLength="60" id="update-address" name="update-address" class="address-input"
                            placeholder="Home Address" value="<?= htmlspecialchars($userData['address'] ?? '') ?>" />
                        <i class='bx bxs-map'></i>
                    </div>
                    <!--Postcode Input Container-->
                    <div class="input-container">
                        <label for="update-postcode">Postcode</label>
                        <!-- Ensure that PHP code correctly populates the value attribute -->
                        <input type="text"maxLength="10" id="update-postcode" name="update-postcode" class="postcode-input" placeholder="Postcode" value="<?php echo htmlspecialchars($userData['Postcode'] ?? '', ENT_QUOTES); ?>">
                        <i class='bx bxs-map-pin'></i>
                    </div>
                    <!--Current Password Input Container-->
                    <div class="input-container">
                        <label for="current-password">Current Password<span style="color: red;">*</span></label>
                        <input type="password" id="current-password" name="current-password" class="password-input"
                            placeholder="Current Password" required />
                        <i class='bx bxs-lock'></i>
                    </div>
                    <!--New Password Input Container-->
                    <div class="input-container">
                        <label for="new-password">New Password (optional)</label>
                        <input type="password" id="new-password" name="new-password" class="password-input" placeholder="New Password" />
                        <i class='bx bxs-lock'></i>
                    </div>

                    <!--Update Button-->
                    <button type="submit" class="update-account-btn">Update Now</button>
                    <input type="hidden" name="submit-update" value="TRUE" />
                </form>

            </div>
        </section>
    </main>

    <footer>
        &copy; 2023 Petopia
    </footer>

    
</body>

</html>
