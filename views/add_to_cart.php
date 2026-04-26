<?php
session_start();
include '../connection/connectdb.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pid        = intval($_POST['pid']);
    $colorName  = $_POST['color'];
    $sizeName   = $_POST['size'];
    $requestQty = intval($_POST['qty']);

    // 1. Get stock + product info INCLUDING colorID and sizeID
    $stmt = $conn->prepare("
        SELECT s.quantity, s.colorID, s.sizeID,
               p.productName, p.price, p.discountedPrice,
               (SELECT photoName FROM photo WHERE productID = p.productID LIMIT 1) AS photo
        FROM stock s
        JOIN color c   ON s.colorID   = c.colorID
        JOIN size sz   ON s.sizeID    = sz.sizeID
        JOIN product p ON s.productID = p.productID
        WHERE s.productID = ? AND c.colorName = ? AND sz.sizeName = ?
    ");
    $stmt->bind_param("iss", $pid, $colorName, $sizeName);
    $stmt->execute();
    $dbData = $stmt->get_result()->fetch_assoc();

    if (!$dbData) {
        echo json_encode(['success' => false, 'message' => 'Variant not found.']);
        exit;
    }

    if ($dbData['quantity'] < $requestQty) {
        echo json_encode(['success' => false, 'message' => 'Only ' . $dbData['quantity'] . ' items left in stock.']);
        exit;
    }

    // 2. Calculate quantity-based discount
    $basePrice      = $dbData['discountedPrice'] ?: $dbData['price'];
    $totalQtyInCart = $requestQty;

    $itemKey = $pid . "_" . $colorName . "_" . $sizeName;
    if (isset($_SESSION['cart'][$itemKey])) {
        $totalQtyInCart = $_SESSION['cart'][$itemKey]['qty'] + $requestQty;
    }

    $finalPrice = getDiscountedPrice($conn, $pid, $basePrice, $totalQtyInCart);

    // 3. Build cart item — now includes colorID and sizeID
    $cartItem = [
        'pid'      => $pid,
        'name'     => $dbData['productName'],
        'price'    => $finalPrice,
        'oldPrice' => $basePrice,
        'color'    => $colorName,
        'size'     => $sizeName,
        'colorID'  => (int)$dbData['colorID'],  // ← FIXED: store the actual ID
        'sizeID'   => (int)$dbData['sizeID'],   // ← FIXED: store the actual ID
        'qty'      => $totalQtyInCart,
        'img'      => $dbData['photo'],
        'maxStock' => $dbData['quantity']
    ];

    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

    if (isset($_SESSION['cart'][$itemKey])) {
        if ($totalQtyInCart > $dbData['quantity']) {
            echo json_encode(['success' => false, 'message' => 'Total in cart exceeds stock.']);
            exit;
        }
        $_SESSION['cart'][$itemKey] = $cartItem;
    } else {
        $_SESSION['cart'][$itemKey] = $cartItem;
    }

    echo json_encode(['success' => true]);
}

// ── Helper: look up discount table and return final unit price ──
function getDiscountedPrice($conn, $pid, $basePrice, $qty) {
    $stmt = $conn->prepare("
        SELECT percentage FROM discount
        WHERE productID = ?
          AND range1 <= ?
          AND (range2 >= ? OR range2 IS NULL)
        ORDER BY range1 DESC
        LIMIT 1
    ");
    $stmt->bind_param("iii", $pid, $qty, $qty);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();

    if ($row) {
        return $basePrice * (1 - $row['percentage'] / 100);
    }
    return $basePrice;
}
?>