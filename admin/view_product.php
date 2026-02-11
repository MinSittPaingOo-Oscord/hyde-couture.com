<?php
include "../connection/connectdb.php";
if (session_status() === PHP_SESSION_NONE) session_start();
include './layout/login_error_message.php';
$currentPage = "view_product.php";
include './logInCheck.php';

$id = (int)$_GET['id'];
$product = $conn->query("SELECT * FROM product WHERE productID = $id")->fetch_assoc();
if (!$product) {
    header("Location: product.php");
    exit();
}

// Get categories with parent info
$categoriesQuery = "
    SELECT
        c.categoryID,
        c.categoryName,
        c.parentID,
        p.categoryName AS parentName
    FROM category c
    LEFT JOIN category p ON c.parentID = p.categoryID
    JOIN productxcategory pxc ON c.categoryID = pxc.categoryID
    WHERE pxc.productID = $id
    ORDER BY p.categoryName, c.categoryName
";

$categories = $conn->query($categoriesQuery);

$photos = $conn->query("SELECT photoName FROM photo WHERE productID = $id");

$stock = $conn->query("SELECT s.*, sz.sizeName, cl.colorName, cl.colorCode
    FROM stock s
    JOIN size sz ON s.sizeID = sz.sizeID
    JOIN color cl ON s.colorID = cl.colorID
    WHERE s.productID = $id
");

// Determine Standalone or Subcategory
$isStandalone = false;
$standaloneNames = [];
$subcategoryInfo = [];
while ($cat = $categories->fetch_assoc()) {
    if (is_null($cat['parentID'])) {
        $hasChildren = $conn->query("SELECT 1 FROM category WHERE parentID = {$cat['categoryID']} LIMIT 1")->num_rows > 0;
        if (!$hasChildren) {
            $isStandalone = true;
            $standaloneNames[] = $cat['categoryName'];
        }
    } else {
        $subcategoryInfo[] = $cat['categoryName'] . " → " . $cat['parentName'];
    }
}

// === RELATED PRODUCTS ===
// $relatedQuery = "
//     SELECT DISTINCT p.productID, p.productName, p.price, p.discountedPrice, p.preorder,
//            (SELECT photoName FROM photo WHERE productID = p.productID LIMIT 1) AS mainPhoto
//     FROM product p
//     JOIN relatedproduct rp ON (p.productID = rp.productID1 OR p.productID = rp.productID2)
//     WHERE (rp.productID1 = $id OR rp.productID2 = $id) AND p.productID != $id
//     ORDER BY p.postedDate DESC
// ";
$relatedQuery = "
    SELECT DISTINCT p.productID, p.productName, p.price, p.discountedPrice, p.preorder, p.postedDate,
           (SELECT photoName FROM photo WHERE productID = p.productID LIMIT 1) AS mainPhoto
    FROM product p
    JOIN relatedproduct rp ON (p.productID = rp.productID1 OR p.productID = rp.productID2)
    WHERE (rp.productID1 = $id OR rp.productID2 = $id) AND p.productID != $id
    ORDER BY p.postedDate DESC
";
$relatedProducts = $conn->query($relatedQuery);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Product: <?= htmlspecialchars($product['productName']) ?></title>
    <?php include "./layout/header.php"; ?>
    <style>
        body {
            background: #f8f8f8;
            font-family: Arial, sans-serif;
        }

        .page-header {
            background: linear-gradient(135deg, #004d00, #002600);
            color: white;
            padding: 20px 25px;
            margin: 0 0 0px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .page-header h1 {
            margin: 0;
            font-size: 1.6rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-back {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            padding: 10px 16px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-back:hover {
            background: rgba(255, 255, 255, 0.25);
        }

        .detail-section {
            background: white;
            padding: 35px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            max-width: 1200px;
            margin: 0 auto 40px;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
        }

        .product-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 16px;
        }

        .product-gallery img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            border-radius: 8px;
            border: 3px solid #e0f0e0;
            cursor: pointer;
            transition: 0.3s;
        }

        .product-gallery img:hover {
            transform: scale(1.05);
            border-color: #004d00;
        }

        .detail-info h2 {
            color: #004d00;
            font-size: 1.5rem;
            margin: 25px 0 15px;
            text-transform: uppercase;
            border-bottom: 3px solid #004d00;
            padding-bottom: 10px;
            display: inline-block;
        }

        .price {
            font-size: 2.2rem;
            font-weight: 700;
            color: #006400;
            margin: 20px 0;
        }

        .discounted {
            color: #cc0000;
            text-decoration: line-through;
            margin-right: 15px;
            font-size: 1.4rem;
        }

        .status-preorder {
            background: #fff3cd;
            color: #856404;
            padding: 8px 16px;
            border-radius: 30px;
            font-size: 0.9rem;
            font-weight: 600;
            margin-left: 12px;
        }

        .category-badge {
            display: inline-block;
            background: #e6f7e6;
            color: #004d00;
            padding: 7px 16px;
            border-radius: 30px;
            font-size: 0.95rem;
            font-weight: 600;
            margin: 5px 8px 5px 0;
        }

        .category-type-text {
            font-weight: 700;
            font-size: 1.1rem;
        }

        .standalone-highlight {
            color: #004d00;
        }

        .subcategory-highlight {
            color: #1a4099;
        }

        .stock-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: #f9f9f9;
            border-radius: 8px;
            overflow: hidden;
        }

        .stock-table th {
            background: #004d00;
            color: white;
            padding: 15px;
            text-transform: uppercase;
            font-size: 0.9rem;
        }

        .stock-table td {
            padding: 14px 15px;
            border-bottom: 1px solid #eee;
        }

        .color-swatch {
            display: inline-block;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            border: 2px solid #fff;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
            vertical-align: middle;
            margin-right: 10px;
        }

        /* === RELATED PRODUCTS SECTION === */
        .related-section {
            background: white;
            padding: 40px 35px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin: 40px auto;
            max-width: 1200px;
        }

        .related-header {
            color: #004d00;
            font-size: 1.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            border-bottom: 4px solid #004d00;
            padding-bottom: 12px;
            margin-bottom: 30px;
        }

        .related-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 25px;
        }

        .related-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            transition: 0.4s;
            text-decoration: none;
            color: inherit;
        }

        .related-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 16px 35px rgba(0, 77, 0, 0.15);
        }

        .related-card img {
            width: 100%;
            height: 220px;
            object-fit: cover;
        }

        .related-info {
            padding: 18px;
        }

        .related-name {
            font-size: 1.1rem;
            font-weight: 600;
            color: #004d00;
            margin: 0 0 10px 0;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .related-price {
            font-size: 1.4rem;
            font-weight: 700;
            color: #006400;
        }

        .related-discounted {
            text-decoration: line-through;
            color: #cc0000;
            font-size: 1rem;
            margin-right: 8px;
        }

        .related-preorder {
            display: inline-block;
            background: #fff3cd;
            color: #856404;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            margin-top: 8px;
        }

        /* Lightbox */
        .lightbox {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.92);
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .lightbox.active {
            display: flex;
        }

        .lightbox img {
            max-width: 90%;
            max-height: 90%;
            border-radius: 8px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.6);
            animation: zoomIn 0.4s ease;
        }

        .lightbox-close {
            position: absolute;
            top: 30px;
            right: 40px;
            color: white;
            font-size: 50px;
            font-weight: 300;
            cursor: pointer;
            opacity: 0.8;
        }

        .lightbox-close:hover {
            opacity: 1;
            transform: scale(1.2);
        }

        .lightbox-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.1);
            color: white;
            width: 50px;
            height: 80px;
            font-size: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            user-select: none;
            border-radius: 8px;
        }

        .lightbox-nav:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .prev {
            left: 30px;
        }

        .next {
            right: 30px;
        }

        @keyframes zoomIn {
            from {
                transform: scale(0.7);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        @media (max-width:768px) {
            .detail-grid {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .product-gallery img {
                height: 160px;
            }

            .price {
                font-size: 1.9rem;
            }

            .related-grid {
                grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
                gap: 20px;
            }

            .lightbox img {
                max-width: 95%;
                max-height: 80%;
            }

            .lightbox-close {
                font-size: 40px;
                top: 20px;
                right: 20px;
            }

            .lightbox-nav {
                width: 40px;
                height: 60px;
                font-size: 30px;
            }

            .prev {
                left: 10px;
            }

            .next {
                right: 10px;
            }
        }
    </style>
</head>

<body>
    <?php include "nav.php"; ?>
    <?php if ($_SESSION['login'] ?? false): ?>
        <div class="main-content">

            <div class="page-header">
                <h1>Product Details</h1>
                <div class="header-actions">
                    <a href="product.php" class="btn-back">Back to Products</a>
                </div>
            </div>

            <div class="detail-section">
                <div class="detail-grid">
                    <!-- Gallery -->
                    <div>
                        <h2>Gallery</h2>
                        <div class="product-gallery">
                            <?php if ($photos->num_rows > 0): ?>
                                <?php $index = 0;
                                while ($ph = $photos->fetch_assoc()): ?>
                                    <img src="../image/<?= htmlspecialchars($ph['photoName']) ?>"
                                        alt="Product image <?= $index + 1 ?>"
                                        class="gallery-img"
                                        data-index="<?= $index++ ?>">
                                <?php endwhile; ?>
                            <?php else: ?>
                                <p style="color:#888;font-style:italic;grid-column:1/-1;">No images available.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Product Info -->
                    <div class="detail-info">
                        <h2>
                            <?= htmlspecialchars($product['productName']) ?>
                            <?php if ($product['preorder']): ?>
                                <span class="status-preorder">PRE-ORDER</span>
                            <?php endif; ?>
                        </h2>

                        <div class="price">
                            <?php if ($product['discountedPrice']): ?>
                                <span class="discounted"><?= number_format($product['price']) ?> MMK</span>
                                <?= number_format($product['discountedPrice']) ?> MMK
                            <?php else: ?>
                                <?= number_format($product['price']) ?> MMK
                            <?php endif; ?>
                        </div>

                        <p><strong>Description:</strong><br><?= nl2br(htmlspecialchars($product['description'])) ?></p>

                        <p><strong>Category Type:</strong>
                            <?php if ($isStandalone && !empty($standaloneNames)): ?>
                                <span class="category-type-text standalone-highlight">Standalone Category</span>
                            <?php else: ?>
                                <span class="category-type-text subcategory-highlight">Subcategory</span>
                            <?php endif; ?>
                        </p>

                        <p><strong>Categories:</strong><br>
                            <?php if ($isStandalone && !empty($standaloneNames)): ?>
                                <?php foreach ($standaloneNames as $name): ?>
                                    <span class="category-badge"><?= htmlspecialchars($name) ?></span>
                                <?php endforeach; ?>
                            <?php elseif (!empty($subcategoryInfo)): ?>
                                <?php foreach ($subcategoryInfo as $info): ?>
                                    <span class="category-badge"><?= htmlspecialchars($info) ?></span>
                                <?php endforeach; // Fixed: Removed extra 'foreach' 
                                ?>
                            <?php else: // Fixed: Added required colon 
                            ?>
                                <em>None</em>
                            <?php endif; ?>
                        </p>

                        <p><strong>Waiting Week:</strong> <?= $product['waitingWeek'] ?> weeks</p>
                        <p><strong>Status:</strong>
                            <span style="color: <?= $product['preorder'] ? '#b8860b' : '#006400' ?>; font-weight:700;">
                                <?= $product['preorder'] ? 'Pre-Order Only' : 'Ready Stock' ?>
                            </span>
                        </p>
                        <p><strong>Posted Date:</strong> <?= date('F d, Y', strtotime($product['postedDate'])) ?></p>

                        <h2>Stock Details</h2>
                        <?php if ($stock->num_rows > 0): ?>
                            <table class="stock-table">
                                <thead>
                                    <tr>
                                        <th>Color</th>
                                        <th>Size</th>
                                        <th>Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($st = $stock->fetch_assoc()): ?>
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
                        <?php else: ?>
                            <p style="color:#666;font-style:italic;padding:20px;background:#f9f9f9;border-radius:8px;">
                                No stock information available.
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- RELATED PRODUCTS SECTION -->
            <div class="related-section">
                <h2 class="related-header">Related Products (Recommendations)</h2>

                <?php if ($relatedProducts->num_rows > 0): ?>
                    <div class="related-grid">
                        <?php while ($rel = $relatedProducts->fetch_assoc()): ?>
                            <a href="view_product.php?id=<?= $rel['productID'] ?>" class="related-card">
                                <img src="../image/<?= $rel['mainPhoto'] ? htmlspecialchars($rel['mainPhoto']) : 'no-image.jpg' ?>"
                                    alt="<?= htmlspecialchars($rel['productName']) ?>">
                                <div class="related-info">
                                    <h3 class="related-name"><?= htmlspecialchars($rel['productName']) ?></h3>
                                    <div>
                                        <?php if ($rel['discountedPrice']): ?>
                                            <span class="related-discounted"><?= number_format($rel['price']) ?></span>
                                        <?php endif; ?>
                                        <span class="related-price">
                                            <?= number_format($rel['discountedPrice'] ?: $rel['price']) ?> MMK
                                        </span>
                                    </div>
                                    <?php if ($rel['preorder']): ?>
                                        <div class="related-preorder">PRE-ORDER</div>
                                    <?php endif; ?>
                                </div>
                            </a>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <p style="text-align:center; color:#666; font-style:italic; padding:40px; background:#f9f9f9; border-radius:8px; font-size:1.1rem;">
                        No related products have been added yet.
                    </p>
                <?php endif; ?>
            </div>

        </div>

        <!-- Fullscreen Lightbox -->
        <div id="lightbox" class="lightbox">
            <span class="lightbox-close">×</span>
            <div class="lightbox-nav prev">&lt;</div>
            <div class="lightbox-nav next">&gt;</div>
            <img id="lightbox-img" src="" alt="Full screen view">
        </div>
    <?php endif; ?>

    <script>
        const imgs = document.querySelectorAll('.gallery-img');
        const lightbox = document.getElementById('lightbox');
        const lightboxImg = document.getElementById('lightbox-img');
        const closeBtn = document.querySelector('.lightbox-close');
        const prevBtn = document.querySelector('.prev');
        const nextBtn = document.querySelector('.next');
        let currentIndex = 0;

        imgs.forEach((img, idx) => {
            img.addEventListener('click', () => {
                currentIndex = idx;
                lightbox.classList.add('active');
                lightboxImg.src = img.src;
            });
        });

        function closeLightbox() {
            lightbox.classList.remove('active');
            setTimeout(() => lightboxImg.src = '', 400);
        }
        closeBtn.onclick = closeLightbox;
        lightbox.onclick = (e) => {
            if (e.target === lightbox || e.target === lightboxImg) closeLightbox();
        };
        document.addEventListener('keydown', (e) => {
            if (!lightbox.classList.contains('active')) return;
            if (e.key === 'Escape') closeLightbox();
            if (e.key === 'ArrowLeft') prevImage();
            if (e.key === 'ArrowRight') nextImage();
        });

        prevBtn.onclick = () => prevImage();
        nextBtn.onclick = () => nextImage();

        function prevImage() {
            currentIndex = (currentIndex - 1 + imgs.length) % imgs.length;
            lightboxImg.src = imgs[currentIndex].src;
        }

        function nextImage() {
            currentIndex = (currentIndex + 1) % imgs.length;
            lightboxImg.src = imgs[currentIndex].src;
        }
    </script>
</body>

</html>