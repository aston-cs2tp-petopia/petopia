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
require_once('../admin-website\php\adminCheckRedirect.php');
require_once('../php/alerts.php');

// Handle the POST request from the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Extract form data
    $name = $_POST['productName'];
    $price = $_POST['price'];
    $numInStock = $_POST['numInStock'];
    $description = $_POST['description'];
    $addCategoryName = $_POST['addCategoryName'];

    // Check product already exists
    $productExists = false;

    $stmt = $db->prepare("SELECT COUNT(*) FROM product WHERE Name = ?");
    $stmt->execute([$name]);
    $productCount = $stmt->fetchColumn();
    if ($productCount > 0) {
        jsAlert("Product already exists. Please choose a different product name.", false, 3000);
    } else {
        // Check if the uploaded file is a JPEG
        $fileType = pathinfo($_FILES["imageFile"]["name"], PATHINFO_EXTENSION);
        if ($fileType !== 'jpeg') {
            jsAlert('Only JPEG image file is valid.', false, 3000);
        } else {
            // Add the product to the database
            try {
                $stmt = $db->prepare("INSERT INTO product (Name, Price, Num_In_Stock, Description) VALUES (?, ?, ?, ?)");
                $stmt->execute([$name, $price, $numInStock, $description]);

                // Get the last inserted product ID
                $productID = $db->lastInsertId();

                // Move file to assets folder with correct naming convention
                $assetsFolder = ("../assets/ProductImages/");
                $fileImport = $assetsFolder . "ImageID_" . $productID . ".jpeg";
                if (move_uploaded_file($_FILES["imageFile"]["tmp_name"], $fileImport)) {
                    // Redirect or inform of success
                    jsAlert("Product added successfully.", true, 3000);
                } else {
                    jsAlert('An error occurred when uploading the file.', false, 3000);
                }

                if ($addCategoryName != "Select") {
                    $addCatNameIDSTMT = $db->prepare("SELECT Category_ID FROM category WHERE Name = ?");
                    $addCatNameIDSTMT->execute([$addCategoryName]);
                    $addCategoryID = $addCatNameIDSTMT->fetch(PDO::FETCH_ASSOC);
                    
                    $addProdCatStmt = $db->prepare("INSERT INTO productcategory (Category_ID, Product_ID) VALUES (?, ?)");
                    $addProdCatStmt->execute([$addCategoryID['Category_ID'], $productID]);
                }

            } catch (PDOException $e) {
                jsAlert("Error adding product: " . $e->getMessage(), false, 3000);
                // Handle error
            }
        }
    }
}
?>

    <section class="admin-form-section admin-first-section">
    <h2 class="">Add Product</h2>
    <h3 class="admin-heading">Fill the Form to Create a Product</h3>
    <a class="go-back-link" href="productManagement.php">Back to Inventory Management System</a> <!-- Back to Admin Dashboard Button -->

    <form action="addProduct.php" method="post" enctype="multipart/form-data">
        <div class="input-container">
            <label for="productName">Name:</label>
            <input type="text" id="productName" name="productName" title="Please enter only alphabetic characters" required>
        </div>

        <div class="input-container">
            <label for="price">Price: </label>
            <input class="admin-input-number" min="0" type="number" id="price" name="price" title="Please enter the products price (£)" required>
        </div>

        <div class="input-container">
            <label for="numInStock">Stock:</label>
            <input class="admin-input-number" min="0" type="number" id="numInStock" name="numInStock"  required>
        </div>

        <label class="label-textarea" for="description">Description:</label>
        <textarea type="text" id="description" name="description" title="Please enter a product description" required></textarea>

        <div class="input-container-select">
            <label for="addCategoryName">Add Category to product</label>
            <select id="addCategoryName" name="addCategoryName">
                <?php
                    echo '<option>Select</option>';

                    $allowedCategoryIDs = [5, 6, 19, 20, 21];
                    $inClause = rtrim(str_repeat('?,', count($allowedCategoryIDs)), ',');
                    
                    $addCatStat = $db->prepare("SELECT * FROM category WHERE Category_ID IN ($inClause)");
                    $addCatStat->execute($allowedCategoryIDs);
                    $addCategories = $addCatStat->fetchAll(PDO::FETCH_ASSOC);

                    foreach($addCategories as $addCategory){
                        echo "<option>". $addCategory['Name'] ."</option>";
                    }
                ?>
            </select>
        </div>

        <div class="label-colors image-padding-container">
            <label for="imageFile">Image File:</label>
            <input type="file" id="imageFile" name="imageFile" title="Image Filename must start with ImageID_[IMAGEID]" required>
        </div>

        <button class="submit-btn" type="submit">Add Product</button>
    </form>
    </section>
    </body>
    
</html>
