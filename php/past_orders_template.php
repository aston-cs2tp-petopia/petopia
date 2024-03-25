<?php
require_once('connectdb.php');

$username = $_SESSION['username']; //Ensures there is a username set

//Grab customer's orders and order it by order id
try {
    $stmt = $db->prepare("SELECT Orders_ID, Order_Date, Total_Amount, Order_Status FROM orders WHERE Customer_ID = (SELECT Customer_ID FROM customer WHERE Username = ?) ORDER BY Orders_ID DESC");
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
        echo '<h3 class="template-orderid-text">Order ID: <span>' . htmlspecialchars($order["Orders_ID"]) . '</span></h3>';
        echo '<p class="template-date-text">Date: <span>' . htmlspecialchars($order["Order_Date"]) . '</span></p>';
        echo '<p class="template-status-text">Status: <span>' . htmlspecialchars($order["Order_Status"]) . '</span></p>'; // UPDATE THIS IN THE FUTURE WHEN PROCESSING IS ADDED
        echo '<p class="tempalte-price-text">Total: <span>£' . htmlspecialchars($order["Total_Amount"]) . '</span></p>';
        //View Receipt button
        echo '<a href="orderconfirm.php?orderId=' . htmlspecialchars($order["Orders_ID"]) . '" class="view-receipt-btn"><div>View Receipt</div></a>';
        //Placeholder for Review button
        echo '</li>';
    }
    echo '</ul>';
} else {
    echo "<p>You haven't placed any orders yet.</p>";
}
?>
