<?php
session_start();
require_once('../php/connectdb.php');
$isAdmin = include('../php/isAdmin.php');

require_once('../admin-website/php/adminCheckRedirect.php');
require_once('../php/alerts.php'); // Include alerts.php for displaying messages

// Fetch current stock levels
$stmtStock = $db->prepare("SELECT Product_ID, Name, Num_In_Stock FROM product");
$stmtStock->execute();
$currentStock = $stmtStock->fetchAll(PDO::FETCH_ASSOC);

// Fetch incoming orders
$stmtIncomingOrders = $db->prepare("SELECT od.Product_ID, SUM(od.Quantity) AS Total_Incoming FROM orders o JOIN ordersdetails od ON o.Orders_ID = od.Orders_ID WHERE o.Order_Status != 'Cancelled' GROUP BY od.Product_ID");
$stmtIncomingOrders->execute();
$incomingOrders = $stmtIncomingOrders->fetchAll(PDO::FETCH_ASSOC);

// Fetch outgoing orders
$stmtOutgoingOrders = $db->prepare("SELECT od.Product_ID, SUM(od.Quantity) AS Total_Outgoing FROM orders o JOIN ordersdetails od ON o.Orders_ID = od.Orders_ID WHERE o.Order_Status = 'Delivered' GROUP BY od.Product_ID");
$stmtOutgoingOrders->execute();
$outgoingOrders = $stmtOutgoingOrders->fetchAll(PDO::FETCH_ASSOC);

// Fetch weekly order data
$weeklyOrderData = array_fill(0, 7, 0); // Initialize array to hold order count for each day of the week

$stmtWeeklyOrders = $db->prepare("SELECT DAYOFWEEK(Order_Date) AS day, COUNT(*) AS order_count FROM orders WHERE Order_Date BETWEEN CURDATE() - INTERVAL 1 WEEK AND CURDATE() GROUP BY DAYOFWEEK(Order_Date)");
$stmtWeeklyOrders->execute();
$weeklyOrders = $stmtWeeklyOrders->fetchAll(PDO::FETCH_ASSOC);

foreach ($weeklyOrders as $row) {
    $day = intval($row['day']) - 1; // Adjust day index to start from 0
    $weeklyOrderData[$day] = intval($row['order_count']);
}

// Function to convert data to CSV format
function arrayToCsv($currentStock, $incomingOrders, $outgoingOrders) {
    if (count($currentStock) == 0 && count($incomingOrders) == 0 && count($outgoingOrders) == 0) {
        return null;
    }
    ob_start();
    $df = fopen("php://output", 'w');

    // Write titles for current stock
    fputcsv($df, array('Current Stock'));
    fputcsv($df, array('Product ID', 'Product Name', 'Current Stock'));
    foreach ($currentStock as $row) {
        fputcsv($df, $row);
    }

    // Write titles for incoming orders
    fputcsv($df, array(''));
    fputcsv($df, array('Incoming Orders'));
    fputcsv($df, array('Product ID', 'Total Incoming'));
    foreach ($incomingOrders as $row) {
        fputcsv($df, $row);
    }

    // Write titles for outgoing orders
    fputcsv($df, array(''));
    fputcsv($df, array('Outgoing Orders'));
    fputcsv($df, array('Product ID', 'Total Outgoing'));
    foreach ($outgoingOrders as $row) {
        fputcsv($df, $row);
    }

    fclose($df);
    return ob_get_clean();
}

// Export data to CSV
if (isset($_POST['export_csv'])) {
    $csvData = arrayToCsv($currentStock, $incomingOrders, $outgoingOrders);
    if ($csvData) {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="report.csv"');
        echo $csvData;
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Petopia</title>
        
        <!--Charts.js Library-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>

        <!--[Google Fonts]-->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link
            href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,700;1,800&family=Work+Sans:wght@700;800&display=swap"
            rel="stylesheet">

        <!--Box Icons-->
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

        <!--Flickity-->
        <link rel="stylesheet" href="https://unpkg.com/flickity@2/dist/flickity.min.css">
        <script src="https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js"></script>

        <!--
            [Navigation & Footer]
        -->
        <script src="../admin-website/jScript/navigationTemplate.js"></script>
        <link href="../css/navigation.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="../css/footer.css">


        <!--CSS-->
        <link href="css/admin-reports-template.css" rel="stylesheet" type="text/css">

        <!--CSS Templates-->
        <link rel="stylesheet" href="../templates/hero-banner.css">

</head>

<body>
    <header></header>

    <section class="admin-report-section admin-first-section">
        <h2 class="">Reports Management</h2>
        <h3 class="admin-heading">View and Export Different Reports</h3>
        <a class="go-back-link" href="adminDashboard.php">Back to Admin Dashboard</a>

        <div class="charts-container">   
            <!-- Data visualization with Chart.js -->
            <div>
                <h3>Weekly Order Tracking</h3>
                <canvas id="orderChart" width="350" height="350"></canvas>
            </div>
            <!--Incoming Orders-->
            <div class="">
                <h3>Incoming Orders</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Product ID</th>
                            <th>Total Incoming</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($incomingOrders as $order): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($order['Product_ID']); ?></td>
                                <td><?php echo htmlspecialchars($order['Total_Incoming']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <!--Outgoing Orders-->
            <div class="">
                <h3>Outgoing Orders</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Product ID</th>
                            <th>Total Outgoing</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($outgoingOrders as $order): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($order['Product_ID']); ?></td>
                                <td><?php echo htmlspecialchars($order['Total_Outgoing']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add CSV export button -->
        <form action="" method="post">
            <button class="submit-btn" type="submit" name="export_csv">Export CSV</button>
        </form>

        <!--Stock Levels-->
        <div clas="add-pd-top" style="padding-top: 50px;"> 
            <h3>Current Stock Levels</h3>
            <table>
                <thead>
                    <tr>
                        <th>Product ID</th>
                        <th>Product Name</th>
                        <th>Current Stock</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($currentStock as $stock): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($stock['Product_ID']); ?></td>
                            <td><?php echo htmlspecialchars($stock['Name']); ?></td>
                            <td><?php echo htmlspecialchars($stock['Num_In_Stock']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>

    <script>
        const orderChartCanvas = document.getElementById('orderChart').getContext('2d');
        const orderData = {
            labels: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
            datasets: [{
                label: 'Number of Orders',
            data: <?php echo json_encode($weeklyOrderData); ?>,
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            borderColor: 'rgba(255, 99, 132, 1)',
            borderWidth: 1
            }]
            };
            const orderChart = new Chart(orderChartCanvas, {
            type: 'bar',
            data: orderData,
            options: {
            scales: {
            y: {
            beginAtZero: true
            }
            }
        }
        });
    </script>

            </body>
            </html>
