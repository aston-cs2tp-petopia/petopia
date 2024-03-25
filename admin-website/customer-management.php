<?php
require_once('../php/connectdb.php');
session_start();
$isAdmin = include('../php/isAdmin.php');
$loggedInUsername = $_SESSION['username'] ?? '';

require_once('../admin-website\php\adminCheckRedirect.php');
require_once('../php/alerts.php');

$searchTerm = $_GET['search'] ?? '';

// Fetch customers based on search term if provided
try {
    $query = "SELECT Customer_ID, First_Name, Last_Name, Contact_Email, Phone_Number, Home_Address, Postcode, Username, Is_Admin 
            FROM customer 
            WHERE CONCAT(First_Name, ' ', Last_Name) LIKE :searchTerm OR Customer_ID LIKE :searchTerm";
    $stmt = $db->prepare($query);
    $stmt->execute(['searchTerm' => "%$searchTerm%"]);
    $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    jsAlert('Error: ' . $e->getMessage(), false, 4000);
    exit;
}

// Handle delete operation
if (isset($_GET['delete']) && $_GET['delete'] !== $loggedInUsername) {
    $customerIdToDelete = $_GET['delete'];
    try {
        $deleteQuery = "DELETE FROM customer WHERE Customer_ID = ?";
        $stmt = $db->prepare($deleteQuery);
        $stmt->execute([$customerIdToDelete]);
        
        // Redirect back to the same page after deletion with success parameter
        header("Location: customer-management.php?success=1");
        exit;
    } catch (PDOException $e) {
        jsAlert('Error deleting customer: ' . $e->getMessage(), true, 3000);
        exit;
    }
}

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
        <script src="../admin-website/jScript/navigationTemplate.js"></script>
        <link href="../css/navigation.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="../css/footer.css">


        <!--CSS-->
        <link href="css/admin-table-template.css" rel="stylesheet" type="text/css">

        <!--CSS Templates-->
        <link rel="stylesheet" href="../templates/hero-banner.css">

    </head>
    <body>
<header></header>
    <section class="admin-search-section admin-first-section">
    <h2 class="">Customer Management</h2>
    <h3 class="admin-heading">Make Changes to Customer Profiles</h3>
    <a class="go-back-link" href="adminDashboard.php">Back to Admin Dashboard</a>
        <form action="customer-management.php" method="get">
            <div class="input-container">
                <input type="text" name="search" placeholder="Search by Name or Customer ID" value="<?php echo htmlspecialchars($searchTerm); ?>">
                <i class="bx bx-search"></i>
            </div>
            <button class="search-button" type="submit">Search</button>
        </form>

        <div class="other-links">
            <a class="add-link" href="add-customer.php"><div>New Customer</div></a>
        </div>
</section>

    <section class="admin-table-section">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Address</th>
                    <th>Postcode</th>
                    <th>Username</th>
                    <th>Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($customers as $customer): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($customer['Customer_ID']); ?></td>
                        <td><?php echo htmlspecialchars($customer['First_Name'] . ' ' . $customer['Last_Name']); ?></td>
                        <td><?php echo htmlspecialchars($customer['Contact_Email']); ?></td>
                        <td><?php echo htmlspecialchars($customer['Phone_Number']); ?></td>
                        <td><?php echo htmlspecialchars($customer['Home_Address']); ?></td>
                        <td><?php echo htmlspecialchars($customer['Postcode']); ?></td>
                        <td><?php echo htmlspecialchars($customer['Username']); ?></td>
                        <td><?php echo $customer['Is_Admin'] == 2 ? 'Admin' : ($customer['Is_Admin'] == 1 ? 'Requested Admin' : 'Customer'); ?></td>
                        <td>
                            <?php if ($customer['Username'] !== $loggedInUsername): // Prevent actions against self ?>
                                <a href="edit-customer.php?Customer_ID=<?php echo $customer['Customer_ID']; ?>">Edit</a> |
                                <!-- Include Delete link only if not the logged-in user -->
                                <a href="customer-management.php?delete=<?php echo $customer['Customer_ID']; ?>" onclick="return confirm('Are you sure you want to delete this customer?')">Delete</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
</section>
<?php
// Display success message if present
if (isset($_GET['success']) && $_GET['success'] == 1) {
    require_once('../php/alerts.php');
    jsAlert('Customer has been successfully deleted.', true, 3000);
}
?>

    </body>
</html>
