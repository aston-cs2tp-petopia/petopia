<?php
require_once 'connectdb.php';
require_once 'alerts.php';

$input = json_decode(file_get_contents('php://input'), true);

if (isset($input['Customer_ID'], $input['Product_ID'], $input['rating'], $input['Rev_Text'])) {
    $customerId = $input['Customer_ID'];
    $productId = $input['Product_ID'];
    $rating = $input['rating'];
    $reviewText = $input['Rev_Text'];
    $reviewDate = date('Y-m-d'); //Current date

    //Query
    $query = "INSERT INTO reviews (Customer_ID, Product_ID, Review_Date, rating, Rev_Text) VALUES (?, ?, ?, ?, ?)";

    $stmt = $db->prepare($query);
    $success = $stmt->execute([$customerId, $productId, $reviewDate, $rating, $reviewText]);

    if ($success) {
        echo json_encode(['status' => 'success', 'message' => 'Review submitted successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to submit review']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
}
?>
