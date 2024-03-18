<?php
require_once "php/mainLogCheck.php";
$tempPID = $_GET["Product_ID"];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Petopia</title>
    <link href="css/item.css" rel="stylesheet" type="text/css">

    <!--[Google Fonts]-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!--Nunito Font-->
    <link
        href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,700;1,800&family=Work+Sans:wght@700;800&display=swap"
        rel="stylesheet">

    <!--Box Icons-->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!--
        [Navigation & Footer]
    -->
    <script src="templates/navigationTemplate.js"></script>
    <link href="css/navigation.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="css/footer.css">

    <!--Flickity-->
    <!--CSS-->
    <link rel="stylesheet" href="https://unpkg.com/flickity@2/dist/flickity.min.css">
    <link rel="stylesheet" href="templates/hero-banner.css">
    <!--JS-->
    <script src="https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js"></script>

</head>

<body>
        <!--
        [NAVIGATION/HEADER]
    -->
    <header></header>
    <!--
        [HEADER/NAVIGATION END]
    -->

    <main>
        <section class="item-section">
            <div class="item-image">
                <img src="assets/ProductImages/ImageID_<?php echo $tempPID; ?>.jpeg" alt="">
            </div>

            <div class="right-container">
                <!-- <h4 class="category-text"></h4> -->
                <?php
                require_once "php/connectdb.php";
                try {
                    // $query = "select * from projects where pid like " . "'" . $pid . "'";
                    $productQuery =
                        "SELECT * from  product WHERE Product_ID LIKE " .
                        " '" .
                        $tempPID .
                        "'"; //need to add 'where' query once i have a category variable
                    $rows = $db->query($productQuery);

                    //display the query edited table
                    if ($rows && $rows->rowCount() > 0) {
                        foreach ($rows as $row) { ?>
                                <h4 class="title-text"><?php echo $row[
                                    "Name"
                                ]; ?></h4>
                                <div class="price-stock-container">
                                    <h5 class="price-text">£<?php echo $row[
                                        "Price"
                                    ]; ?></h5>
                                    <p class="in-stock-text">Stock: <?php
                                    $adjustedStock = $row["Num_In_Stock"];
                                    if (isset($b) && $b === true && isset($_SESSION['username'])) {
                                        $username = $_SESSION['username'];

                                        //Grabs user's basket
                                        $query = $db->prepare("SELECT SUM(Quantity) AS Quantity FROM basket WHERE Customer_ID = (SELECT Customer_ID FROM customer WHERE Username = ?) AND Product_ID = ?");
                                        $query->execute([$username, $row["Product_ID"]]);
                                        $basketQuantity = $query->fetchColumn();

                                        //Adjustes the basket based on what's in the basket
                                        $adjustedStock -= $basketQuantity;
                                    }

                                    echo max(0, $adjustedStock);//Ensures positive values
                                    ?></p>
                                </div>
                                <div class="item-rating-container">
                                <p><?php 
                                //Grab the rating data
                                $avgRatingQuery = "SELECT AVG(rating) as averageRating FROM reviews WHERE Product_ID = ?";
                                $stmt = $db->prepare($avgRatingQuery);
                                $stmt->execute([$tempPID]);
                                $avgRatingResult = $stmt->fetch(PDO::FETCH_ASSOC);
                                $averageRating = round($avgRatingResult['averageRating'] * 2) / 2; //Round the number
                                echo number_format($averageRating, 1); ?></p>
                                <div class="item-rating-stars">
                                        <?php
                                        for ($i = 0; $i < 5; $i++) {
                                            if ($averageRating >= ($i + 1)) {
                                                echo '<i class="bx bxs-star" style="color: #f1c40f;"></i>'; //Full star
                                            } elseif ($averageRating >= ($i + 0.5)) {
                                                echo '<i class="bx bxs-star-half" style="color: #f1c40f;"></i>'; //Half star
                                            } else {
                                                echo '<i class="bx bx-star" style="color: #f1c40f;"></i>'; //Empty star
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                                <p class="desc-text"><?php echo $row[
                                    "Description"
                                ]; ?></p>
                            
                                <div class="item-bottom-container">
                                <?php if ($b == true) {
                                    if ($adjustedStock > 0) {
                                        //User is logged in and stock is available
                                        echo "<form method='post' action='products.php'>";
                                        echo '<input type="hidden" name="productID" value="' .
                                            $row["Product_ID"] .
                                            '">';
                                        echo '<input type="hidden" name="quantity" value="1">';
                                        echo '<button class="add-cart-btn" type="submit" name="add">Add to Cart<div class="bx bx-cart-add"></div></button>';
                                        echo "</form>";
                                    } else {
                                        //User is logged in but no stock is available
                                        echo '<button class="add-cart-btn" disabled>Out of Stock</button>';
                                    }
                                } else {
                                    //User is not logged in
                                    echo '<button class="add-cart-btn" onclick="location.href=\'login.php\'">Login to Add to Cart</button>';
                                } ?>
                        <?php }
                    } else {
                        echo "<p>No matching Product.</p>\n"; //no match found
                    }
                } catch (PDOexception $ex) {
                    echo "Sorry, a database error occurred! <br>";
                    echo "Error details: <em>" . $ex->getMessage() . "</em>";
                }
                ?>
            </div>

        </section>

        <!--Reviews Section-->
        <section class="reviews-section">
            <h2 class="reviews-heading">Reviews</h2>
            <div class="reviews-template">
                <?php require_once('php/reviews_template.php');?>
            </div>
        </section>

    </main>
</body>

<footer>
    &copy; 2023 Petopia
</footer>
</body>

</html>