<?php

include "../connection/connectdb.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include './layout/login_error_message.php';
$currentPage = "active_order.php";
include './logInCheck.php'; 

if(isset($_GET['orderID'])){
    $orderID = intval($_GET['orderID']);
} else {
    exit;
}

$queryStock = "SELECT * FROM stock";
$stocks = [];
$resultStock = $conn->query($queryStock);
while($row = $resultStock->fetch_assoc()) {
    $stocks[] = $row;
}

$queryProduct = "SELECT * FROM product";
$products = [];
$resultProduct = $conn->query($queryProduct);
while($row = $resultProduct->fetch_assoc()) {
    $products[] = $row;
}

$queryColor = "SELECT * FROM color";
$colors = [];
$resultColor = $conn->query($queryColor);
while($row = $resultColor->fetch_assoc()) {
    $colors[$row['colorID']] = $row['colorName'];
}

$querySize = "SELECT * FROM size";
$sizes = [];
$resultSize = $conn->query($querySize);
while($row = $resultSize->fetch_assoc()) {
    $sizes[$row['sizeID']] = $row['sizeName'];
}

$success = isset($_GET['success']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Order Item</title>
    <?php include "./layout/header.php"; ?>
    <style>
    

        .page-header {
            background: linear-gradient(135deg, #004d00, #002600);
            color: white;
            padding: 20px 25px;
            border-radius: 0;
            margin: 0 0 20px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .page-header h1 {
            margin: 0;
            font-size: 1.6rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .selection-form {
            background: white;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            border-radius: 0;
            padding: 30px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .selection-form label {
            font-weight: 500;
            color: #004d00;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        .selection-form select,
        .selection-form input[type="number"] {
            background: #006400;
            color: #fff;
            border: 0px solid #b8860b;
            padding: 12px 16px;
            border-radius: 0;
            font-size: 1rem;
            font-weight: 500;
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3e%3cpath fill='%23b8860b' d='M0 0l6 8 6-8z'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 16px center;
            cursor: pointer;
        }

        .selection-form select:hover,
        .selection-form select:focus,
        .selection-form input[type="number"]:hover,
        .selection-form input[type="number"]:focus {
            background-color: #004d00;
            outline: none;
            box-shadow: 0 0 0 3px rgba(184, 134, 11, 0.3);
        }

        .selection-form select option {
            background: #e6f3e6;
            color: #004d00;
            font-weight: 600;
        }

        .selection-form button {
            background: linear-gradient(45deg, #004d00, #006600);
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 0;
            font-size: 1rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: all 0.3s ease;
            align-self: flex-start;
        }

        .selection-form button:hover {
            background: linear-gradient(45deg, #006600, #008000);
            transform: translateY(-1px);
            box-shadow: 0 3px 8px rgba(0,102,0,0.3);
        }

        .success-message {
            background: #e6f7e6;
            color: #004d00;
            padding: 12px 20px;
            border-left: 5px solid #006600;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .btn-back {
            background: #f8f9fa;
            color: #004d00;
            padding: 8px 16px;
            text-decoration: none;
            font-weight: 500;
            border: 1px solid #e0f0e0;
        }

        .btn-back:hover {
            background: #e6f7e6;
        }

        @media (max-width: 768px) {
            .selection-form { padding: 20px; }
            .selection-form button { width: 100%; }
        }
    </style>
</head>
<body>
    <?php
        include "nav.php";
        $login = $_SESSION['login'] ?? false;  
        if($login == true) {
            echo "<div class='main-content'>";

            echo "<div class='page-header'>";
            echo "<h1>Add Order Item #{$orderID}</h1>";
            echo "<a href='specific_manual_order.php?orderID={$orderID}' class='btn-back'>← Back to Order</a>";
            echo "</div>";

            if ($success) {
                echo "<div class='success-message'>✅ Order item added successfully!</div>";
            }
          
            echo "<form class='selection-form' method='POST' action='add_order_items_action.php'>";

            echo "<label for='product'>Product</label>";
            echo "<select name='product' id='product' onchange='updateColors()' required>";
            foreach($products as $product) {
                echo "<option value='{$product['productID']}'>{$product['productName']}</option>";
            }
            echo "</select>";

            echo "<label for='color'>Color</label>";
            echo "<select name='color' id='color' onchange='updateSizes()' required></select>";

            echo "<label for='size'>Size</label>";
            echo "<select name='size' id='size' required></select>";

            echo "<label for='quantity'>Quantity</label>";
            echo "<input type='number' id='quantity' name='quantity' min='1' required>";

            echo "<input type='hidden' name='orderID' value='{$orderID}'>";

            echo "<button type='submit'>Add Item</button>";
            echo "</form>";

            echo "</div>";
        }
    ?>
    <script>
        var stocks = <?php echo json_encode($stocks); ?>;
        var colors = <?php echo json_encode($colors); ?>;
        var sizes = <?php echo json_encode($sizes); ?>;

        function updateColors() {
            var productID = document.getElementById('product').value;
            var availColors = new Set();
            stocks.forEach(function(stock) {
                if (stock.productID == productID) availColors.add(stock.colorID);
            });
            var colorSelect = document.getElementById('color');
            colorSelect.innerHTML = '';
            availColors.forEach(function(colorID) {
                var option = document.createElement('option');
                option.value = colorID;
                option.text = colors[colorID];
                colorSelect.add(option);
            });
            updateSizes();
        }

        function updateSizes() {
            var productID = document.getElementById('product').value;
            var colorID = document.getElementById('color').value;
            if (!colorID) return;
            var availSizes = new Set();
            stocks.forEach(function(stock) {
                if (stock.productID == productID && stock.colorID == colorID) {
                    availSizes.add(stock.sizeID);
                }
            });
            var sizeSelect = document.getElementById('size');
            sizeSelect.innerHTML = '';
            availSizes.forEach(function(sizeID) {
                var option = document.createElement('option');
                option.value = sizeID;
                option.text = sizes[sizeID];
                sizeSelect.add(option);
            });
        }

        window.onload = function() {
            updateColors();
        };
    </script>
</body>
</html>