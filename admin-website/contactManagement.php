<?php
session_start();
require_once('../php/connectdb.php');
$isAdmin = include('../php/isAdmin.php');
require_once('../admin-website/php/adminCheckRedirect.php');

$searchTerm = $_GET['search'] ?? '';

// Fetch customers based on search term if provided
try {
    if (!empty($searchTerm)) {
        // Search query to find customers based on Name or Contact_Email
        $stmt = $db->prepare("
            SELECT * FROM contactforms 
            WHERE Name LIKE :searchTerm OR Contact_Email LIKE :searchTerm
            ORDER BY Contact_ID DESC
        ");
        $stmt->execute(['searchTerm' => "%$searchTerm%"]);
    } else {
        // Default query to fetch all customers sorted by Contact_ID
        $stmt = $db->prepare("
            SELECT * FROM contactforms
            ORDER BY Contact_ID DESC
        ");
        $stmt->execute();
    }
    $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Management</title>
    <link rel="stylesheet" href="../css/admin.css"> <!-- Make sure you have this CSS for styling -->
</head>
<body>

<div class="customer-search">
    <form action="../admin-website/contactManagement.php" method="get">
        <input type="text" name="search" placeholder="Search by Name or Email" value="<?php echo htmlspecialchars($searchTerm); ?>">
        <button type="submit">Search</button>
    </form>
    <a href="adminDashboard.php">Back to Admin Dashboard</a> <!-- Back to Admin Dashboard Button -->
</div>

<div class="customer-list">
    <?php if (!empty($customers)): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Date</th>
                    <th>Message</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($customers as $customer): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($customer['Contact_ID']); ?></td>
                        <td><?php echo htmlspecialchars($customer['Name']); ?></td>
                        <td><?php echo htmlspecialchars($customer['Contact_Email']); ?></td>
                        <td><?php echo htmlspecialchars($customer['Contact_Date']); ?></td>
                        <td><?php echo htmlspecialchars($customer['Contact_Text']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No customers found.</p>
    <?php endif; ?>
</div>

</body>
</html>
