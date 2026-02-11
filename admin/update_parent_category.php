<?php 

include "../connection/connectdb.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include './layout/login_error_message.php';
$currentPage = "edit_parent_category.php";
include './logInCheck.php'; 

$categoryID = $_POST['categoryID'];
$categoryName = $_POST['categoryName'];

$stmt = $conn->prepare("UPDATE category SET categoryName = ? WHERE categoryID = ?");
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("si", $categoryName, $categoryID);
   
if ($stmt->execute()) {
    header("Location: edit_parent_category.php?categoryID=$categoryID");
} else {
    header("Location: edit_parent_category.php?categoryID=$categoryID");
}
$stmt->close();

?>
