<?php
require_once('../php/connectdb.php');
session_start();
$isAdmin = include('../php/isAdmin.php');

// Redirect if not admin or not logged in
if (!$isAdmin || !isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}

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
        if ($usernameExists) {
            echo "Username already exists. Please choose a different username.";
        }
        if ($emailExists) {
            echo "Email already exists. Please use a different email address.";
        }
    } else {
        // Hash the password for storage
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Add the customer to the database
        try {
            $stmt = $db->prepare("INSERT INTO customer (First_Name, Last_Name, Contact_Email, Phone_Number, Home_Address, Postcode, Username, Password, Is_Admin) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$firstName, $lastName, $email, $phoneNumber, $address, $postcode, $username, $hashedPassword, $isAdmin]);
            // Redirect or inform of success
            echo "Customer added successfully.";
        } catch (PDOException $e) {
            echo "Error adding customer: " . $e->getMessage();
            // Handle error
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Customer</title>
    <!-- Add necessary CSS links -->
</head>
<body>

<h2>Add Customer</h2>

<form action="add-customer.php" method="post">
    <div>
        <label for="firstName">First Name:</label>
        <input type="text" id="firstName" name="firstName" pattern="[A-Za-z]+" title="Please enter only alphabetic characters" required>
    </div>
    <div>
        <label for="lastName">Last Name:</label>
        <input type="text" id="lastName" name="lastName" pattern="[A-Za-z]+" title="Please enter only alphabetic characters" required>
    </div>
    <div>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
    </div>
    <div>
        <label for="phoneNumber">Phone Number:</label>
        <input type="text" id="phoneNumber" name="phoneNumber" pattern="[0-9]{1,11}" title="Please enter a maximum of 11 digits" maxlength="11" required>
    </div>
    <div>
        <label for="address">Home Address:</label>
        <input type="text" id="address" name="address" required>
    </div>
    <div>
        <label for="postcode">Postcode:</label>
        <input type="text" id="postcode" name="postcode" required>
    </div>
    <div>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
    </div>
    <div>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
    </div>
    <div>
        <label for="isAdmin">Admin Status:</label>
        <select id="isAdmin" name="isAdmin">
            <option value="0">Customer</option>
            <option value="1">Requested Admin</option>
            <option value="2">Admin</option>
        </select>
    </div>
    <button type="submit">Add Customer</button>
</form>

</body>
</html>
