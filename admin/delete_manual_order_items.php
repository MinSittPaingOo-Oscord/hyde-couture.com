<?php
include "../connection/connectdb.php";
session_start();
if (!($_SESSION['login'] ?? false)) {
    header("Location: login.php");
    exit;
}

// Get orderItemID and orderID from GET
if (!isset($_GET['orderItemID']) || !isset($_GET['orderID'])) {
    header("Location: specific_manual_order.php?orderID=" . $_GET['orderID'] . "&error=missing_params");
    exit;
}

$orderItemID = (int)$_GET['orderItemID'];
$orderID = (int)$_GET['orderID'];

// Start transaction
$conn->begin_transaction();

try {
    // Fetch order item details, joining with product for price and discountedPrice
    $stmt_item = $conn->prepare("SELECT oi.quantity, oi.productID, oi.size, oi.color, oi.isStockReduce, p.price, p.discountedPrice 
                                 FROM orderitem oi 
                                 JOIN product p ON oi.productID = p.productID 
                                 WHERE oi.orderItemID = ?");
    $stmt_item->bind_param("i", $orderItemID);
    $stmt_item->execute();
    $result_item = $stmt_item->get_result();
    if ($result_item->num_rows === 0) {
        throw new Exception('item_not_found');
    }
    $item = $result_item->fetch_assoc();
    $stmt_item->close();

    // Calculate the sub-total to subtract (same logic as in display)
    $uncal = $item['discountedPrice'] ?? $item['price'];
    $quantity = $item['quantity'];
    $discount = 0;

    // Fetch discount if any
    $stmt_discount = $conn->prepare("SELECT percentage, range1, range2 FROM discount WHERE productID = ?");
    $stmt_discount->bind_param("i", $item['productID']);
    $stmt_discount->execute();
    $result_discount = $stmt_discount->get_result();
    while ($row_discount = $result_discount->fetch_assoc()) {
        if ($quantity >= $row_discount['range1'] && ($row_discount['range2'] === null || $quantity <= $row_discount['range2'])) {
            $discount = $row_discount['percentage'];
            break;
        }
    }
    $stmt_discount->close();

    $calculatedPrice = $uncal - ($uncal * $discount / 100);
    $subTotal = $quantity * $calculatedPrice;

    // Subtract from order's totalCost
    $stmt_order = $conn->prepare("UPDATE orderr SET totalCost = totalCost - ? WHERE orderID = ?");
    $stmt_order->bind_param("di", $subTotal, $orderID);
    $stmt_order->execute();
    $stmt_order->close();

    // If isStockReduce == 1, add back to stock
    if ($item['isStockReduce'] == 1) {
        $stmt_stock = $conn->prepare("UPDATE stock SET quantity = quantity + ? 
                                      WHERE productID = ? AND sizeID = ? AND colorID = ?");
        $stmt_stock->bind_param("iiii", $item['quantity'], $item['productID'], $item['size'], $item['color']);
        $stmt_stock->execute();
        $stmt_stock->close();
    }

    // Delete the order item
    $stmt_delete = $conn->prepare("DELETE FROM orderitem WHERE orderItemID = ?");
    $stmt_delete->bind_param("i", $orderItemID);
    $stmt_delete->execute();
    $stmt_delete->close();

    // Commit transaction
    $conn->commit();

    header("Location: specific_manual_order.php?orderID=$orderID&success=deleted");
    exit;

} catch (Exception $e) {
    $conn->rollback();
    header("Location: specific_manual_order.php?orderID=$orderID&error=" . $e->getMessage());
    exit;
}

$conn->close();
?>