<?php
session_start();
include '../connection/connectdb.php';

// ====================== FORCE LOGIN FOR CHECKOUT ======================
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true || empty($_SESSION['accountID'])) {
    $_SESSION['alert'] = "Please log in to proceed to checkout.";
    header("Location: profile.php");
    exit();
}
// =====================================================================

// Redirect if cart is empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<script>alert('Your cart is empty'); window.location.href='index.php';</script>";
    exit;
}

// ---------------------------------------------------------
// 1. AJAX FIRST — before ANY HTML output (nav/footer etc.)
// ---------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax_action'])) {
    // Clean any accidental output buffers so JSON is pure
    while (ob_get_level()) ob_end_clean();
    header('Content-Type: application/json');

    $action = $_POST['ajax_action'];
    $accID  = $accID = intval($_SESSION['accountID']);;

    if ($action === 'add') {
        $street   = trim($_POST['street']   ?? '');
        $township = trim($_POST['township'] ?? '');
        $city     = trim($_POST['city']     ?? '');
        $state    = trim($_POST['state']    ?? '');
        $postal   = trim($_POST['postal']   ?? '');
        $country  = trim($_POST['country']  ?? 'Myanmar');
        $mapLink  = trim($_POST['mapLink']  ?? '');
        $parts    = array_filter([$street, $township, $city, $state, $country]);
        $complete = implode(', ', $parts);
        if ($postal) $complete .= ' ' . $postal;

        $stmt = $conn->prepare(
            "INSERT INTO address (street, township, city, state, postalCode, country, completeAddress, mapLink, accountID)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param("ssssssssi", $street, $township, $city, $state, $postal, $country, $complete, $mapLink, $accID);
        if ($stmt->execute()) {
            $newID = $conn->insert_id;
            echo json_encode([
                'success' => true,
                'address' => [
                    'id'          => $newID,
                    'street'      => $street,
                    'township'    => $township,
                    'city'        => $city,
                    'state'       => $state,
                    'country'     => $country,
                    'postal'      => $postal,
                    'mapLink'     => $mapLink,
                    'fullAddress' => $complete,
                ]
            ]);
        } else {
            echo json_encode(['success' => false, 'error' => $conn->error]);
        }
        exit;
    }

    if ($action === 'edit') {
        $addrID   = intval($_POST['addressID'] ?? 0);
        $street   = trim($_POST['street']   ?? '');
        $township = trim($_POST['township'] ?? '');
        $city     = trim($_POST['city']     ?? '');
        $state    = trim($_POST['state']    ?? '');
        $postal   = trim($_POST['postal']   ?? '');
        $country  = trim($_POST['country']  ?? 'Myanmar');
        $mapLink  = trim($_POST['mapLink']  ?? '');
        $parts    = array_filter([$street, $township, $city, $state, $country]);
        $complete = implode(', ', $parts);
        if ($postal) $complete .= ' ' . $postal;

        $stmt = $conn->prepare(
            "UPDATE address SET street=?, township=?, city=?, state=?, postalCode=?, country=?, completeAddress=?, mapLink=?
             WHERE addressID=? AND accountID=?"
        );
        $stmt->bind_param("ssssssssii", $street, $township, $city, $state, $postal, $country, $complete, $mapLink, $addrID, $accID);
        if ($stmt->execute()) {
            echo json_encode([
                'success' => true,
                'address' => [
                    'id'          => $addrID,
                    'street'      => $street,
                    'township'    => $township,
                    'city'        => $city,
                    'state'       => $state,
                    'country'     => $country,
                    'postal'      => $postal,
                    'mapLink'     => $mapLink,
                    'fullAddress' => $complete,
                ]
            ]);
        } else {
            echo json_encode(['success' => false, 'error' => $conn->error]);
        }
        exit;
    }

if ($action === 'remove') {
    $addrID = intval($_POST['addressID'] ?? 0);
    
    // Check if linked to orders
    $check = $conn->query("SELECT COUNT(*) as cnt FROM orderr WHERE addressID = $addrID");
    $cnt = $check->fetch_assoc()['cnt'];
    
    if ($cnt > 0) {
        // Unlink from account instead of deleting
        $stmt = $conn->prepare("UPDATE address SET accountID = NULL WHERE addressID=? AND accountID=?");
    } else {
        $stmt = $conn->prepare("DELETE FROM address WHERE addressID=? AND accountID=?");
    }
    $stmt->bind_param("ii", $addrID, $accID);
    echo json_encode(['success' => $stmt->execute()]);
    exit;
}

    echo json_encode(['success' => false, 'error' => 'Unknown action']);
    exit;
}

// ---------------------------------------------------------
// NOW safe to include layout (outputs HTML)
// ---------------------------------------------------------
include '../layout/nav.php';

