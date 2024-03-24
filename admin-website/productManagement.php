<?php
    session_start();
    require_once('../php/connectdb.php');

    $isAdmin=include('../php/isAdmin.php');
    // echo "$isAdmin"

    require_once('../admin-website\php\adminCheckRedirect.php');

    $searchTerm = $_GET['search'] ?? '';

    // Fetch products based on search term if provided
    try {
        $query = "SELECT Product_ID, Name, Price, Num_In_Stock, Description, Image 
                FROM product 
                WHERE Name LIKE :searchTerm OR Product_ID LIKE :searchTerm";
        $stmt = $db->prepare($query);
        $stmt->execute(['searchTerm' => "%$searchTerm%"]);
        $product = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit;
    }

    // Handle delete operation
    if (isset($_GET['delete']) && $_GET['delete'] !== $loggedInUsername) {
        $productIdToDelete = $_GET['delete'];
        try {
            $deleteQuery = "DELETE FROM product WHERE Product_ID = ?";
            $stmt = $db->prepare($deleteQuery);
            $stmt->execute([$productIdToDelete]);
            header("Location: productManagement.php"); // Redirect after successful deletion
            exit;
        } catch (PDOException $e) {
            echo "Error deleting product: " . $e->getMessage();
            exit;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>

        <!--[Google Fonts]-->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <!--Nunito Font-->
        
        <link
            href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,700;1,800&family=Work+Sans:wght@700;800&display=swap"
            rel="stylesheet">

        <!--Box Icons-->
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

        <!--CSS-->
        <link rel="stylesheet" href="css/admin-dashboard.css">
    </head>

    <body>
        <!--Navigation-->
        <nav class="admin-nav">

        </nav>

        <div class="product-search">
            <form action="productManagement.php" method="get">
                <input type="text" name="search" placeholder="Search by Name or Product ID" value="<?php echo htmlspecialchars($searchTerm); ?>">
                <button type="submit">Search</button>
            </form>
            <a href="addProduct.php">Add Product</a> <!-- Add Product Button -->
            <a href="adminDashboard.php">Back to Admin Dashboard</a> <!-- Back to Admin Dashboard Button -->
        </div>

        <section id="textholder1">
                <?php
                    try {
                        $query = "select * from  product";
                        //run  the query
                        $rows =  $db->query($query);

                        //step 3: display the course list in a table 	
                        if ($rows && $rows->rowCount() > 0) {
                ?>
                        <table cellspacing="10" cellpadding="15" id="productTable">
                            <tr>
                                <th align='left'><b>Image</b></th>
                                <th align='left'><b>Product ID</b></th>
                                <th align='left'><b>Name</b></th>
                                <th align='left'><b>Stock</b></th>
                            </tr>
                    <?php
                        foreach ($rows as $row) {
                            
                            echo  "<td align='left'> <img src='../assets/ProductImages/ImageID_" . $row['Image'] . " .jpeg' alt='Product Image' style='width:50px;'>" . "</td>";
                            echo "<td align='left'>" . $row['Product_ID'] . "</td>";
                            echo "<td align='left'>" . $row['Name'] . "</td>";
                            if ($row['Num_In_Stock']>0){
                                echo "<td align='left'>" . $row['Num_In_Stock'] . "</td>";
                            } else {
                                echo "out of stock";
                            }
                            $productID=$row['Product_ID'];
                            echo '<td align="left"><a href="editProduct.php?productID=' . $productID . '"><Button type="button">Edit Details</Button></a></td>';
                            echo '<td align="left"><a href="productManagement.php?delete=' . $productID .'"><Button type="button">Delete</Button></a></td></tr>';
                        }
                        echo  '</table>';
                    } else {
                        echo  "<p>No  course in the list.</p>\n"; //no match found
                    }
                } catch (PDOexception $ex) {
                    echo "Sorry, a database error occurred! <br>";
                    echo "Error details: <em>" . $ex->getMessage() . "</em>";
                }
                    ?>
            </section>

    </body>

</html>