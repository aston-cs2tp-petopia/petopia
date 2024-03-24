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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Management</title>
    <link rel="stylesheet" href="../css/admin.css"> <!-- Make sure you have this CSS for styling -->
</head>
<body>

<div class="user-list">
    <h2>Users Requesting Admin Status</h2>
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
                                <button type="submit" name="approve">Approve</button>
                                <button type="submit" name="disapprove">Disapprove</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

</body>
</html>