// ---------------------------------------------------------
// 2. Final Order Placement
// ---------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    $conn->begin_transaction();
    try {
        $accountID = $accID = intval($_SESSION['accountID']);;
        $addressID = intval($_POST['selected_address_id'] ?? 0);
        if (!$addressID) throw new Exception("Please select a delivery address.");

        // ── STOCK CHECK FOR ALL CART ITEMS BEFORE PLACING ORDER ──
// ── STOCK CHECK FOR ALL CART ITEMS BEFORE PLACING ORDER ──
foreach ($_SESSION['cart'] as $key => $item) {
    $pid       = intval($item['pid'] ?? $item['id'] ?? 0);
    $colorName = $item['color'] ?? '';
    $sizeName  = $item['size']  ?? '';
    $qty       = intval($item['qty']);

    $stmt = $conn->prepare("
        SELECT s.stockID, s.quantity AS stock_qty, s.colorID, s.sizeID,
               COALESCE((
                   SELECT SUM(oi.quantity)
                   FROM orderitem oi
                   WHERE oi.productID = ?
                     AND oi.color = s.colorID
                     AND oi.size  = s.sizeID
                     AND oi.isStockReduce = 1
               ), 0) AS used_qty
        FROM stock s
        JOIN color c ON s.colorID = c.colorID
        JOIN size  sz ON s.sizeID  = sz.sizeID
        WHERE s.productID = ? AND c.colorName = ? AND sz.sizeName = ?
    ");
    $stmt->bind_param("iiss", $pid, $pid, $colorName, $sizeName);
    $stmt->execute();
    $stockRow = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$stockRow) {
        throw new Exception("Product '{$item['name']}' variant not found in stock.");
    }

    $available = $stockRow['stock_qty'] - $stockRow['used_qty'];
    if ($available < $qty) {
        throw new Exception(
            "Sorry, '{$item['name']}' ({$colorName}, {$sizeName}) " .
            "only has {$available} units available but you ordered {$qty}."
        );
    }
}
// ── END STOCK CHECK ──

        $subtotal = 0;
        foreach ($_SESSION['cart'] as $item) {
            $subtotal += ($item['price'] * $item['qty']);
        }
        $grandTotal    = $subtotal + 35;
        $paymentTypeID = ($_POST['high_level_payment'] === 'cod') ? 1 : 2;

        $stmtOrder = $conn->prepare(
            "INSERT INTO orderr (totalCost, orderDate, paymentStatus, orderStatus, trackingStatus, 
                                 accountID, paymentType, addressID, isManual, paymentValid)
             VALUES (?, CURDATE(), 1, 1, 1, ?, ?, ?, 0, 1)"
        );
        $stmtOrder->bind_param("diiii", $grandTotal, $accountID, $paymentTypeID, $addressID);
        $stmtOrder->execute();
        $orderID = $conn->insert_id;

        if ($paymentTypeID === 2 && isset($_FILES['payment_slip']) && $_FILES['payment_slip']['error'] === UPLOAD_ERR_OK) {
            $fileName = 'paymentSlip_' . uniqid() . '.' . pathinfo($_FILES['payment_slip']['name'], PATHINFO_EXTENSION);
            if (move_uploaded_file($_FILES['payment_slip']['tmp_name'], '../image/' . $fileName)) {
                $sp = $conn->prepare("INSERT INTO photo (photoName) VALUES (?)");
                $sp->bind_param("s", $fileName);
                $sp->execute();
                $photoID = $conn->insert_id;
                $ss = $conn->prepare("INSERT INTO paymentslip (paymentSlip, orderID) VALUES (?, ?)");
                $ss->bind_param("ii", $photoID, $orderID);
                $ss->execute();
            }
        }

        $stmtItem = $conn->prepare(
            "INSERT INTO orderitem (quantity, productID, totalCost, orderID, color, size)
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        foreach ($_SESSION['cart'] as $item) {
            $qty   = intval($item['qty']);
            $pid   = intval($item['pid'] ?? $item['id'] ?? 0);
            $cost  = floatval($item['price'] * $item['qty']);
            $color = intval($item['colorID'] ?? 1);
            $size  = intval($item['sizeID']  ?? 1);
            $stmtItem->bind_param("iidiii", $qty, $pid, $cost, $orderID, $color, $size);
            $stmtItem->execute();
        }

        $conn->commit();
        unset($_SESSION['cart']);
        echo "<script>alert('Order placed successfully!'); window.location.href='index.php';</script>";
        exit;
    } catch (Exception $e) {
        $conn->rollback();
        $orderError = $e->getMessage();
    }
}

// ---------------------------------------------------------
// 3. Page data
// ---------------------------------------------------------
$subtotal = 0;
foreach ($_SESSION['cart'] as $item) {
    $subtotal += ($item['price'] * $item['qty']);
}
$grandTotal = $subtotal + 35;

