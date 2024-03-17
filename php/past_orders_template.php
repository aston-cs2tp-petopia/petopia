<?php
require_once('connectdb.php');

$username = $_SESSION['username']; //Ensures there is a username set

//Grab customer's orders and order it by order id
try {
    $stmt = $db->prepare("SELECT Orders_ID, Order_Date, Total_Amount FROM orders WHERE Customer_ID = (SELECT Customer_ID FROM customer WHERE Username = ?) ORDER BY Orders_ID DESC");
    $stmt->execute([$username]);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}

if ($orders) {
    echo '<ul class="orders-list">';
    foreach ($orders as $order) {
        echo '<li class="order-item">';
        echo '<h3>Order ID: ' . htmlspecialchars($order["Orders_ID"]) . '</h3>';
        echo '<p>Date: ' . htmlspecialchars($order["Order_Date"]) . '</p>';
        echo '<p>Status: Processing</p>'; // UPDATE THIS IN THE FUTURE WHEN PROCESSING IS ADDED
        echo '<p>Total: £' . htmlspecialchars($order["Total_Amount"]) . '</p>';
        //View Receipt button
        echo '<a href="orderconfirm.php?orderId=' . htmlspecialchars($order["Orders_ID"]) . '" class="view-receipt-btn">View Receipt</a>';
        //Placeholder for Review button
        echo '<button class="review-product-btn">Review Product</button>';
        echo '</li>';
    }
    echo '</ul>';
} else {
    echo "<p>You haven't placed any orders yet.</p>";
}
?>
