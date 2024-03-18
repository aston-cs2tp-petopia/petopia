<?php
require_once 'connectdb.php';

//Checks if it's available
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['productId'], $_POST['customerId'])) {
    $productId = $_POST['productId'];
    $customerId = $_POST['customerId'];

    //Deletes the review
    $sql = "DELETE FROM reviews WHERE Product_ID = ? AND Customer_ID = ?";
    $stmt = $db->prepare($sql);
    $result = $stmt->execute([$productId, $customerId]);

    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'Review deleted successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete review']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>
