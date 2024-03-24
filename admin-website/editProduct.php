<?php
    session_start();
    require_once('../php/connectdb.php');
    $isAdmin = include('../php/isAdmin.php');

    require_once('../admin-website\php\adminCheckRedirect.php');

    $productID = $_GET['productID'] ?? null;
    if ($productID === null) {
        die("Product ID is required.");
    }

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

        try {
            $stmt = $db->prepare("UPDATE product SET Name = ?, Price = ?, Num_In_Stock = ?, Description = ?, Image = ? WHERE Product_ID = ?");
            $stmt->execute([$productName, $price, $numInStock, $description, $image, $productID]);
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
        <title>Edit Customer</title>
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
            <input type="text" id="description" name="description" rows="4" cols="35" value="<?php echo htmlspecialchars($product['Description']); ?>" required>

            <label for="image">Image:</label>
            <input type="text" id="image" name="image" value="<?php echo htmlspecialchars($product['Image']); ?>" required>

            <button type="submit" name="updateProduct">Update Product</button>
        </form>
    </body>
</html>