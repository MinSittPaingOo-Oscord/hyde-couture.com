<?php
session_start();
include '../connection/connectdb.php';
include '../layout/nav.php';

// Handle AJAX remove from favourites
if (isset($_POST['action']) && $_POST['action'] === 'remove') {
    $pid   = intval($_POST['pid']);
    $accID = intval($_SESSION['accountID'] ?? 0);
    if ($accID && $pid) {
        $stmt = $conn->prepare("DELETE FROM favourite WHERE productID = ? AND accountID = ?");
        $stmt->bind_param("ii", $pid, $accID);
        $stmt->execute();
    }
    exit;
}

// Fetch favourites for the logged-in user
$accID    = intval($_SESSION['accountID'] ?? 0);
$favItems = [];

if ($accID) {
    $res = $conn->query("
        SELECT p.productID, p.productName, p.price,
               (SELECT photoName FROM photo WHERE productID = p.productID LIMIT 1) AS img
        FROM favourite f
        JOIN product p ON f.productID = p.productID
        WHERE f.accountID = $accID
    ");
    while ($row = $res->fetch_assoc()) {
        $favItems[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Favourites | HYDE COUTURE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Vollkorn:wght@400;600&family=Cinzel:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Vollkorn', serif; background-color: #fff; color: #111; }
        .fav-container { max-width: 900px; margin: 2rem auto; padding: 0 1rem; min-height: 400px; }

        /* Same card style as cart.php */
        .fav-item {
            display: flex; align-items: center; gap: 1.5rem;
            padding: 1.5rem 0; border-bottom: 1px solid #eee;
            transition: opacity .3s;
        }
        .fav-item img { width: 100px; height: 130px; object-fit: cover; border-radius: 4px; }
        .item-details { flex: 1; }
        .item-title {
            font-family: 'Cinzel', serif; font-weight: 700;
            margin-bottom: 5px; text-transform: uppercase;
        }
        .item-price {
            text-align: right; font-family: 'Cinzel', serif;
            font-weight: 700; min-width: 120px;
        }

        /* Action row */
        .action-row { display: flex; align-items: center; gap: 1rem; margin-top: 10px; }

        /* "Select Options" button — same green as checkout-btn */
        .select-btn {
            background: #005A2B; color: #fff; border: none;
            border-radius: 4px; padding: 7px 20px;
            font-family: 'Cinzel'; font-weight: 700;
            font-size: 0.8rem; cursor: pointer; transition: 0.2s;
            text-decoration: none; display: inline-block;
        }
        .select-btn:hover { background: #004420; color: #fff; }

        /* Remove button — identical trash icon to cart.php */
        .remove-btn {
            color: #999; cursor: pointer; border: none;
            background: none; transition: 0.2s; padding: 0;
        }
        .remove-btn:hover { color: #dc3545; }

        /* Bottom CTA — same as checkout-btn in cart.php */
        .shop-btn {
            background: #005A2B; color: #fff; width: 100%;
            padding: 15px; border: none; border-radius: 5px;
            font-family: 'Cinzel'; font-weight: 700; transition: 0.3s;
            cursor: pointer;
        }
        .shop-btn:hover { background: #004420; }

        /* Empty state */
        .empty-msg { text-align: center; padding: 100px 0; }
    </style>
</head>
<body>

<div class="fav-container">
    <h2 class="text-center mb-5" style="font-family: 'Cinzel'; letter-spacing: 2px;">YOUR FAVOURITES</h2>

    <?php if (empty($favItems)): ?>
        <div class="empty-msg">
            <p class="fs-5">You haven't saved any favourites yet.</p>
            <a href="index.php" class="btn btn-outline-dark px-4 py-2 mt-3">BROWSE PRODUCTS</a>
        </div>

    <?php else: ?>
        <div id="fav-list">
            <?php foreach ($favItems as $item): ?>
            <div class="fav-item" id="fav-<?php echo $item['productID']; ?>">

                <!-- Product image -->
                <a href="specific_product.php?id=<?php echo $item['productID']; ?>">
                    <img src="../image/<?php echo htmlspecialchars($item['img'] ?: 'placeholder.jpg'); ?>"
                         alt="<?php echo htmlspecialchars($item['productName']); ?>">
                </a>

                <!-- Details -->
                <div class="item-details">
                    <a href="specific_product.php?id=<?php echo $item['productID']; ?>"
                       class="text-decoration-none text-dark">
                        <p class="item-title"><?php echo htmlspecialchars($item['productName']); ?></p>
                    </a>

                    <div class="action-row">
                        <!-- Takes user to product page to pick colour/size before adding to cart -->
                        <a href="specific_product.php?id=<?php echo $item['productID']; ?>"
                           class="select-btn">View</a>

                        <!-- Remove from favourites (AJAX, no page reload) -->
                        <button class="remove-btn"
                                onclick="removeFav(<?php echo $item['productID']; ?>)"
                                title="Remove from favourites">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                </path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Price -->
                <div class="item-price">
                    BHAT <?php echo number_format($item['price']); ?>
                </div>

            </div>
            <?php endforeach; ?>
        </div>

        <!-- Bottom CTA — mirrors the summary box in cart.php -->
        <div id="fav-cta" class="mt-5 p-4 bg-light rounded shadow-sm text-center">
            <p class="text-muted mb-3">
                Ready to order? Visit a product page to choose your size and colour.
            </p>
            <button class="shop-btn" onclick="location.href='index.php'">CONTINUE SHOPPING</button>
        </div>

    <?php endif; ?>
</div>

<script>
function removeFav(pid) {
    if (!confirm("Remove this item from favourites?")) return;

    const data = new FormData();
    data.append('action', 'remove');
    data.append('pid', pid);

    fetch('user_fav.php', { method: 'POST', body: data })
        .then(res => {
            if (!res.ok) return;

            const el = document.getElementById(`fav-${pid}`);
            if (!el) return;

            // Fade out then remove from DOM
            el.style.opacity = '0';
            setTimeout(() => {
                el.remove();

                // If no items remain, show empty state and hide CTA
                if (!document.querySelector('.fav-item')) {
                    document.getElementById('fav-list').innerHTML =
                        '<div class="empty-msg">' +
                            '<p class="fs-5">You have no more favourites.</p>' +
                            '<a href="index.php" class="btn btn-outline-dark px-4 py-2 mt-3">BROWSE PRODUCTS</a>' +
                        '</div>';
                    const cta = document.getElementById('fav-cta');
                    if (cta) cta.remove();
                }
            }, 300);
        });
}
</script>

<?php include '../layout/footer.php'; ?>
</body>
</html>