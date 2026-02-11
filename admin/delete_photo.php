<?php
// delete_photo.php
include "../connection/connectdb.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include './layout/login_error_message.php';
$currentPage = "delete_photo.php";
include './logInCheck.php'; // Ensures only logged-in admin can delete

// Must have both photo_id and product_id
if (!isset($_GET['photo_id']) || !isset($_GET['product_id'])) {
    header("Location: product.php");
    exit();
}

$photo_id   = (int)$_GET['photo_id'];
$product_id = (int)$_GET['product_id'];

// Security: verify this photo really belongs to this product
$stmt = $conn->prepare("SELECT photoName FROM photo WHERE photoID = ? AND productID = ?");
$stmt->bind_param("ii", $photo_id, $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $filename = $row['photoName'];
    $filepath = "../image/" . $filename;

    // Delete file from server
    if (file_exists($filepath)) {
        unlink($filepath);
    }

    // Delete record from database
    $delete_stmt = $conn->prepare("DELETE FROM photo WHERE photoID = ?");
    $delete_stmt->bind_param("i", $photo_id);
    $delete_stmt->execute();
    $delete_stmt->close();
}

$stmt->close();
$conn->close();

// Redirect back to the edit page
header("Location: edit_product.php?id=$product_id");
exit();
?>