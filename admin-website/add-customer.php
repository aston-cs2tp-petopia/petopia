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
        <script src="../admin-website/jScript/navigationTemplate.js"></script>
        <link href="../css/navigation.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="../css/footer.css">


        <!--CSS-->
        <link href="css/admin-form-template.css" rel="stylesheet" type="text/css">

        <!--CSS Templates-->
        <link rel="stylesheet" href="../templates/hero-banner.css">

    </head>
<body>

<header></header>
<?php
session_start();
require_once('../php/connectdb.php');
$isAdmin = include('../php/isAdmin.php');
require_once('../admin-website/php/adminCheckRedirect.php');
require_once('../php/alerts.php'); // Include alerts.php for displaying messages

// Handle the POST request from the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Extract form data
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $phoneNumber = $_POST['phoneNumber'];
    $address = $_POST['address'];
    $postcode = $_POST['postcode'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $isAdmin = $_POST['isAdmin'];

    // Check if username or email already exists
    $usernameExists = false;
    $emailExists = false;

    $stmt = $db->prepare("SELECT COUNT(*) FROM customer WHERE Username = ?");
    $stmt->execute([$username]);
    $usernameCount = $stmt->fetchColumn();
    if ($usernameCount > 0) {
        $usernameExists = true;
    }

    $stmt = $db->prepare("SELECT COUNT(*) FROM customer WHERE Contact_Email = ?");
    $stmt->execute([$email]);
    $emailCount = $stmt->fetchColumn();
    if ($emailCount > 0) {
        $emailExists = true;
    }

    // If username or email already exists, display error message
    if ($usernameExists || $emailExists) {
        $errorMessage = "";
        if ($usernameExists) {
            $errorMessage .= "Username already exists. Please choose a different username.";
        }
        if ($emailExists) {
            $errorMessage .= "Email already exists. Please use a different email address.";
        }
        jsAlert($errorMessage, false, 3000); // Display error message
    } else {
        // Hash the password for storage
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Add the customer to the database
        try {
            $stmt = $db->prepare("INSERT INTO customer (First_Name, Last_Name, Contact_Email, Phone_Number, Home_Address, Postcode, Username, Password, Is_Admin) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$firstName, $lastName, $email, $phoneNumber, $address, $postcode, $username, $hashedPassword, $isAdmin]);
            jsAlert("Customer added successfully.", true, 3000); // Display success message
        } catch (PDOException $e) {
            jsAlert('Error adding customer: ' . $e->getMessage(), false, 3000); // Display error message
        }
    }
}
?>
<section class="admin-form-section admin-first-section">
    <h2 class="">Add Customer</h2>
    <h3 class="admin-heading">Fill the Form to Create an Account</h3>
    <a class="go-back-link" href="customer-management.php">Back to Customer Management</a>

    <form action="add-customer.php" method="post">
        <div class="input-container">
            <label for="firstName">First Name:</label>
            <input type="text" id="firstName" name="firstName" pattern="[A-Za-z]+" title="Please enter only alphabetic characters" required>
        </div>
        <div class="input-container">
            <label for="lastName">Last Name:</label>
            <input type="text" id="lastName" name="lastName" pattern="[A-Za-z]+" title="Please enter only alphabetic characters" required>
        </div>
        <div class="input-container">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="input-container">
            <label for="phoneNumber">Phone Number:</label>
            <input type="text" id="phoneNumber" name="phoneNumber" pattern="[0-9]{1,11}" title="Please enter a maximum of 11 digits" maxlength="11" required>
        </div>
        <div class="input-container">
            <label for="address">Home Address:</label>
            <input type="text" id="address" name="address" required>
        </div>
        <div class="input-container">
            <label for="postcode">Postcode:</label>
            <input type="text" id="postcode" name="postcode" required>
        </div>
        <div class="input-container">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="input-container">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="input-container">
            <label for="isAdmin">Admin Status:</label>
            <select id="isAdmin" name="isAdmin">
                <option value="0">Customer</option>
                <option value="1">Requested Admin</option>
                <option value="2">Admin</option>
            </select>
        </div>
        <button class="submit-btn" type="submit">Add Customer</button>
    </form>

</section>

</body>
</html>
