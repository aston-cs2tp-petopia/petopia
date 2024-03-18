<?php
require_once('php/mainLogCheck.php');
require_once('php/connectdb.php');
require_once('php/alerts.php');

if (!$b) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'] ?? '';
$userData = [];

if ($username) {
    $stmt = $db->prepare("SELECT First_Name, Last_Name, Home_Address, Postcode, Contact_Email FROM customer WHERE Username = ?");
    $stmt->execute([$username]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);
}

$basketItems = [];
$query = "SELECT product.Product_ID, product.Name, basket.Quantity, basket.Subtotal
          FROM basket
          JOIN product ON product.Product_ID = basket.Product_ID
          JOIN customer ON basket.Customer_ID = customer.Customer_ID
          WHERE customer.Username = ?";
$stmt = $db->prepare($query);
$stmt->execute([$username]);
$basketItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Check if the basket is empty
if (empty($basketItems)) {
    header("Location: basket.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Petopia</title>
    <link href="css/checkout.css" rel="stylesheet" type="text/css">

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
    <!--CSS Templates-->
    <link rel="stylesheet" href="https://unpkg.com/flickity@2/dist/flickity.min.css">
    <link rel="stylesheet" href="templates/hero-banner.css">
</head>

<body>

<header></header>
    
    <main>
        <!--Hero Banner-->
        <section class="hero-banner">
            <!--Hero Banner Image-->
            <div class="hero-banner-image"><img src="assets/Homepage/hero-banner2.jpg" alt=""></div>

            <!--Hero Banner Text Container-->
            <div class="hero-banner-left">

                <div class="hero-banner-content">
                    <h2>Checkout</h2>
                    <p></p>
                </div>
            </div>
        </section>

        <!--Checkout Form-->
        <section class="checkout-flex-container">
            <div class="checkout-form-container">
                <form action="php/process_checkout.php" method="post" class="checkout-form">
                    <h2 class="checkout-heading">Checkout</h2>
                    <h2 class="checkout-subheading">Shipping Details</h2>
                    <div class="form-group">
                        <label for="full-name">Full Name *</label>
                        <input type="text" id="full-name" name="full_name" required value="<?= htmlspecialchars($userData['First_Name'] ?? '') . ' ' . htmlspecialchars($userData['Last_Name'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label for="address">Full Address *</label>
                        <input type="text" id="address" name="address" required value="<?= htmlspecialchars($userData['Home_Address'] ?? '') ?>">
                    </div>

                    <h2 class="checkout-subheading">Payment Information</h2>
                    <div class="form-group">
                        <label for="card-number">Card Number *</label>
                        <input type="text" id="card-number" maxlength="16" name="card_number" pattern="\d{16}" title="Card number must be 16 digits" required>
                    </div>
                    <div class="form-group">
                        <label for="name-on-card">Name on Card *</label>
                        <input type="text" id="name-on-card" name="name_on_card" pattern="[A-Za-z ]+" required title="Name must contain only letters and spaces">
                    </div>
                    <div class="form-group">
                        <label for="exp-month">Exp Month *</label>
                        <input type="number" id="exp-month" name="exp_month" min="1" max="12" title="Month must be between 01 and 12" required>
                    </div>
                    <div class="form-group">
                        <label for="exp-year">Exp Year *</label>
                        <input type="number" id="exp-year" name="exp_year" min="2024" max="2099" pattern="\d{4}" title="Year must be 2024 or later" required>
                    </div>
                    <div class="form-group">
                        <label for="security-code">Security Code *</label>
                        <input type="number" id="security-code" min="100" max="999" name="security_code" pattern="\d{3}" title="Security code must be 3 digits" required>
                    </div>

                    <button type="submit" class="place-order-btn">Place Order</button>
                </form>
            </div>


            <div class="order-summary-container">
                <h2 class="template-header">Order Summary</h2>
                <?php
                // Fetch basket items again if needed or use existing variables
                $totalItems = count($basketItems);
                $totalPrice = array_sum(array_column($basketItems, "Subtotal"));
                $itemText = $totalItems === 1 ? "item" : "items";
                ?>
                <h4 class="template-text">Subtotal (<?php echo $totalItems . ' ' . $itemText; ?>): £<?php echo $totalPrice; ?></h4>
            </div>
        </section>

    </main>
</body>
    
    <footer>
        &copy; 2023 Petopia
    </footer>

</body>
</html>