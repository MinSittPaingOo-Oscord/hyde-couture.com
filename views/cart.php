<?php
session_start();
include '../connection/connectdb.php';
include '../layout/nav.php';

// ── Helper: same discount function ──
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

// ── Helper: get ALL discount tiers for a product ──
function getDiscountTiers($conn, $pid) {
    $stmt = $conn->prepare("
        SELECT range1, range2, percentage 
        FROM discount 
        WHERE productID = ? 
        ORDER BY range1 ASC
    ");
    $stmt->bind_param("i", $pid);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

if (isset($_POST['action'])) {
    $key = $_POST['key'];

    if ($_POST['action'] === 'remove') {
        unset($_SESSION['cart'][$key]);

    } elseif ($_POST['action'] === 'update') {
        $newQty = intval($_POST['qty']);

        if (isset($_SESSION['cart'][$key]) && $newQty >= 1) {
            $item     = $_SESSION['cart'][$key];
            $maxStock = $item['maxStock'] ?? PHP_INT_MAX;
            if ($newQty > $maxStock) exit;

            $newPrice = getDiscountedPrice($conn, $item['pid'], $item['oldPrice'], $newQty);
            $_SESSION['cart'][$key]['qty']   = $newQty;
            $_SESSION['cart'][$key]['price'] = $newPrice;
        }
    }
    exit;
}

$cartItems = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$cartItems = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// ── Refresh prices from DB on every page load ──
foreach ($cartItems as $key => $item) {
    $freshPrice = getDiscountedPrice($conn, $item['pid'], $item['oldPrice'], $item['qty']);
    $_SESSION['cart'][$key]['price'] = $freshPrice;
    $cartItems[$key]['price']        = $freshPrice;
}
?>
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopping Cart | HYDE COUTURE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Vollkorn:wght@400;600&family=Cinzel:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Vollkorn', serif; background-color: #fff; color: #111; }
        .cart-container { max-width: 900px; margin: 2rem auto; padding: 0 1rem; min-height: 400px; }
        .cart-item { display: flex; align-items: flex-start; gap: 1.5rem; padding: 1.5rem 0; border-bottom: 1px solid #eee; }
        .cart-item img { width: 100px; height: 130px; object-fit: cover; border-radius: 4px; }
        .item-details { flex: 1; }
        .item-title { font-family: 'Cinzel', serif; font-weight: 700; margin-bottom: 5px; text-transform: uppercase; }
        .item-variation { font-size: 0.9rem; color: #666; margin: 0; }
        .quantity-row { display: flex; align-items: center; gap: 1rem; margin-top: 10px; }
        .qty-controls { display: flex; align-items: center; border: 1px solid #ddd; border-radius: 4px; background: #fff; }
        .qty-controls button { background: none; border: none; padding: 5px 15px; cursor: pointer; font-weight: bold; }
        .qty-controls button:hover { background-color: #f8f9fa; }
        .qty-controls span { padding: 0 10px; font-weight: 600; min-width: 30px; text-align: center; }
        .remove-btn { color: #999; cursor: pointer; border: none; background: none; transition: 0.2s; padding: 0; }
        .remove-btn:hover { color: #dc3545; }
        .item-price { text-align: right; font-family: 'Cinzel', serif; font-weight: 700; min-width: 120px; }
        .old-price { text-decoration: line-through; color: #aaa; font-size: 0.85rem; display: block; }
        .checkout-btn { background: #005A2B; color: #fff; width: 100%; padding: 15px; border: none; border-radius: 5px; font-family: 'Cinzel'; font-weight: 700; transition: 0.3s; }
        .checkout-btn:hover { background: #004420; }
        .empty-msg { text-align: center; padding: 100px 0; }

        /* ── Discount Tier Styles ── */
        .discount-info {
            margin-top: 10px;
            font-size: 0.82rem;
        }
        .discount-tiers {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
            margin-bottom: 6px;
        }
        .tier-badge {
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 600;
            border: 1px solid #ddd;
            color: #555;
            background: #f9f9f9;
        }
        .tier-badge.active {
            background: #005A2B;
            color: #fff;
            border-color: #005A2B;
        }
        .tier-badge.next {
            background: #fff8e1;
            color: #856404;
            border-color: #ffc107;
        }
        .discount-hint {
            color: #856404;
            font-weight: 600;
            background: #fff8e1;
            border: 1px dashed #ffc107;
            border-radius: 6px;
            padding: 5px 10px;
            display: inline-block;
            margin-top: 4px;
        }
        .discount-applied {
            color: #005A2B;
            font-weight: 600;
            background: #e8f5e9;
            border: 1px dashed #005A2B;
            border-radius: 6px;
            padding: 5px 10px;
            display: inline-block;
            margin-top: 4px;
        }
        .discount-savings {
            color: #005A2B;
            font-size: 0.85rem;
            font-weight: 600;
            display: block;
            margin-top: 2px;
        }
    </style>
</head>
<body>

<div class="cart-container">
    <h2 class="text-center mb-5" style="font-family: 'Cinzel'; letter-spacing: 2px;">YOUR CART</h2>

    <?php if (empty($cartItems)): ?>
        <div class="empty-msg">
            <p class="fs-5">Your cart is currently empty.</p>
            <a href="index.php" class="btn btn-outline-dark px-4 py-2 mt-3">RETURN TO SHOP</a>
        </div>
    <?php else: ?>
        <div id="cart-list">
            <?php 
            $subtotal      = 0;
            $totalSavings  = 0;
            foreach ($cartItems as $key => $item): 
                $itemTotal  = $item['price'] * $item['qty'];
                $origTotal  = $item['oldPrice'] * $item['qty'];
                $subtotal  += $itemTotal;
                $savings    = $origTotal - $itemTotal;
                $totalSavings += $savings;

                // Fetch discount tiers for this product
                $tiers = getDiscountTiers($conn, $item['pid']);

                // Find current active tier
                $activeTier = null;
                $nextTier   = null;
                foreach ($tiers as $tier) {
                    if ($item['qty'] >= $tier['range1'] && ($tier['range2'] === null || $item['qty'] <= $tier['range2'])) {
                        $activeTier = $tier;
                    }
                }
                // Find next tier (first tier with range1 > current qty)
                foreach ($tiers as $tier) {
                    if ($tier['range1'] > $item['qty']) {
                        $nextTier = $tier;
                        break;
                    }
                }
            ?>
            <div class="cart-item" data-key="<?php echo $key; ?>">
                <img src="../image/<?php echo $item['img']; ?>" alt="<?php echo $item['name']; ?>">
                
                <div class="item-details">
                    <p class="item-title"><?php echo $item['name']; ?></p>
                    <p class="item-variation">Color: <?php echo $item['color']; ?></p>
                    <p class="item-variation">Size: <?php echo $item['size']; ?></p>
                    
                    <div class="quantity-row">
                        <div class="qty-controls">
                            <button onclick="updateCart('<?php echo $key; ?>', -1)">-</button>
                            <span class="qty-val"><?php echo $item['qty']; ?></span>
                            <button onclick="updateCart('<?php echo $key; ?>', 1)">+</button>
                        </div>
                        <button class="remove-btn" onclick="removeItem('<?php echo $key; ?>')" title="Remove item">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- ── Discount Tier Info ── -->
                    <?php if (!empty($tiers)): ?>
                    <div class="discount-info">
                        <div class="discount-tiers">
                            <?php foreach ($tiers as $tier): 
                                $isActive = ($activeTier && $tier['range1'] === $activeTier['range1']);
                                $isNext   = ($nextTier   && $tier['range1'] === $nextTier['range1']);
                                $label    = 'Buy ' . $tier['range1'] . ($tier['range2'] ? '–' . $tier['range2'] : '+') . ' → ' . $tier['percentage'] . '% off';
                                $class    = $isActive ? 'active' : ($isNext ? 'next' : '');
                            ?>
                                <span class="tier-badge <?php echo $class; ?>"><?php echo $label; ?></span>
                            <?php endforeach; ?>
                        </div>

                        <?php if ($activeTier): ?>
                            <span class="discount-applied">
                                ✅ <?php echo $activeTier['percentage']; ?>% discount applied!
                            </span>
                            <?php if ($savings > 0): ?>
                                <span class="discount-savings">You save BHAT <?php echo number_format($savings); ?> on this item</span>
                            <?php endif; ?>
                        <?php elseif ($nextTier): ?>
                            <?php $needed = $nextTier['range1'] - $item['qty']; ?>
                            <span class="discount-hint">
                                🏷️ Add <?php echo $needed; ?> more to get <?php echo $nextTier['percentage']; ?>% off!
                            </span>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                </div>
                
                <div class="item-price">
                    <?php if ($savings > 0): ?>
                        <span class="old-price">BHAT <?php echo number_format($item['oldPrice']); ?></span>
                        <span style="color:#005A2B;">BHAT <?php echo number_format($item['price']); ?></span>
                    <?php else: ?>
                        <span>BHAT <?php echo number_format($item['price']); ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="mt-5 p-4 bg-light rounded shadow-sm">
            <?php if ($totalSavings > 0): ?>
            <div class="d-flex justify-content-between mb-2 text-success fw-bold">
                <span>🎉 Total Savings</span>
                <span>- BHAT <?php echo number_format($totalSavings); ?></span>
            </div>
            <?php endif; ?>
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Subtotal</span>
                <span class="fw-bold">BHAT <?php echo number_format($subtotal); ?></span>
            </div>
            <div class="d-flex justify-content-between mb-4">
                <span class="text-muted">Delivery (Flat Rate)</span>
                <span class="fw-bold">BHAT 35</span>
            </div>
            <div class="d-flex justify-content-between fs-4 fw-bold border-top pt-3">
                <span style="font-family: 'Cinzel';">Total</span>
                <span class="text-success">BHAT <?php echo number_format($subtotal + 35); ?></span>
            </div>
            <?php if (isset($_SESSION['login']) && $_SESSION['login'] === true): ?>
                <button class="checkout-btn mt-4" onclick="location.href='order_summary.php'">
                    PROCEED TO CHECKOUT
                </button>
            <?php else: ?>
                <button onclick="location.href='profile.php'" 
                        class="checkout-btn mt-4" 
                        style="background:#6c757d; width:100%; text-align:center;">
                    LOGIN TO PROCEED TO CHECKOUT
                </button>
                <p class="text-center text-muted mt-2 small">You must be logged in to place an order.</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<script>
function updateCart(key, change) {
    const row = document.querySelector(`.cart-item[data-key="${key}"]`);
    const qtySpan = row.querySelector('.qty-val');
    let currentQty = parseInt(qtySpan.textContent);
    let newQty = currentQty + change;

    if (newQty < 1) return;

    const data = new FormData();
    data.append('action', 'update');
    data.append('key', key);
    data.append('qty', newQty);

    fetch('cart.php', { method: 'POST', body: data }).then(response => {
        if (response.ok) location.reload();
    });
}

function removeItem(key) {
    if (!confirm("Are you sure you want to remove this item?")) return;

    const data = new FormData();
    data.append('action', 'remove');
    data.append('key', key);

    fetch('cart.php', { method: 'POST', body: data }).then(response => {
        if (response.ok) location.reload();
    });
}
</script>

<?php include '../layout/footer.php'; ?>
</body>
</html>