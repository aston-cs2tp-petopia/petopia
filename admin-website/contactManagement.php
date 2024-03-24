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
    <h2 class="">Contact Management</h2>
    <h3 class="admin-heading">View and Respond to Customer Forms</h3>
    <a class="go-back-link" href="adminDashboard.php">Back to Admin Dashboard</a>
    <form action="../admin-website/contactManagement.php" method="get">
        <div class="input-container">
            <input type="text" name="search" placeholder="Search by Name or Email" value="<?php echo htmlspecialchars($searchTerm); ?>">
            <i class="bx bx-search"></i>
        </div>
        <button class="search-button" type="submit">Search</button>
    </form>
</section>

<section class="admin-table-section">
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
                        <td><a href="mailto:<?php echo htmlspecialchars($customer['Contact_Email']); ?>"><?php echo htmlspecialchars($customer['Contact_Email']); ?></a></td>
                        <td><?php echo htmlspecialchars($customer['Contact_Date']); ?></td>
                        <td><?php echo htmlspecialchars($customer['Contact_Text']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No customers found.</p>
    <?php endif; ?>
</section>

</body>
</html>
