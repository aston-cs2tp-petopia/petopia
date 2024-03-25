<?php
    session_start();
    require_once('../php/connectdb.php');
    $isAdmin = include('../php/isAdmin.php');

    require_once('../admin-website\php\adminCheckRedirect.php');

    $productID = $_GET['productID'] ?? null;
    if ($productID === null) {
        die("Product ID is required.");
    }

    $product=[];

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($productID)) {
        try {
            $stmt = $db->prepare("SELECT * FROM product WHERE Product_ID = ?");
            $stmt->execute([$productID]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error fetching product data: " . $e->getMessage());
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateProduct'])) {
        $productName = $_POST['productName'];
        $price = $_POST['price'];
        $numInStock = $_POST['numInStock'];
        $description = $_POST['description'];
        $imageID = $_POST['imageID'];
        $addCategoryName = $_POST['addCategoryName'];
        $delCategoryName = $_POST['delCategoryName'];

        try {
            $stmt = $db->prepare("UPDATE product SET Name = ?, Price = ?, Num_In_Stock = ?, Description = ?, Image = ? WHERE Product_ID = ?");
            $stmt->execute([$productName, $price, $numInStock, $description, $imageID, $productID]);

            $fileValid = true;
            $assetsFolder = ("../assets/ProductImages/");
            $fileImport = $assetsFolder . $_FILES["imageFile"]["name"];
            $fileType = pathinfo($_FILES["imageFile"]["name"], PATHINFO_EXTENSION);
            $acceptedTypes = array("jpg", "jpeg", "png");

            if (!getimagesize($_FILES["imageFile"]["tmp_name"])){
                echo 'File is not compatible';
                $fileValid=false;
            }
            
            // 300 kb max
            if ($_FILES["imageFile"]["size"] > 307200) {
                echo "File is too large. Upload a smaller file.";
                $fileValid = false;
            }

            if (!in_array($fileType, $acceptedTypes)) {
                echo "Only JPG, JPEG & PNG are valid.";
                $fileValid = false;
            }

            if ($fileValid) {
                // Move file to assets folder
                if (move_uploaded_file($_FILES["imageFile"]["tmp_name"], $fileImport)) {
                    // Upload complete
                    echo "File: ". htmlspecialchars($_FILES["imageFile"]["name"]). " has been uploaded.";
                } else {
                    echo "An error occurred when uploading the file.";
                }
            } else {
                echo "File is incompatible.";
            }

            if ($addCategoryName!="Select"){
                $addCatNameIDSTMT = $db->prepare("SELECT Category_ID FROM category WHERE Name = ?");
                $addCatNameIDSTMT->execute([$addCategoryName]);
                $addCategoryID = $addCatNameIDSTMT->fetch(PDO::FETCH_ASSOC);

                $addProdCatStmt = $db->prepare("INSERT INTO productcategory (Category_ID, Product_ID) values(?, ?)");
                $addProdCatStmt->execute([$addCategoryID['Category_ID'], $productID]);
            }

            if ($delCategoryName!="Select"){
                $delCatNameIDSTMT = $db->prepare("SELECT Category_ID FROM category WHERE Name = ?");
                $delCatNameIDSTMT->execute([$delCategoryName]);
                $delCategoryID = $delCatNameIDSTMT->fetch(PDO::FETCH_ASSOC);

                $delProdCatStmt = $db->prepare("DELETE FROM productcategory WHERE Category_ID = ? AND Product_ID = ?");
                echo'issue starts at deleted';
                $delProdCatStmt->execute([$delCategoryID['Category_ID'], $productID]);
            }

            echo "Product information updated successfully.";
        } catch (PDOException $e) {
            die("Error updating product data: " . $e->getMessage());
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
        <link href="css/admin-form-template.css" rel="stylesheet" type="text/css">

        <!--CSS Templates-->
        <link rel="stylesheet" href="../templates/hero-banner.css">
    </head>

    <body>
        <section class="admin-form-section admin-first-section">
            <h2>Edit Product</h2>
            <button><a href="productManagement.php">back</a></button>

            
            <form action="editProduct.php?productID=<?php echo htmlspecialchars($productID); ?>" method="post" enctype="multipart/form-data">
                <div class="input-container">
                    <label for="productName">Name:</label>
                    <input type="text" id="productName" name="productName" value="<?php echo htmlspecialchars($product['Name']); ?>" required>
                </div>

                <div class="input-container">
                    <label for="price">Price: £</label>
                    <input type="number" id="price" name="price" value="<?php echo htmlspecialchars($product['Price']); ?>" required>
                </div>
                    
                <div class="input-container">
                    <label for="numInStock">Stock:</label>
                    <input type="number" id="numInStock" name="numInStock" value="<?php echo htmlspecialchars($product['Num_In_Stock']); ?>" required>
                </div>
                
                <div class="input-container">
                    <label for="description">Description:</label>
                    <input type="text" id="description" name="description" value="<?php echo htmlspecialchars($product['Description']); ?>" required>
                </div>

                <div class="input-container">
                    <label for="imageID">Image ID:</label>
                    <input type="text" id="imageID" name="imageID" value="<?php echo htmlspecialchars($product['Image']); ?>" required>
                </div>

                <div class="input-container">
                    <label for="imageFile">Image File:</label>
                    <input type="file" id="imageFile" name="imageFile" title="Image Filename must start with ImageID_[IMAGEID]" required>
                </div>

                <div class="input-container">
                    <!-- Add a category to a product -->
                    <label for="addCategoryName">Add Category to product</label>
                    <select id="addCategoryName" name="addCategoryName">
                        <?php
                            echo '<option>Select</option>';

                            $addCatStat = $db->prepare("SELECT * FROM category");
                            $addCatStat->execute();
                            $addCategories = $addCatStat->fetchAll(PDO::FETCH_ASSOC);

                            foreach($addCategories as $addCategory){
                                echo "<option>". $addCategory['Name'] ."</option>";
                            }

                        ?>
                    </select>
                </div>
                
                <div class="input-container">
                    <!-- Delete a category from a product -->
                    <label for="delCategoryName">Remove Category to product</label>
                    <select id="delCategoryName" name="delCategoryName">
                        <?php
                            echo '<option>Select</option>';

                            $delProdCatStat = $db->prepare("SELECT Category_ID FROM productcategory where Product_ID = ?");
                            $delProdCatStat->execute([$product['Product_ID']]);
                            $delProdCatArr=$delProdCatStat->fetchAll(PDO::FETCH_ASSOC);

                            foreach($delProdCatArr as $delProdCat){
                                $delCatStat = $db->prepare("SELECT Name FROM category WHERE Category_ID = ?");
                                $delCatStat->execute([$delProdCat['Category_ID']]);
                                $delCategories = $delCatStat->fetch(PDO::FETCH_ASSOC);
                                echo "<option>". $delCategories['Name'] ."</option>";
                            }

                        ?>
                    </select>
                </div>
                <br>
                <button class="submit-btn" type="submit" name="updateProduct">Update Customer</button>
            </form>
        </section>

    </body>
</html>
