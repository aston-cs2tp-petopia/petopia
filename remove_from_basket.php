<?php
require_once('php/connectdb.php');

if (isset($_POST['productId'])) {
    $productId = $_POST['productId'];

    // Perform the database query to remove the item
    $deleteQuery = "DELETE FROM basket WHERE Product_ID = :productId";
    $statement = $db->prepare($deleteQuery);
    $statement->bindParam(':productId', $productId, PDO::PARAM_INT);

    try {
        $statement->execute();
        echo 'Item removed successfully';
    } catch (PDOException $ex) {
        echo 'Error removing item from the basket: ' . $ex->getMessage();
    }
} else {
    echo 'Invalid request';
}
?>
