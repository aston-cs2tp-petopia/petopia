<?php
session_start();
require_once('../php/connectdb.php');
$isAdmin = include('../php/isAdmin.php');
require_once('../admin-website/php/adminCheckRedirect.php');
require_once('../php/alerts.php'); // Include alerts.php for displaying messages

// Handle approve/disapprove actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['approve'], $_POST['Customer_ID'])) {
        $customerId = $_POST['Customer_ID'];
        // Process approval action
        try {
            $stmt = $db->prepare("UPDATE customer SET Is_Admin = 2 WHERE Customer_ID = ?");
            $stmt->execute([$customerId]);
            jsAlert('Admin status approved successfully.', true, 3000); // Display success message
        } catch (PDOException $e) {
            jsAlert('Error approving admin: ' . $e->getMessage(), false, 3000); // Display error message
        }
    } elseif (isset($_POST['disapprove'], $_POST['Customer_ID'])) {
        $customerId = $_POST['Customer_ID'];
        // Process disapproval action
        try {
            $stmt = $db->prepare("UPDATE customer SET Is_Admin = 0 WHERE Customer_ID = ?");
            $stmt->execute([$customerId]);
            jsAlert('Admin status disapproved successfully.', true, 3000); // Display success message
        } catch (PDOException $e) {
            jsAlert('Error disapproving admin: ' . $e->getMessage(), false, 3000); // Display error message
        }
    }
}

// Fetch users who have requested admin status
try {
    $stmt = $db->prepare("SELECT * FROM customer WHERE Is_Admin = 1");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    jsAlert('Error fetching users: ' . $e->getMessage(), false, 3000); // Display error message
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

<section class="admin-table-section admin-first-section">
    <h2 class="">Admin Management</h2>
    <h3 class="admin-heading">Users Requesting Admin Status</h3>
    <a class="go-back-link" href="adminDashboard.php">Back to Admin Dashboard</a> <!-- Back to Admin Dashboard Button -->
    <?php if (empty($users)): ?>
        <p>No admin requests at the moment.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['Customer_ID']); ?></td>
                        <td><a href="edit-customer.php?Customer_ID=<?php echo $user['Customer_ID']; ?>"><?php echo htmlspecialchars($user['First_Name'] . ' ' . $user['Last_Name']); ?></a></td>
                        <td><?php echo htmlspecialchars($user['Contact_Email']); ?></td>
                        <td>
                            <form action="adminManagement.php" method="POST">
                                <input type="hidden" name="Customer_ID" value="<?php echo $user['Customer_ID']; ?>">
                                <button class="green-btn" type="submit" name="approve">Approve</button>
                                <button class="red-btn" type="submit" name="disapprove">Disapprove</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>

</body>
</html>
