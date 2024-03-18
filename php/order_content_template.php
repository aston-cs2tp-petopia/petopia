<?php
require_once('php/connectdb.php');
$orderId = $orderId ?? 0;

try {
    $query = "SELECT od.Product_ID, p.Name, od.Quantity, od.Subtotal FROM ordersdetails od JOIN product p ON p.Product_ID = od.Product_ID WHERE od.Orders_ID = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$orderId]);
    $orderItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($orderItems) {
        echo '<div class="shopping-basket-container">';
        echo '<h2 class="template-header">Order Content</h2>';
        echo '<div class="shopping-basket-items">';
        foreach ($orderItems as $item) {
            //Checks if a review exists
            $checkReview = $db->prepare("SELECT Review_ID FROM reviews WHERE Product_ID = ? AND Customer_ID = ?");
            $checkReview->execute([$item['Product_ID'], $userId]);
            $reviewExists = $checkReview->fetch(PDO::FETCH_ASSOC);
            echo '<div class="basket-item-template">';
            echo '<div class="item-image"><img src="assets/ProductImages/ImageID_' . htmlspecialchars($item["Product_ID"]) . '.jpeg" alt="' . htmlspecialchars($item["Name"]) . '"></div>';
            echo '<div class="item-right-container">';
            echo '<div class="item-info">';
            echo '<p class="item-name"><a href="item.php?Product_ID=' . htmlspecialchars($item["Product_ID"]) . '" style="text-decoration: none; color: #a349a4;">' . htmlspecialchars($item["Name"]) . '</a></p>';
            echo '<p class="item-stock">Quantity: ' . htmlspecialchars($item["Quantity"]) . '</p>';
            echo '</div>'; //item-info
            echo '<div class="item-price-info">';
            echo '<p class="subtotal-heading">Total Price</p>';
            echo '<p class="item-subtotal">£' . htmlspecialchars($item["Subtotal"]) . '</p>';
            echo '</div>'; //item-price-info
            
            if ($reviewExists) {
                echo '<button class="delete-review-btn" data-product-id="' . htmlspecialchars($item["Product_ID"]) . '">Delete Review</button>';
            } else {
                echo '<button class="review-btn" data-product-id="' . htmlspecialchars($item["Product_ID"]) . '">Review</button>';
            }

            echo '</div>'; //item-right-container
            echo '</div>'; //basket-item-template
        }
        echo '</div>'; //shopping-basket-items
        echo '</div>'; //shopping-basket-container
    } else {
        echo "<p>No items in this order.</p>";
    }
} catch (PDOException $ex) {
    echo "Error: " . $ex->getMessage();
}
?>