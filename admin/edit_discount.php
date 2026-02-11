<?php 

include '../connection/connectdb.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include './layout/login_error_message.php';
$currentPage = "edit_discount.php";
include './logInCheck.php'; 

$discountID = $_GET['discountID'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_discount'])) {
    $productID = $_POST['productID'];
    $range1 = $_POST['range1'];
    $range2 = $_POST['range2'];
    $percentage = $_POST['percentage'];

    $productID = (int)$_POST['productID'];
    $range1    = (int)$_POST['range1'];

    $range2 = '';
    if(isset($_POST['range2'])){
        $range2 = $_POST['range2'];
    }
    else{
        $range2 = '';
    }

    $percentage  = (double)$_POST['percentage'];

    if($range2 === NULL || $range2 === ''){
        $updateQuery = "UPDATE discount SET productID = $productID, range1 = $range1,range2=NULL, percentage = $percentage WHERE discountID = $discountID";
    }
    else{
        $range2 = (int)$range2;
        $updateQuery = "UPDATE discount SET productID = $productID, range1 = $range1, range2 = $range2, percentage = $percentage WHERE discountID = $discountID";
    }

    $conn->query($updateQuery);

    header("Location: discount.php");
    exit();
}


$query = "SELECT * FROM discount JOIN product ON discount.productID = product.productID WHERE discount.discountID= $discountID";
$result = $conn->query($query);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Discount</title>
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

        .header-actions {
            display: flex;
            gap: 12px;
            align-items: center;
            flex-wrap: wrap;
        }

        .header-actions button {
            background: rgba(255,255,255,0.15);
            border: none;
            color: white;
            padding: 8px 14px;
            border-radius: 0;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .header-actions button:hover {
            background: rgba(255,255,255,0.25);
            transform: translateY(-1px);
        }

        .discount-form {
            background: white;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            border-radius: 0;
            padding: 25px;
            width: 100%;
            margin: 0 auto;
            margin-top : 0px !important;
        }

        .discount-form label {
            display: block;
            margin-bottom: 25px;
            font-weight: 500;
            color: #004d00;
            font-size: 1rem;
        }

        .discount-form input[type="text"],
        .discount-form select {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #e0f0e0;
            border-radius: 0;
            background: #f8fff8;
            font-size: 1rem;
            color: #333;
            margin-top: 5px;
            transition: all 0.3s ease;
        }

        .discount-form input[type="text"]:focus,
        .discount-form select:focus {
            border-color: #004d00;
            box-shadow: 0 0 0 3px rgba(0,77,0,0.1);
            outline: none;
        }

        .btn-update {
            padding: 12px 24px;
            border: none;
            border-radius: 0;
            text-decoration: none;
            font-size: 1rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            display: inline-block;
            background: linear-gradient(45deg, #004d00, #006600);
            color: white;
            cursor: pointer;
            margin-top: 20px;
        }

        .btn-update:hover {
            background: linear-gradient(45deg, #006600, #008000);
            transform: translateY(-1px);
            box-shadow: 0 3px 8px rgba(0,102,0,0.3);
        }

        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                text-align: center;
            }

            .header-actions {
                justify-content: center;
            }

            .discount-form {
                padding: 20px;
            }

            .btn-update {
                width: 100%;
                padding: 14px;
            }
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
        echo "<h1>Edit Discount</h1>";
        echo "<div class='header-actions'>";
        echo "<button onclick=\"window.location.href='discount.php'\"><i class='fa fa-arrow-left'></i> Back to Discounts</button>";
        echo "</div>";
        echo "</div>";
        
        if ($result && $result->num_rows > 0 && $row = $result->fetch_assoc()) {
            
            echo "<form method='POST' action='edit_discount.php?discountID=".$discountID."' class='discount-form'>";

            $query_product = "SELECT * FROM product";
            $result_product = $conn->query($query_product);
                
            if ($result_product && $result_product->num_rows > 0 ) {
            
                echo "<label>Product
                <select name='productID' required>";

                while ($row_product = $result_product->fetch_assoc()) {
                    echo "<option value='".$row_product['productID']."' ".($row_product['productID'] == $row['productID'] ? 'selected' : '').">".$row_product['productName']."</option>";
                }

                echo "</select></label>";
            }

            echo "<label>Range Start From<input type='text' value='".$row['range1']."' name='range1' required></label>";
            echo "<label>Range End<input type='text' value='".$row['range2']."' name='range2'></label>";
            echo "<label>Percentage <input type='text' value='".$row['percentage']."' name='percentage' required></label>";
            echo "<button type='submit' name='update_discount' class='btn-update'>Update Discount</button>";
            echo "</form>";

        }
        
        echo "</div>";
        }
    ?>
</body>
</html>