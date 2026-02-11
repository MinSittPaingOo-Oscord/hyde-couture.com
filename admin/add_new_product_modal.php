<?php
//KST
// This file is included in product.php, so $conn is already available.

// Fetch data for dropdowns
$query_categories = "SELECT * FROM category ORDER BY parentID ASC, categoryName ASC";
$result_categories = $conn->query($query_categories);

$query_colors = "SELECT * FROM color ORDER BY colorName ASC";
$result_colors = $conn->query($query_colors);

$query_sizes = "SELECT * FROM size ORDER BY sizeID ASC";
$result_sizes = $conn->query($query_sizes);
?>

<div id="addProductModal" class="modal">

  <div class="modal-content">
    <span class="close-btn">&times;</span>
    <h2 style="color: #004d00; border-bottom: 2px solid #e0f0e0; padding-bottom: 10px; margin-bottom: 20px;">Create New Product</h2>
    
    <form action="create_product.php" method="POST" class="add-form">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            
            <div class="form-group">
                <label for="productName">Product Name *</label>
                <input type="text" id="productName" name="productName" required class="form-input">
            </div>

            <div class="form-group">
                <label for="basePrice">Base Price (Raw) *</label>
                <input type="number" id="basePrice" name="basePrice" required class="form-input" min="0">
            </div>

            <div class="form-group">
                <label for="discountedPrice">Discounted Price (Raw)</label>
                <input type="number" id="discountedPrice" name="discountedPrice" class="form-input" min="0" placeholder="Optional">
            </div>

            <div class="form-group">
                <label for="waitingWeeks">Waiting Weeks (Delivery) *</label>
                <input type="number" id="waitingWeeks" name="waitingWeeks" required class="form-input" min="1" value="2">
            </div>
        </div>

        <div class="form-group" style="margin-top: 15px;">
            <label for="description">Description *</label>
            <textarea id="description" name="description" rows="4" required class="form-input"></textarea>
        </div>

        <div style="display: flex; gap: 20px; align-items: center; margin-bottom: 30px;">
            <div class="form-group" style="flex-grow: 1; margin-bottom: 0;">
                <label for="categoryID">Category *</label>
                <select id="categoryID" name="categoryID" required class="form-select">
                    <option value="">Select Category</option>
                    <?php 
                    $categories = [];
                    while ($row = $result_categories->fetch_assoc()) {
                        $categories[] = $row;
                    }

                    // Group categories by parent for display (optional, but good practice)
                    $parent_categories = array_filter($categories, fn($c) => $c['parentID'] === NULL);
                    $child_categories = array_filter($categories, fn($c) => $c['parentID'] !== NULL);
                    $child_map = [];
                    foreach ($child_categories as $child) {
                        $child_map[$child['parentID']][] = $child;
                    }

                    foreach ($parent_categories as $parent) {
                        echo "<optgroup label='".htmlspecialchars($parent['categoryName'])."'>";
                        echo "<option value='".$parent['categoryID']."'>".$parent['categoryName']." (Parent)</option>";
                        if (isset($child_map[$parent['categoryID']])) {
                            foreach ($child_map[$parent['categoryID']] as $child) {
                                echo "<option value='".$child['categoryID']."'>-- ".htmlspecialchars($child['categoryName'])."</option>";
                            }
                        }
                        echo "</optgroup>";
                    }
                    // Handle categories that are purely top-level with no children structure (like 'Outlet')
                    foreach ($categories as $cat) {
                        if($cat['parentID'] === NULL && !isset($parent_categories[$cat['categoryID']])) {
                             echo "<option value='".$cat['categoryID']."'>".htmlspecialchars($cat['categoryName'])."</option>";
                        }
                    }

                    ?>
                </select>
            </div>
            
            <div class="form-group" style="margin-bottom: 0; padding-top: 25px;">
                <input type="checkbox" id="preorder" name="preorder" value="1" style="width: auto;">
                <label for="preorder" style="display: inline; color: #333; font-weight: 400; text-transform: none;">Mark as Preorder</label>
            </div>
        </div>

        <h3 style="color: #004d00; margin-top: 30px; border-top: 1px solid #e0f0e0; padding-top: 20px;">Initial Stock Setup</h3>
        <p style="margin-bottom: 20px; font-size: 0.9rem;">Define the first color/size variant and its initial quantity. You can add more later in Stock Management.</p>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label for="colorID">Color *</label>
                <select id="colorID" name="colorID" required class="form-select">
                    <option value="">Select Color</option>
                    <?php while ($row = $result_colors->fetch_assoc()): ?>
                        <option value="<?= $row['colorID'] ?>"><?= htmlspecialchars($row['colorName']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="sizeID">Size *</label>
                <select id="sizeID" name="sizeID" required class="form-select">
                    <option value="">Select Size</option>
                    <?php while ($row = $result_sizes->fetch_assoc()): ?>
                        <option value="<?= $row['sizeID'] ?>"><?= htmlspecialchars($row['sizeName']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="initialStock">Initial Stock *</label>
                <input type="number" id="initialStock" name="initialStock" required class="form-input" min="1" value="10">
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 30px; border-top: 1px solid #e0f0e0; padding-top: 20px;">
            <button type="button" class="btn-submit" onclick="document.getElementById('addProductModal').style.display='none'" 
                style="background: #ccc; color: #333; width: 150px;">Cancel</button>
            <button type="submit" name="save_product" class="btn-submit" style="width: 200px;">Save Product</button>
        </div>
    </form>

  </div>
</div>