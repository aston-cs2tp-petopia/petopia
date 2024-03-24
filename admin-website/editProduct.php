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
        $image = $_POST['image'];
        $addCategoryName = $_POST['addCategoryName'];
        $delCategoryName = $_POST['delCategoryName'];

        try {
            $stmt = $db->prepare("UPDATE product SET Name = ?, Price = ?, Num_In_Stock = ?, Description = ?, Image = ? WHERE Product_ID = ?");
            $stmt->execute([$productName, $price, $numInStock, $description, $image, $productID]);

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
        <meta charset="UTF-8">
        <title>Edit Product</title>
        <!-- Add CSS links -->
    </head>

    <body>
        <h2>Edit Product</h2>
        
        <form action="editProduct.php?productID=<?php echo htmlspecialchars($productID); ?>" method="post">
            <label for="productName">Name:</label>
            <input type="text" id="productName" name="productName" value="<?php echo htmlspecialchars($product['Name']); ?>" required>
            
            <label for="price">Price: £</label>
            <input type="number" id="price" name="price" value="<?php echo htmlspecialchars($product['Price']); ?>" required>
                
            <label for="numInStock">Stock:</label>
            <input type="number" id="numInStock" name="numInStock" value="<?php echo htmlspecialchars($product['Num_In_Stock']); ?>" required>
            
            <label for="description">Description:</label>
            <input type="text" id="description" name="description" value="<?php echo htmlspecialchars($product['Description']); ?>" required>

            <label for="image">Image:</label>
            <input type="text" id="image" name="image" value="<?php echo htmlspecialchars($product['Image']); ?>" required>

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
            
            <!-- Delete a category from a product -->
            <label for="delCategoryName">Remove Category to product</label>
            <select id="delCategoryName" name="delCategoryName">
                <?php
                    echo '<option>Select</option>';

                    $delProdCatStat = $db->prepare("SELECT Category_ID FROM productcategory where Product_ID = ?");
                    $delProdCatStat->execute([$product['Product_ID']]);
                    $delProdCatArr=$delProdCatStat->fetchAll(PDO::FETCH_ASSOC);

                    foreach($delProdCatArr as $delProdCat){
                        echo "var_dump($delProdCatArr)";
                        $delCatStat = $db->prepare("SELECT Name FROM category WHERE Category_ID = ?");
                        $delCatStat->execute($delProdCat['Category_ID']);
                        $delCategories = $delCatStat->fetch(PDO::FETCH_ASSOC);
                        echo "<option>". $delCategory['Name'] ."</option>";
                    }

                ?>
            </select>
            <br>
            <button type="submit" name="updateProduct">Update Product</button>
        </form>

        <br>

        <button><a href="productManagement.php">back</a></button>
    </body>
</html>
