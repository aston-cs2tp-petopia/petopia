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
                    <p>Personal Profile
                    </p>
                </div>
            </div>
        </section>

        <?php
require_once 'php/connectdb.php'; // Include the file where the database connection is established
require_once 'php/validateSignup.php'; // Include the validation script
session_start();

$username = $_SESSION['username'] ?? '';

// Initialize an array to hold user data
$userData = [];

if ($username) {
    // Replace the field names according to your actual database schema
    $stmt = $db->prepare("SELECT First_Name, Last_Name, Contact_Email AS email, Phone_Number AS phone, Home_Address AS address, Postcode FROM customer WHERE Username = ?");
    $stmt->execute([$username]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);
}

if (isset($_POST['submit-update']) && !empty($_SESSION['username'])) {
    $username = $_SESSION['username'];
    
    // Extract and sanitize input data
    $firstName = filter_input(INPUT_POST, 'update-first-name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $lastName = filter_input(INPUT_POST, 'update-last-name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'update-email', FILTER_SANITIZE_EMAIL);
    $phone = filter_input(INPUT_POST, 'update-phone', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $address = filter_input(INPUT_POST, 'update-address', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $postcode = filter_input(INPUT_POST, 'update-postcode', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // Validate the form data using validateSignupData function from validateSignup.php
    $errors = validateSignupData($firstName, $lastName, $email, $phone, $username, $_POST['update-password']);

    // If there are validation errors, display them to the user
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<script>alert('$error');</script>";
        }
    } else {
        // Check for unique email and phone, excluding the current user
        $stmt = $db->prepare("SELECT COUNT(*) FROM customer WHERE (Contact_Email = :email OR Phone_Number = :phone) AND Username <> :username");
        $stmt->execute([':email' => $email, ':phone' => $phone, ':username' => $username]);
        if ($stmt->fetchColumn() > 0) {
            // Display message if email or phone number is already in use
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        var messageDiv = document.createElement('div');
                        messageDiv.textContent = 'Email or phone number already in use. Please choose another.';
                        messageDiv.style.position = 'fixed';
                        messageDiv.style.top = '50%';
                        messageDiv.style.left = '50%';
                        messageDiv.style.transform = 'translate(-50%, -50%)';
                        messageDiv.style.backgroundColor = '#f8d7da';
                        messageDiv.style.color = '#721c24';
                        messageDiv.style.padding = '20px';
                        messageDiv.style.border = '1px solid #f5c6cb';
                        messageDiv.style.borderRadius = '5px';
                        messageDiv.style.boxShadow = '0 4px 8px rgba(0,0,0,0.1)';
                        messageDiv.style.zIndex = '1000';
                        document.body.appendChild(messageDiv);
                        
                        setTimeout(function() {
                            document.body.removeChild(messageDiv);
                        }, 3000); // Message will disappear after 3 seconds
                    });
                </script>";
        } else {
            // Update the user's account information in the database
            $updateStmt = $db->prepare("UPDATE customer SET First_Name = ?, Last_Name = ?, Contact_Email = ?, Phone_Number = ?, Home_Address = ?, Postcode = ? WHERE Username = ?");
            $updateStmt->execute([$firstName, $lastName, $email, $phone, $address, $postcode, $username]);
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    var messageDiv = document.createElement('div');
                    messageDiv.textContent = 'Account updated successfully.';
                    messageDiv.style.position = 'fixed';
                    messageDiv.style.top = '50%';
                    messageDiv.style.left = '50%';
                    messageDiv.style.transform = 'translate(-50%, -50%)';
                    messageDiv.style.backgroundColor = '#d4edda';
                    messageDiv.style.color = '#155724';
                    messageDiv.style.padding = '20px';
                    messageDiv.style.border = '1px solid #c3e6cb';
                    messageDiv.style.borderRadius = '5px';
                    messageDiv.style.boxShadow = '0 4px 8px rgba(0,0,0,0.1)';
                    messageDiv.style.zIndex = '1000';
                    document.body.appendChild(messageDiv);
                    
                    setTimeout(function() {
                        document.body.removeChild(messageDiv);
                    }, 3000); // Message will disappear after 3 seconds
                });
            </script>";
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
                        <input type="text" id="update-first-name" name="update-first-name" class="name-input"
                            placeholder="First Name" value="<?= htmlspecialchars($userData['First_Name'] ?? '') ?>" required />
                        <i class='bx bxs-user'></i>
                    </div>
                    <!--Last Name Input Container-->
                    <div class="input-container">
                        <label for="update-last-name">Last Name<span style="color: red;">*</span></label>
                        <input type="text" id="update-last-name" name="update-last-name" class="name-input"
                            placeholder="Last Name" value="<?= htmlspecialchars($userData['Last_Name'] ?? '') ?>" required />
                        <i class='bx bxs-user'></i>
                    </div>
                    <!--Email Input Container-->
                    <div class="input-container">
                        <label for="update-email">Email<span style="color: red;">*</span></label>
                        <input type="email" id="update-email" name="update-email" class="email-input"
                            placeholder="Email" value="<?= htmlspecialchars($userData['email'] ?? '') ?>" required />
                        <i class='bx bxs-envelope'></i>
                    </div>
                    <!--Phone Number Input Container-->
                    <div class="input-container">
                        <label for="update-phone">Phone Number</label>
                        <input type="tel" id="update-phone" name="update-phone" class="phone-input"
                            placeholder="Phone Number" value="<?= htmlspecialchars($userData['phone'] ?? '') ?>" />
                        <i class='bx bxs-phone'></i>
                    </div>
                    <!--Home Address Input Container-->
                    <div class="input-container">
                        <label for="update-address">Home Address</label>
                        <input type="text" id="update-address" name="update-address" class="address-input"
                            placeholder="Home Address" value="<?= htmlspecialchars($userData['address'] ?? '') ?>" />
                        <i class='bx bxs-map'></i>
                    </div>
                    <!--Postcode Input Container-->
                    <div class="input-container">
                        <label for="update-postcode">Postcode</label>
                        <!-- Ensure that PHP code correctly populates the value attribute -->
                        <input type="text" id="update-postcode" name="update-postcode" class="postcode-input" placeholder="Postcode" value="<?php echo htmlspecialchars($userData['Postcode'] ?? '', ENT_QUOTES); ?>">
                        <i class='bx bxs-map-pin'></i>
                    </div>

                    <!--Password Input Container-->
                    <div class="input-container">
                        <label for="update-password">Password</label>
                        <input type="password" id="update-password" name="update-password" class="password-input"
                            placeholder="Password" />
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
