<?php 

$productID = $_GET['productID'];

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include './layout/login_error_message.php';
$currentPage = "specific_product.php";
include './logInCheck.php';


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <?php include "./layout/header.php"; ?>
</head>
<body>
    <?php
        include "nav.php";
        $login = $_SESSION['login'] ?? false;
        if($login == true) {
        echo "<div class='main-content'>";
        echo "<h1>Specific Product</h1>";
        echo "Account ID is ".$_SESSION['accountID'];
        echo "</div>";
        }
    ?>
</body>
</html>