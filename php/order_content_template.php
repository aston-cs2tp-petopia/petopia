<?php
require_once('php/connectdb.php');

// Assuming $orderId is correctly passed into this template
$orderId = $orderId ?? 0;

try {
    // Fetch order details
    $query = "SELECT od.Product_ID, p.Name, od.Quantity, od.Subtotal
              FROM ordersdetails od
              JOIN product p ON p.Product_ID = od.Product_ID
              WHERE od.Orders_ID = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$orderId]);
    $orderItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($orderItems) {
        echo '<div class="shopping-basket-container">';
        echo '<h2 class="template-header">Order Content</h2>';
        echo '<div class="shopping-basket-items">';

        foreach ($orderItems as $item) {
            echo '<div class="basket-item-template">';
            // Placeholder image path, replace with actual product image path if available
            echo '<div class="item-image"><img src="../assets/Homepage/hero-banner2.jpg" alt="' . htmlspecialchars($item["Name"]) . '"></div>';
            echo '<div class="item-info">';
            // Hyperlink to the product page, assuming 'item.php?Product_ID=x' is the correct path
            echo '<p class="item-name"><a href="item.php?Product_ID=' . htmlspecialchars($item["Product_ID"]) . '" style="text-decoration: none; color: #a349a4;">' . htmlspecialchars($item["Name"]) . '</a></p>';
            echo '<p class="item-stock">Quantity: ' . htmlspecialchars($item["Quantity"]) . '</p>';
            echo '</div>'; // Close item-info
            echo '<div class="item-price-info">';
            echo '<p class="subtotal-heading">Total Price</p>';
            echo '<p class="item-subtotal">£' . htmlspecialchars($item["Subtotal"]) . '</p>';
            echo '</div>'; // Close item-price-info
            echo '</div>'; // Close basket-item-template
        }

        echo '</div>'; // Close shopping-basket-items
        echo '</div>'; // Close shopping-basket-container
    } else {
        echo "<p>No items in this order.</p>";
    }
} catch (PDOException $ex) {
    echo "Sorry, a database error occurred! <br>";
    echo "Error details: <em>" . $ex->getMessage() . "</em>";
}
?>