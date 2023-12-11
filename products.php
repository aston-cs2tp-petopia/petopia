<?php
 require_once('php/mainLogCheck.php');
    
    //when the basket button is pressed, send the product id and customer id to order details
    if (isset($_POST['add'])) {
        echo "HELLO WORLD";

        require_once('php/connectdb.php');

        $username = $_SESSION["username"];
        $custIDQuery = $db->prepare('SELECT Customer_ID FROM customer WHERE username = ?');
        $custIDQuery->execute([$username]);
        $custID = $custIDQuery->fetchColumn();

        $productID = $_POST['productID'];
        $quantity = $_POST['quantity'];

        // Get product price
        $priceQuery = $db->prepare('SELECT Price FROM product WHERE Product_ID = ?');
        $priceQuery->execute([$productID]);
        $price = $priceQuery->fetchColumn();

        // Calculate subtotal
        $subtotal = $quantity * $price;

        try {
            $basketQuery = $db->prepare("INSERT INTO basket (Customer_ID, Product_ID, Quantity, Subtotal) VALUES (?, ?, ?, ?)");
            $basketQuery->execute(array($custID, $productID, $quantity, $subtotal));
            echo "added to basket";

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
    <link href="css/navigation.css" rel="stylesheet" type="text/css">
    <link href="css/footer.css" rel="stylesheet" type="text/css">

    <!--[Google Fonts]-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!--Nunito Font-->
    <link
        href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,700;1,800&family=Work+Sans:wght@700;800&display=swap"
        rel="stylesheet">

    <!--Box Icons-->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!--Flickity-->
    <!--CSS-->
    <link rel="stylesheet" href="https://unpkg.com/flickity@2/dist/flickity.min.css">
    <!--JS-->
    <script src="https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js"></script>

</head>

<body>
    <header>
        <!--
        [NAVIGATION/HEADER]
    -->
    <header>
        <!--Logo-->
        <div class="logo-container"><a href="#"><img src="assets/logo.png" alt=""></a></div>

        <!--Middle Navigation-->
        <nav class="desktop-nav">
            <ul class="desktop-nav-ul">
                <li><a href="index.php">Home</a></li>
                <!--Dropdown-->
                <li class="dropdown">
                    <a href="#">Pets v</a>
                    <ul class="dropdown-menu">
                        <li class="dropdown-li"><a href="products.php">Cats</a></li>
                        <li class="dropdown-li"><a href="products.php">Dogs</a></li>
                    </ul>
                </li>
                <!--Dropdown-->
                <li class="dropdown">
                    <a href="#">Shop v</a>
                    <ul class="dropdown-menu">
                        <li class="dropdown-li"><a href="products.php">Toys</a></li>
                        <li class="dropdown-li"><a href="products.php">Grooming</a></li>
                        <li><a href="products.php">Treats</a></li>
                    </ul>
                </li>
                <li><a href="advice.php">Advice</a></li>
                <li><a href="about.php">About Us</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </nav>

        <!--Right Navigation-->
        <div class="right-nav">
            <!--Shoppiung Basket Button-->
            <?php
                //Login Button
                if ($b==true) {
                    //Log out button
                    //Shoppiung Basket Button
                    echo '<a href="" class="basket-link"><div class="basket-button bx bx-basket"></div></a>';
                    echo '<div class="login-button"><a href="php/signOut.php"">Log Out</a></div>';
                }else{
                    //Login Button
                    echo '<div class="login-button"><a href="login.php">Login</a></div>';
                }       
            ?>
        </div>

        <!--Mobile-Background-->
        <div class="mobile-background"></div>
        <!--Mobile Navigation-->
        <nav class="mobile-nav">
            <div class="mobile-nav-top">
                <div class="mobile-logo"><img src="assets/logo.png" alt=""></div>
                <p class="close-menu-button" draggable="false">X</p>
            </div>
            <ul>
                <li><a href="index.php">Home</a></li>
                <!--Dropdown-->
                <li class="dropdown">
                    <a href="#">Pets v</a>
                    <ul class="dropdown-menu-mobile">
                        <li class="dropdown-li"><a href="products.php">Cats</a></li>
                        <li class="dropdown-li"><a href="products.php">Dogs</a></li>
                    </ul>
                </li>
                <!--Dropdown-->
                <li class="dropdown">
                    <a href="#">Shop v</a>
                    <ul class="dropdown-menu-mobile">
                        <li class="dropdown-li"><a href="products.php">Cats</a></li>
                        <li class="dropdown-li"><a href="products.php">Dogs</a></li>
                    </ul>
                </li>
                <li><a href="advice.php">Advice</a></li>
                <li><a href="about.php">About Us</a></li>
                <li><a href="contact.php">Contact</a></li>
                <div class="mobile-bottom-nav">
                    <?php
                //Login Button
                if ($b==true) {
                    //Log out button
                    //Shoppiung Basket Button
                    echo '<a href="basket.php" class="basket-link"><div class="basket-button bx bx-basket"></div></a>';
                    echo '<div class="login-button"><a href="php/signOut.php"">Log Out</a></div>';
                }else{
                    //Login Button
                    echo '<div class="login-button"><a href="login.php">Login</a></div>';
                }       
            ?>
                </div>
            </ul>
        </nav>
        <!--Mobile Hamburger-->
        <div id="hamburger-button" class='bx bx-menu'></div>
    </header>
    <!--
        [HEADER/NAVIGATION END]
    -->
    </header>
    
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
            <?php
                require_once('php/connectdb.php');
                try {
                    $productQuery = "select * from  product "; //need to add 'where' query once i have a category variable

                    //run  the query
                    // $db = new PDO("mysql:dbname=$db_name;host=$db_host", $username, $password);
                    $rows =  $db->query($productQuery);

                    //display the query edited table	
                    if ($rows && $rows->rowCount() > 0) {
                        foreach ($rows as $row) {
            ?>
                            <div class="item-template">
                                <div class="item-image">
                                    <img src="assets/Homepage/hero-banner2.jpg" alt="">
                                </div>
                            
                                <div class="item-info">
                                    <h4><?php echo $row['Name']; ?></h4>
                                    <h5>£<?php echo $row['Price'];?></h5>
                            
                                    <div class="item-bottom-container">
                                        <p>Stock: <?php echo $row['Num_In_Stock'];?></p>
                                        <?php
                                            if ($b==true && $row['Num_In_Stock']>0) {
                                                // echo '<form method="post" action="products.php">';
                                                // //basket icon only appears if logged in
                                                // echo "<input type='submit'><a name='add'><div class='bx bx-cart-add'></div></a></input>";
                                                // echo '<input type="hidden" name="productID" value="' . $row['Product_ID'] . '">';
                                                // echo '<input type="hidden" name="quantity" value="1">';
                                                // echo '</form>';
                                                echo "<form method='post' action='products.php'>";
                                                echo '<input type="hidden" name="productID" value="' . $row['Product_ID'] . '">';
                                                echo '<input type="hidden" name="quantity" value="1">';
                                                echo '<button type="submit" name="add"><div class="bx bx-cart-add"></div></button>';
                                                echo '</form>';
                                            }   
                                        ?>
                                    </div>
                                </div>
                            </div>
            <?php
                        }
                    } else {
                        echo  "<p>No matching Product.</p>\n"; //no match found
                    }
                } catch (PDOexception $ex) {
                    echo "Sorry, a database error occurred! <br>";
                    echo "Error details: <em>" . $ex->getMessage() . "</em>";
                }
            ?>
        </section>
        </main>
</body>
    
    <footer>
        &copy; 2023 Petopia
    </footer>
</body>
</html>