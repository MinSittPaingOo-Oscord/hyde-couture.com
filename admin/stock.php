<?php 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../connection/connectdb.php';

include './layout/login_error_message.php';
$currentPage = "stock.php";
include './logInCheck.php'; 

$export = $_GET['export'] ?? false;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_stock'])) {
    $productID = $_POST['productID'];
    $sizeID = $_POST['sizeID'];
    $colorID = $_POST['colorID'];
    $quantity = $_POST['quantity'];

    $insertQuery = "INSERT INTO stock (productID, sizeID, colorID, quantity) VALUES ($productID, $sizeID, $colorID, $quantity)";
    $conn->query($insertQuery);

    header("Location: stock.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_size'])) {
    $sizeName = $_POST['sizeName'];

    $insertSize = "INSERT INTO size (sizeName) VALUES ('$sizeName')";
    $conn->query($insertSize);

    header("Location: stock.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_color'])) {
    $colorName = $_POST['colorName'];
    $colorCode = $_POST['colorCode'];

    $insertColor = "INSERT INTO color (colorName, colorCode) VALUES ('$colorName', '$colorCode')";
    $conn->query($insertColor);

    header("Location: stock.php");
    exit();
}

$query = "SELECT * FROM stock JOIN product ON stock.productID = product.productID JOIN size ON stock.sizeID = size.sizeID JOIN color ON stock.colorID = color.colorID";
$result = $conn->query($query);

if ($export === 'csv') {
    $csvResult = $conn->query($query);

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=product_stock_list_' . date('Ymd_His') . '.csv');

    $out = fopen('php://output', 'w');
    fputcsv($out, ['Product Name', 'Quantity', 'Size', 'Color']);

    while ($row = $csvResult->fetch_assoc()) {
        fputcsv($out, [
            $row['productName'],
            $row['quantity'],
            $row['sizeName'],
            $row['colorName']
        ]);
    }
    fclose($out);
    exit;
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock</title>
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

        .header-actions select {
            background: rgba(255,255,255,0.15);
            border: none;
            color: white;
            padding: 8px 14px;
            border-radius: 0;
            font-size: 0.9rem;
        }

        .stock-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            border-radius: 0;
            overflow: hidden;
        }

        .stock-table thead {
            background: #f8f9fa;
            border-bottom: 2px solid #e0f0e0;
        }

        .stock-table thead th {
            padding: 16px 15px;
            text-align: left;
            font-weight: 500;
            color: #004d00;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        .stock-table tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid #e9ecef;
        }

        .stock-table tbody tr:hover {
            background-color: #f8fff8;
            box-shadow: 0 2px 8px rgba(0,77,0,0.05);
        }

        .stock-table td {
            padding: 16px 15px;
            vertical-align: middle;
            font-size: 1rem;
            color: #333;
        }

        .stock-id {
            font-family: 'Courier New', monospace;
            background: #e6f7e6;
            color: #004d00;
            padding: 6px 10px;
            border-radius: 0;
            font-weight: 600;
            font-size: 0.9rem;
            display: inline-block;
        }

        .btn-edit, .btn-delete {
            padding: 8px 16px;
            border: none;
            border-radius: 0;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            display: inline-block;
            margin-right: 10px;
        }

        .btn-edit {
            background: linear-gradient(45deg, #004d00, #006600);
            color: white;
        }

        .btn-edit:hover {
            background: linear-gradient(45deg, #006600, #008000);
            transform: translateY(-1px);
            box-shadow: 0 3px 8px rgba(0,102,0,0.3);
        }

        .btn-delete {
            background: linear-gradient(45deg, #cc0000, #990000);
            color: white;
        }

        .btn-delete:hover {
            background: linear-gradient(45deg, #990000, #660000);
            transform: translateY(-1px);
            box-shadow: 0 3px 8px rgba(204,0,0,0.3);
        }

        .no-stock {
            text-align: center;
            padding: 40px 20px;
            color: #666;
            font-style: italic;
            background: #f8fff8;
            border: 1px dashed #e0f0e0;
            border-radius: 0;
            margin: 20px 0;
        }

        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                text-align: center;
            }

            .header-actions {
                justify-content: center;
            }

            .stock-table {
                border: 0;
            }

            .stock-table thead {
                display: none;
            }

            .stock-table tbody tr {
                display: block;
                margin-bottom: 24px;
                border: 1px solid #e0f0e0;
                border-radius: 0;
                padding: 16px;
                background: white;
                box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            }

            .stock-table td {
                display: block;
                text-align: right;
                padding: 12px 10px;
                border: none;
                position: relative;
                padding-left: 50%;
                font-size: 0.95rem;
                color: #333;
                box-sizing: border-box;
            }

            .stock-table td:before {
                content: attr(data-label);
                position: absolute;
                left: 0;
                width: 50%;
                padding-left: 10px;
                padding-right: 10px;
                font-weight: 600;
                color: #004d00;
                text-transform: uppercase;
                font-size: 0.8rem;
                letter-spacing: 0.5px;
                top: 50%;
                transform: translateY(-50%);
                text-align: left;
                box-sizing: border-box;
            }

            .stock-table td:last-child {
                text-align: center;
                padding-top: 15px;
            }

            .btn-edit, .btn-delete {
                width: 48%;
                padding: 12px;
                font-size: 1rem;
                margin : 20px;
                justify-content : right;
            }

            .add-form {
                grid-template-columns: 1fr;
            }

            .stock-table tbody tr {
            display: block;
            margin-bottom: 0px; /* Reduced from 24px to minimize vertical spacing between stock items */
            border: 1px solid #e0f0e0;
            border-radius: 4px; /* Added subtle rounding for neatness */
            padding: 12px; /* Slightly reduced padding for tighter layout */
            background: white;
            box-shadow: 0 2px 6px rgba(0,0,0,0.06); /* Lighter shadow for cleaner look */
            position: relative; /* For better button positioning */
        }

        .stock-table td {
            display: block;
            text-align: right;
            padding: 8px 10px; /* Reduced padding for compactness */
            border: none;
            position: relative;
            padding-left: 50%;
            font-size: 0.95rem;
            color: #333;
            box-sizing: border-box;
        }

        .stock-table td:before {
            content: attr(data-label);
            position: absolute;
            left: 0;
            width: 50%;
            padding-left: 10px;
            padding-right: 10px;
            font-weight: 600;
            color: #004d00;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
            top: 50%;
            transform: translateY(-50%);
            text-align: left;
            box-sizing: border-box;
        }

        .stock-table td:last-child {
            text-align: center;
            padding: 10px 0 5px 0; /* Adjusted for tighter button area */
            border-top: 1px solid #e9ecef; /* Subtle separator above buttons */
        }

        /* Wrap buttons in a flex container for better alignment */
        .stock-table td:last-child {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            gap: 8px; /* Small gap between buttons */
            padding: 10px 0;
        }

        .btn-edit, .btn-delete {
            flex: 1; /* Equal width */
            width: auto; /* Override previous width */
            padding: 10px 8px; /* Compact padding */
            font-size: 0.9rem; /* Slightly smaller for mobile */
            margin: 0; /* Remove margins for tight fit */
            min-width: 80px; /* Ensure minimum tappable size */
            text-align: center;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .add-form {
            grid-template-columns: 1fr;
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
            echo "<h1>Stock Management</h1>";
            echo "<div class='header-actions'>";
            echo "<form id='filterForm' method='get' style='display:inline;'>";
            echo "<button type='submit' name='export' value='csv'><i class='bi bi-download'></i> Export</button>";
            echo "</form>";
            echo "<button onclick=\"window.location.href='add_new_stock.php'\"><i class='fa fa-plus'></i> Add Stock</button>";
            echo "<button onclick=\"window.location.href='add_new_color.php'\"><i class='fa fa-plus'></i> Add Color</button>";
            echo "<button onclick=\"window.location.href='add_new_size.php'\"><i class='fa fa-plus'></i> Add Size</button>";
            echo "</div>";
            echo "</div>";

            if ($result && $result->num_rows > 0) {
                echo "<table class='stock-table'>";
                echo "<thead><tr>";
                echo "<th>Product Name</th>";
                echo "<th>Quantity</th>";
                echo "<th>Size</th>";
                echo "<th>Color</th>";
                echo "<th>Actions</th>";
                echo "</tr></thead>";
                echo "<tbody>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td data-label='Product Name'>".$row['productName']."</td>";
                    echo "<td data-label='Quantity'>".$row['quantity']."</td>";
                    echo "<td data-label='Size'>".$row['sizeName']."</td>";
                    echo "<td data-label='Color'>".$row['colorName']."</td>";
                    echo "<td data-label='Actions'>";
                    echo "<a href='edit_stock.php?stockID=".$row['stockID']."' class='btn-edit'>Edit</a>";
                    echo "<a href='delete_stock.php?stockID=".$row['stockID']."' class='btn-delete'>Delete</a>";
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "<div class='no-stock'>No stock items available.</div>";
            }

            echo "</div>";
        }
    ?>
</body>
</html>