<?php
    require_once('php/mainLogCheck.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Petopia</title>
    <link href="css/basket.css" rel="stylesheet" type="text/css">
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
    <!--CSS Templates-->
    <link rel="stylesheet" href="https://unpkg.com/flickity@2/dist/flickity.min.css">
    <link rel="stylesheet" href="templates/hero-banner.css">
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
        <div class="logo-container"><a href="index.php"><img src="assets/logo.png" alt=""></a></div>

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
                    <!--Login Button-->
                    <?php
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
                    <h2>Basket</h2>
                    <p></p>
                </div>
            </div>
        </section>
        <!--Basket Container-->
        <section class="basket-container">
            <!--Basket Container-->
                <div class="top-container">
                    <h3>Your basket</h3>
                </div>
            <!--Basket Table-->
            <?php
                require_once('php/connectdb.php');

                try {
                    $query = 'SELECT product.Product_ID, product.Name, SUM(Quantity) as TotalQuantity, Subtotal 
                            FROM basket 
                            JOIN product ON product.Product_ID = basket.Product_ID 
                            JOIN customer ON basket.Customer_ID = customer.Customer_ID
                            GROUP BY product.Product_ID';

                    //run the query
                    $rows = $db->query($query);

                    //step 3: display the basket items in a table
                    if ($rows && $rows->rowCount() > 0) {
                ?>
                        <table cellspacing="10" cellpadding="15" class="productTable">
                            <tr class="basket-top">
                                <th align='center'><b>Image</b></th>
                                <th align='center'><b>Product Name</b></th>
                                <th align='center'><b>Quantity</b></th>
                                <th align='center'><b>Subtotal</b></th>
                                <th align='center'><b>Action</b></th>
                            </tr>
                            <?php
                            foreach ($rows as $row) {
                                echo  "<tr class='basket-row' data-product-id='" . $row['Product_ID'] . "'>
                                            <td align='center'><img src='assets/Homepage/hero-banner2.jpg' alt='Product Image' width='50' height='50'></td>
                                            <td align='center'>" . $row['Name'] . "</td>
                                            <td align='center'>" . $row['TotalQuantity'] . "</td>
                                            <td align='center'>" . $row['Subtotal'] . "</td>
                                            <td align='center'><button class='remove-basket'>Remove</button></td>
                                        </tr>";
                            }
                            ?>
                        </table>

                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                var removeButtons = document.querySelectorAll('.remove-basket');

                                removeButtons.forEach(function (button) {
                                    button.addEventListener('click', function () {
                                        var productId = this.closest('.basket-row').dataset.productId;

                                        // Make an AJAX request to remove the item
                                        var xhr = new XMLHttpRequest();
                                        xhr.open('POST', 'remove_from_basket.php', true);
                                        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                                        xhr.onreadystatechange = function () {
                                            if (xhr.readyState == 4 && xhr.status == 200) {
                                                // Refresh the page or update the UI as needed
                                                location.reload();
                                            } else if (xhr.readyState == 4 && xhr.status != 200) {
                                                alert('Error removing item from the basket.');
                                            }
                                        };

                                        xhr.send('productId=' + encodeURIComponent(productId));
                                    });
                                });
                            });
                        </script>

                    <?php
                    } else {
                        echo  "<p>No items in the basket.</p>\n"; //no match found
                    }
                } catch (PDOException $ex) {
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var removeButtons = document.querySelectorAll('.remove-basket');

            removeButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    var productId = this.closest('.basket-row').dataset.productId;

                    // Make an AJAX request to remove the item
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', 'remove_from_basket.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                    xhr.onreadystatechange = function () {
                        if (xhr.readyState == 4 && xhr.status == 200) {
                            // Refresh the page or update the UI as needed
                            location.reload();
                        } else if (xhr.readyState == 4 && xhr.status != 200) {
                            alert('Error removing item from the basket.');
                        }
                    };

                    xhr.send('productId=' + encodeURIComponent(productId));
                });
            });
        });
    </script>

</body>
</html>