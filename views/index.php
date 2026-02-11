<?php
include '../connection/connectdb.php';
include '../layout/nav.php';

$category_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

include './home_product_display.php';

include '../layout/footer.php'; 

?>