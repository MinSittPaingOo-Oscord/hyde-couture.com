<?php
include "../connection/connectdb.php";
if (session_status() === PHP_SESSION_NONE) session_start();
include './layout/login_error_message.php';
$currentPage = "create_product.php";
include './logInCheck.php';

// Handle Create Logic
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $conn->real_escape_string($_POST['productName']);
    $price = (float)$_POST['price'];
    $discounted = !empty($_POST['discountedPrice']) ? (float)$_POST['discountedPrice'] : null;
    $desc = $conn->real_escape_string($_POST['description']);
    $week = (int)$_POST['waitingWeek'];
    $preorder = isset($_POST['preorder']) ? 1 : 0;
    $categories = $_POST['categories'] ?? [];
    $related = $_POST['related_products'] ?? [];

    $conn->query("INSERT INTO product 
        (productName, price, discountedPrice, postedDate, description, waitingWeek, preorder)
        VALUES ('$name', $price, " . ($discounted ? $discounted : 'NULL') . ", CURDATE(), '$desc', $week, $preorder)");
    $productID = $conn->insert_id;

    foreach ($categories as $catID) {
        $catID = (int)$catID;
        $conn->query("INSERT INTO productxcategory (productID, categoryID) VALUES ($productID, $catID)");
    }

    foreach ($related as $relatedID) {
        $relatedID = (int)$relatedID;
        if ($relatedID != $productID) {
            $conn->query("INSERT IGNORE INTO relatedproduct (productID1, productID2) 
                         VALUES ($productID, $relatedID), ($relatedID, $productID)");
        }
    }

    if (!empty($_FILES['photos']['name'][0])) {
        foreach ($_FILES['photos']['tmp_name'] as $key => $tmp_name) {
            if ($_FILES['photos']['error'][$key] !== UPLOAD_ERR_OK) continue;
            $ext = pathinfo($_FILES['photos']['name'][$key], PATHINFO_EXTENSION);
            $filename = "p{$productID}_i" . uniqid() . ".$ext";
            $dest = "../image/$filename";
            if (move_uploaded_file($tmp_name, $dest)) {
                $conn->query("INSERT INTO photo (photoName, productID) VALUES ('$filename', $productID)");
            }
        }
    }

    header("Location: product.php?success=1");
    exit();
}

// All products for related section
$allProducts = $conn->query("SELECT productID, productName FROM product ORDER BY postedDate DESC");

// Categories (unchanged)
$standalone = $conn->query("
    SELECT c.* FROM category c
    WHERE c.parentID IS NULL
      AND NOT EXISTS (SELECT 1 FROM category child WHERE child.parentID = c.categoryID)
    ORDER BY c.categoryName
");

$subcategories = $conn->query("
    SELECT c.*, p.categoryName AS parentName
    FROM category c
    JOIN category p ON c.parentID = p.categoryID
    WHERE c.parentID IS NOT NULL
    ORDER BY p.categoryName, c.categoryName
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Create Product</title>
<?php include "./layout/header.php"; ?>
<style>
    .admin-navbar {width:100%;background:linear-gradient(45deg,#004d00,#006600);color:white;padding:15px 30px;display:flex;justify-content:space-between;align-items:center;box-shadow:0 2px 4px rgba(0,0,0,0.2);position:fixed;top:0;left:0;z-index:1000;}
    .add-form {background:white;padding:30px;box-shadow:0 4px 12px rgba(0,0,0,0.08);border-radius:8px;}
    .form-grid {display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:25px;}
    .form-group label {display:block;margin-bottom:8px;font-weight:600;color:#004d00;text-transform:uppercase;font-size:0.9rem;}
    .form-input,.form-textarea {width:100%;padding:12px 15px;border:1px solid #ddd;background:#f8fff8;border-radius:4px;}
    .checkbox-grid {display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:12px;margin-top:10px;}
    .group-header {font-weight:700;color:#004d00;margin:20px 0 8px 0;padding-bottom:6px;border-bottom:2px solid #004d00;text-transform:uppercase;font-size:1rem;}
    .photo-preview {display:grid;grid-template-columns:repeat(auto-fill,minmax(100px,1fr));gap:10px;margin-top:10px;}
    .photo-preview img {width:100%;height:100px;object-fit:cover;border-radius:4px;border:2px solid #e0f0e0;}
    .btn-submit {background:linear-gradient(45deg,#004d00,#006600);color:white;border:none;padding:16px;width:100%;font-size:1.1rem;font-weight:600;text-transform:uppercase;letter-spacing:1px;cursor:pointer;transition:background .3s;margin-top:30px;border-radius:6px;}
    .btn-submit:hover {background:linear-gradient(45deg,#006600,#008000);}
    .page-header {background:linear-gradient(135deg,#004d00,#002600);color:white;padding:20px 25px;margin:0 0 0px 0;border-radius:8px 8px 0 0;}
    .page-header h1 {font-size:1.6rem;font-weight:600;text-transform:uppercase;letter-spacing:1px;margin:0;}
    .tab-links {display:flex;gap:10px;margin-bottom:15px;}
    .tab-link {background:#f0f0f0;border:none;padding:10px 24px;cursor:pointer;font-weight:600;color:#004d00;transition:all .2s;border-radius:4px;}
    .tab-link.active {background:#004d00;color:white;}
    .tab-content {display:none;}
    .tab-content.active {display:block;}

    /* YOUR ORIGINAL BEAUTIFUL CATEGORY CHECKBOX STYLE - RESTORED */
    .checkbox-grid label {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 14px;
        background: #f8fff8;
        border: 2px solid #e0f0e0;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.95rem;
    }
    .checkbox-grid label:hover {
        background: #e6f7e6;
        border-color: #004d00;
        transform: translateY(-1px);
    }
    .checkbox-grid input[type="checkbox"]:checked + span,
    .checkbox-grid label input:checked ~ * {
        font-weight: 600;
        color: #004d00;
    }

    /* RELATED PRODUCTS SECTION - NEW & CLEAN */
    .related-section {
        grid-column: 1 / -1;
        background: #f8fff8;
        padding: 25px;
        border-radius: 8px;
        border: 3px solid #004d00;
        margin-top: 40px;
    }
    .related-header {
        font-size: 1.4rem;
        font-weight: 700;
        color: #004d00;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        border-bottom: 3px solid #004d00;
        padding-bottom: 10px;
        margin-bottom: 15px;
    }
    .related-search {
        width: 100%;
        padding: 14px 16px;
        border: 2px solid #ddd;
        border-radius: 6px;
        font-size: 1rem;
        margin-bottom: 15px;
    }
    .related-list {
        max-height: 420px;
        overflow-y: auto;
        border: 1px solid #ddd;
        border-radius: 6px;
        background: white;
        padding: 10px;
    }
    .related-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px;
        border-bottom: 1px solid #eee;
        font-size: 0.98rem;
    }
    .related-item:last-child { border-bottom: none; }
    .related-item label {
        margin: 0;
        cursor: pointer;
        width: 100%;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .related-item span.id {
        color: #888;
        font-size: 0.85rem;
        margin-left: auto;
    }
</style>
</head>
<body>
<?php include "nav.php"; ?>
<div class="main-content">
<div class="page-header">
<h1>Create Product</h1>
</div>

<form method="POST" enctype="multipart/form-data" class="add-form">
<div class="form-grid">

    <!-- Left Column -->
    <div>
        <div class="form-group">
            <label>Product Name</label>
            <input type="text" name="productName" class="form-input" required>
        </div>
        <div class="form-group">
            <label>Price (MMK)</label>
            <input type="number" name="price" class="form-input" required>
        </div>
        <div class="form-group">
            <label>Discounted Price (Optional)</label>
            <input type="number" name="discountedPrice" class="form-input">
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-textarea" rows="5" required></textarea>
        </div>
        <div class="form-group">
            <label>Waiting Week</label>
            <input type="number" name="waitingWeek" value="0" class="form-input" required>
        </div>
        <div class="form-group">
            <label><input type="checkbox" name="preorder" value="1"> Pre-Order Item</label>
        </div>
    </div>

    <!-- Right Column -->
    <div>
        <div class="form-group">
            <label>Categories (Select multiple)</label>
            <div class="tab-links">
                <button type="button" class="tab-link active" onclick="openTab('standalone')">Standalone Categories</button>
                <button type="button" class="tab-link" onclick="openTab('sub')">Subcategories</button>
            </div>

            <div id="standalone" class="tab-content active">
                <div class="checkbox-grid">
                    <?php while ($cat = $standalone->fetch_assoc()): ?>
                    <label>
                        <input type="checkbox" name="categories[]" value="<?= $cat['categoryID'] ?>" class="cat-checkbox standalone-cat">
                        <?= htmlspecialchars($cat['categoryName']) ?>
                    </label>
                    <?php endwhile; ?>
                </div>
            </div>

            <div id="sub" class="tab-content">
                <?php
                $currentParent = '';
                while ($cat = $subcategories->fetch_assoc()):
                    if ($currentParent !== $cat['parentName']):
                        if ($currentParent !== '') echo '</div>';
                        $currentParent = $cat['parentName'];
                        echo '<h4 class="group-header">' . htmlspecialchars($currentParent) . '</h4>';
                        echo '<div class="checkbox-grid">';
                    endif;
                ?>
                <label>
                    <input type="checkbox" name="categories[]" value="<?= $cat['categoryID'] ?>" class="cat-checkbox sub-cat">
                    <?= htmlspecialchars($cat['categoryName']) ?>
                </label>
                <?php endwhile; if ($currentParent !== '') echo '</div>'; ?>
            </div>
        </div>

        <div class="form-group">
            <label>Product Photos (Multiple)</label>
            <input type="file" name="photos[]" multiple accept="image/*" required>
            <div class="photo-preview" id="preview"></div>
        </div>
    </div>
</div>

<!-- RELATED PRODUCTS SECTION -->
<div class="related-section">
    <div class="related-header">RELATED PRODUCTS (RECOMMENDATIONS)</div>
    <p style="color:#666;margin:10px 0 20px;font-style:italic;">
        Select items to recommend alongside this product.
    </p>

    <input type="text" id="relatedSearch" class="related-search" placeholder="Search products by name..." onkeyup="filterRelated()">

    <div class="related-list" id="relatedList">
        <?php while($prod = $allProducts->fetch_assoc()): ?>
        <div class="related-item">
            <label>
                <input type="checkbox" name="related_products[]" value="<?= $prod['productID'] ?>">
                <?= htmlspecialchars($prod['productName']) ?>
                <span class="id">(ID:<?= $prod['productID'] ?>)</span>
            </label>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<button type="submit" class="btn-submit">CREATE PRODUCT</button>
</form>
</div>

<script>
// Image preview
document.querySelector('input[name="photos[]"]').onchange = function(e) {
    const preview = document.getElementById('preview');
    preview.innerHTML = '';
    [...this.files].forEach(file => {
        const div = document.createElement('div');
        const img = document.createElement('img');
        img.src = URL.createObjectURL(file);
        div.appendChild(img);
        preview.appendChild(div);
    });
};

// Tab switch
function openTab(tabName) {
    document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
    document.getElementById(tabName).classList.add('active');
    document.querySelectorAll('.tab-link').forEach(l => l.classList.remove('active'));
    event.target.classList.add('active');
}

// Search related products
function filterRelated() {
    const input = document.getElementById("relatedSearch").value.toLowerCase();
    const items = document.querySelectorAll('.related-item');
    items.forEach(item => {
        const text = item.textContent.toLowerCase();
        item.style.display = text.includes(input) ? "" : "none";
    });
}

// Mutually exclusive categories (your original logic)
document.addEventListener('change', function(e) {
    if (!e.target.matches('.cat-checkbox')) return;
    if (!e.target.checked) return;

    const isStandalone = e.target.classList.contains('standalone-cat');
    const isSub = e.target.classList.contains('sub-cat');
    const hasStandalone = [...document.querySelectorAll('.standalone-cat')].some(cb => cb.checked);
    const hasSub = [...document.querySelectorAll('.sub-cat')].some(cb => cb.checked);

    if (isStandalone && hasSub) {
        if (confirm('Are you sure? This will remove all Subcategory selections.')) {
            document.querySelectorAll('.sub-cat').forEach(cb => cb.checked = false);
        } else {
            e.target.checked = false;
        }
    } else if (isSub && hasStandalone) {
        if (confirm('Are you sure? This will remove all Standalone selections.')) {
            document.querySelectorAll('.standalone-cat').forEach(cb => cb.checked = false);
        } else {
            e.target.checked = false;
        }
    }
});
</script>
</body>
</html>