$accID      = $accID = intval($_SESSION['accountID']);;
$addrResult = $conn->query("SELECT * FROM address WHERE accountID = $accID ORDER BY addressID DESC");
$dbAddresses = [];
while ($row = $addrResult->fetch_assoc()) {
    $dbAddresses[] = [
        'id'          => (int)$row['addressID'],
        'street'      => $row['street'],
        'township'    => $row['township'],
        'city'        => $row['city'],
        'state'       => $row['state'],
        'country'     => $row['country'] ?? 'Myanmar',
        'postal'      => $row['postalCode'] ?? '',
        'mapLink'     => $row['mapLink'] ?? '',
        'fullAddress' => $row['completeAddress'],
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order and Payment Submission</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .checkout-page {
            --cp-green-dark:   #0f4f1d;
            --cp-green-border: #38a848;
            --cp-green-light:  #e5f6e5;
            --cp-text-dark:    #333;
            --cp-text-light:   #666;
            --cp-white:        #ffffff;
            font-family: Arial, sans-serif;
            background: #f8f8f8;
            padding: 20px;
            display: flex;
            justify-content: center;
        }
        .checkout-page * { box-sizing: border-box; }
        .checkout-page .main-container {
            background: var(--cp-white);
            padding: 30px;
            max-width: 860px;
            width: 100%;
            box-shadow: 0 0 12px rgba(0,0,0,.06);
        }
        .cp-title { color: var(--cp-green-dark); font-size: 28px; font-weight: bold; margin: 0 0 25px 0; }
        .cp-section-header {
            font-size: 12px; font-weight: bold; color: var(--cp-text-light);
            text-transform: uppercase; letter-spacing: 1px;
            margin: 28px 0 12px; padding-bottom: 6px; border-bottom: 1px solid #eee;
        }

        /* Payment toggles */
        .cp-pay-toggles { display: flex; gap: 12px; }
        .cp-pay-toggle-label {
            flex: 1; padding: 14px; border: 1px solid #ccc;
            border-radius: 6px; text-align: center; cursor: pointer;
            font-size: 15px; font-weight: bold; transition: .2s;
        }
        .cp-pay-toggle-label.selected {
            border-color: var(--cp-green-border);
            background: var(--cp-green-light);
            color: var(--cp-green-dark);
        }
        .cp-pay-toggles input[type="radio"] { display: none; }

        /* Address header */
        .cp-addr-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
        .cp-addr-title  { font-size: 22px; font-weight: bold; color: var(--cp-green-dark); margin: 0; }

        /* Address card */
        .cp-addr-item {
            background: #fff; border: 1px solid #ddd;
            border-radius: 8px; margin-bottom: 12px; overflow: hidden;
            transition: border-color .2s;
        }
        /* SELECTED state */
        .cp-addr-item.is-selected { border: 2px solid var(--cp-green-border); }
        .cp-addr-item.is-selected > .cp-addr-accordion-title { background: var(--cp-green-light); }

        .cp-addr-accordion-title {
            padding: 14px 16px;
            display: flex; justify-content: space-between; align-items: center;
            cursor: pointer; background: #f7f7f7;
            font-weight: bold; color: var(--cp-green-dark);
            transition: background .2s;
            user-select: none;
        }
        .cp-addr-accordion-title:hover { background: var(--cp-green-light); }

        /* SELECTED badge pill */
        .cp-selected-badge {
            display: inline-flex; align-items: center; gap: 5px;
            background: var(--cp-green-border); color: #fff;
            font-size: 11px; font-weight: bold;
            padding: 3px 10px; border-radius: 20px; letter-spacing: .5px;
        }

        .cp-addr-content { padding: 18px 20px; border-top: 1px solid #eee; display: none; }
        .cp-addr-detail-grid {
            display: grid; grid-template-columns: 110px 1fr;
            gap: 8px 16px; margin-bottom: 18px;
        }
        .cp-addr-label {
            font-size: 11px; text-transform: uppercase;
            font-weight: bold; color: var(--cp-text-light);
            align-self: start; padding-top: 2px;
        }
        .cp-addr-value { font-size: 14px; color: var(--cp-text-dark); }
        .cp-addr-full-row { grid-column: 1/-1; padding-top: 10px; border-top: 1px dashed #eee; }
        .cp-addr-actions { display: flex; gap: 10px; justify-content: space-between; align-items: center; }

        /* Inline form */
        .cp-addr-form {
            display: none; padding: 20px 20px 16px;
            border-top: 1px solid #eee; background: #fafafa;
        }
        .cp-form-title  { margin: 0 0 16px; font-size: 15px; color: var(--cp-green-dark); }
        .cp-form-row    { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 12px; }
        .cp-form-group  { display: flex; flex-direction: column; }
        .cp-form-group label { font-size: 12px; font-weight: bold; margin-bottom: 5px; color: var(--cp-text-dark); }
        .cp-form-group input,
        .cp-form-group select {
            padding: 9px 11px; border: 1px solid #ccc;
            border-radius: 4px; font-size: 14px; transition: border .2s;
        }
        .cp-form-group input:focus,
        .cp-form-group select:focus { outline: none; border-color: var(--cp-green-border); }
        .cp-form-actions { margin-top: 6px; display: flex; gap: 8px; justify-content: flex-end; }

        /* Buttons */
        .cp-btn { padding: 9px 18px; border: none; border-radius: 5px; cursor: pointer; font-size: 13px; font-weight: bold; transition: opacity .2s; }
        .cp-btn:hover { opacity: .85; }
        .cp-btn-dark   { background: var(--cp-green-dark);   color: #fff; }
        .cp-btn-green  { background: var(--cp-green-border); color: #fff; }
        .cp-btn-remove { background: #d9534f; color: #fff; }
        .cp-btn-cancel { background: #ccc; color: #333; }

        /* Deliver button */
        .cp-deliver-btn {
            background: var(--cp-green-dark); color: #fff;
            padding: 9px 18px; border: none; border-radius: 5px;
            cursor: pointer; font-size: 13px; font-weight: bold; transition: opacity .2s;
        }
        .cp-deliver-btn:hover    { opacity: .85; }
        .cp-deliver-btn:disabled { opacity: .55; cursor: default; }

        /* Payment cards */
        .cp-pay-methods { display: flex; flex-direction: column; gap: 10px; max-width: 500px; }
        .cp-pay-card {
            display: flex; align-items: center; justify-content: space-between;
            padding: 14px 16px; border: 1px solid #eee; border-radius: 8px;
            cursor: pointer; background: #fff; transition: .2s;
        }
        .cp-pay-card.selected-card { border-color: var(--cp-green-border); background: var(--cp-green-light); }
        .cp-pay-card:hover { box-shadow: 0 2px 6px rgba(0,0,0,.06); }
        .cp-pay-card input[type="radio"] { display: none; }
        .cp-pay-card-left { display: flex; align-items: center; gap: 14px; }
        .cp-pay-logo { width: 40px; height: 40px; object-fit: contain; }
        .cp-pay-type { font-size: 11px; color: var(--cp-text-light); }
        .cp-pay-name { font-size: 15px; font-weight: bold; color: var(--cp-text-dark); }
        .cp-checkmark {
            width: 20px; height: 20px; background: #eee;
            border: 1px solid #ccc; border-radius: 50%;
            position: relative; flex-shrink: 0;
        }
        .cp-pay-card input:checked ~ .cp-checkmark { background: #fff; border-color: var(--cp-green-border); }
        .cp-pay-card input:checked ~ .cp-checkmark::after {
            content: ''; position: absolute; top: 50%; left: 50%;
            width: 10px; height: 10px; border-radius: 50%;
            background: var(--cp-green-border); transform: translate(-50%,-50%);
        }

        /* Bank info + QR */
        .cp-bank-info-row {
            display: flex; gap: 20px; align-items: flex-start;
            border: 1px solid #eee; padding: 20px; border-radius: 8px;
            background: #fff; margin-bottom: 20px;
        }
        .cp-bank-details { flex: 1; }
        .cp-bank-logo-row { display: flex; align-items: center; gap: 12px; margin-bottom: 14px; }
        .cp-bank-logo { width: 42px; height: 42px; object-fit: contain; }
        .cp-bank-type { font-size: 11px; color: var(--cp-text-light); }
        .cp-bank-name { font-size: 16px; font-weight: bold; color: var(--cp-green-dark); }
        .cp-detail-row { display: grid; grid-template-columns: 70px 1fr; font-size: 14px; line-height: 1.9; }
        .cp-detail-label { color: var(--cp-green-dark); }
        .cp-detail-value { font-weight: bold; }
        .cp-qr-section { text-align: center; flex-shrink: 0; }
        .cp-qr-img { width: 140px; height: 140px; border: 1px solid #ddd; padding: 4px; }
        .cp-scan-text { font-size: 12px; color: var(--cp-green-dark); font-weight: bold; margin-top: 5px; }

        /* Slip upload */
        .cp-slip-area {
            border: 2px dashed #ccc; border-radius: 8px;
            padding: 25px; text-align: center; background: #fafafa; margin-bottom: 18px;
        }
        .cp-slip-preview {
            display: none; max-width: 240px; max-height: 320px;
            margin: 14px auto 0; border: 1px solid #ddd; cursor: zoom-in;
        }

        /* Policy box */
        .cp-policy-box {
            background: var(--cp-green-light); border-left: 4px solid var(--cp-green-border);
            padding: 16px 18px; border-radius: 6px; font-size: 13px;
            color: var(--cp-text-dark); line-height: 1.6; margin-bottom: 20px;
        }
        .cp-policy-box h4 { margin: 0 0 8px; color: var(--cp-green-dark); }
        .cp-policy-box ul { margin: 0 0 10px; padding-left: 18px; }
        .cp-policy-box li { margin-bottom: 5px; }

        /* Order summary */
        .cp-order-summary {
            border: 2px solid var(--cp-green-dark); border-radius: 8px;
            padding: 22px; margin-top: 22px;
        }
        .cp-summary-header {
            font-size: 13px; font-weight: bold; color: var(--cp-green-dark);
            border-bottom: 2px solid var(--cp-green-border); padding-bottom: 6px;
            margin-bottom: 16px; display: inline-block;
        }
        .cp-summary-item-row {
            display: grid; grid-template-columns: 3fr 60px 1fr;
            padding: 9px 0; border-bottom: 1px solid #f0f0f0; font-size: 14px;
        }
        .cp-summary-item-row span:last-child { text-align: right; }
        .cp-summary-totals { margin-top: 14px; }
        .cp-total-row { display: flex; justify-content: space-between; padding: 5px 0; font-size: 14px; }
        .cp-grand-total {
            border-top: 2px solid var(--cp-green-dark);
            margin-top: 10px; padding-top: 12px;
            font-weight: bold; font-size: 18px; color: var(--cp-green-dark);
            display: flex; justify-content: space-between;
        }
        .cp-summary-meta {
            margin-top: 14px; font-size: 12px; color: #555;
            background: #f9f9f9; padding: 10px 14px; border-radius: 4px; line-height: 1.8;
        }

        /* Submit */
        .cp-submit-btn {
            width: 100%; padding: 17px; background: var(--cp-green-dark);
            color: #fff; border: none; font-size: 18px; font-weight: bold;
            text-transform: uppercase; letter-spacing: 1px; border-radius: 5px;
            cursor: pointer; margin-top: 24px; transition: opacity .2s;
        }
        .cp-submit-btn:hover { opacity: .88; }
        .cp-security-text { font-size: 12px; color: var(--cp-text-light); text-align: center; margin-top: 20px; }

        /* Lightbox */
        .cp-lightbox {
            display: none; position: fixed; z-index: 300;
            inset: 0; background: rgba(0,0,0,.88);
            justify-content: center; align-items: center;
        }
        .cp-lightbox.open { display: flex; }
        .cp-lightbox img  { max-width: 85%; max-height: 85%; object-fit: contain; border: 3px solid #fff; }
        .cp-lightbox-close {
            position: fixed; top: 16px; right: 28px;
            font-size: 38px; color: #fff; cursor: pointer; z-index: 301;
        }

        /* Responsive */
        @media (max-width: 600px) {
            .checkout-page { padding: 8px; }
            .cp-form-row { grid-template-columns: 1fr; }
            .cp-bank-info-row { flex-direction: column; }
            .cp-qr-section { width: 100%; text-align: center; }
            .cp-addr-detail-grid { grid-template-columns: 90px 1fr; }
        }
    </style>
</head>
<body>

<div class="checkout-page">
<div class="main-container">

    <?php if (!empty($orderError)): ?>
        <div style="background:#f8d7da;color:#721c24;padding:12px 16px;border-radius:5px;margin-bottom:18px;">
            <strong>Error:</strong> <?php echo htmlspecialchars($orderError); ?>
        </div>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data" id="checkoutForm">
        <input type="hidden" name="place_order"         value="1">
        <input type="hidden" name="selected_address_id" id="selected_address_id" value="">

        <h1 class="cp-title">ORDER SUBMIT</h1>

        <!-- STEP 1: PAYMENT METHOD -->
        <div class="cp-section-header">PAYMENT METHOD</div>
        <div class="cp-pay-toggles">
            <label class="cp-pay-toggle-label selected" id="lbl-bank">
                <input type="radio" name="high_level_payment" value="bank_transfer" checked
                       onchange="onHighPayChange(this.value)">
                Bank Transfer
            </label>
            <label class="cp-pay-toggle-label" id="lbl-cod">
                <input type="radio" name="high_level_payment" value="cod"
                       onchange="onHighPayChange(this.value)">
                Cash on Delivery (COD)
            </label>
        </div>

        <!-- STEP 2: DELIVERY ADDRESS -->
        <div class="cp-section-header">DELIVERY ADDRESS</div>

        <div class="cp-addr-header">
            <h2 class="cp-addr-title">ADDRESS LIST</h2>
            <button type="button" class="cp-btn cp-btn-dark" onclick="toggleNewAddressForm()">
                <i class="fas fa-plus"></i> Add New
            </button>
        </div>

        <!-- Add New form -->
        <div id="new-addr-form" class="cp-addr-form"
             style="display:none; border:2px solid var(--cp-green-border); border-radius:8px; margin-bottom:14px;">
            <h4 class="cp-form-title"><i class="fas fa-map-marker-alt"></i> Add New Address</h4>
            <div class="cp-form-row">
                <div class="cp-form-group">
                    <label>Street</label>
                    <input type="text" id="new_street" placeholder="Street / Road">
                </div>
                <div class="cp-form-group">
                    <label>Township</label>
                    <input type="text" id="new_township" placeholder="Township / District">
                </div>
            </div>
            <div class="cp-form-row">
                <div class="cp-form-group">
                    <label>City</label>
                    <input type="text" id="new_city" placeholder="City">
                </div>
                <div class="cp-form-group">
                    <label>State / Region</label>
                    <input type="text" id="new_state" placeholder="State or Region">
                </div>
            </div>
            <div class="cp-form-row">
                <div class="cp-form-group">
                    <label>Country</label>
                    <select id="new_country">
                        <option value="Myanmar">Myanmar</option>
                        <option value="Thailand">Thailand</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="cp-form-group">
                    <label>Postal Code</label>
                    <input type="text" id="new_postal" placeholder="Postal / ZIP Code">
                </div>
            </div>
            <div class="cp-form-group" style="margin-bottom:12px;">
                <label>Google Map Link (optional)</label>
                <input type="text" id="new_mapLink" placeholder="https://maps.app.goo.gl/...">
            </div>
            <div class="cp-form-actions">
                <button type="button" class="cp-btn cp-btn-cancel" onclick="toggleNewAddressForm()">Cancel</button>
                <button type="button" class="cp-btn cp-btn-green" onclick="saveNewAddress()">
                    <i class="fas fa-save"></i> Save Address
                </button>
            </div>
        </div>

        <!-- Address list rendered by JS -->
        <div id="address-list-container"></div>

        <!-- STEP 3: SELECT PAYMENT PROVIDER -->
        <div id="bankTransferSection">
            <div class="cp-section-header">SELECT PAYMENT METHOD</div>
            <div class="cp-pay-methods">

                <label class="cp-pay-card selected-card" data-logo="kpay.png" data-name="KPlus" data-type="Mobile Wallet">
                    <div class="cp-pay-card-left">
                        <img src="../image/kpay.png" class="cp-pay-logo" alt="KPlus">
                        <div><div class="cp-pay-type">Mobile Wallet</div><div class="cp-pay-name">KPlus</div></div>
                    </div>
                    <input type="radio" name="detailed_payment" value="kbz_pay"
                           data-phone="09-123 456 789" data-payname="Mr Aung Aung"
                           data-qr="../image/qr.png" checked onchange="onDetailedPayChange(this)">
                    <span class="cp-checkmark"></span>
                </label>

                <label class="cp-pay-card" data-logo="kbz.png" data-name="SCB Bank" data-type="Bank Transfer">
                    <div class="cp-pay-card-left">
                        <img src="../image/kbz.png" class="cp-pay-logo" alt="SCB Bank">
                        <div><div class="cp-pay-type">Bank Transfer</div><div class="cp-pay-name">SCB Bank</div></div>
                    </div>
                    <input type="radio" name="detailed_payment" value="kbz_bank"
                           data-phone="001-234-567-890" data-payname="Daw Mya Mya"
                           data-qr="../image/qr_kbz_bank.png" onchange="onDetailedPayChange(this)">
                    <span class="cp-checkmark"></span>
                </label>

                <label class="cp-pay-card" data-logo="uab.png" data-name="Bangkok Bank" data-type="Mobile Wallet">
                    <div class="cp-pay-card-left">
                        <img src="../image/uab.png" class="cp-pay-logo" alt="Bangkok Bank">
                        <div><div class="cp-pay-type">Mobile Wallet</div><div class="cp-pay-name">Bangkok Bank</div></div>
                    </div>
                    <input type="radio" name="detailed_payment" value="uab_pay"
                           data-phone="09-444 555 666" data-payname="U Hla Tun"
                           data-qr="../image/qr_uab.png" onchange="onDetailedPayChange(this)">
                    <span class="cp-checkmark"></span>
                </label>

                <label class="cp-pay-card" data-logo="aya.png" data-name="True Money" data-type="Mobile Wallet">
                    <div class="cp-pay-card-left">
                        <img src="../image/aya.png" class="cp-pay-logo" alt="True Money">
                        <div><div class="cp-pay-type">Mobile Wallet</div><div class="cp-pay-name">True Money</div></div>
                    </div>
                    <input type="radio" name="detailed_payment" value="aya_pay"
                           data-phone="09-777 888 999" data-payname="Ma Thidar"
                           data-qr="../image/qr_aya.png" onchange="onDetailedPayChange(this)">
                    <span class="cp-checkmark"></span>
                </label>

            </div>

            <p class="cp-security-text"><i class="fas fa-lock"></i> All payments are secured and encrypted</p>

            <!-- STEP 4: TRANSFER DETAILS & SLIP -->
            <div class="cp-section-header">TRANSFER &amp; UPLOAD SLIP</div>

            <div class="cp-bank-info-row">
                <div class="cp-bank-details">
                    <div class="cp-bank-logo-row">
                        <img src="../image/kpay.png" id="bank-logo-img" class="cp-bank-logo" alt="Payment Logo">
                        <div>
                            <div class="cp-bank-type" id="bank-type-text">Mobile Wallet</div>
                            <div class="cp-bank-name" id="bank-name-text">KPlus</div>
                        </div>
                    </div>
                    <div class="cp-detail-row">
                        <span class="cp-detail-label">Name</span>
                        <span class="cp-detail-value" id="bank-detail-name">Mr Aung Aung</span>
                    </div>
                    <div class="cp-detail-row">
                        <span class="cp-detail-label">Ph No</span>
                        <span class="cp-detail-value" id="bank-detail-phone">09-123 456 789</span>
                    </div>
                    <div class="cp-detail-row">
                        <span class="cp-detail-label">Amount</span>
                        <span class="cp-detail-value" id="bank-detail-amount">
                            <?php echo number_format($grandTotal); ?> BHAT
                        </span>
                    </div>
                </div>
                <div class="cp-qr-section">
                    <img src="../image/qr.png" id="bank-qr-img" class="cp-qr-img" alt="QR Code">
                    <div class="cp-scan-text">Scan Here</div>
                </div>
            </div>

            <div class="cp-slip-area">
                <p style="margin:0 0 10px; font-weight:bold; color:var(--cp-green-dark);">
                    <i class="fas fa-upload"></i> Upload Payment Slip
                </p>
                <input type="file" name="payment_slip" id="slip-file-input" accept="image/*" style="display:none;">
                <button type="button" class="cp-btn cp-btn-dark"
                        onclick="document.getElementById('slip-file-input').click()">
                    Select Screenshot
                </button>
                <p id="slip-status" style="margin:8px 0 0; font-size:13px; color:#888;">No file selected.</p>
                <img id="slip-preview" class="cp-slip-preview" alt="Slip Preview">
            </div>

            <div class="cp-policy-box">
                <h4><i class="fas fa-info-circle"></i> PAYMENT &amp; RETURN POLICY</h4>
                <ul>
                    <li>Full payment must be completed within <strong>2 days</strong> of placing the order.</li>
                    <li>Orders not paid within 2 days will be automatically cancelled.</li>
                    <li>Your order will be confirmed only after payment is received and verified.</li>
                </ul>
                <strong>Return Policy:</strong> Contact us via Facebook Page Messenger or email with your
                <em>Order ID</em> to initiate a return.
            </div>
        </div><!-- /bankTransferSection -->

        <!-- ORDER SUMMARY -->
        <div class="cp-order-summary">
            <div class="cp-summary-header">ORDER SUMMARY</div>

            <div class="cp-summary-item-row"
                 style="font-weight:bold; color:var(--cp-text-light); border-bottom:2px solid #ddd;">
                <span>Product</span><span>Qty</span><span>Subtotal</span>
            </div>

            <?php foreach ($_SESSION['cart'] as $item): ?>
            <div class="cp-summary-item-row">
                <span><?php echo htmlspecialchars($item['name']); ?></span>
                <span>×<?php echo intval($item['qty']); ?></span>
                <span><?php echo number_format($item['price'] * $item['qty']); ?> BHAT</span>
            </div>
            <?php endforeach; ?>

            <div class="cp-summary-totals">
                <div class="cp-total-row"><span>Items Subtotal</span><span><?php echo number_format($subtotal); ?> BHAT</span></div>
                <div class="cp-total-row"><span>Delivery Fee</span><span>35 BHAT</span></div>
                <div class="cp-grand-total">
                    <span>Total Payment</span>
                    <span><?php echo number_format($grandTotal); ?> BHAT</span>
                </div>
            </div>

            <div class="cp-summary-meta">
                <i class="fas fa-truck"></i>&nbsp; Shipping to:
                <strong id="summary-addr-view">Not selected yet</strong><br>
                <i class="fas fa-wallet"></i>&nbsp; Payment:
                <strong id="summary-pay-view">Bank Transfer</strong>
            </div>
        </div>

        <button type="submit" class="cp-submit-btn">PLACE ORDER NOW</button>
    </form>
</div>
</div>

<!-- Lightbox -->
<div class="cp-lightbox" id="slipLightbox" onclick="closeLightbox()">
    <span class="cp-lightbox-close">&times;</span>
    <img id="lightbox-img" src="#" alt="Payment Slip">
</div>

<script>
/* ─── Data from PHP ─── */
let addressData    = <?php echo json_encode(array_values($dbAddresses)); ?>;
let selectedAddrId = null;   // JS tracBHAT the selected address as a plain number

/* ─── Escape helper ─── */
function esc(str) {
    if (!str) return '';
    return String(str)
        .replace(/&/g,'&amp;').replace(/</g,'&lt;')
        .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

/* ═══════════════════════════════
   RENDER ADDRESS LIST
═══════════════════════════════ */
function renderAddresses() {
    const container = document.getElementById('address-list-container');
    container.innerHTML = '';

    if (addressData.length === 0) {
        container.innerHTML =
            '<p style="color:#888;font-size:14px;">No saved addresses. Click "+ Add New" to add one.</p>';
        return;
    }

    addressData.forEach((addr, i) => {
        const isSelected = (addr.id === selectedAddrId);

        const div = document.createElement('div');
        div.className = 'cp-addr-item' + (isSelected ? ' is-selected' : '');
        div.id = `addr-item-${addr.id}`;

        div.innerHTML = `
            <div class="cp-addr-accordion-title" onclick="toggleAccordion(${addr.id})">
                <span>
                    <i class="fas fa-chevron-${isSelected ? 'down' : 'right'}" id="chev-${addr.id}"></i>
                    &nbsp;ADDRESS ${i + 1}
                </span>
                ${isSelected
                    ? '<span class="cp-selected-badge"><i class="fas fa-check-circle"></i>&nbsp;SELECTED</span>'
                    : '<span></span>'}
            </div>

            <div id="addr-content-${addr.id}" class="cp-addr-content"
                 style="display:${isSelected ? 'block' : 'none'};">

                <div class="cp-addr-detail-grid">
                    <span class="cp-addr-label">Street</span>
                    <span class="cp-addr-value">${esc(addr.street)}</span>

                    <span class="cp-addr-label">Township</span>
                    <span class="cp-addr-value">${esc(addr.township)}</span>

                    <span class="cp-addr-label">City</span>
                    <span class="cp-addr-value">${esc(addr.city)}</span>

                    <div class="cp-addr-full-row">
                        <span class="cp-addr-label">Full Address</span><br>
                        <span class="cp-addr-value">${esc(addr.fullAddress)}</span>
                    </div>
                </div>

                <div class="cp-addr-actions">
                    <button type="button" class="cp-deliver-btn"
                            onclick="selectAddress(${addr.id})"
                            ${isSelected ? 'disabled' : ''}>
                        <i class="fas fa-map-marker-alt"></i>
                        ${isSelected ? 'Delivering Here ✓' : 'Deliver to this Address'}
                    </button>
                    <div style="display:flex;gap:8px;">
                        <button type="button" class="cp-btn cp-btn-green"
                                onclick="toggleEditForm(${addr.id})">Edit</button>
                        <button type="button" class="cp-btn cp-btn-remove"
                                onclick="removeAddress(${addr.id})">Remove</button>
                    </div>
                </div>
            </div>

            <!-- Inline edit form -->
            <div id="edit-form-${addr.id}" class="cp-addr-form">
                <h4 class="cp-form-title">Edit Address</h4>
                <div class="cp-form-row">
                    <div class="cp-form-group">
                        <label>Street</label>
                        <input type="text" id="edit_street_${addr.id}" value="${esc(addr.street)}">
                    </div>
                    <div class="cp-form-group">
                        <label>Township</label>
                        <input type="text" id="edit_township_${addr.id}" value="${esc(addr.township)}">
                    </div>
                </div>
                <div class="cp-form-row">
                    <div class="cp-form-group">
                        <label>City</label>
                        <input type="text" id="edit_city_${addr.id}" value="${esc(addr.city)}">
                    </div>
                    <div class="cp-form-group">
                        <label>State / Region</label>
                        <input type="text" id="edit_state_${addr.id}" value="${esc(addr.state)}">
                    </div>
                </div>
                <div class="cp-form-row">
                    <div class="cp-form-group">
                        <label>Country</label>
                        <select id="edit_country_${addr.id}">
                            <option value="Myanmar"  ${addr.country==='Myanmar'  ? 'selected':''}>Myanmar</option>
                            <option value="Thailand" ${addr.country==='Thailand' ? 'selected':''}>Thailand</option>
                            <option value="Other"    ${(addr.country!=='Myanmar'&&addr.country!=='Thailand') ? 'selected':''}>Other</option>
                        </select>
                    </div>
                    <div class="cp-form-group">
                        <label>Postal Code</label>
                        <input type="text" id="edit_postal_${addr.id}" value="${esc(addr.postal)}">
                    </div>
                </div>
                <div class="cp-form-group" style="margin-bottom:12px;">
                    <label>Google Map Link (optional)</label>
                    <input type="text" id="edit_mapLink_${addr.id}" value="${esc(addr.mapLink)}">
                </div>
                <div class="cp-form-actions">
                    <button type="button" class="cp-btn cp-btn-cancel"
                            onclick="toggleEditForm(${addr.id})">Cancel</button>
                    <button type="button" class="cp-btn cp-btn-green"
                            onclick="saveEditAddress(${addr.id})">
                        <i class="fas fa-save"></i> Update
                    </button>
                </div>
            </div>
        `;
        container.appendChild(div);
    });
}

/* ═══════════════════════════════
   ACCORDION
═══════════════════════════════ */
function toggleAccordion(id) {
    const content = document.getElementById(`addr-content-${id}`);
    const chevron = document.getElementById(`chev-${id}`);
    const isOpen  = content.style.display === 'block';

    document.querySelectorAll('.cp-addr-content').forEach(c => c.style.display = 'none');
    document.querySelectorAll('[id^="chev-"]').forEach(ch => ch.className = 'fas fa-chevron-right');
    document.querySelectorAll('.cp-addr-form').forEach(f  => f.style.display = 'none');

    if (!isOpen) {
        content.style.display = 'block';
        chevron.className = 'fas fa-chevron-down';
    }
}

/* ═══════════════════════════════
   SELECT DELIVERY ADDRESS
═══════════════════════════════ */
function selectAddress(id) {
    selectedAddrId = id;                                          // update JS tracker
    document.getElementById('selected_address_id').value = id;   // update hidden input

    const addr = addressData.find(a => a.id === id);
    if (addr) {
        document.getElementById('summary-addr-view').textContent = addr.fullAddress;
    }
    renderAddresses();   // re-render → green border + SELECTED badge appear instantly
}

/* ═══════════════════════════════
   NEW ADDRESS FORM
═══════════════════════════════ */
function toggleNewAddressForm() {
    const form   = document.getElementById('new-addr-form');
    const isOpen = form.style.display === 'block';
    document.querySelectorAll('.cp-addr-form').forEach(f => f.style.display = 'none');
    form.style.display = isOpen ? 'none' : 'block';
    if (!isOpen) {
        ['new_street','new_township','new_city','new_state','new_postal','new_mapLink']
            .forEach(id => document.getElementById(id).value = '');
        document.getElementById('new_country').value = 'Myanmar';
    }
}

function saveNewAddress() {
    const street   = document.getElementById('new_street').value.trim();
    const township = document.getElementById('new_township').value.trim();
    const city     = document.getElementById('new_city').value.trim();
    const state    = document.getElementById('new_state').value.trim();
    const country  = document.getElementById('new_country').value;
    const postal   = document.getElementById('new_postal').value.trim();
    const mapLink  = document.getElementById('new_mapLink').value.trim();

    if (!street || !city) { alert('Please fill in at least Street and City.'); return; }

    const fd = new FormData();
    fd.append('ajax_action','add'); fd.append('street',street);
    fd.append('township',township); fd.append('city',city);
    fd.append('state',state);       fd.append('country',country);
    fd.append('postal',postal);     fd.append('mapLink',mapLink);

    fetch('', { method:'POST', body:fd })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                addressData.unshift(data.address);
                document.getElementById('new-addr-form').style.display = 'none';
                selectAddress(data.address.id);   // auto-select + re-render
            } else {
                alert('Failed to save: ' + (data.error || 'Unknown error'));
            }
        })
        .catch(err => alert('Error: ' + err));
}

/* ═══════════════════════════════
   EDIT FORM
═══════════════════════════════ */
function toggleEditForm(id) {
    const f      = document.getElementById(`edit-form-${id}`);
    const isOpen = f.style.display === 'block';
    document.querySelectorAll('.cp-addr-form').forEach(x => x.style.display = 'none');
    f.style.display = isOpen ? 'none' : 'block';
}

function saveEditAddress(id) {
    const street   = document.getElementById(`edit_street_${id}`).value.trim();
    const township = document.getElementById(`edit_township_${id}`).value.trim();
    const city     = document.getElementById(`edit_city_${id}`).value.trim();
    const state    = document.getElementById(`edit_state_${id}`).value.trim();
    const country  = document.getElementById(`edit_country_${id}`).value;
    const postal   = document.getElementById(`edit_postal_${id}`).value.trim();
    const mapLink  = document.getElementById(`edit_mapLink_${id}`).value.trim();

    if (!street || !city) { alert('Please fill in at least Street and City.'); return; }

    const fd = new FormData();
    fd.append('ajax_action','edit'); fd.append('addressID',id);
    fd.append('street',street);      fd.append('township',township);
    fd.append('city',city);          fd.append('state',state);
    fd.append('country',country);    fd.append('postal',postal);
    fd.append('mapLink',mapLink);

    fetch('', { method:'POST', body:fd })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const idx = addressData.findIndex(a => a.id === id);
                if (idx !== -1) addressData[idx] = data.address;   // update in-memory data
                // If this was the selected address, refresh the summary text
                if (selectedAddrId === id) {
                    document.getElementById('summary-addr-view').textContent = data.address.fullAddress;
                }
                renderAddresses();   // re-render → changes show immediately, no reload needed
            } else {
                alert('Failed to update: ' + (data.error || 'Unknown error'));
            }
        })
        .catch(err => alert('Error: ' + err));
}

/* ═══════════════════════════════
   REMOVE ADDRESS
═══════════════════════════════ */
function removeAddress(id) {
    if (!confirm('Remove this address?')) return;

    const fd = new FormData();
    fd.append('ajax_action','remove');
    fd.append('addressID',id);

    fetch('', { method:'POST', body:fd })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                addressData = addressData.filter(a => a.id !== id);
                if (selectedAddrId === id) {
                    selectedAddrId = null;
                    document.getElementById('selected_address_id').value = '';
                    document.getElementById('summary-addr-view').textContent = 'Not selected yet';
                }
                renderAddresses();
            } else {
                alert('Could not remove address.');
            }
        })
        .catch(err => alert('Error: ' + err));
}

/* ═══════════════════════════════
   PAYMENT TOGGLES
═══════════════════════════════ */
function onHighPayChange(val) {
    const isBT = (val === 'bank_transfer');
    document.getElementById('bankTransferSection').style.display = isBT ? 'block' : 'none';
    document.getElementById('lbl-bank').classList.toggle('selected',  isBT);
    document.getElementById('lbl-cod').classList.toggle('selected',  !isBT);
    document.getElementById('summary-pay-view').textContent = isBT ? 'Bank Transfer' : 'Cash on Delivery';
}

function onDetailedPayChange(radio) {
    document.querySelectorAll('.cp-pay-card').forEach(c => c.classList.remove('selected-card'));
    const card = radio.closest('.cp-pay-card');
    card.classList.add('selected-card');
    document.getElementById('bank-logo-img').src             = '../image/' + card.getAttribute('data-logo');
    document.getElementById('bank-name-text').textContent    = card.getAttribute('data-name');
    document.getElementById('bank-type-text').textContent    = card.getAttribute('data-type');
    document.getElementById('bank-detail-name').textContent  = radio.getAttribute('data-payname');
    document.getElementById('bank-detail-phone').textContent = radio.getAttribute('data-phone');
    document.getElementById('bank-qr-img').src               = radio.getAttribute('data-qr');
    document.getElementById('summary-pay-view').textContent  = card.getAttribute('data-name');
}

/* ═══════════════════════════════
   SLIP PREVIEW & LIGHTBOX
═══════════════════════════════ */
document.getElementById('slip-file-input').addEventListener('change', function () {
    if (!this.files[0]) return;
    const reader = new FileReader();
    reader.onload = e => {
        const preview = document.getElementById('slip-preview');
        preview.src   = e.target.result;
        preview.style.display = 'block';
        document.getElementById('slip-status').textContent =
            'File: ' + this.files[0].name + ' — click to enlarge';
    };
    reader.readAsDataURL(this.files[0]);
});

document.getElementById('slip-preview').addEventListener('click', function () {
    document.getElementById('lightbox-img').src = this.src;
    document.getElementById('slipLightbox').classList.add('open');
});

function closeLightbox() {
    document.getElementById('slipLightbox').classList.remove('open');
}

/* ═══════════════════════════════
   SUBMIT GUARD
═══════════════════════════════ */
document.getElementById('checkoutForm').addEventListener('submit', function (e) {
    if (!selectedAddrId) {
        e.preventDefault();
        alert('Please select a delivery address before placing the order.');
    }
});

/* ═══════════════════════════════
   INIT (script is at bottom — DOM is already ready, no DOMContentLoaded needed)
═══════════════════════════════ */
(function init() {
    renderAddresses();
    if (addressData.length > 0) {
        selectAddress(addressData[0].id);   // auto-select & show first address
    }
    onHighPayChange('bank_transfer');
})();
</script>

<?php include '../layout/footer.php'; ?>
</body>
</html>