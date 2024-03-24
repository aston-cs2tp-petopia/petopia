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
        $image = $_POST['image'];
        $addCategoryName = $_POST['addCategoryName'];

        // Check if username or email already exists
        $productExists = false;

        $stmt = $db->prepare("SELECT COUNT(*) FROM product WHERE Name = ?");
        $stmt->execute([$name]);
        $productCount = $stmt->fetchColumn();
        if ($productCount > 0) {
            $productExists = true;
        }

        // If username or email already exists, display error message
        if ($productExists) {
            echo "Username already exists. Please choose a different username.";
        } else {
            // Add the product to the database
            try {
                $stmt = $db->prepare("INSERT INTO product (Name, Price, Num_In_Stock, Description, Image) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$name, $price, $numInStock, $description, $image]);
                $productIDFetch=$stmt->fetchAll(PDO::FETCH_ASSOC);

                if (!$addCategoryName!="Select"){
                    $addCatNameIDSTMT = $db->prepare("SELECT Category_ID FROM category WHERE Name = ?");
                    $addCatNameIDSTMT->execute([$addCategoryName]);
                    $addCategoryID=$addCatNameIDSTMT->fetch(PDO::FETCH_ASSOC);
                    
                    $productIDStmt=$db->prepare("SELECT Product_ID FROM product WHERE Name = ?");
                    $productIDStmt->execute([$name]);
                    $productID=$productIDStmt->fetch(PDO::FETCH_ASSOC);
    
                    $addProdCatStmt = $db->prepare("INSERT INTO productcategory Category_ID = ?, Product_ID = ?");
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

<form action="addProduct.php" method="post">
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
        <label for="image">Image:</label>
        <input type="text" id="image" name="image" title="Please enter an image link of the product" required>
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
