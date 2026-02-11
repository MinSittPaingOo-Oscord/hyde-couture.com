<?php
include "../connection/connectdb.php";
session_start();
if (!($_SESSION['login'] ?? false)) header("Location: login.php");

$id = (int)$_GET['id'];
if ($id) {
    // Delete photos from folder
    $photos = $conn->query("SELECT photoName FROM photo WHERE productID = $id");
    while ($ph = $photos->fetch_assoc()) {
        @unlink("../image/" . $ph['photoName']);
    }
    $conn->query("DELETE FROM photo WHERE productID = $id");
    $conn->query("DELETE FROM stock WHERE productID = $id");
    $conn->query("DELETE FROM productxcategory WHERE productID = $id");
    $conn->query("DELETE FROM discount WHERE productID = $id");
    $conn->query("DELETE FROM relatedproduct WHERE productID1 = $id OR productID2 = $id");
    $conn->query("DELETE FROM product WHERE productID = $id");
}
header("Location: product.php?deleted=1");
?>