<?php 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../connection/connectdb.php';

include './layout/login_error_message.php';
$currentPage = "edit_stock.php";
include './logInCheck.php'; 

$stockID = $_GET['stockID'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_stock'])) {
    $productID = $_POST['productID'];
    $sizeID = $_POST['sizeID'];
    $colorID = $_POST['colorID'];
    $quantity = $_POST['quantity'];

    $updateQuery = "UPDATE stock SET productID = $productID, sizeID = $sizeID, colorID = $colorID, quantity = $quantity WHERE stockID = $stockID";
    $conn->query($updateQuery);

    header("Location: stock.php");
    exit();
}

$query = "SELECT * FROM stock JOIN product ON stock.productID = product.productID JOIN size ON stock.sizeID = size.sizeID JOIN color ON stock.colorID = color.colorID WHERE stock.stockID = $stockID";
$result = $conn->query($query);
$row = $result->fetch_assoc();

$query_products = "SELECT * FROM product";
$result_products = $conn->query($query_products);

$query_sizes = "SELECT * FROM size";
$result_sizes = $conn->query($query_sizes);

$query_colors = "SELECT * FROM color";
$result_colors = $conn->query($query_colors);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Stock</title>
    <?php include "./layout/header.php"; ?>
    <style>
        .main-content {
            padding: 20px;
        }

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

        .edit-section {
            background: white;
            padding: 15px; /* Reduced from 20px */
            margin-bottom: 15px; /* Reduced from 20px */
            box-shadow: 0 2px 6px rgba(0,0,0,0.06); /* Lighter shadow for subtlety */
            border-radius: 4px; /* Add subtle rounding for cleanliness */
            margin-top : 20px;
        }

        .edit-section h2 {
            color: #004d00;
            margin-bottom: 30px; /* Reduced from 15px */
            font-size: 1.2rem; /* Slightly smaller for balance */
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .edit-form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); /* Slightly narrower minmax for tighter fit */
            gap: 12px; /* Reduced from 15px */
            align-items: end; /* Align items to the bottom for even rows */
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            margin-bottom: 4px; /* Reduced from 5px—tiny but adds up */
            color: #004d00;
            font-weight: 500;
            font-size: 0.9rem; /* Smaller labels for cleanliness */
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .form-group select, .form-group input {
            padding: 8px; /* Reduced from 10px */
            border: 1px solid #e0f0e0;
            border-radius: 2px; /* Subtle rounding */
            background: #f8fff8;
            font-size: 0.95rem; /* Consistent sizing */
        }
        
        .btn-submit {
            background: linear-gradient(45deg, #004d00, #006600);
            color: white;
            padding: 8px 16px; /* Reduced from 12px 20px—smaller and neater */
            border: none;
            border-radius: 2px; /* Match input rounding */
            cursor: pointer;
            font-size: 0.9rem; /* Slightly smaller text */
            text-transform: uppercase;
            transition: all 0.3s ease;
            grid-column: span 1; /* Keeps it in its own grid cell */
            align-self: end; /* Aligns with form fields */
            min-width: 120px; /* Ensures it doesn't shrink too much on small screens */
        }

        .btn-submit:hover {
            background: linear-gradient(45deg, #006600, #008000);
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,102,0,0.2); /* Lighter shadow */
        }

        select {
            background: #006400;
            color: black;
            padding: 8px 30px 8px 8px; /* Space for custom arrow */
            border-radius: 2px;
            font-size: 0.95rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8' fill='%23b8860b'%3e%3cpath d='M0 0l6 8 6-8z'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 8px center;
            background-size: 12px;
            appearance: none; /* Removes default arrow */
        }

        select:hover, select:focus {
            background-color: #004d00;
            /* border-color: #b8860b; */
            outline: none;
            box-shadow: 0 0 0 2px rgba(184, 134, 11, 0.2); /* Subtle gold glow */
            color : white;
        }

        select option {
            background: #e6f3e6;
            color: #004d00;
            font-weight: 500;
        }

        select option:checked, select option:hover {
            background: #006400;
            color: #fff; /* White for better contrast */
        }

        @media (max-width: 768px) {
            .edit-form {
                grid-template-columns: 1fr;
                gap: 10px; /* Even smaller gap on mobile */
            }

            .btn-submit {
                width: 100%; /* Full width on mobile for easy tap */
                padding: 10px; /* Slightly more on mobile for touch */
                min-width: auto;
            }

            .main-content {
                padding: 12px; /* Further reduce on small screens */
            }

            .edit-section {
                padding: 12px;
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
            echo "<h1>Edit Stock</h1>";
            echo "</div>";

            echo "<section class='edit-section'>";
            echo "<h2>Update Stock Details</h2>";
            echo "<form method='POST' class='edit-form'>";
            echo "<div class='form-group'>";
            echo "<label>Product</label>";
            echo "<select name='productID' required>";
            while ($row_product = $result_products->fetch_assoc()) {
                $selected = ($row_product['productID'] == $row['productID']) ? 'selected' : '';
                echo "<option value='".$row_product['productID']."' $selected>".$row_product['productName']."</option>";
            }
            mysqli_data_seek($result_products, 0); // Reset pointer
            echo "</select>";
            echo "</div>";
            echo "<div class='form-group'>";
            echo "<label>Size:</label>";
            echo "<select name='sizeID' required>";
            while ($row_size = $result_sizes->fetch_assoc()) {
                $selected = ($row_size['sizeID'] == $row['sizeID']) ? 'selected' : '';
                echo "<option value='".$row_size['sizeID']."' $selected>".$row_size['sizeName']."</option>";
            }
            mysqli_data_seek($result_sizes, 0); // Reset pointer
            echo "</select>";
            echo "</div>";
            echo "<div class='form-group'>";
            echo "<label>Color</label>";
            echo "<select name='colorID' required>";
            while ($row_color = $result_colors->fetch_assoc()) {
                $selected = ($row_color['colorID'] == $row['colorID']) ? 'selected' : '';
                echo "<option value='".$row_color['colorID']."' $selected>".$row_color['colorName']."</option>";
            }
            mysqli_data_seek($result_colors, 0); // Reset pointer
            echo "</select>";
            echo "</div>";
            echo "<div class='form-group'>";
            echo "<label>Quantity</label>";
            echo "<input type='number' name='quantity' value='".$row['quantity']."' required>";
            echo "</div>";
            echo "<button type='submit' name='update_stock' class='btn-submit'>Update Stock</button>";
            echo "</form>";
            echo "</section>";

            echo "</div>";
        }
    ?>
</body>
</html>