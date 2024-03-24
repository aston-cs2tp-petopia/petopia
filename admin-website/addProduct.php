<?php
    session_start();
    require_once('../php/connectdb.php');
    $isAdmin = include('../php/isAdmin.php');
    require_once('../admin-website\php\adminCheckRedirect.php');

    // Handle the POST request from the form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Extract form data
        $name = $_POST['Name'];
        $price = $_POST['Price'];
        $numInStock = $_POST['Num_In_Stock'];
        $description = $_POST['Description'];
        $image = $_POST['Image'];

        // Check if username or email already exists
        $productNameExists = false;

        $stmt = $db->prepare("SELECT COUNT(*) FROM product WHERE Name = ?");
        $stmt->execute([$username]);
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
                $stmt->execute([$Name, $price, $numInStock, $description, $image]);
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
    <title>Add Customer</title>
    <!-- Add necessary CSS links -->
</head>
<body>

<h2>Add Customer</h2>

<form action="add-customer.php" method="post">
    <div>
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" title="Please enter product name" required>
    </div>

    <div>
        <label for="price">Price: £</label>
        <input type="text" id="price" name="price" pattern="&pound;\d+" value="&pound;" title="Please enter the product price (£)" required>
    </div>

    <div>
        <label for="numInStock">Stock:</label>
        <input type="numInStock" id="numInStock" name="numInStock" required>
    </div>

    <div>
        <label for="description">Description:</label>
        <input type="text" id="description" name="description" title="Please enter product description" required>
    </div>

    <div>
        <label for="image">Image:</label>
        <input type="text" id="image" name="image" title="Please import the products image" required>
    </div>

    <button type="submit">Add Product</button>
</form>

</body>
</html>
