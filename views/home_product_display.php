<?php

if ($category_id > 0) {
    $product_query = "SELECT p.* FROM product p JOIN productxcategory px ON p.productID = px.productID WHERE px.categoryID = ".$category_id;
}
else{
    $product_query = "SELECT p.* FROM product p";
}

$stmt = $conn->prepare($product_query);
$stmt->execute();
$result_product = $stmt->get_result();
  
        if($result_product && $result_product->num_rows > 0 ){
            while ($product = $result_product->fetch_assoc()) { 
                echo "<div>";
                echo "<h3>".htmlspecialchars($product['productName'])."</h3>";
                echo "<p>".number_format($product['price'])."MMK</p>";
                echo "</div>";
             } 
        }
        else{
            echo "No Product Available yet for this category";
        }
    ?>
    <!-- Video Banner -->

