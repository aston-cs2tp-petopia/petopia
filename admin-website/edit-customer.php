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

$customerId = $_GET['Customer_ID'] ?? null;
if ($customerId === null) {
    jsAlert("Customer ID is required.", false, 3000); // Display error message
    exit;
}

$customer = ['Is_Admin' => 0]; // Initialize $customer with the 'Is_Admin' key set to 0 by default

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['Customer_ID'])) {
    try {
        $stmt = $db->prepare("SELECT * FROM customer WHERE Customer_ID = ?");
        $stmt->execute([$customerId]);
        $customer = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        jsAlert("Error fetching customer data: " . $e->getMessage(), false, 3000); // Display error message
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_customer'])) {
    $firstName = $_POST['First_Name'];
    $lastName = $_POST['Last_Name'];
    $email = $_POST['Contact_Email'];
    $phoneNumber = $_POST['Phone_Number'];
    $homeAddress = $_POST['Home_Address'];
    $postcode = $_POST['Postcode'];
    $isAdminStatus = $_POST['Is_Admin'];
    $password = isset($_POST['Password']) ? password_hash($_POST['Password'], PASSWORD_DEFAULT) : $customer['Password'];

    try {
        $stmt = $db->prepare("UPDATE customer SET First_Name = ?, Last_Name = ?, Contact_Email = ?, Phone_Number = ?, Home_Address = ?, Postcode = ?, Is_Admin = ?, Password = ? WHERE Customer_ID = ?");
        $stmt->execute([$firstName, $lastName, $email, $phoneNumber, $homeAddress, $postcode, $isAdminStatus, $password, $customerId]);
        jsAlert("Profile information updated successfully.", true, 3000); // Display success message

        // Retrieve the updated customer information from the database
        $stmt = $db->prepare("SELECT * FROM customer WHERE Customer_ID = ?");
        $stmt->execute([$customerId]);
        $customer = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        jsAlert("Error updating customer data: " . $e->getMessage(), false, 3000); // Display error message
    }
}
?>

<section class="admin-form-section admin-first-section">
    <h2 class="">Edit Customer</h2>
    <h3 class="admin-heading">Edit and Update Customer Details</h3>
    <a class="go-back-link" href="customer-management.php">Back to Customer Management</a>

    <form action="edit-customer.php?Customer_ID=<?php echo htmlspecialchars($customerId); ?>" method="post">
        <div class="input-container">
            <label for="First_Name">First Name:</label>
            <input type="text" id="First_Name" name="First_Name" value="<?php echo htmlspecialchars($customer['First_Name'] ?? ''); ?>" required>
        </div>
        <div class="input-container">
            <label for="Last_Name">Last Name:</label>
            <input type="text" id="Last_Name" name="Last_Name" value="<?php echo htmlspecialchars($customer['Last_Name'] ?? ''); ?>" required>
        </div>
        <div class="input-container">
            <label for="Contact_Email">Email:</label>
            <input type="email" id="Contact_Email" name="Contact_Email" value="<?php echo htmlspecialchars($customer['Contact_Email'] ?? ''); ?>" required>
        </div>
        <div class="input-container">
            <label for="Phone_Number">Phone Number:</label>
            <input type="text" id="Phone_Number" name="Phone_Number" value="<?php echo htmlspecialchars($customer['Phone_Number'] ?? ''); ?>" required>
        </div>
        <div class="input-container">
            <label for="Home_Address">Home Address:</label>
            <input type="text" id="Home_Address" name="Home_Address" value="<?php echo htmlspecialchars($customer['Home_Address'] ?? ''); ?>" required>
        </div>
        <div class="input-container">
            <label for="Postcode">Postcode:</label>
            <input type="text" id="Postcode" name="Postcode" value="<?php echo htmlspecialchars($customer['Postcode'] ?? ''); ?>" required>
        </div>
        <div class="input-container">
            <label for="Is_Admin">Status:</label>
            <select id="Is_Admin" name="Is_Admin">
                <option value="0" <?php echo $customer['Is_Admin'] == 0 ? 'selected' : ''; ?>>Customer</option>
                <option value="1" <?php echo $customer['Is_Admin'] == 1 ? 'selected' : ''; ?>>Requested Admin</option>
                <option value="2" <?php echo $customer['Is_Admin'] == 2 ? 'selected' : ''; ?>>Admin</option>
            </select>
        </div>
        <div class="input-container">
            <label for="Password">Reset Password:</label>
            <input type="password" id="Password" name="Password" placeholder="Enter new password">
        </div>
        <button class="submit-btn" type="submit" name="update_customer">Update Customer</button>
    </form>

</section>
</body>
</html>
