<?php
include "../connection/connectdb.php";
if (session_status() === PHP_SESSION_NONE) session_start();
include './layout/login_error_message.php';
$currentPage = "product.php";
include './logInCheck.php';

$query = "SELECT p.*, 
       GROUP_CONCAT(DISTINCT c.categoryName ORDER BY c.categoryID SEPARATOR ', ') AS categories
       FROM product p
       LEFT JOIN productxcategory pxc ON p.productID = pxc.productID
       LEFT JOIN category c ON pxc.categoryID = c.categoryID
       GROUP BY p.productID
       ORDER BY p.postedDate DESC";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <?php include "./layout/header.php"; ?>
    <style>
        body { background-color: #f8f8f8; font-family: 'Arial', sans-serif; color: #333; }
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
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .btn-add {
            background: rgba(255,255,255,0.15);
            border: none;
            color: white;
            padding: 8px 14px;
            border-radius: 0;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .btn-add:hover {
            background: rgba(255,255,255,0.25);
            transform: translateY(-1px);
        }
        .products-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            border-radius: 0;
            overflow: hidden;
        }
        .products-table thead {
            background: #f8f9fa;
            border-bottom: 2px solid #e0f0e0;
        }
        .products-table thead th {
            padding: 16px 15px;
            text-align: left;
            font-weight: 500;
            color: #004d00;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }
        .products-table tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid #e9ecef;
        }
        .products-table tbody tr:hover {
            background-color: #f8fff8;
            box-shadow: 0 2px 8px rgba(0,77,0,0.05);
        }
        .products-table td {
            padding: 16px 15px;
            vertical-align: middle;
            font-size: 1rem;
        }
        .product-name {
            font-weight: 600;
            color: #004d00;
            text-decoration: none;
            transition: color 0.3s ease, text-decoration 0.3s ease;
        }
        .product-name:hover {
            color: #006400;
            text-decoration: underline;
        }
        .price {
            font-weight: 600;
            color: #006400;
        }
        .discounted {
            color: #cc0000;
            text-decoration: line-through;
            margin-right: 8px;
            font-size: 0.9em;
        }
        .status-preorder {
            background: #fff3cd;
            color: #856404;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        .actions a {
            padding: 8px 16px;
            margin-right: 5px;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            text-transform: uppercase;
            border-radius: 0;
            transition: all 0.3s ease;
            display: inline-block;
        }
        .btn-view {
            background: linear-gradient(45deg, #004d00, #006600);
            color: white;
            margin : 10px;
            border-radius : 20px;
        }
        .btn-view:hover {
            background: linear-gradient(45deg, #006600, #008000);
            transform: translateY(-1px);
        }
        .btn-edit {
            background: linear-gradient(45deg, #004d00, #006600);
            color: white;
            margin : 10px;
            border-radius : 20px;
        }
        .btn-edit:hover {
            background: linear-gradient(45deg, #006600, #008000);
            transform: translateY(-1px);
        }
        .btn-delete {
            background: linear-gradient(45deg, #cc0000, #ff4d4d);
            color: white;
            margin : 10px;
            border-radius : 20px;
        }
        .btn-delete:hover {
            background: linear-gradient(45deg, #ff4d4d, #ff6666);
            transform: translateY(-1px);
        }
        .no-items {
            text-align: center;
            padding: 40px;
            color: #666;
            background: #f8fff8;
            border: 1px dashed #e0f0e0;
            font-style: italic;
        }
        .modal {
            display: none; position: fixed; z-index: 1050; left: 0; top: 0; width: 100%; height: 100%;
            background-color: rgba(0,0,0,0.8); overflow: auto; backdrop-filter: blur(5px);
        }
        .modal-content {
            background-color: white; margin: 5% auto; padding: 30px; width: 90%; max-width: 900px;
            border-radius: 8px; box-shadow: 0 20px 40px rgba(0,0,0,0.3); position: relative;
        }
        .close { color: #aaa; float: right; font-size: 32px; font-weight: bold; cursor: pointer; }
        .close:hover { color: #000; }
        .modal-header { border-bottom: 2px solid #e0f0e0; padding-bottom: 15px; margin-bottom: 20px; }
        .modal-header h2 { margin: 0; color: #004d00; text-transform: uppercase; letter-spacing: 1px; }
        .product-gallery { display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 10px; margin: 20px 0; }
        .product-gallery img { width: 100%; height: 140px; object-fit: cover; border-radius: 4px; border: 2px solid #e0f0e0; }
        .stock-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .stock-table th, .stock-table td { padding: 10px; text-align: center; border: 1px solid #ddd; }
        .stock-table th { background: #f8f9fa; color: #004d00; }
        @media (max-width: 768px) {
            .page-header { flex-direction: column; text-align: center; }
            .products-table thead { display: none; }
            .products-table tbody tr {
                display: block;
                margin-bottom: 0px;
                border: 1px solid #e0f0e0;
                padding: 16px;
                background: white;
                box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            }
            .products-table td {
                display: block;
                text-align: right;
                padding: 10px 0;
                position: relative;
                padding-left: 50%;
            }
            .products-table td:before {
                content: attr(data-label);
                position: absolute;
                left: 0;
                width: 50%;
                padding-left: 15px;
                font-weight: 600;
                color: #004d00;
                text-transform: uppercase;
                font-size: 0.8rem;
            }
            .actions { text-align: center; margin-top: 10px; }
        }
    </style>
</head>
<body>
    <?php include "nav.php"; ?>
    <?php if($_SESSION['login'] ?? false): ?>
    <div class="main-content">
        <div class="page-header">
            <h1>Products</h1>
            <div class="header-actions">
                <a href="create_product.php" class="btn-add">+ Add New Product</a>
            </div>
        </div>

        <?php if ($result && $result->num_rows > 0): ?>
        <table class="products-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Product Name</th>
                    <th>Price (MMK)</th>
                    <th>Categories</th>
                    <th>Posted Date</th>
                    <!-- <th>Status</th> -->
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td data-label="ID"><?php echo $row['productID']; ?></td>
                    <td data-label="Name">
                        <a href="view_product.php?id=<?php echo $row['productID']; ?>" class="product-name">
                            <?php echo htmlspecialchars($row['productName']); ?>
                        </a>
                        <?php if($row['preorder'] == 1): ?>
                            <span class="status-preorder">PRE-ORDER ALLOWED</span>
                        <?php endif; ?>
                    </td>
                    <td data-label="Price" class="price">
                        <?php if($row['discountedPrice']): ?>
                            <span class="discounted"><?php echo number_format($row['price']); ?></span>
                            <?php echo number_format($row['discountedPrice']); ?>
                        <?php else: ?>
                            <?php echo number_format($row['price']); ?>
                        <?php endif; ?> MMK
                    </td>
                    <td data-label="Categories">
                        <?php echo $row['categories'] ? htmlspecialchars($row['categories']) : '<em style="color:#999">-</em>'; ?>
                    </td>
                    <td data-label="Posted Date">
                        <?php echo date('M d, Y', strtotime($row['postedDate'])); ?>
                    </td>
                    <!-- <td data-label="Status">
                        <strong style="color: <?php echo $row['preorder'] ? '#b8860b' : '#006400'; ?>">
                            <?php echo $row['preorder'] ? 'Pre-Order' : 'In Stock'; ?>
                        </strong>
                    </td> -->
                    <td data-label="Actions" class="actions">
                        <a href="#" class="btn-view" onclick="openModal(<?php echo $row['productID']; ?>)">View</a>
                        <a href="edit_product.php?id=<?php echo $row['productID']; ?>" class="btn-edit">Edit</a>
                        <a href="delete_product.php?id=<?php echo $row['productID']; ?>" class="btn-delete" 
                           onclick="return confirm('Delete this product permanently?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
            <div class="no-items">No products found.</div>
        <?php endif; ?>
    </div>

    <!-- Modal -->
    <div id="productModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="document.getElementById('productModal').style.display='none'">×</span>
            <div class="modal-header">
                <h2 id="modalTitle">Product Preview</h2>
            </div>
            <div id="modalBody"></div>
        </div>
    </div>

    <script>
        function openModal(id) {
            fetch('product_modal.php?id=' + id)
                .then(r => r.text())
                .then(html => {
                    document.getElementById('modalBody').innerHTML = html;
                    document.getElementById('productModal').style.display = 'block';
                });
        }
        window.onclick = function(e) {
            if (e.target == document.getElementById('productModal')) {
                document.getElementById('productModal').style.display = 'none';
            }
        }
    </script>
    <?php endif; ?>
</body>
</html>