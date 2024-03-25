<?php
session_start();
require_once('../php/connectdb.php');
$isAdmin = include('../php/isAdmin.php');

if (!$isAdmin) {
    // Redirect non-admin users to a suitable page
    header("Location: ../index.php");
    exit;
}

if (isset($_GET['Product_ID']) && !empty($_GET['Product_ID'])) {
    $productID = $_GET['Product_ID'];

    try {
        // Check if the product exists
        $stmt = $db->prepare("SELECT * FROM product WHERE Product_ID = :productID");
        $stmt->bindParam(':productID', $productID);
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($product) {
            // Check if there are any associated records in the ordersdetails table
            $orderCheckQuery = "SELECT COUNT(*) FROM ordersdetails WHERE Product_ID = :productID";
            $stmt = $db->prepare($orderCheckQuery);
            $stmt->bindParam(':productID', $productID);
            $stmt->execute();
            $orderCount = $stmt->fetchColumn();

            // If there are associated records in the ordersdetails table, return error 2
            if ($orderCount > 0) {
                header("Location: ../admin-website/productManagement.php?error=2");
                exit;
            } else {
                // No orders associated with the product, proceed with deletion
                // First, delete any associated categories
                $deleteCategoryQuery = "DELETE FROM productcategory WHERE Product_ID = :productID";
                $stmtDeleteCategory = $db->prepare($deleteCategoryQuery);
                $stmtDeleteCategory->bindParam(':productID', $productID);
                $stmtDeleteCategory->execute();

                // Then, delete the product itself
                $deleteProductQuery = "DELETE FROM product WHERE Product_ID = :productID";
                $stmtDeleteProduct = $db->prepare($deleteProductQuery);
                $stmtDeleteProduct->bindParam(':productID', $productID);
                $stmtDeleteProduct->execute();

                // Redirect back to the product management page with success parameter
                header("Location: ../admin-website/productManagement.php?success=1");
                exit;
            }
        } else {
            // Product does not exist, return error 3
            header("Location: ../admin-website/productManagement.php?error=3");
            exit;
        }
    } catch (PDOException $e) {
        // Handle any database errors, return error 4
        header("Location: ../admin-website/productManagement.php?error=4");
        exit;
    }
} else {
    // Product ID not provided or empty
    header("Location: ../admin-website/productManagement.php?error=3");
    exit;
}
?>
