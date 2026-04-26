<?php
// Query to fetch products for the category and their primary photo
$product_query = "SELECT p.*, ph.photoName 
                  FROM product p 
                  JOIN productxcategory px ON p.productID = px.productID 
                  LEFT JOIN photo ph ON p.productID = ph.productID 
                  WHERE px.categoryID = $category_id 
                  GROUP BY p.productID";

$result_product = $conn->query($product_query);

if($result_product && $result_product->num_rows > 0 ) {
    while ($product = $result_product->fetch_assoc()) { 
        $imagePath = !empty($product['photoName']) ? "../image/" . $product['photoName'] : "../image/placeholder.jpg";
        ?>
        <div class="col-6 col-md-4 col-lg-3">
            <div class="product-card text-center">
                <div class="position-relative mb-3">
                    <a href="specific_product.php?id=<?php echo $product['productID']; ?>">
                        <img src="<?php echo $imagePath; ?>" class="img-fluid w-100" alt="<?php echo $product['productName']; ?>">
                    </a>
                </div>
                <h6 class="mt-2 mb-1"><?php echo htmlspecialchars($product['productName']); ?></h6>
                <div class="d-flex justify-content-center gap-2">
                    <span class="fw-bold">BHAT <?php echo number_format($product['price']); ?></span>
                    <?php if($product['discountedPrice']): ?>
                        <span class="text-muted text-decoration-line-through small">BHAT <?php echo number_format($product['discountedPrice']); ?></span>
                    <?php endif; ?>
                </div>
                <p class="text-muted small"><?php echo htmlspecialchars($product['description']); ?></p>
            </div>
        </div>
        <?php
    }
} else {
    echo "<div class='col-12 text-center'><p class='text-muted'>No products found in this category.</p></div>";
}
?>