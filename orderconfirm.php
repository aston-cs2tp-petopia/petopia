<?php
require_once "php/mainLogCheck.php";
require_once "php/connectdb.php";

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];
$stmt = $db->prepare("SELECT Customer_ID FROM customer WHERE Username = ?");
$stmt->execute([$username]);
$userData = $stmt->fetch(PDO::FETCH_ASSOC);
$userId = $userData['Customer_ID'] ?? 0;

$orderId = isset($_GET['orderId']) ? intval($_GET['orderId']) : 0;
$feedbackMessage = ''; // For user feedback

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancelOrder'], $_POST['orderId'])) {
    $orderId = intval($_POST['orderId']);

    $db->beginTransaction();
    try {
        // Cancel the order if it's in 'Processing' state
        $updateOrder = $db->prepare("UPDATE orders SET Order_Status = 'Cancelled' WHERE Orders_ID = ? AND Customer_ID = ? AND Order_Status = 'Processing'");
        $updateOrder->execute([$orderId, $userId]);

        if ($updateOrder->rowCount() > 0) {
            // Order was successfully updated to 'Cancelled', now adjust the stock
            $orderDetails = $db->prepare("SELECT Product_ID, Quantity FROM ordersdetails WHERE Orders_ID = ?");
            $orderDetails->execute([$orderId]);

            while ($product = $orderDetails->fetch(PDO::FETCH_ASSOC)) {
                $updateStock = $db->prepare("UPDATE product SET Num_In_Stock = Num_In_Stock + ? WHERE Product_ID = ?");
                $updateStock->execute([$product['Quantity'], $product['Product_ID']]);
            }

            $db->commit();
            $feedbackMessage = "Order has been successfully cancelled.";
        } else {
            // Order status was not 'Processing' or order does not belong to user
            $db->rollback();
            $feedbackMessage = "Order could not be cancelled. It may have already been processed or does not exist.";
        }
    } catch (Exception $e) {
        $db->rollback();
        $feedbackMessage = "An error occurred: " . $e->getMessage();
    }
}

// Re-fetch order details to reflect any changes or for initial page load
$stmt = $db->prepare("SELECT Orders_ID, Total_Amount, Order_Status FROM orders WHERE Orders_ID = ? AND Customer_ID = ?");
$stmt->execute([$orderId, $userId]);
$orderExists = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$orderExists) {
    header("Location: index.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Petopia</title>
    <link href="css/orderconfirm.css" rel="stylesheet" type="text/css">
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
    <!--JS-->
    <script src="https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js"></script>

</head>

<body data-customer-id="<?php echo htmlspecialchars($userId); ?>">
    <!--
        [NAVIGATION/HEADER]
    -->
    <header></header>
    <!--
        [HEADER/NAVIGATION END]
    -->

    <!--Hero Banner-->
    <section class="hero-banner">
        <!--Hero Banner Image-->
        <div class="hero-banner-image"><img src="assets/Homepage/hero-banner2.jpg" alt=""></div>

        <div class="hero-banner-left">
            <div class="hero-banner-content">
                <h2 class="order-number-text">Order Number: <?php echo htmlspecialchars($orderId); ?></h2>
                <!-- Display dynamic order total and status -->
                <h1 class="order-total-text">Order Total: £<?php echo htmlspecialchars($orderExists['Total_Amount']); ?></h1>
                <p class="order-progress-text">Order Progress: <?php echo htmlspecialchars($orderExists['Order_Status']); ?></p>
                <a href="orders.php" style="text-decoration: none;"><div class="hero-banner-button">Return to orders</div></a>
            </div>
        </div>
    </section>
    
    <!--Section for order content-->
    <section class="main-content">
        <h2 class="order-summary-heading">Order Summary</h2>
        <div class="order-template-container">
            <?php require_once('php/order_content_template.php');?>
        </div>

        <?php if (isset($orderExists) && $orderExists['Order_Status'] === 'Processing'): ?>
            <form action="orderconfirm.php?orderId=<?php echo htmlspecialchars($orderId); ?>" method="post">
                <input type="hidden" name="cancelOrder" value="1">
                <input type="hidden" name="orderId" value="<?php echo htmlspecialchars($orderId); ?>">
                <button id="cancel-btn" type="submit" onclick="return confirm('Are you sure you want to cancel this order?')">Cancel Order</button>
            </form>
        <?php endif;?>

    </section>


    <script src="scripts/orderconfirm_review.js"></script>
                
    <footer>
        &copy; 2023 Petopia
    </footer>
</body>

</html>
