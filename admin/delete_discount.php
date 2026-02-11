<?php 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../connection/connectdb.php';
include './layout/login_error_message.php';
$currentPage = "delete_stock.php";
include './logInCheck.php'; 

$discountID = $_GET['discountID'];

if (isset($_GET['confirm']) && $_GET['confirm'] == 'yes') {
    $deleteQuery = "DELETE FROM discount WHERE discountID = $discountID";
    $conn->query($deleteQuery);

    header("Location: discount.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Discount</title>
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

        .delete-section {
            background: white;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            border-radius: 0;
            text-align: center;
        }

        .delete-section p {
            color: #004d00;
            font-size: 1.2rem;
            margin-bottom: 20px;
        }

        .btn-confirm, .btn-cancel {
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
            margin: 0 10px;
        }

        .btn-confirm {
            background: linear-gradient(45deg, #cc0000, #990000);
            color: white;
        }

        .btn-confirm:hover {
            background: linear-gradient(45deg, #990000, #660000);
            transform: translateY(-1px);
            box-shadow: 0 3px 8px rgba(204,0,0,0.3);
        }

        .btn-cancel {
            background: linear-gradient(45deg, #004d00, #006600);
            color: white;
        }

        .btn-cancel:hover {
            background: linear-gradient(45deg, #006600, #008000);
            transform: translateY(-1px);
            box-shadow: 0 3px 8px rgba(0,102,0,0.3);
        }

        @media (max-width: 768px) {
            .btn-confirm, .btn-cancel {
                width: 100%;
                margin: 10px 0;
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
            echo "<h1>Delete Discount</h1>";
            echo "</div>";

            echo "<section class='delete-section'>";
            echo "<p>Are you sure you want to delete this discount?</p>";
            echo "<a href='delete_discount.php?discountID=$discountID&confirm=yes' class='btn-confirm'>Yes</a>";
            echo "<a href='discount.php' class='btn-cancel'>No</a>";
            echo "</section>";

            echo "</div>";
        }
    ?>
</body>
</html>