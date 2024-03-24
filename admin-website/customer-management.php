<?php
    require_once('../php/connectdb.php');
    session_start();
    $isAdmin = include('../php/isAdmin.php');
    $loggedInUsername = $_SESSION['username'] ?? ''; // Assuming session username is stored upon login

    require_once('../admin-website\php\adminCheckRedirect.php');

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
        echo "Error: " . $e->getMessage();
        exit;
    }

    // Handle delete operation
    if (isset($_GET['delete']) && $_GET['delete'] !== $loggedInUsername) {
        $customerIdToDelete = $_GET['delete'];
        try {
            $deleteQuery = "DELETE FROM customer WHERE Customer_ID = ?";
            $stmt = $db->prepare($deleteQuery);
            $stmt->execute([$customerIdToDelete]);
            header("Location: customer-management.php"); // Redirect after successful deletion
            exit;
        } catch (PDOException $e) {
            echo "Error deleting customer: " . $e->getMessage();
            exit;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Customer Management</title>
        <!-- Add necessary CSS links -->
    </head>
    <body>

    <div class="customer-search">
        <form action="customer-management.php" method="get">
            <input type="text" name="search" placeholder="Search by Name or Customer ID" value="<?php echo htmlspecialchars($searchTerm); ?>">
            <button type="submit">Search</button>
        </form>
        <a href="add-customer.php">Add Customer</a> <!-- Add Customer Button -->
        <a href="adminDashboard.php">Back to Admin Dashboard</a> <!-- Back to Admin Dashboard Button -->
    </div>

    <div class="customer-list">
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
                                <a href="edit-customer.php?Customer_ID=<?php echo $customer['Customer_ID']; ?>">Edit</a>
                                <!-- Include Delete link only if not the logged-in user -->
                                <a href="customer-management.php?delete=<?php echo $customer['Customer_ID']; ?>" onclick="return confirm('Are you sure you want to delete this customer?')">Delete</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    </body>
</html>
