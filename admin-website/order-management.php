<?php
session_start();
require_once('../php/connectdb.php');
$isAdmin = include('../php/isAdmin.php');
require_once('../admin-website\php\adminCheckRedirect.php');
require_once('../php/alerts.php'); // Include alerts.php for displaying messages

$searchTerm = $_GET['search'] ?? '';
$statusFilter = $_GET['status'] ?? ''; // Added status filter

// Fetch orders based on search term and status filter if provided
try {
    if (!empty($searchTerm) || !empty($statusFilter)) {
        // Search query to find orders based on Orders_ID, Customer_ID, or Delivery_Address, and optional status filter
        $stmt = $db->prepare("
            SELECT o.*, CONCAT(c.First_Name, ' ', c.Last_Name) AS Customer_Name 
            FROM orders o
            JOIN customer c ON o.Customer_ID = c.Customer_ID
            WHERE (o.Orders_ID LIKE :searchTerm OR o.Customer_ID LIKE :searchTerm OR o.Delivery_Address LIKE :searchTerm)
            AND (:statusFilter = '' OR o.Order_Status = :statusFilter)  -- Added status filter condition
            ORDER BY Orders_ID DESC
        ");
        $stmt->execute(['searchTerm' => "%$searchTerm%", 'statusFilter' => $statusFilter]); // Added status filter parameter
    } else {
        // Default query to fetch all orders
        $stmt = $db->prepare("
            SELECT o.*, CONCAT(c.First_Name, ' ', c.Last_Name) AS Customer_Name 
            FROM orders o
            JOIN customer c ON o.Customer_ID = c.Customer_ID
            ORDER BY Orders_ID DESC
        ");
        $stmt->execute();
    }
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
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
    <h2 class="">Order Management</h2>
    <h3 class="admin-heading">Make Changes to Existing Orders</h3>
    <a class="go-back-link" href="adminDashboard.php">Back to Admin Dashboard</a>
    <form action="order-management.php" method="get">
        <div class="input-container">
            <input type="text" name="search" placeholder="Search by Order ID, Customer ID, or Address" value="<?php echo htmlspecialchars($searchTerm); ?>">
            <i class="bx bx-search"></i>
        </div>

        <!-- Dropdown menu for status filter -->
        <select name="status">
            <option value="">All Status</option>
            <option value="Processing" <?php if ($statusFilter == 'Processing') echo 'selected'; ?>>Processing</option>
            <option value="Shipped" <?php if ($statusFilter == 'Shipped') echo 'selected'; ?>>Shipped</option>
            <option value="Delivered" <?php if ($statusFilter == 'Delivered') echo 'selected'; ?>>Delivered</option>
            <option value="Cancelled" <?php if ($statusFilter == 'Cancelled') echo 'selected'; ?>>Cancelled</option>
            <option value="Returning" <?php if ($statusFilter == 'Returning') echo 'selected'; ?>>Returning</option>
            <option value="Returned" <?php if ($statusFilter == 'Returned') echo 'selected'; ?>>Returned</option>
        </select>

        <button class="search-button" type="submit">Search</button>
    </form>
</section>

<section class="admin-table-section">
    <?php if (!empty($orders)): ?>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Address</th>
                    <th>Total Amount</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['Orders_ID']); ?></td>
                        <td><?php echo htmlspecialchars($order['Customer_Name']); ?></td>
                        <td><?php echo htmlspecialchars($order['Order_Date']); ?></td>
                        <td><?php echo htmlspecialchars($order['Order_Status']); ?></td>
                        <td><?php echo htmlspecialchars($order['Delivery_Address']); ?></td>
                        <td>£<?php echo htmlspecialchars($order['Total_Amount']); ?></td>
                        <td>
                            <a href="edit-order.php?Orders_ID=<?php echo $order['Orders_ID']; ?>">Edit</a> |
                            <a href="delete-order.php?Orders_ID=<?php echo $order['Orders_ID']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No orders found.</p>
    <?php endif; ?>
</section>

<?php
// Display success message if present
if (isset($_GET['success']) && $_GET['success'] == 1) {
    require_once('../php/alerts.php'); // Include alerts.php for displaying messages
    jsAlert('Order has been successfully deleted.', true, 3000);
}
?>

</body>
</html>
