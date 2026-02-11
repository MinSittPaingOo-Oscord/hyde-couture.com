<?php 

include "../connection/connectdb.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include './layout/login_error_message.php';
$currentPage = "category.php"; // Redirect back here after create
include './logInCheck.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categoryName = $conn->real_escape_string($_POST['categoryName']); // Sanitize to prevent SQL injection
    $parentCategory = $_POST['parentCategory'];

    $parentID = ($parentCategory === '') ? 'NULL' : intval($parentCategory);

    // Note: For security, prepared statements are better, but using escape for simplicity
    $query = "INSERT INTO category (categoryName, parentID) VALUES ('$categoryName', $parentID)";
    $result = $conn->query($query);

    if ($result) {
        // Success: Redirect back to category list
        header("Location: category.php");
        exit();
    } else {
        // Error handling: Display error (or log it)
        echo "Error creating category: " . $conn->error;
    }
} else {
    // If not POST, redirect or show error
    header("Location: add_new_category.php");
    exit();
}

?>