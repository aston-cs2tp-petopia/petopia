<?php
session_start();
require_once('../php/connectdb.php');

$isAdmin = include('../php/isAdmin.php');
require_once('../admin-website/php/adminCheckRedirect.php');
require_once('../php/alerts.php');

$searchTerm = $_GET['search'] ?? '';
$sortFilter = $_GET['sort'] ?? 'Product_ID'; // Default sorting based on Product ID
$categoryFilter = $_GET['categoryFilter'] ?? ''; // Selected category filter

$loggedInUsername = $_SESSION['username'] ?? ''; // Initialize loggedInUsername

try {
    // Fetch products based on search term, category filter, and sorting option
    $query = "SELECT DISTINCT p.Product_ID, p.Name, p.Price, p.Num_In_Stock, p.Description, p.Image 
            FROM product p 
            JOIN productcategory pc ON p.Product_ID = pc.Product_ID
            WHERE (p.Name LIKE :searchTerm OR p.Product_ID LIKE :searchTerm)";

    // Add category filter condition if a category is selected
    if (!empty($categoryFilter)) {
        $query .= " AND pc.Category_ID = :categoryFilter";
    }

    // Complete the query with sorting condition
    switch ($sortFilter) {
        case 'PriceHighToLow':
            $query .= " ORDER BY p.Price DESC";
            break;
        case 'PriceLowToHigh':
            $query .= " ORDER BY p.Price ASC";
            break;
        case 'NameAZ':
            $query .= " ORDER BY p.Name ASC";
            break;
        case 'NameZA':
            $query .= " ORDER BY p.Name DESC";
            break;
        default:
            $query .= " ORDER BY p.Product_ID ASC"; // Default sorting by Product ID
            break;
    }

    // Prepare and execute the query
    $stmt = $db->prepare($query);
    $stmt->bindValue(':searchTerm', "%$searchTerm%");
    if (!empty($categoryFilter)) {
        $stmt->bindValue(':categoryFilter', $categoryFilter);
    }
    $stmt->execute();
    $product = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    jsAlert('Error: ' . $e->getMessage(), false, 4000);
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
    <h2 class="">Inventory Management System</h2>
    <h3 class="admin-heading">Make Changes to the Inventory</h3>
    <a class="go-back-link" href="adminDashboard.php">Back to Admin Dashboard</a>
    <form action="productManagement.php" method="get">
        <!-- Search -->
        <div class="input-container">
            <input type="text" name="search" placeholder="Search by Name or Product ID" value="<?php echo htmlspecialchars($searchTerm); ?>">
            <i class="bx bx-search"></i>
        </div>

        <div>
        <select id="categoryFilter" name="categoryFilter">
                <option value="">All Categories</option>
                <?php
                // Define allowed category IDs
                $allowedCategoryIDs = [5, 6, 19, 20, 21];

                // Fetch categories based on allowed IDs
                $inClause = rtrim(str_repeat('?,', count($allowedCategoryIDs)), ',');
                $query = "SELECT Category_ID, Name FROM category WHERE Category_ID IN ($inClause)";
                $stmt = $db->prepare($query);
                $stmt->execute($allowedCategoryIDs);
                $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Output category options
                foreach ($categories as $category) {
                    echo "<option value=\"{$category['Category_ID']}\" ";
                    echo isset($_GET['categoryFilter']) && $_GET['categoryFilter'] == $category['Category_ID'] ? 'selected' : '';
                    echo ">{$category['Name']}</option>";
                }
                ?>
            </select>

        <!-- Sorting -->
        <select name="sort">
            <option value="Product_ID" <?php if ($sortFilter == 'Product_ID') echo 'selected'; ?>>Product ID</option>
            <option value="PriceHighToLow" <?php if ($sortFilter == 'PriceHighToLow') echo 'selected'; ?>>Price: High to Low</option>
            <option value="PriceLowToHigh" <?php if ($sortFilter == 'PriceLowToHigh') echo 'selected'; ?>>Price: Low to High</option>
            <option value="NameAZ" <?php if ($sortFilter == 'NameAZ') echo 'selected'; ?>>Name: A-Z</option>
            <option value="NameZA" <?php if ($sortFilter == 'NameZA') echo 'selected'; ?>>Name: Z-A</option>
        </select>
        </div>

        <button class="search-button" type="submit">Search</button>
    </form>
    <a class="add-link" href="addProduct.php" style="padding-top: 10px;"><div>Add Product</div></a> <!-- Add Product Button -->
</section>

<section class="admin-table-section">
        <?php if (!empty($product)): ?>
        <table>
            <thead>
                <tr>
                    <th><b>Image</b></th>
                    <th><b>Product ID</b></th>
                    <th><b>Name</b></th>
                    <th><b>Price</b></th>
                    <th><b>Stock</b></th>
                    <th><b>Description</b></th>
                    <th colspan="2"><b>Actions</b></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($product as $row): ?>
                <tr>
                    <td> <img src='../assets/ProductImages/ImageID_<?php echo $row['Product_ID']; ?>.jpeg' alt='Product Image' style='width:50px;'></td>
                    <td><?php echo $row['Product_ID']; ?></td>
                    <td><?php echo $row['Name']; ?></td>
                    <td>£<?php echo $row['Price']; ?></td>
                    <td><?php echo $row['Num_In_Stock'] > 0 ? $row['Num_In_Stock'] : 'Out of stock'; ?></td>
                    <td class="description-text"><?php echo $row['Description']; ?></td>
                    <td>
                        <a href="editProduct.php?productID=<?php echo $row['Product_ID']; ?>">Edit</a> |
                        <a href="delete-product.php?Product_ID=<?php echo $row['Product_ID']; ?>" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        <p>No products found.</p>
        <?php endif; ?>
    </section>
<?php
// Display success message if present
if (isset($_GET['success']) && $_GET['success'] == 1) {
    require_once('../php/alerts.php');
    jsAlert('Product has been successfully deleted.', true, 3000);
} else if (isset($_GET['error'])) {
    $error = $_GET['error'];
    if ($error == 2) {
        require_once('../php/alerts.php');
        jsAlert('Cannot delete: Existing order uses product', false, 5000);
    } else if ($error == 3) {
        require_once('../php/alerts.php');
        jsAlert('Product ID not provided or empty.', false, 3000);
    }
}
?>


    </body>

</html>