<?php 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include './layout/login_error_message.php';
$currentPage = "report.php";
include './logInCheck.php'; 

include '../connection/connectdb.php'; 

$revenueOverTimeQuery = "SELECT orderDate, SUM(totalCost) as revenue FROM orderr GROUP BY orderDate ORDER BY orderDate";
$revenueOverTimeResult = mysqli_query($conn, $revenueOverTimeQuery);
$dates = [];
$revenues = [];
while ($row = mysqli_fetch_assoc($revenueOverTimeResult)) {
    $dates[] = $row['orderDate'];
    $revenues[] = (float)$row['revenue'];
}

$revenueByProductQuery = "SELECT p.productName, SUM(oi.totalCost) as revenue FROM orderitem oi JOIN product p ON oi.productID = p.productID GROUP BY oi.productID";
$revenueByProductResult = mysqli_query($conn, $revenueByProductQuery);
$productNames = [];
$productRevenues = [];
while ($row = mysqli_fetch_assoc($revenueByProductResult)) {
    $productNames[] = $row['productName'];
    $productRevenues[] = (float)$row['revenue'];
}

$discountImpactQuery = "SELECT p.productName, SUM(oi.totalCost) as original, SUM(oi.discountedTotalCost) as discounted FROM orderitem oi JOIN product p ON oi.productID = p.productID WHERE oi.discountedTotalCost IS NOT NULL GROUP BY oi.productID";
$discountImpactResult = mysqli_query($conn, $discountImpactQuery);
$discountProducts = [];
$originals = [];
$discounteds = [];
while ($row = mysqli_fetch_assoc($discountImpactResult)) {
    $discountProducts[] = $row['productName'];
    $originals[] = (float)$row['original'];
    $discounteds[] = (float)$row['discounted'];
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product</title>
    <?php include "./layout/header.php"; ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .report-section {
            background-color: #ffffff; /* White background */
            font-family: 'Arial', sans-serif;
            color: #000000; /* Black text */
            margin : 0px !important;
        }
        .analysis-container {
            background-color: #f8f8f8; /* Light gray for contrast */
            color: #000000;
            margin-bottom: 30px;
            text-align: center;
            width: 100%;
            box-sizing: border-box;
        }
        .analysis-container h2 {
            color: #000000; /* Black */
            font-size: 2rem;
            margin-top: 0px !important;
            padding-top : 20px;
            margin-bottom: 20px;
            text-transform: uppercase;
        }
        .chart-wrapper {
            background-color: #ffffff;
            padding: 20px;
            margin: 0 auto 40px auto;
            max-width: none; /* Full width */
            width: 100%;
            border: 1px solid #000000; /* Black border */
        }
        .chart-description {
            font-size: 1rem;
            color: #333333;
            margin: 10px 0 20px 0;
            text-align: left;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }
        canvas {
            width: 100% !important;
            height: auto !important;
        }
    </style>
</head>
<body>
    <?php
        include "nav.php";
        $login = $_SESSION['login'] ?? false;  // Get from session
        if($login == true) {
        echo "<div class='main-content'>";
        echo "<div class='report-section'>";
        
        // Sales and Revenue Analysis Section
        echo "<div class='analysis-container'>";
        echo "<h2>Sales and Revenue Analysis</h2>";
        
        // Total Revenue Over Time
        echo "<h3>Total Revenue Over Time</h3>";
        echo "<p class='chart-description'>This line chart illustrates the total revenue generated from orders, grouped by date. It helps identify trends, peaks, and dips in sales over time.</p>";
        echo "<div class='chart-wrapper'>";
        echo "<canvas id='revenueOverTimeChart'></canvas>";
        echo "</div>";
        
        // Revenue by Product
        echo "<h3>Revenue by Product</h3>";
        echo "<p class='chart-description'>This bar chart shows the revenue contributed by each individual product. Use it to spot top-performing products and those that may need more promotion.</p>";
        echo "<div class='chart-wrapper'>";
        echo "<canvas id='revenueByProductChart'></canvas>";
        echo "</div>";
        
        // Discount Impact
        echo "<h3>Impact of Discounts on Revenue</h3>";
        echo "<p class='chart-description'>This grouped bar chart compares original revenue versus discounted revenue for products with applied discounts, showing the financial impact of promotions.</p>";
        echo "<div class='chart-wrapper'>";
        echo "<canvas id='discountImpactChart'></canvas>";
        echo "</div>";
        
        echo "</div>"; // End analysis-container
        
        echo "</div>"; // End report-section
        echo "</div>"; // End main-content
        }
    ?>
    <script>
        // Total Revenue Over Time (Line Chart)
        const revenueOverTimeCtx = document.getElementById('revenueOverTimeChart').getContext('2d');
        new Chart(revenueOverTimeCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($dates); ?>,
                datasets: [{
                    label: 'Revenue',
                    data: <?php echo json_encode($revenues); ?>,
                    borderColor: '#000000',
                    backgroundColor: 'rgba(0, 0, 0, 0.2)',
                    borderWidth: 2
                }]
            },
            options: {
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });

        // Revenue by Product (Bar Chart)
        const revenueByProductCtx = document.getElementById('revenueByProductChart').getContext('2d');
        new Chart(revenueByProductCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($productNames); ?>,
                datasets: [{
                    label: 'Revenue',
                    data: <?php echo json_encode($productRevenues); ?>,
                    backgroundColor: '#000000'
                }]
            },
            options: {
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });

        // Discount Impact (Grouped Bar Chart)
        const discountImpactCtx = document.getElementById('discountImpactChart').getContext('2d');
        new Chart(discountImpactCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($discountProducts); ?>,
                datasets: [
                    {
                        label: 'Original',
                        data: <?php echo json_encode($originals); ?>,
                        backgroundColor: '#333333'
                    },
                    {
                        label: 'Discounted',
                        data: <?php echo json_encode($discounteds); ?>,
                        backgroundColor: '#000000'
                    }
                ]
            },
            options: {
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    </script>
</body>
</html>