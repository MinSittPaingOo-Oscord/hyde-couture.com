<?php
include '../connection/connectdb.php';
include '../layout/nav.php';

/* ── Parent tab categories ── */
$parents_query = "SELECT * FROM category WHERE parentID IS NULL ORDER BY categoryID";
$parents_res   = $conn->query($parents_query);
$parent_categories = [];
while ($p = $parents_res->fetch_assoc()) $parent_categories[] = $p;

/* ── All products (for ALL tab) ── */
$all_products_query = "
    SELECT p.productID, p.productName, p.price, p.discountedPrice,
           (SELECT photoName FROM photo WHERE productID = p.productID LIMIT 1) AS photoName
    FROM product p
    GROUP BY p.productID
    ORDER BY p.productID DESC
";
$all_products_res = $conn->query($all_products_query);
$all_products = [];
while ($row = $all_products_res->fetch_assoc()) $all_products[] = $row;

/* ── Build grouped data: parent → [subcat → products] ── */
$grouped_data = [];
foreach ($parent_categories as $parent) {
    $pid = $parent['categoryID'];
    $grouped_data[$pid] = [];

    $sub_query = "SELECT * FROM category WHERE parentID = $pid ORDER BY categoryID";
    $sub_res   = $conn->query($sub_query);
    $subs      = [];
    while ($sub = $sub_res->fetch_assoc()) $subs[] = $sub;

    if (!empty($subs)) {
        foreach ($subs as $sub) {
            $sid = $sub['categoryID'];
            $prod_query = "
                SELECT p.productID, p.productName, p.price, p.discountedPrice,
                       (SELECT photoName FROM photo WHERE productID = p.productID LIMIT 1) AS photoName
                FROM product p
                JOIN productxcategory px ON p.productID = px.productID
                WHERE px.categoryID = $sid
                ORDER BY p.productID DESC
            ";
            $prod_res = $conn->query($prod_query);
            $products = [];
            while ($prod = $prod_res->fetch_assoc()) $products[] = $prod;
            if (!empty($products)) {
                $grouped_data[$pid][] = ['subcat' => $sub, 'products' => $products];
            }
        }
    } else {
        $prod_query = "
            SELECT p.productID, p.productName, p.price, p.discountedPrice,
                   (SELECT photoName FROM photo WHERE productID = p.productID LIMIT 1) AS photoName
            FROM product p
            JOIN productxcategory px ON p.productID = px.productID
            WHERE px.categoryID = $pid
            ORDER BY p.productID DESC
        ";
        $prod_res = $conn->query($prod_query);
        $products = [];
        while ($prod = $prod_res->fetch_assoc()) $products[] = $prod;
        if (!empty($products)) {
            $grouped_data[$pid][] = [
                'subcat'   => ['categoryID' => $pid, 'categoryName' => $parent['categoryName']],
                'products' => $products
            ];
        }
    }
}

/* ── New arrivals (categoryID = 5) ── */
$new_arrivals_query = "
    SELECT p.productID, p.productName, p.price, p.discountedPrice,
           (SELECT photoName FROM photo WHERE productID = p.productID LIMIT 1) AS photoName
    FROM product p
    JOIN productxcategory px ON p.productID = px.productID
    WHERE px.categoryID = 5
    ORDER BY p.productID DESC
