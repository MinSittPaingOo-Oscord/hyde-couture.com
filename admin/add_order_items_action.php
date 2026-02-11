<?php

include "../connection/connectdb.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include './layout/login_error_message.php';
include './logInCheck.php'; 

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('Invalid request');
}

$orderID   = intval($_POST['orderID']);
$productID = intval($_POST['product']);
$colorID   = intval($_POST['color']);
$sizeID    = intval($_POST['size']);
$quantity  = intval($_POST['quantity']);

if ($quantity <= 0) {
    header("Location: specific_manual_order.php?orderID=$orderID&error=invalid_quantity");
    exit;
}

// 1. Get unit price (use discountedPrice if exists, otherwise price)
$stmt = $conn->prepare("SELECT COALESCE(discountedPrice, price) AS unit_price FROM product WHERE productID = ?");
$stmt->bind_param("i", $productID);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
if (!$row) {
    header("Location: specific_manual_order.php?orderID=$orderID&error=product_not_found");
    exit;
}
$unit_price = $row['unit_price'];
$stmt->close();

$totalCost = $quantity * $unit_price;

// 2. Get applicable discount percentage
// Logic:
// - range1 <= quantity <= range2     (when range2 is set)
// - range1 <= quantity               (when range2 IS NULL)
$percentage = null;
$stmt = $conn->prepare(
    "SELECT percentage 
     FROM discount 
     WHERE productID = ? 
       AND range1 <= ? 
       AND (range2 >= ? OR range2 IS NULL)
     ORDER BY range1 DESC 
     LIMIT 1"
);
$stmt->bind_param("iii", $productID, $quantity, $quantity);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $percentage = $row['percentage'];
}
$stmt->close();

$discountedTotalCost = null;
if ($percentage !== null && $percentage > 0) {
    $discountedTotalCost = $totalCost * (1 - $percentage / 100);
}

// 3. Check stock availability
$stmt = $conn->prepare("SELECT stockID, quantity AS stock_qty 
                        FROM stock 
                        WHERE productID = ? AND colorID = ? AND sizeID = ?");
$stmt->bind_param("iii", $productID, $colorID, $sizeID);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
if (!$row || $row['stock_qty'] < $quantity) {
    header("Location: specific_manual_order.php?orderID=$orderID&error=insufficient_stock");
    exit;
}
$stockID = $row['stockID'];
$stmt->close();

// 4. Insert order item
$stmt = $conn->prepare(
    "INSERT INTO orderitem 
    (quantity, productID, totalCost, orderID, discountedTotalCost, color, size) 
    VALUES (?, ?, ?, ?, ?, ?, ?)"
);
$stmt->bind_param("iididii", $quantity, $productID, $totalCost, $orderID, $discountedTotalCost, $colorID, $sizeID);

if (!$stmt->execute()) {
    header("Location: specific_manual_order.php?orderID=$orderID&error=insert_failed");
    exit;
}
$orderItemID = $stmt->insert_id;
$stmt->close();

// 5. Check if stock should be reduced based on conditions
$stmt = $conn->prepare("SELECT paymentStatus, paymentType, trackingStatus 
                        FROM orderr WHERE orderID = ?");
$stmt->bind_param("i", $orderID);
$stmt->execute();
$result = $stmt->get_result();
$ord = $result->fetch_assoc();
$stmt->close();

$shouldReduceStock = false;

if ($ord) {
    if ($ord['paymentStatus'] == 2 && $ord['paymentType'] == 2) {
        $shouldReduceStock = true;  // Condition 1: Paid via bank transfer
    } elseif ($ord['paymentType'] == 1 && $ord['trackingStatus'] == 2) {
        $shouldReduceStock = true;  // Condition 2: COD and shipped
    }
}

if ($shouldReduceStock) {
    // Reduce stock only if conditions are met
    $stmt = $conn->prepare("UPDATE stock SET quantity = quantity - ? WHERE stockID = ?");
    $stmt->bind_param("ii", $quantity, $stockID);
    $stmt->execute();
    $stmt->close();
}

// 6. Update isStockReduce for the new order item
$isReduce = $shouldReduceStock ? 1 : 0;
$stmt = $conn->prepare("UPDATE orderitem SET isStockReduce = ? WHERE orderItemID = ?");
$stmt->bind_param("ii", $isReduce, $orderItemID);
$stmt->execute();
$stmt->close();

// 7. Update order total
$stmt = $conn->prepare(
    "UPDATE orderr 
     SET totalCost = (
         SELECT SUM(COALESCE(discountedTotalCost, totalCost)) 
         FROM orderitem 
         WHERE orderID = ?
     ) 
     WHERE orderID = ?"
);
$stmt->bind_param("ii", $orderID, $orderID);
$stmt->execute();
$stmt->close();

header("Location: specific_manual_order.php?orderID=$orderID&success=1");
exit;
?>