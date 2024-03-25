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
require_once('../php/alerts.php');

// Check if it's an edit request (requires product ID in the URL)
$productID = isset($_GET['productID']) ? $_GET['productID'] : null;

if ($productID) {
  // Fetch product information for pre-filling the form
  try {
    $stmt = $db->prepare("SELECT * FROM product WHERE Product_ID = ?");
    $stmt->execute([$productID]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    jsAlert("Error fetching product data: " . $e->getMessage(), false, 3000); // Display alert
    die();
  }

  // If there's no product data, redirect or display an error
  if (!$product) {
    jsAlert("Product not found.", false, 3000);
    // (Redirect to a suitable page)
    die();
  }
} else {
  // No product ID provided, handle error (or redirect)
  jsAlert("Error: No product ID provided for edit.", false, 3000);
  // (Redirect to a suitable page)
  die();
}

// Handle the POST request from the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['productName'];
  $price = $_POST['price'];
  $numInStock = $_POST['numInStock'];
  $description = $_POST['description'];
  $addCategoryName = $_POST['addCategoryName'];

  // Check if the uploaded file is a JPEG
  $fileType = "";
  if (isset($_FILES["imageFile"]["name"]) && $_FILES["imageFile"]["name"] !== "") {
    $fileType = pathinfo($_FILES["imageFile"]["name"], PATHINFO_EXTENSION);
  }

  // Edit product (update existing product with ID)
  if ($fileType === '' || $fileType === 'jpeg') {
    try {
      $stmt = $db->prepare("UPDATE product SET Name = ?, Price = ?, Num_In_Stock = ?, Description = ? WHERE Product_ID = ?");
      $stmt->execute([$name, $price, $numInStock, $description, $productID]);

      // Handle optional image upload (if valid JPEG)
      if ($fileType !== '') {
        // Move file to assets folder with correct naming
        $assetsFolder = ("../assets/ProductImages/");
        $fileImport = $assetsFolder . "ImageID_" . $productID . ".jpeg";
        if (move_uploaded_file($_FILES["imageFile"]["tmp_name"], $fileImport)) {
          jsAlert("Product updated successfully. Image uploaded.", true, 3000);
        } else {
          jsAlert('An error occurred when uploading the file.', false, 3000);
        }
      } else {
        jsAlert("Product updated successfully. No image changes.", true, 3000);
      }

      // Update category
      if ($addCategoryName != "Select") {

        $deleteCatStmt = $db->prepare("DELETE FROM productcategory WHERE Product_ID = ?");
        $deleteCatStmt->execute([$productID]);
  
        $addCatNameIDSTMT = $db->prepare("SELECT Category_ID FROM category WHERE Name = ?");
        $addCatNameIDSTMT->execute([$addCategoryName]);
        $addCategoryID = $addCatNameIDSTMT->fetch(PDO::FETCH_ASSOC);
  
        $addProdCatStmt = $db->prepare("INSERT INTO productcategory (Category_ID, Product_ID) VALUES (?, ?)");
        $addProdCatStmt->execute([$addCategoryID['Category_ID'], $productID]);
      }
    } catch (PDOException $e) {
      jsAlert("Error updating product: " . $e->getMessage(), false, 3000);
    }
  } else {
    jsAlert('Only JPEG image file is valid. Product information not updated.', false, 3000);
  }
}
?>

<section class="admin-form-section admin-first-section">
    <h2 class="">Edit Product</h2>
    <h3 class="admin-heading">Fill the Form to Update Product</h3>
    <a class="go-back-link" href="productManagement.php">Back to Inventory Management System</a>

    <form action="editProduct.php?productID=<?php echo htmlspecialchars($productID); ?>" method="post" enctype="multipart/form-data">

    <div class="admin-form-center">
    <div class="admin-form-image">
          <img src="../assets/ProductImages/ImageID_<?php echo $product['Product_ID']; ?>.jpeg" alt="Current Product Image">
        </div>

    </div>
    
        <div class="input-container">
            <label for="productName">Name:</label>
            <input type="text" id="productName" name="productName" value="<?php echo isset($_POST['productName']) ? htmlspecialchars($_POST['productName']) : (isset($product['Name']) ? htmlspecialchars($product['Name']) : ''); ?>" required>
        </div>

        <div class="input-container">
            <label for="price">Price: </label>
            <input class="admin-input-number" min="0" type="number" id="price" name="price" value="<?php echo isset($_POST['price']) ? htmlspecialchars($_POST['price']) : (isset($product['Price']) ? htmlspecialchars($product['Price']) : ''); ?>" required>
        </div>

        <div class="input-container">
            <label for="numInStock">Stock:</label>
            <input class="admin-input-number" min="0" type="number" id="numInStock" name="numInStock" value="<?php echo isset($_POST['numInStock']) ? htmlspecialchars($_POST['numInStock']) : (isset($product['Num_In_Stock']) ? htmlspecialchars($product['Num_In_Stock']) : ''); ?>" required>
        </div>

        <div class="input-container">
            <label for="description">Description:</label>
            <input type="text" id="description" name="description" value="<?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : (isset($product['Description']) ? htmlspecialchars($product['Description']) : ''); ?>" required>
        </div>

        <div class="input-container">
            <!-- Add a category to a product -->
            <label for="addCategoryName">Add Category to product</label>
            <select id="addCategoryName" name="addCategoryName">
                <?php
                // Fetch and display categories
                $currentCategories = []; // Initialize empty array for category names

                // Join productcategory and category tables to get ALL categories for the product
                $query = "SELECT c.Name AS category_name
                          FROM productcategory pc
                          JOIN category c ON pc.Category_ID = c.Category_ID
                          WHERE pc.Product_ID = ?";
                $stmt = $db->prepare($query);
                $stmt->execute([$productID]);

                // Fetch all associated category names
                while ($categoryRow = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $currentCategories[] = $categoryRow['category_name'];
                }

                // Define the allowed category IDs
                $allowedCategoryIDs = [5, 6, 19, 20, 21];

                // Fetch all categories (while filtered by allowed IDs)
                $inClause = rtrim(str_repeat('?,', count($allowedCategoryIDs)), ',');
                $query = "SELECT Name FROM category WHERE Category_ID IN ($inClause) ORDER BY Name";
                $stmt = $db->prepare($query);
                $stmt->execute($allowedCategoryIDs); // If using allowed IDs

                $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Output options for categories
                echo '<option value="">Select Category</option>';
                foreach ($categories as $category) {
                    $selected = (in_array($category['Name'], $currentCategories)) ? ' selected' : '';
                    echo "<option value=\"" . htmlspecialchars($category['Name']) . "\"" . $selected . ">" . htmlspecialchars($category['Name']) . "</option>";
                }
                ?>
            </select>
        </div>

        <div class="label-colors image-padding-container">
            <label for="imageFile">Image File:</label>
            <input type="file" id="imageFile" name="imageFile" title="Image Filename must start with ImageID_[IMAGEID]">
        </div>

        <button class="submit-btn" type="submit" name="updateProduct">Update Product</button>
    </form>
</section>


</body>
</html>