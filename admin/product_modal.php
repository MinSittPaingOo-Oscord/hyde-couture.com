<?php
include "../connection/connectdb.php";
$id = (int)$_GET['id'];
$p = $conn->query("SELECT * FROM product WHERE productID = $id")->fetch_assoc();
if (!$p) { echo "<p style='color:red;text-align:center;padding:30px;'>Product not found.</p>"; exit; }

$photos = $conn->query("SELECT photoName FROM photo WHERE productID = $id");

$stock = $conn->query("SELECT s.*, sz.sizeName, c.colorName, c.colorCode
    FROM stock s
    JOIN size sz ON s.sizeID = sz.sizeID
    JOIN color c ON s.colorID = c.colorID
    WHERE s.productID = $id");

$categories = $conn->query("SELECT c.categoryName, c.parentID, p.categoryName AS parentName
    FROM category c
    LEFT JOIN category p ON c.parentID = p.categoryID
    JOIN productxcategory pxc ON c.categoryID = pxc.categoryID
    WHERE pxc.productID = $id");

// Format categories nicely
$catDisplay = [];
while ($cat = $categories->fetch_assoc()) {
    if ($cat['parentID']) {
        $catDisplay[] = htmlspecialchars($cat['categoryName']) . " to " . htmlspecialchars($cat['parentName']);
    } else {
        $catDisplay[] = htmlspecialchars($cat['categoryName']);
    }
}

// Related Products
// $related = $conn->query("
//     SELECT DISTINCT pr.productID, pr.productName, pr.price, pr.discountedPrice, pr.preorder,
//            (SELECT photoName FROM photo WHERE productID = pr.productID LIMIT 1) AS mainPhoto
//     FROM product pr
//     JOIN relatedproduct rp ON (pr.productID = rp.productID1 OR pr.productID = rp.productID2)
//     WHERE (rp.productID1 = $id OR rp.productID2 = $id) AND pr.productID != $id
//     ORDER BY pr.postedDate DESC
//     LIMIT 8
// ");

$related = $conn->query("
    SELECT DISTINCT pr.productID, pr.productName, pr.price, pr.discountedPrice, pr.preorder, pr.postedDate,
           (SELECT photoName FROM photo WHERE productID = pr.productID LIMIT 1) AS mainPhoto
    FROM product pr
    JOIN relatedproduct rp ON (pr.productID = rp.productID1 OR pr.productID = rp.productID2)
    WHERE (rp.productID1 = $id OR rp.productID2 = $id) AND pr.productID != $id
    ORDER BY pr.postedDate DESC
    LIMIT 8
");
?>

<style>
    .modal-detail { font-family:Arial,sans-serif; color:#333; line-height:1.6; }
    .modal-detail h3 {
        color:#004d00; font-size:1.5rem; margin:0 0 20px; text-transform:uppercase;
        border-bottom:3px solid #004d00; padding-bottom:8px; display:inline-block;
    }
    .modal-grid { display:grid; grid-template-columns:1fr 1fr; gap:35px; margin-bottom:30px; }
    .product-gallery {
        display:grid; grid-template-columns:repeat(auto-fit,minmax(130px,1fr)); gap:12px;
    }
    .product-gallery img {
        width:100%; height:150px; object-fit:cover; border-radius:8px;
        border:3px solid #e0f0e0; transition:0.3s; cursor:pointer;
    }
    .product-gallery img:hover { border-color:#004d00; transform:scale(1.05); }
    .price-big {
        font-size:2rem; font-weight:700; color:#006400; margin:15px 0;
    }
    .old-price { color:#cc0000; text-decoration:line-through; font-size:1.3rem; margin-right:10px; }
    .preorder-badge {
        background:#fff3cd; color:#856404; padding:6px 14px; border-radius:30px;
        font-size:0.85rem; font-weight:600; margin-left:10px;
    }
    .stock-table {
        width:100%; border-collapse:collapse; background:#f9f9f9; border-radius:8px; overflow:hidden;
        margin-top:15px;
    }
    .stock-table th {
        background:#004d00; color:white; padding:12px; text-transform:uppercase; font-size:0.9rem;
    }
    .stock-table td { padding:12px; border-bottom:1px solid #eee; }
    .color-swatch {
        display:inline-block; width:22px; height:22px; border-radius:50%;
        border:2px solid #fff; box-shadow:0 0 5px rgba(0,0,0,0.3); vertical-align:middle; margin-right:8px;
    }

    /* Related Products */
    .related-header {
        color:#004d00; font-size:1.5rem; font-weight:700; text-transform:uppercase;
        border-bottom:3px solid #004d00; padding-bottom:8px; margin:35px 0 20px;
    }
    .related-grid {
        display:grid; grid-template-columns:repeat(auto-fill,minmax(200px,1fr)); gap:18px;
    }
    .related-card {
        background:white; border-radius:10px; overflow:hidden; box-shadow:0 6px 18px rgba(0,0,0,0.1);
        transition:0.3s; text-decoration:none; color:inherit;
    }
    .related-card:hover { transform:translateY(-8px); box-shadow:0 12px 25px rgba(0,77,0,0.15); }
    .related-card img {
        width:100%; height:170px; object-fit:cover;
    }
    .related-info {
        padding:14px;
    }
    .related-name {
        font-weight:600; color:#004d00; margin:0 0 6px; font-size:1rem;
        display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;
    }
    .related-price {
        font-weight:700; color:#006400; font-size:1.2rem;
    }
    .related-old { text-decoration:line-through; color:#cc0000; font-size:0.95rem; margin-right:6px; }
    .related-preorder {
        background:#fff3cd; color:#856404; padding:4px 10px; border-radius:20px; font-size:0.75rem; margin-top:5px; display:inline-block;
    }

    @media (max-width:768px) {
        .modal-grid { grid-template-columns:1fr; gap:25px; }
        .related-grid { grid-template-columns:repeat(2,1fr); }
    }
</style>

<div class="modal-detail">

    <div class="modal-grid">
        <!-- Gallery -->
        <div>
            <h3>Gallery</h3>
            <div class="product-gallery">
                <?php while($ph = $photos->fetch_assoc()): ?>
                    <img src="../image/<?= htmlspecialchars($ph['photoName']) ?>" alt="Product">
                <?php endwhile; ?>
            </div>
        </div>

        <!-- Details -->
        <div>
            <h3>Product Details</h3>
            <p><strong>Name:</strong> <?= htmlspecialchars($p['productName']) ?>
                <?php if($p['preorder']): ?><span class="preorder-badge">PRE-ORDER</span><?php endif; ?>
            </p>

            <div class="price-big">
                <?php if($p['discountedPrice']): ?>
                    <span class="old-price"><?= number_format($p['price']) ?></span>
                <?php endif; ?>
                <?= number_format($p['discountedPrice'] ?: $p['price']) ?> MMK
            </div>

            <p><strong>Description:</strong><br><?= nl2br(htmlspecialchars($p['description'])) ?></p>

            <?php if(!empty($catDisplay)): ?>
            <p><strong>Categories:</strong><br>
                <?php foreach($catDisplay as $c): ?>
                    <span style="background:#e6f7e6;color:#004d00;padding:5px 12px;border-radius:30px;font-size:0.9rem;margin:3px 5px 3px 0;display:inline-block;">
                        <?= $c ?>
                    </span>
                <?php endforeach; ?>
            </p>
            <?php endif; ?>

            <p><strong>Waiting Week:</strong> <?= $p['waitingWeek'] ?> weeks</p>
            <p><strong>Status:</strong>
                <span style="color:<?= $p['preorder'] ? '006400' : '##b8860b' ?>;font-weight:700;">
                    <?= $p['preorder'] ? 'Pre-Order Allowed' : 'Pre-Order Not Allowed' ?>
                </span>
            </p>

            <?php if($stock->num_rows > 0): ?>
            <h3 style="margin:30px 0 10px;">Stock by Color & Size</h3>
            <table class="stock-table">
                <thead><tr><th>Color</th><th>Size</th><th>Qty</th></tr></thead>
                <tbody>
                    <?php while($st = $stock->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <span class="color-swatch" style="background:<?= htmlspecialchars($st['colorCode']) ?>"></span>
                            <?= htmlspecialchars($st['colorName']) ?>
                        </td>
                        <td><?= htmlspecialchars($st['sizeName']) ?></td>
                        <td><strong><?= $st['quantity'] ?></strong></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </div>

    <!-- RELATED PRODUCTS -->
    <?php if($related->num_rows > 0): ?>
    <div class="related-header">Related Products (Recommendations)</div>
    <div class="related-grid">
        <?php while($r = $related->fetch_assoc()): ?>
            <a href="view_product.php?id=<?= $r['productID'] ?>" class="related-card" target="_blank">
                <img src="../image/<?= $r['mainPhoto'] ? htmlspecialchars($r['mainPhoto']) : 'no-image.jpg' ?>" alt="<?= htmlspecialchars($r['productName']) ?>">
                <div class="related-info">
                    <div class="related-name"><?= htmlspecialchars($r['productName']) ?></div>
                    <div>
                        <?php if($r['discountedPrice']): ?>
                            <span class="related-old"><?= number_format($r['price']) ?></span>
                        <?php endif; ?>
                        <span class="related-price"><?= number_format($r['discountedPrice'] ?: $r['price']) ?> MMK</span>
                    </div>
                    <?php if($r['preorder']): ?>
                        <div class="related-preorder">PRE-ORDER</div>
                    <?php endif; ?>
                </div>
            </a>
        <?php endwhile; ?>
    </div>
    <?php else: ?>
        <p style="text-align:center;color:#888;font-style:italic;padding:30px;background:#f9f9f9;border-radius:8px;margin-top:30px;">
            No related products added yet.
        </p>
    <?php endif; ?>

</div>