<?php
require_once('php/connectdb.php');

try {
    $username = $_SESSION["username"];
    $query = "SELECT product.Product_ID, product.Name, product.Num_In_Stock, basket.Quantity, basket.Subtotal
              FROM basket 
              JOIN product ON product.Product_ID = basket.Product_ID 
              JOIN customer ON basket.Customer_ID = customer.Customer_ID
              WHERE customer.Username = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$username]);
    $basketItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($basketItems) {
        ?>
        <div class="shopping-basket-container">
            <h2 class="template-header">Shopping Basket</h2>
            <div class="shopping-basket-items">
                <?php foreach ($basketItems as $item) { ?>
                    <div class="basket-item-template">
                        <div class="item-image">
                            <img src="../assets/Homepage/hero-banner2.jpg" alt="">
                        </div>
                        <div class="item-info">
                            <!-- Hyperlink for item name -->
                            <p class="item-name"><a style="text-decoration: none; color: #a349a4;" href="item.php?Product_ID=<?php echo htmlspecialchars($item["Product_ID"]); ?>"><?php echo htmlspecialchars($item["Name"]); ?></a></p>
                            <p class="item-stock">Stock: <?php echo htmlspecialchars($item["Num_In_Stock"]); ?></p>
                            <!-- Quantity Selector without redundant text -->
                            <form action='php/update_quantity_basket.php' method='post'>
                                <input type='hidden' name='productID' value="<?php echo htmlspecialchars($item["Product_ID"]); ?>">
                                Quantity:
                                <select name='quantity' class='quantity-selector' onchange='this.form.submit()'>
                                    <option value='0'<?php echo $item["Quantity"] == 0 ? " selected" : ""; ?>>0 (Remove)</option>
                                    <?php for ($i = 1; $i <= $item["Num_In_Stock"]; $i++) {
                                        echo "<option value='$i'" . ($i === (int)$item["Quantity"] ? " selected" : "") . ">$i</option>";
                                    } ?>
                                </select>
                            </form>
                        </div>
                        <div class="item-price-info">
                            <p class="subtotal-heading">Total Price</p>
                            <p class="item-subtotal">£<?php echo htmlspecialchars($item["Subtotal"]); ?></p>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>

        <div class="order-summary-container">
            <h2 class="template-header">Order Summary</h2>
            <?php
            //Calculate total price and num of items
            $totalItems = count($basketItems);
            $totalPrice = array_sum(array_column($basketItems, "Subtotal"));
            //Use correct term (item/items)
            $itemText = $totalItems === 1 ? "item" : "items";
            ?>
            <h4 class="template-text">Subtotal (<?php echo $totalItems . ' ' . $itemText; ?>): £<?php echo $totalPrice; ?></h4>
            <a style="text-decoration:none;" href="checkout.php"><div class="checkout-btn">Proceed to Checkout</div></a>
            <!--Clear Basket Button-->
            <form action="php/clear_basket.php" method="post">
                <button type="submit" class="clear-basket-btn">Clear Basket</button>
            </form>
        </div>


        <?php
    } else {
        echo "<p>No items in the basket.</p>\n";
    }
} catch (PDOException $ex) {
    echo "Sorry, a database error occurred! <br>";
    echo "Error details: <em>" . $ex->getMessage() . "</em>";
}
?>
