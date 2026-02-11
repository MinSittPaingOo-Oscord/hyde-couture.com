<?php 

include "../connection/connectdb.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include './layout/login_error_message.php';
$currentPage = "category.php"; // Redirect back here after update
include './logInCheck.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categoryID = $_POST['categoryID'];
    $categoryName = $_POST['categoryName'];
    $parentID = $_POST['parentCategory'];

    // Note: For security, use prepared statements in production to prevent SQL injection
    $query = "UPDATE category SET categoryName = '$categoryName', parentID = $parentID WHERE categoryID = $categoryID";
    $result = $conn->query($query);

    if ($result) {
        // Success: Redirect back to category list
        header("Location: category.php");
        exit();
    } else {
        // Error handling: Display error (or log it)
        echo "Error updating category: " . $conn->error;
    }
} else {
    // If not POST, redirect or show error
    header("Location: category.php");
    exit();
}

?>