";
$new_arrivals_res = $conn->query($new_arrivals_query);
$new_arrivals = [];
while ($row = $new_arrivals_res->fetch_assoc()) $new_arrivals[] = $row;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Hyde Couture</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="../frontend/style.css"/>
    <style>
        /* ══════════════════════════════
           TABS
        ══════════════════════════════ */
        .category-scroll-wrapper {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            padding: 10px 0;
            justify-content: center;
        }
        .category-tab {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px 22px;
            cursor: pointer;
            font-weight: 600;
            background: #fff;
            transition: background 0.25s, border-color 0.25s;
            font-family: 'Cinzel', serif;
            letter-spacing: 1px;
            font-size: 0.82rem;
            white-space: nowrap;
        }
        .category-tab.active { background: #e8f5e9; border-color: #005A2B; }
        .category-tab:hover:not(.active) { background: #f5f5f5; }

        /* ══════════════════════════════
           PANELS
        ══════════════════════════════ */
        .parent-section { display: none; }
        .parent-section.active { display: block; }

        /* ══════════════════════════════
           SUBCAT SECTION
        ══════════════════════════════ */
        .subcat-section { margin-bottom: 48px; }

        .subcat-header {
            display: flex;
            align-items: baseline;
            justify-content: space-between;
            border-bottom: 1px solid #e0e0e0;
            padding-bottom: 10px;
            margin-bottom: 18px;
        }
        .subcat-label {
            font-family: 'Cinzel', serif;
            font-size: 0.88rem;
            letter-spacing: 3px;
            font-weight: 700;
            color: #222;
            text-transform: uppercase;
            white-space: nowrap;
        }
        .subcat-label .count {
            font-weight: 400;
            letter-spacing: 1px;
            color: #888;
            font-size: 0.7rem;
            margin-left: 8px;
        }
        .see-more-link {
            font-family: 'Cinzel', serif;
            font-size: 0.72rem;
            letter-spacing: 2px;
            font-weight: 600;
            color: #222;
            text-decoration: none;
            border-bottom: 1px solid #222;
            padding-bottom: 1px;
            white-space: nowrap;
            transition: color 0.2s, border-color 0.2s;
        }
        .see-more-link:hover { color: #005A2B; border-color: #005A2B; }

        /* ══════════════════════════════
           SCROLL ROW — NO VISIBLE BAR
           .scroll-outer clips the overflow so no space is reserved for the bar.
           .product-scroll-row uses scroll but hides the bar via all three methods.
        ══════════════════════════════ */
        .scroll-outer {
            overflow: hidden;   /* hides scrollbar gutter entirely */
        }
        .product-scroll-row {
            display: flex;
            flex-wrap: nowrap;
            gap: 16px;
            overflow-x: scroll;
            scroll-snap-type: x mandatory;
            -webkit-overflow-scrolling: touch;
            /* hide scrollbar — every browser */
            scrollbar-width: none;        /* Firefox */
            -ms-overflow-style: none;     /* IE / old Edge */
            padding-bottom: 2px;
            cursor: grab;
            user-select: none;
        }
        .product-scroll-row:active  { cursor: grabbing; }
        .product-scroll-row::-webkit-scrollbar { display: none; } /* Chrome / Safari / new Edge */

        /* ══════════════════════════════
           PRODUCT CARD
        ══════════════════════════════ */
        .product-card-item {
            flex: 0 0 210px;
            width: 210px;
            scroll-snap-align: start;
        }
        .product-card-item a { text-decoration: none; color: inherit; }

        /* Image wrapper for badge positioning */
        .card-img-wrap {
            position: relative;
            overflow: hidden;
            border-radius: 8px;
            background: #f5f5f5;
        }
        .card-img-wrap img {
            width: 100%;
            aspect-ratio: 3/4;
            object-fit: cover;
            display: block;
            transition: transform 0.35s ease;
            pointer-events: none; /* prevents drag ghost */
        }
        .product-card-item:hover .card-img-wrap img { transform: scale(1.03); }

        /* Discount badge */
        .discount-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background: #222;
            color: #fff;
            font-family: 'Cinzel', serif;
            font-size: 0.62rem;
            font-weight: 700;
            letter-spacing: 1px;
            padding: 4px 8px;
            border-radius: 4px;
            pointer-events: none;
        }

        .card-name {
            font-size: 0.78rem;
            font-weight: 700;
            font-family: 'Cinzel', serif;
            margin-top: 8px;
            margin-bottom: 3px;
            text-align: center;
            line-height: 1.4;
        }
        .price-block {
            font-size: 0.78rem;
            color: #333;
            text-align: center;
        }
        .price-block .sale { color: #c0392b; font-weight: 600; }
        .price-block .orig { color: #999; text-decoration: line-through; margin-left: 5px; font-size: 0.72rem; }

        /* ══════════════════════════════
           VIEW ALL BUTTON
        ══════════════════════════════ */
        .view-all-btn {
            display: inline-block;
            margin-top: 16px;
            border: 1px solid #222;
            padding: 10px 36px;
            background: transparent;
            font-family: 'Cinzel', serif;
            font-size: 0.75rem;
            letter-spacing: 2px;
            font-weight: 600;
            cursor: pointer;
            text-transform: uppercase;
            transition: background 0.25s, color 0.25s;
            text-decoration: none;
            color: #222;
        }
        .view-all-btn:hover { background: #222; color: #fff; }

        /* ══════════════════════════════
           RESPONSIVE
        ══════════════════════════════ */
        @media (max-width: 1100px) { .product-card-item { flex: 0 0 185px; width: 185px; } }
        @media (max-width: 768px)  { .product-card-item { flex: 0 0 155px; width: 155px; } }
        @media (max-width: 480px)  { .product-card-item { flex: 0 0 135px; width: 135px; } }
    </style>
</head>
<body>

<!-- ══════════════════════
     CATEGORY TABS
══════════════════════ -->
<div class="container mt-5">
    <h2 class="text-center mb-4" style="font-family:'Cinzel',serif;letter-spacing:4px;">CATEGORIES</h2>
    <div class="category-scroll-wrapper" id="categoryTabs">
        <div class="category-tab active" data-target="panel-all">ALL</div>
        <?php foreach ($parent_categories as $cat): ?>
            <div class="category-tab" data-target="panel-<?php echo $cat['categoryID']; ?>">
                <?php echo strtoupper(htmlspecialchars($cat['categoryName'])); ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- ══════════════════════
     ALL PANEL
══════════════════════ -->
<section class="container mt-5 parent-section active" id="panel-all">
    <h2 class="text-center mb-4" style="font-family:'Cinzel',serif;letter-spacing:4px;">OUR COLLECTION</h2>
    <div class="scroll-outer">
        <div class="product-scroll-row">
            <?php foreach ($all_products as $prod):
                $img = !empty($prod['photoName']) ? "../image/" . $prod['photoName'] : "../image/placeholder.jpg";
                $disc_pct = 0;
                if ($prod['discountedPrice'] && $prod['price'] > 0)
                    $disc_pct = round((($prod['price'] - $prod['discountedPrice']) / $prod['price']) * 100);
            ?>
                <div class="product-card-item">
                    <a href="specific_product.php?id=<?php echo $prod['productID']; ?>">
                        <div class="card-img-wrap">
                            <img src="<?php echo $img; ?>" alt="<?php echo htmlspecialchars($prod['productName']); ?>">
                            <?php if ($disc_pct > 0): ?>
                                <div class="discount-badge">-<?php echo $disc_pct; ?>%</div>
                            <?php endif; ?>
                        </div>
                        <div class="card-name"><?php echo htmlspecialchars($prod['productName']); ?></div>
                        <div class="price-block">
                            <?php if ($prod['discountedPrice']): ?>
                                <span class="sale">BHAT <?php echo number_format($prod['discountedPrice']); ?></span>
                                <span class="orig">BHAT <?php echo number_format($prod['price']); ?></span>
                            <?php else: ?>
                                <span>BHAT <?php echo number_format($prod['price']); ?></span>
                            <?php endif; ?>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ══════════════════════
     PARENT PANELS
══════════════════════ -->
<?php foreach ($parent_categories as $parent):
    $pid     = $parent['categoryID'];
    $subcats = $grouped_data[$pid] ?? [];
?>
<section class="container mt-5 parent-section" id="panel-<?php echo $pid; ?>">
    <h2 class="text-center mb-5" style="font-family:'Cinzel',serif;letter-spacing:4px;">
        <?php echo strtoupper(htmlspecialchars($parent['categoryName'])); ?>
    </h2>

    <?php if (empty($subcats)): ?>
        <p class="text-center text-muted py-5" style="font-family:'Cinzel',serif;letter-spacing:3px;">
            NO PRODUCTS AVAILABLE
        </p>
    <?php else: ?>
        <?php foreach ($subcats as $group):
            $subcat   = $group['subcat'];
            $products = $group['products'];
            $total    = count($products);
            $sid      = $subcat['categoryID'];
        ?>
        <div class="subcat-section">
            <div class="subcat-header">
                <span class="subcat-label">
                    <?php echo strtoupper(htmlspecialchars($subcat['categoryName'])); ?>
                    <span class="count">(<?php echo $total; ?>)</span>
                </span>
                <?php if ($total > 4): ?>
                    <a class="see-more-link" href="subcat_detail.php?id=<?php echo $sid; ?>">
                        SEE ALL <?php echo $total; ?> →
                    </a>
                <?php endif; ?>
            </div>

            <div class="scroll-outer">
                <div class="product-scroll-row">
                    <?php foreach ($products as $prod):
                        $img = !empty($prod['photoName']) ? "../image/" . $prod['photoName'] : "../image/placeholder.jpg";
                        $disc_pct = 0;
                        if ($prod['discountedPrice'] && $prod['price'] > 0)
                            $disc_pct = round((($prod['price'] - $prod['discountedPrice']) / $prod['price']) * 100);
                    ?>
                        <div class="product-card-item">
                            <a href="specific_product.php?id=<?php echo $prod['productID']; ?>">
                                <div class="card-img-wrap">
                                    <img src="<?php echo $img; ?>" alt="<?php echo htmlspecialchars($prod['productName']); ?>">
                                    <?php if ($disc_pct > 0): ?>
                                        <div class="discount-badge">-<?php echo $disc_pct; ?>%</div>
                                    <?php endif; ?>
                                </div>
                                <div class="card-name"><?php echo htmlspecialchars($prod['productName']); ?></div>
                                <div class="price-block">
                                    <?php if ($prod['discountedPrice']): ?>
                                        <span class="sale">BHAT <?php echo number_format($prod['discountedPrice']); ?></span>
                                        <span class="orig">BHAT <?php echo number_format($prod['price']); ?></span>
                                    <?php else: ?>
                                        <span>BHAT <?php echo number_format($prod['price']); ?></span>
                                    <?php endif; ?>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</section>
<?php endforeach; ?>

<!-- ══════════════════════
     NEW ARRIVALS
══════════════════════ -->
<section class="container mt-5 pt-5 border-top">
    <h2 class="text-center mb-4" style="font-family:'Cinzel',serif;letter-spacing:4px;">NEW ARRIVALS</h2>

    <?php if (!empty($new_arrivals)): ?>
        <div class="scroll-outer">
            <div class="product-scroll-row">
                <?php foreach ($new_arrivals as $new):
                    $img = !empty($new['photoName']) ? "../image/" . $new['photoName'] : "../image/placeholder.jpg";
                    $disc_pct = 0;
                    if ($new['discountedPrice'] && $new['price'] > 0)
                        $disc_pct = round((($new['price'] - $new['discountedPrice']) / $new['price']) * 100);
                ?>
                    <div class="product-card-item">
                        <a href="specific_product.php?id=<?php echo $new['productID']; ?>">
                            <div class="card-img-wrap">
                                <img src="<?php echo $img; ?>" alt="<?php echo htmlspecialchars($new['productName']); ?>">
                                <?php if ($disc_pct > 0): ?>
                                    <div class="discount-badge">-<?php echo $disc_pct; ?>%</div>
                                <?php endif; ?>
                            </div>
                            <div class="card-name"><?php echo htmlspecialchars($new['productName']); ?></div>
                            <div class="price-block">
                                <?php if ($new['discountedPrice']): ?>
                                    <span class="sale">BHAT <?php echo number_format($new['discountedPrice']); ?></span>
                                    <span class="orig">BHAT <?php echo number_format($new['price']); ?></span>
                                <?php else: ?>
                                    <span>BHAT <?php echo number_format($new['price']); ?></span>
                                <?php endif; ?>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="text-center mt-4">
            <a href="subcat_detail.php?id=5" class="view-all-btn">VIEW ALL NEW ARRIVALS →</a>
        </div>
    <?php else: ?>
        <div class="text-center py-5">
            <h3 class="display-6 text-muted" style="font-family:'Cinzel',serif;letter-spacing:5px;">COMING SOON</h3>
            <p class="text-secondary">We are currently preparing our next exclusive drop.</p>
        </div>
    <?php endif; ?>
</section>

<?php include '../layout/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ── Drag-to-scroll (mouse) on every scroll row ── */
    document.querySelectorAll('.product-scroll-row').forEach(function (slider) {
        var isDown = false, startX, scrollLeft;

        slider.addEventListener('mousedown', function (e) {
            isDown     = true;
            startX     = e.pageX - slider.offsetLeft;
            scrollLeft = slider.scrollLeft;
        });
        slider.addEventListener('mouseleave', function () { isDown = false; });
        slider.addEventListener('mouseup',    function () { isDown = false; });
        slider.addEventListener('mousemove',  function (e) {
            if (!isDown) return;
            e.preventDefault();
            var walk = (e.pageX - slider.offsetLeft - startX) * 1.2;
            slider.scrollLeft = scrollLeft - walk;
        });
    });

    /* ── Tab switching ── */
    document.querySelectorAll('.category-tab').forEach(function (tab) {
        tab.addEventListener('click', function () {
            document.querySelectorAll('.category-tab').forEach(function (t) { t.classList.remove('active'); });
            this.classList.add('active');
            document.querySelectorAll('.parent-section').forEach(function (s) { s.classList.remove('active'); });
            var panel = document.getElementById(this.dataset.target);
            if (panel) panel.classList.add('active');
            document.getElementById('categoryTabs').scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });

});
</script>
</body>
</html>