<?php

include '../connection/connectdb.php';

if (session_status() === PHP_SESSION_NONE) session_start();

include './layout/login_error_message.php';
$currentPage = "add_new_discount.php";
include './logInCheck.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_discount'])) {
    $productID = (int)$_POST['productID'];
    $range1    = (int)$_POST['range1'];

    $range2 = '';
    if(isset($_POST['range2'])){
        $range2 = $_POST['range2'];
    }
    else{
        $range2 = "";
    }

    $percentage  = (double)$_POST['percentage'];

    if($range2 === NULL || $range2 === ''){

        $conn->query("INSERT INTO discount (productID, range1, percentage) VALUES ($productID, $range1, $percentage)");
    }
    else{
        $range2 = (int)$range2;
        $conn->query("INSERT INTO discount (productID, range1, range2, percentage) VALUES ($productID, $range1, $range2, $percentage)");

    }
    header("Location: discount.php"); exit();
}

$result = $conn->query("SELECT * FROM product");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Discount</title>
    <?php include "./layout/header.php"; ?>
    <style>
        body {
            background-color: #f8f8f8; /* Light neutral background for contrast */
            color: #333; /* Dark text for readability */
            font-family: 'Georgia', serif; /* Serif font for luxury elegance */
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #006039; /* Rolex green border */
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .page-header h1 {
            color: #006039; /* Rolex green for headings */
            font-size: 2em;
            margin-top : 40px;
            margin-left : 20px;
            text-transform: uppercase; /* Uppercase for premium feel */
            letter-spacing: 1px;
        }

        .header-actions {
            /* Placeholder for any actions; style buttons or links here if needed */
        }

        .add-section {
            padding: 20px;
            background-color: #f0f0f0; /* Light gray for section separation */
            border-radius: 8px;
        }

        form {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            align-items: flex-end;
        }

        .add-form {
            /* Specific to add_new_stock.php form */
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }

        .form-group {
            flex: 1;
            min-width: 180px; /* Ensure responsiveness */
            display: flex;
            flex-direction: column;
        }

        .form-group.product-wide {
            flex: 1 1 100%; /* Full width for product select in add_new_stock */
        }

        label {
            font-weight: bold;
            color: #006039; /* Green labels */
            margin-bottom: 5px;
            text-transform: uppercase;
            font-size: 0.9em;
            letter-spacing: 0.5px;
        }

        input[type="text"],
        input[type="number"],
        input[type="color"],
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #006039; /* Green border */
            border-radius: 4px;
            background-color: #fff;
            color: #333;
            font-size: 1em;
            transition: border-color 0.3s ease;
        }

        input[type="color"] {
            height: 42px; /* As specified in PHP */
            padding: 0; /* Color picker doesn't need padding */
        }

        input:focus,
        select:focus {
            border-color: #CBB26A; /* Gold on focus for accent */
            outline: none;
        }

        .btn-submit {
            background-color: #006039; /* Rolex green button */
            color: #fff; /* White text */
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1em;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: background-color 0.3s ease, color 0.3s ease;
            height: fit-content;
            width: auto;
        }

        .btn-submit:hover {
            background-color: #CBB26A; 
            color: #006039; 
        }

        nav {
            background-color: #006039; 
            padding: 10px;
            color: #fff;
        }

        @media (max-width: 768px) {
            .form-group {
                min-width: 100%; 
            }
            
            form {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>
</head>
<body>
    <?php include "nav.php";
    $login = $_SESSION['login'] ?? false;
    if($login):
        echo "<div class='main-content'>";
        echo "<div class='page-header'><h1>Add New Stock</h1><div class='header-actions'></div></div>";
        echo "<section class='add-section'>";
        echo "<form method='POST' class='add-form'>";

        echo "<div class='form-group product-wide'> <label>Product</label><select name='productID' required>";
        while ($row = $result->fetch_assoc())
            echo "<option value='{$row['productID']}'>{$row['productName']}</option>";
        echo "</select></div>";

        echo "<div class='form-group'><label>Range 1 </label><input type='text' name='range1' required></div>";
        echo "<div class='form-group'><label>Range 2  </label><input type='text' name='range2'></div>";
        echo "<div class='form-group'><label>Percentage  </label><input type='text' name='percentage' required></div>";

        echo "<button type='submit' name='add_discount' class='btn-submit'>Add Discount</button>";
        echo "</form></section></div>";
    endif; ?>
</body>
</html>