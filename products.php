<?php
require_once "php/mainLogCheck.php";

//$productType=$_GET["productType"];
//echo($productType);

//when the basket button is pressed, send the product id and customer id to order details
if (isset($_POST["add"])) {
    require_once "php/connectdb.php";

    $username = $_SESSION["username"];
    $custIDQuery = $db->prepare(
        "SELECT Customer_ID FROM customer WHERE username = ?"
    );
    $custIDQuery->execute([$username]);
    $custID = $custIDQuery->fetchColumn();

    $productID = $_POST["productID"];
    $quantity = $_POST["quantity"];

    // Get product price
    $priceQuery = $db->prepare(
        "SELECT Price FROM product WHERE Product_ID = ?"
    );
    $priceQuery->execute([$productID]);
    $price = $priceQuery->fetchColumn();

    // Calculate subtotal
    $subtotal = $quantity * $price;

    try {
        $basketQuery = $db->prepare(
            "INSERT INTO basket (Customer_ID, Product_ID, Quantity, Subtotal) VALUES (?, ?, ?, ?)"
        );
        $basketQuery->execute([$custID, $productID, $quantity, $subtotal]);
    } catch (PDOexception $ex) {
        echo "Sorry, a database error occurred! <br>";
        echo "Error details: <em>" . $ex->getMessage() . "</em>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Petopia</title>
    <link href="css/items.css" rel="stylesheet" type="text/css">

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

    <!--CSS Templates-->
    <link rel="stylesheet" href="https://unpkg.com/flickity@2/dist/flickity.min.css">
    <link rel="stylesheet" href="templates/hero-banner.css">

    <!--JS-->
    <script src="https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js"></script>
    <script src="scripts/searchProducts.js"></script>

</head>

<body>
        <!--
        [NAVIGATION/HEADER]
    -->
    <header>

    </header>
    <!--
        [HEADER/NAVIGATION END]
    -->
    
    <main>
        <!--Hero Banner-->
        <section class="hero-banner">
            <!--Hero Banner Image-->
            <div class="hero-banner-image"><img src="assets/Homepage/hero-banner2.jpg" alt=""></div>

            <!--Hero Banner Text Container-->
            <div class="hero-banner-left">

                <div class="hero-banner-content">
                    <h2>Pets and Items</h2>
                    <p> Browse</p>
                </div>
            </div>
        </section>

        <section class="items-container">
            <div class="items-left">

            </div>

            <div class="items-right">

                <div class="filters-top">
                    <!--Filters-->
                    <div class="filters-left">
                        <!--Show N per page-->
                        <div class="show-list-container">
                            <form><label for="show">Show</label>
                                <select id="show" class="selected">
                                    <option value="6">06</option>
                                    <option value="12">12</option>
                                    <option value="18">18</option>
                                    <option value="24">24</option>
                                    <option value="30">30</option>
                                </select>
                            </form>
                        </div>

                        <!--Sort By-->
                        <div class="sort-by-container">
                            <form><label for="sortBy">Sort By</label>
                                <select id="sortBy" class="selected">
                                    <option value="select">Select</option>
                                    <option value="lowToHigh">Low to high</option>
                                    <option value="highToLow">High to low</option>
                                </select>
                            </form>
                        </div>
                    </div>

                    <!--Search-->
                    <form id="filters-right">
                        <!--Search Input Container-->
                        <div class="input-container">
                            <input type="text" id="contact-name" name="contact-name" class="name-input"
                                placeholder="Search..." required />
                            <i class='bx bx-search'></i>
                        </div>
                    </form>
                </div>

                    <div class="results-container">
                    <?php
                    require_once "php/connectdb.php";

                    // Capture search term and category IDs from URL query parameters
                    $searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
                    $category_ids = isset($_GET['category_id']) && is_array($_GET['category_id']) ? array_map('intval', $_GET['category_id']) : [];

                    try {
                        $queryParams = [];
                        $queryParts = [
                            "SELECT DISTINCT p.*, GROUP_CONCAT(DISTINCT c.Name ORDER BY FIELD(c.Category_ID, 5, 6) DESC, c.Name ASC SEPARATOR ', ') AS CategoryNames",
                            "FROM product p",
                            "LEFT JOIN productcategory pc ON p.Product_ID = pc.Product_ID",
                            "LEFT JOIN category c ON pc.Category_ID = c.Category_ID"
                        ];

                        // Building WHERE conditions based on search term and categories
                        $conditions = [];
                        if (!empty($searchTerm)) {
                            $conditions[] = "p.Name LIKE ?";
                            $queryParams[] = "%{$searchTerm}%";
                        }
                        if (!empty($category_ids)) {
                            $placeholders = implode(',', array_fill(0, count($category_ids), '?'));
                            $conditions[] = "pc.Category_ID IN ($placeholders)";
                            $queryParams = array_merge($queryParams, $category_ids);
                        }

                        if (!empty($conditions)) {
                            $queryParts[] = "WHERE " . implode(" AND ", $conditions);
                        }
                        $queryParts[] = "GROUP BY p.Product_ID";

                        // Finalizing and executing the query
                        $finalQuery = implode(" ", $queryParts);
                        $stmt = $db->prepare($finalQuery);
                        $stmt->execute($queryParams);
                        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        //display the query edited table
                        if ($rows) {
                            foreach ($rows as $row) { ?>
                            <div class="item-template">
                                <div class="item-image">
                                    <?php $tempPID = $row["Product_ID"]; ?>
                                    <a href="item.php?Product_ID=<?php echo $tempPID; ?>"><img src="assets/Homepage/hero-banner2.jpg" alt=""></a>
                                </div>
                            
                                <div class="item-info">
                                    <h6><?php echo $row["CategoryNames"]?></h6>
                                    <h4><a href="item.php?Product_ID=<?php echo $tempPID; ?>"><?php echo $row[
                                        "Name"
                                        ]; ?></a></h4>
                                    <!-- <td align="left"><a href="projectdetails.php?pid=' . $pidTemp . '"> -->
                                    <h5>£<?php echo $row["Price"]; ?></h5>
                            
                                    <div class="item-bottom-container">
                                        <p>Stock: <?php echo $row[
                                            "Num_In_Stock"
                                        ]; ?></p>
                                        <?php if (
                                            $b == true &&
                                            $row["Num_In_Stock"] > 0
                                        ) {
                                            echo "<form method='post' action='products.php'>";
                                            echo '<input type="hidden" name="productID" value="' .
                                                $row["Product_ID"] .
                                                '">';
                                            echo '<input type="hidden" name="quantity" value="1">';
                                            echo '<button class="add-cart-btn" type="submit" name="add"><div class="bx bx-cart-add"></div></button>';
                                            echo "</form>";
                                        } ?>
                                    </div>
                                </div>
                            </div>
                        <?php }
                        } else {
                            echo "<p>No matching Product.</p>\n"; //no match found
                        }
                    } catch (PDOexception $ex) {
                        echo "Sorry, a database error occurred! <br>";
                        echo "Error details: <em>" .
                            $ex->getMessage() .
                            "</em>";
                    }
                    ?>

                </div>
                <div id="pagination-controls">
                    <button id="prevPage">Previous</button>
                    <button id="nextPage">Next</button>
                </div>
            </div>
        </section>
        </main>
</body>
    
    <footer>
        &copy; 2023 Petopia
    </footer>
</body>
</html>