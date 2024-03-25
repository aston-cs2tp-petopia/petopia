<?php
    session_start();
    require_once('../php/connectdb.php');
    $isAdmin = include('../php/isAdmin.php');
    require_once('../admin-website\php\adminCheckRedirect.php');

    // Handle the POST request from the form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Extract form data
        $name = $_POST['productName'];
        $price = $_POST['price'];
        $numInStock = $_POST['numInStock'];
        $description = $_POST['description'];
        $imageID = $_POST['imageID'];
        // $imageFile = $_POST['imageFile'];
        $addCategoryName = $_POST['addCategoryName'];

        // Check if username or email already exists
        $productExists = false;

        $stmt = $db->prepare("SELECT COUNT(*) FROM product WHERE Name = ?");
        $stmt->execute([$name]);
        $productCount = $stmt->fetchColumn();
        if ($productCount > 0) {
            $productExists = true;
        }

        // If product already exists, display error message
        if ($productExists) {
            echo "Product already exists. Please choose a different product name.";
        } else {
            // Add the product to the database
            try {
                $stmt = $db->prepare("INSERT INTO product (Name, Price, Num_In_Stock, Description, Image) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$name, $price, $numInStock, $description, $imageID]);
                $productIDFetch=$stmt->fetchAll(PDO::FETCH_ASSOC);

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
                    $addCategoryID=$addCatNameIDSTMT->fetch(PDO::FETCH_ASSOC);
                    
                    $productIDStmt=$db->prepare("SELECT Product_ID FROM product WHERE Name = ?");
                    $productIDStmt->execute([$name]);
                    $productID=$productIDStmt->fetch(PDO::FETCH_ASSOC);
    
                    $addProdCatStmt = $db->prepare("INSERT INTO productcategory (Category_ID, Product_ID) VALUES (?, ?)");
                    $addProdCatStmt->execute([$addCategoryID['Category_ID'], $productID['Product_ID']]);
                }

                // Redirect or inform of success
                echo "Product added successfully.";
            } catch (PDOException $e) {
                echo "Error adding product: " . $e->getMessage();
                // Handle error
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product</title>
    <!-- Add necessary CSS links -->
</head>
    <body>

    <h2>Add Product</h2>
    <a href="productManagement.php">Back to Inventory Management System</a> <!-- Back to Admin Dashboard Button -->

    <form action="addProduct.php" method="post" enctype="multipart/form-data">
        <div>
            <label for="productName">Name:</label>
            <input type="text" id="productName" name="productName" title="Please enter only alphabetic characters" required>
        </div>

        <div>
            <label for="price">Price: £</label>
            <input type="number" id="price" name="price" title="Please enter the products price (£)" required>
        </div>

        <div>
            <label for="numInStock">Stock:</label>
            <input type="number" id="numInStock" name="numInStock"  required>
        </div>

        <div>
            <label for="description">Description:</label>
            <input type="text" id="description" name="description" title="Please enter a product description" required>
        </div>

        <div>
            <label for="imageID">Image ID:</label>
            <input type="text" id="imageID" name="imageID" title="Please enter the image ID" required>
        </div>

        <div>
            <label for="imageFile">Image File:</label>
            <input type="file" id="imageFile" name="imageFile" title="Image Filename must start with ImageID_[IMAGEID]" required>
        </div>

        <div>
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

        <button type="submit">Add Product</button>
    </form>

    </body>
</html>
