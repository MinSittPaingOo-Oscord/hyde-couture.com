<?php
session_start();
include '../connection/connectdb.php';
$pid = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_fav'])) {
    while (ob_get_level()) ob_end_clean();
    header('Content-Type: application/json');
    $accID = intval($_SESSION['accountID'] ?? 0);
    if (!$accID) {
        echo json_encode(['success' => false, 'message' => 'Login required']);
        exit;
    }
    $check = $conn->query("SELECT * FROM favourite WHERE productID=$pid AND accountID=$accID");
    if ($check->num_rows > 0) {
        $conn->query("DELETE FROM favourite WHERE productID=$pid AND accountID=$accID");
        echo json_encode(['success' => true, 'faved' => false]);
    } else {
        $conn->query("INSERT INTO favourite (productID, accountID) VALUES ($pid, $accID)");
        echo json_encode(['success' => true, 'faved' => true]);
    }
    exit;
}

include '../layout/nav.php';

$product_query = $conn->query("SELECT * FROM product WHERE productID = $pid");
$product = $product_query->fetch_assoc();
if (!$product) {
    echo "<div class='container mt-5 text-center'><h3>Product not found.</h3></div>";
    exit;
}

$photo_res = $conn->query("SELECT photoName FROM photo WHERE productID = $pid");
$images = [];
while ($row = $photo_res->fetch_assoc()) { $images[] = "../image/" . $row['photoName']; }
if (empty($images)) $images[] = "../image/placeholder.jpg";

