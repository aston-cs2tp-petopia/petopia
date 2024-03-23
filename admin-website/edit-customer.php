<?php
require_once('../php/connectdb.php');
session_start();
$isAdmin = include('../php/isAdmin.php');

if (!$isAdmin) {
    header("Location: ../index.php");
    exit;
}

$customerId = $_GET['Customer_ID'] ?? null;
if ($customerId === null) {
    die("Customer ID is required.");
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['Customer_ID'])) {
    try {
        $stmt = $db->prepare("SELECT * FROM customer WHERE Customer_ID = ?");
        $stmt->execute([$customerId]);
        $customer = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error fetching customer data: " . $e->getMessage());
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
    $password = $_POST['Password'] ? password_hash($_POST['Password'], PASSWORD_DEFAULT) : $customer['Password'];

    try {
        $stmt = $db->prepare("UPDATE customer SET First_Name = ?, Last_Name = ?, Contact_Email = ?, Phone_Number = ?, Home_Address = ?, Postcode = ?, Is_Admin = ?, Password = ? WHERE Customer_ID = ?");
        $stmt->execute([$firstName, $lastName, $email, $phoneNumber, $homeAddress, $postcode, $isAdminStatus, $password, $customerId]);
        echo "Customer information updated successfully.";
    } catch (PDOException $e) {
        die("Error updating customer data: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Customer</title>
    <!-- Add CSS links -->
</head>
<body>
<h2>Edit Customer</h2>
<form action="edit-customer.php?Customer_ID=<?php echo htmlspecialchars($customerId); ?>" method="post">
    <label for="First_Name">First Name:</label>
    <input type="text" id="First_Name" name="First_Name" value="<?php echo htmlspecialchars($customer['First_Name']); ?>" required>
    
    <label for="Last_Name">Last Name:</label>
    <input type="text" id="Last_Name" name="Last_Name" value="<?php echo htmlspecialchars($customer['Last_Name']); ?>" required>
    
    <label for="Contact_Email">Email:</label>
    <input type="email" id="Contact_Email" name="Contact_Email" value="<?php echo htmlspecialchars($customer['Contact_Email']); ?>" required>
    
    <label for="Phone_Number">Phone Number:</label>
    <input type="text" id="Phone_Number" name="Phone_Number" value="<?php echo htmlspecialchars($customer['Phone_Number']); ?>" required>
    
    <label for="Home_Address">Home Address:</label>
    <input type="text" id="Home_Address" name="Home_Address" value="<?php echo htmlspecialchars($customer['Home_Address']); ?>" required>
    
    <label for="Postcode">Postcode:</label>
    <input type="text" id="Postcode" name="Postcode" value="<?php echo htmlspecialchars($customer['Postcode']); ?>" required>
    
    <label for="Is_Admin">Status:</label>
    <select id="Is_Admin" name="Is_Admin">
        <option value="0" <?php echo $customer['Is_Admin'] == 0 ? 'selected' : ''; ?>>Customer</option>
        <option value="1" <?php echo $customer['Is_Admin'] == 1 ? 'selected' : ''; ?>>Requested Admin</option>
        <option value="2" <?php echo $customer['Is_Admin'] == 2 ? 'selected' : ''; ?>>Admin</option>
    </select>

    <label for="Password">Reset Password:</label>
    <input type="password" id="Password" name="Password" placeholder="Enter new password">

    <button type="submit" name="update_customer">Update Customer</button>
</form>
</body>
</html>