$stock_res = $conn->query("SELECT s.quantity, sz.sizeName, c.colorName, c.colorCode 
                           FROM stock s 
                           JOIN size sz ON s.sizeID = sz.sizeID 
                           JOIN color c ON s.colorID = c.colorID 
                           WHERE s.productID = $pid");
$variants = [];
while ($row = $stock_res->fetch_assoc()) {
    $cName = $row['colorName'];
    if (!isset($variants[$cName])) {
        $variants[$cName] = ['colorName' => $cName, 'colorCode' => $row['colorCode'], 'sizes' => []];
    }
    $variants[$cName]['sizes'][$row['sizeName']] = intval($row['quantity']);
}

$accID   = intval($_SESSION['accountID'] ?? 0);
$isFaved = false;
if ($accID) {
    $favCheck = $conn->query("SELECT * FROM favourite WHERE productID=$pid AND accountID=$accID");
    $isFaved  = ($favCheck->num_rows > 0);
}

// ── Fetch discount tiers for this product ──
$discount_res = $conn->query("SELECT range1, range2, percentage FROM discount WHERE productID = $pid ORDER BY range1 ASC");
$discountTiers = [];
while ($row = $discount_res->fetch_assoc()) {
    $discountTiers[] = $row;
}

// ── Fetch related products ──
$related_res = $conn->query("
    SELECT DISTINCT p.productID, p.productName, p.price, p.discountedPrice,
           p.postedDate,
           (SELECT photoName FROM photo WHERE productID = p.productID LIMIT 1) AS main_img
    FROM product p
    JOIN relatedproduct rp ON (p.productID = rp.productID1 OR p.productID = rp.productID2)
    WHERE (rp.productID1 = $pid OR rp.productID2 = $pid)
      AND p.productID != $pid
    ORDER BY p.postedDate DESC
");
?>
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='utf-8' />
    <meta name='viewport' content='width=device-width,initial-scale=1' />
    <title><?php echo htmlspecialchars($product['productName']); ?></title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="../frontend/style.css">
    <style>
        .specific_class_wrapper { font-family: 'Vollkorn', serif; background-color: #f7f7f7; padding-bottom: 80px; }
        .specific_product_full-width-slider { width: 100vw; margin-left: 50%; transform: translateX(-50%); background: #fff; }

        .prodSwiper { width: 100%; height: 700px; background: #fff; }
        .prodSwiper .swiper-slide {
            display: flex;
            justify-content: center;
            align-items: center;
            background: #fff;
        }
        .prodSwiper .swiper-slide img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            user-select: none;
            -webkit-user-drag: none;
        }
        .prodSwiper .swiper-pagination-bullet { background: #bbb; opacity: 1; }
        .prodSwiper .swiper-pagination-bullet-active { background: #0b6e4f; }

        .specific_product_details-card {
            padding: 40px;
            border-radius: 15px;
            max-width: 900px;
            margin: -60px auto 40px auto;
            position: relative;
            background: #fff;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }
        .specific_product_color-btn { width: 35px; height: 35px; border-radius: 50%; border: 2px solid #ddd; margin-right: 12px; cursor: pointer; display: inline-block; }
        .specific_product_color-btn.active { border-color: #0b6e4f; transform: scale(1.1); }
        .specific_product_size-btn { width: 50px; height: 50px; border: 2px solid #0b6e4f; border-radius: 50%; margin-right: 10px; background: #fff; font-weight: 600; }
        .specific_product_size-btn.active { background: #0b6e4f; color: #fff; }
        .specific_product_size-btn.disabled { border-color: #eee; color: #ccc; cursor: not-allowed; }
        .add-to-cart-btn { background: #0b6e4f; color: #fff; border: none; width: 100%; padding: 15px; border-radius: 8px; font-weight: 700; font-size: 1.2rem; cursor: pointer; transition: background .2s; }
        .add-to-cart-btn:hover { background: #094d36; }

        /* Related product cards */
        .related-product-card { display: block; text-decoration: none; color: inherit; transition: transform 0.2s; }
        .related-product-card:hover { transform: translateY(-4px); }
        .related-product-card img { height: 300px; object-fit: cover; width: 100%; border-radius: 8px; }

        #stock-alert { color: #d9534f; font-weight: bold; display: none; margin-top: 10px; }
        .empty-related { color: #888; font-style: italic; border: 2px dashed #ddd; padding: 40px; border-radius: 10px; }

        .product-title-row { display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; margin-bottom: 4px; }
        .product-title-row h2 { margin: 0; flex: 1; }

        .fav-btn {
            flex-shrink: 0; width: 54px; height: 54px; border-radius: 50%;
            border: 2px solid #e0e0e0; background: #fff;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: border-color .25s, background .25s, transform .2s;
            box-shadow: 0 2px 10px rgba(0,0,0,.08); margin-top: 4px;
        }
        .fav-btn:hover { border-color: #e53e3e; transform: scale(1.08); }
        .fav-btn svg { width: 26px; height: 26px; transition: fill .25s, stroke .25s; }
        .fav-btn .heart-icon { fill: none; stroke: #999; stroke-width: 2; }
        .fav-btn.is-faved { border-color: #e53e3e; background: #fff5f5; }
        .fav-btn.is-faved .heart-icon { fill: #e53e3e; stroke: #e53e3e; }

        @keyframes heartPulse {
            0%   { transform: scale(1); }
            40%  { transform: scale(1.3); }
            70%  { transform: scale(.9); }
            100% { transform: scale(1); }
        }
        .fav-btn.pulse { animation: heartPulse .35s ease; }

        .fav-toast {
            position: fixed; bottom: 32px; left: 50%;
            transform: translateX(-50%) translateY(80px);
            background: #1a1a1a; color: #fff; padding: 12px 24px;
            border-radius: 50px; font-family: 'Vollkorn', serif; font-size: .95rem;
            white-space: nowrap; pointer-events: none; z-index: 9999;
            transition: transform .35s cubic-bezier(.175,.885,.32,1.275), opacity .3s;
            opacity: 0;
        }
        .fav-toast.show { transform: translateX(-50%) translateY(0); opacity: 1; }

        /* ── Discount Tier Styles ── */
        .discount-box {
            background: #f9f6ed;
            border: 1px dashed #c8a84b;
            border-radius: 10px;
            padding: 14px 18px;
            margin: 14px 0;
        }
        .discount-box-title {
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #856404;
            margin-bottom: 10px;
        }
        .discount-tiers-row {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 8px;
        }
        .tier-pill {
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 0.82rem;
            font-weight: 600;
            border: 1.5px solid #ddd;
            color: #555;
            background: #fff;
            transition: all 0.2s;
        }
        .tier-pill.active {
            background: #0b6e4f;
            color: #fff;
            border-color: #0b6e4f;
        }
        .tier-pill.next {
            background: #fff8e1;
            color: #856404;
            border-color: #ffc107;
        }
        .discount-hint-text {
            font-size: 0.85rem;
            color: #856404;
            font-weight: 600;
            margin-top: 4px;
        }
        .discount-active-text {
            font-size: 0.85rem;
            color: #0b6e4f;
            font-weight: 700;
            margin-top: 4px;
        }
        .price-preview {
            font-size: 0.9rem;
            color: #555;
            margin-top: 6px;
        }
        .price-preview strong {
            color: #0b6e4f;
            font-size: 1rem;
        }
    </style>
</head>
<body>

<div class='specific_class_wrapper'>

    <div class='specific_product_full-width-slider'>
        <div class="swiper prodSwiper">
            <div class="swiper-wrapper" id="swiperImgs"></div>
            <div class="swiper-pagination"></div>
        </div>
    </div>

    <div class='container'>
        <div class='specific_product_details-card'>

            <div class="product-title-row">
                <h2 class='fw-bold'><?php echo htmlspecialchars($product['productName']); ?></h2>
                <button class="fav-btn <?php echo $isFaved ? 'is-faved' : ''; ?>"
                        id="favBtn"
                        title="<?php echo $isFaved ? 'Remove from favourites' : 'Save to favourites'; ?>"
                        onclick="toggleFavourite()">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path class="heart-icon" d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                    </svg>
                </button>
            </div>

<!-- Price -->
<?php if ($product['discountedPrice']): ?>
    <p class='fs-3 fw-bold' style="margin-bottom:2px;">
        <span class='text-danger'>BHAT <?php echo number_format($product['discountedPrice']); ?></span>
        &nbsp;<span style="text-decoration:line-through; color:#aaa; font-size:1.2rem;">BHAT <?php echo number_format($product['price']); ?></span>
    </p>
    <p style="font-size:0.85rem; color:#0b6e4f; font-weight:600; margin-bottom:6px;">
        ✅ <?php echo round((1 - $product['discountedPrice']/$product['price'])*100); ?>% already applied
    </p>
<?php else: ?>
    <p class='text-danger fs-3 fw-bold' id="mainPrice">
        BHAT <?php echo number_format($product['price']); ?>
    </p>
<?php endif; ?>

            <!-- ── Discount Tiers Box ── -->
            <?php if (!empty($discountTiers)): ?>
            <div class="discount-box">
                <div class="discount-box-title">🏷️ Bulk Discount Available</div>
                <div class="discount-tiers-row" id="tierPills">
                    <?php foreach ($discountTiers as $tier): 
                        $label = 'Buy ' . $tier['range1'] . ($tier['range2'] ? '–' . $tier['range2'] : '+') . ' → additional ' . $tier['percentage'] . '% off';
                    ?>
                        <span class="tier-pill" 
                              data-range1="<?php echo $tier['range1']; ?>"
                              data-range2="<?php echo $tier['range2'] ?? ''; ?>"
                              data-pct="<?php echo $tier['percentage']; ?>">
                            <?php echo $label; ?>
                        </span>
                    <?php endforeach; ?>
                </div>
                <div id="discountHint" class="discount-hint-text">
                    👆 Buy <?php echo $discountTiers[0]['range1']; ?> or more to unlock a discount!
                </div>
                <div id="pricePreview" class="price-preview" style="display:none;"></div>
            </div>
            <?php endif; ?>

            <hr>
            <p class='text-muted'><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>

            <h6 class='fw-bold mt-4'>COLOR</h6>
            <div id='colorBtns' class='mb-3'></div>

            <h6 class='fw-bold mt-4'>SIZE</h6>
            <div id='sizeBtns' class='mb-3 d-flex flex-wrap'></div>

            <div id="stock-info" class="small mb-3"></div>

            <h6 class='fw-bold'>QUANTITY</h6>
            <div class='input-group mb-3' style='width: 140px;'>
                <button class='btn btn-outline-dark' onclick='updateQty(-1)'>-</button>
                <input type='text' id='qtyInp' class='form-control text-center' value='1' readonly>
                <button class='btn btn-outline-dark' onclick='updateQty(1)'>+</button>
            </div>

            <div id="stock-alert">⚠️ OUT OF STOCK</div>
            <button class='add-to-cart-btn mt-3' onclick="handleAddToCart()">ADD TO CART</button>
        </div>

        <!-- Related Products -->
        <div class="mt-5">
            <h3 class="text-center mb-4" style="font-family: 'Cinzel';">RELATED PRODUCTS</h3>
            <?php if ($related_res && $related_res->num_rows > 0): ?>
                <div class="row g-4">
                    <?php while ($rp = $related_res->fetch_assoc()): ?>
                        <div class="col-6 col-md-3">
                            <a href="specific_product.php?id=<?php echo $rp['productID']; ?>" class="related-product-card">
                                <img src="../image/<?php echo htmlspecialchars($rp['main_img'] ?: 'placeholder.jpg'); ?>"
                                     alt="<?php echo htmlspecialchars($rp['productName']); ?>">
                                <h6 class="mt-2 fw-bold text-truncate"><?php echo htmlspecialchars($rp['productName']); ?></h6>
                                <p class="small text-muted">BHAT <?php echo number_format($rp['price']); ?></p>
                            </a>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="text-center empty-related">
                    <p class="mb-0 fs-5">No related products found.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="fav-toast" id="favToast"></div>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js'></script>
<script>
const pData = {
    id:        <?php echo $pid; ?>,
    basePrice: <?php echo (float)($product['discountedPrice'] ?: $product['price']); ?>,
    images:    <?php echo json_encode($images); ?>,
    variants:  <?php echo json_encode(array_values($variants)); ?>,
    tiers:     <?php echo json_encode($discountTiers); ?>,
    loggedIn:  <?php echo $accID ? 'true' : 'false'; ?>
};

let curVariant = pData.variants[0] ?? null;
let selSize    = null;

window.onload = () => {
    const sBox = document.getElementById('swiperImgs');
    pData.images.forEach(img => {
        sBox.innerHTML += `<div class="swiper-slide"><img src="${img}" draggable="false"></div>`;
    });

    new Swiper('.prodSwiper', {
        loop: pData.images.length > 1,
        grabCursor: true,
        pagination: { el: '.swiper-pagination', clickable: true },
        keyboard: { enabled: true },
        autoplay: { delay: 3000, disableOnInteraction: false },
        speed: 1200,
    });

    const cBox = document.getElementById('colorBtns');
    if (pData.variants.length > 0) {
        pData.variants.forEach((v, i) => {
            const d = document.createElement('div');
            d.className = `specific_product_color-btn ${i===0?'active':''}`;
            d.style.backgroundColor = v.colorCode;
            d.title = v.colorName;
            d.onclick = () => {
                document.querySelectorAll('.specific_product_color-btn').forEach(b => b.classList.remove('active'));
                d.classList.add('active');
                renderSizes(v);
            };
            cBox.appendChild(d);
        });
        renderSizes(pData.variants[0]);
    }

    // Init discount display with qty=1
    updateDiscountDisplay(1);
};

function renderSizes(v) {
    curVariant = v;
    const sBox = document.getElementById('sizeBtns');
    sBox.innerHTML = '';
    selSize = null;
    document.getElementById('stock-alert').style.display = 'none';
    document.getElementById('stock-info').innerHTML = '';

    for (let s in v.sizes) {
        const qty = v.sizes[s];
        const btn = document.createElement('button');
        btn.className = `specific_product_size-btn ${qty === 0 ? 'disabled' : ''}`;
        btn.textContent = s;
        btn.disabled = qty === 0;
        btn.onclick = () => {
            document.querySelectorAll('.specific_product_size-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            selSize = s;
            document.getElementById('stock-info').innerHTML = `Available: ${qty} units`;
            document.getElementById('qtyInp').value = 1;
            document.getElementById('stock-alert').style.display = 'none';
            updateDiscountDisplay(1);
        };
        sBox.appendChild(btn);
    }
}

function updateQty(n) {
    if (!selSize) { alert("Please select a size first"); return; }
    const inp = document.getElementById('qtyInp');
    let v   = parseInt(inp.value) + n;
    let max = curVariant.sizes[selSize];
    if (v > max) {
        document.getElementById('stock-alert').style.display = 'block';
        document.getElementById('stock-alert').innerText = "⚠️ Maximum available stock reached";
        return;
    }
    document.getElementById('stock-alert').style.display = 'none';
    if (v >= 1) {
        inp.value = v;
        updateDiscountDisplay(v); // ← update discount info on qty change
    }
}

// ── Update discount tier highlights and hint text based on current qty ──
function updateDiscountDisplay(qty) {
    if (!pData.tiers || pData.tiers.length === 0) return;

    const pills    = document.querySelectorAll('.tier-pill');
    const hintEl   = document.getElementById('discountHint');
    const previewEl = document.getElementById('pricePreview');

    let activeTier = null;
    let nextTier   = null;

    // Find active and next tier
    pData.tiers.forEach(tier => {
        const r1 = parseInt(tier.range1);
        const r2 = tier.range2 ? parseInt(tier.range2) : null;

        if (qty >= r1 && (r2 === null || qty <= r2)) {
            activeTier = tier;
        }
    });

    if (!activeTier) {
        // Find next tier
        for (let i = 0; i < pData.tiers.length; i++) {
            if (parseInt(pData.tiers[i].range1) > qty) {
                nextTier = pData.tiers[i];
                break;
            }
        }
    }

    // Update pill highlights
    pills.forEach(pill => {
        pill.classList.remove('active', 'next');
        const r1  = parseInt(pill.dataset.range1);
        const r2  = pill.dataset.range2 ? parseInt(pill.dataset.range2) : null;
        const pct = parseFloat(pill.dataset.pct);

        const isActive = activeTier && parseFloat(activeTier.percentage) === pct && parseInt(activeTier.range1) === r1;
        const isNext   = nextTier   && parseInt(nextTier.range1) === r1;

        if (isActive) pill.classList.add('active');
        else if (isNext) pill.classList.add('next');
    });

    // Update hint text and price preview
    if (activeTier) {
        const pct          = parseFloat(activeTier.percentage);
        const discounted   = pData.basePrice * (1 - pct / 100);
        const savings      = pData.basePrice - discounted;

        hintEl.className   = 'discount-active-text';
        hintEl.innerHTML   = `✅ ${pct}% discount active! You save BHAT ${savings.toLocaleString()} per item.`;

        previewEl.style.display = 'block';
        previewEl.innerHTML = `
            <span style="text-decoration:line-through;color:#aaa;">BHAT ${pData.basePrice.toLocaleString()}</span>
            &nbsp;→&nbsp;
            <strong>BHAT ${discounted.toLocaleString(undefined, {minimumFractionDigits:0, maximumFractionDigits:2})}</strong> per item
        `;
    } else if (nextTier) {
        const needed = parseInt(nextTier.range1) - qty;
        hintEl.className  = 'discount-hint-text';
        hintEl.innerHTML  = `🏷️ Add <strong>${needed}</strong> more to get <strong>${nextTier.percentage}% off</strong>!`;
        previewEl.style.display = 'none';
    } else {
        hintEl.className  = 'discount-hint-text';
        hintEl.innerHTML  = `👆 Buy ${pData.tiers[0].range1} or more to unlock a discount!`;
        previewEl.style.display = 'none';
    }
}

function handleAddToCart() {
    if (!selSize) { alert("Please select a size"); return; }
    const data = new FormData();
    data.append('pid',   pData.id);
    data.append('color', curVariant.colorName);
    data.append('size',  selSize);
    data.append('qty',   document.getElementById('qtyInp').value);

    fetch('add_to_cart.php', { method: 'POST', body: data })
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                alert("Added to cart!");
                window.location.href = "cart.php";
            } else {
                document.getElementById('stock-alert').style.display = 'block';
                document.getElementById('stock-alert').innerText = "⚠️ " + res.message;
            }
        });
}

function toggleFavourite() {
    if (!pData.loggedIn) { showFavToast("Please log in to save favourites ❤️"); return; }
    const btn  = document.getElementById('favBtn');
    const data = new FormData();
    data.append('toggle_fav', '1');

    fetch('specific_product.php?id=' + pData.id, { method: 'POST', body: data })
        .then(r => r.json())
        .then(res => {
            if (!res.success) return;
            btn.classList.remove('pulse');
            void btn.offsetWidth;
            btn.classList.add('pulse');
            if (res.faved) {
                btn.classList.add('is-faved');
                btn.title = 'Remove from favourites';
                showFavToast("Saved to favourites ❤️");
            } else {
                btn.classList.remove('is-faved');
                btn.title = 'Save to favourites';
                showFavToast("Removed from favourites");
            }
        })
        .catch(() => showFavToast("Something went wrong, please try again."));
}

function showFavToast(msg) {
    const t = document.getElementById('favToast');
    t.textContent = msg;
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 2500);
}
</script>
</body>
</html>