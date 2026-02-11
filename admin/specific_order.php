<?php 

include "../connection/connectdb.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include './layout/login_error_message.php';
$currentPage = "specific_order.php";
include './logInCheck.php'; 

$orderID = $_GET['orderID'];

$query = "SELECT * FROM orderr JOIN account ON orderr.accountID = account.accountID JOIN paymenttype ON orderr.paymentType = paymenttype.paymentTypeID WHERE orderr.orderID = $orderID";
$result = $conn->query($query);

$query_paymentSlip = "SELECT * FROM paymentslip JOIN photo ON paymentslip.paymentSlip = photo.photoID WHERE paymentslip.orderID = $orderID";
$result_paymentSlip = $conn->query($query_paymentSlip);

$query_payment_status = "SELECT * FROM paymentstatus";
$result_payment_status = $conn->query($query_payment_status);

$query_order_status = "SELECT * FROM orderstatus";
$result_order_status = $conn->query($query_order_status);

$query_tracking_status = "SELECT * FROM trackingstatus";
$result_tracking_status = $conn->query($query_tracking_status);

$query_items = "SELECT * FROM orderitem JOIN color ON orderitem.color = color.colorID JOIN size ON orderitem.size = size.sizeID JOIN product ON orderitem.productID = product.productID WHERE orderitem.orderID=".$orderID;
$result_items = $conn->query($query_items);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product</title>
    <?php include "./layout/header.php"; ?>
    <style>
        body {
            background-color: #f4f7f6;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            line-height: 1.6;
        }
        .rolex-container {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 98, 59, 0.1);
            padding: 30px;
            max-width: 100%;
        }
        .section-header {
            font-size: 1.4em;
            color: #00623b;
            border-bottom: 2px solid #00623b;
            padding-bottom: 10px;
            margin-bottom: 20px !important;
            margin-top: 20px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 15px;
        }
        .info-card {
            background-color: #f9fcfb;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 15px;
            transition: box-shadow 0.3s ease;
        }
        .info-card:hover {
            box-shadow: 0 2px 10px rgba(0, 98, 59, 0.15);
        }
        .info-label {
            font-weight: bold;
            color: #00623b;
            display: inline-block;
            min-width: 180px;
            margin-bottom: 8px;
        }
        .info-value {
            color: #555;
            display: inline-block;
        }
        form {
            margin-top: 30px;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        select {
            padding: 12px;
            border: 1px solid #00623b;
            border-radius: 6px;
            background-color: #fff;
            color: #333;
            font-size: 1em;
            cursor: pointer;
            transition: border-color 0.3s ease;
        }
        select:focus {
            border-color: #00594f;
            outline: none;
        }
        button {
            padding: 12px 20px;
            background-color: #00623b;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 1em;
            cursor: pointer;
            transition: background-color 0.3s ease;
            align-self: flex-start;
        }
        button:hover {
            background-color: #00594f;
        }
        .payment-slips {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 10px;
        }
        .payment-slips img {
            width: 200px;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .payment-slips img:hover {
            transform: scale(1.05);
        }
        .no-slip {
            color: #888;
            font-style: italic;
        }
        .order-items-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 15px;
            margin-top: 20px;
        }
        .order-items-table th {
            background-color: #00623b;
            color: #fff;
            padding: 14px;
            text-align: left;
            font-weight: 600;
            border-radius: 0px 0px 0 0;
        }
        .order-items-table td {
            background-color: #f9fcfb;
            padding: 16px;
            border: 1px solid #e0e0e0;
            border-style: solid none;
            vertical-align: top;
        }
        .order-items-table tr td:first-child {
            border-left-style: solid;
            border-top-left-radius: 8px;
            border-bottom-left-radius: 8px;
        }
        .order-items-table tr td:last-child {
            border-right-style: solid;
            border-top-right-radius: 8px;
            border-bottom-right-radius: 8px;
        }
        .order-item-image {
            width: 270px;
            height: 270px;
            object-fit: cover;
            border-radius: 8px;
            display: block;
            margin: 0 auto;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .item-detail-row {
            display: block;
            margin-bottom: 10px;
        }
        .item-detail-row:last-child {
            margin-bottom: 0;
        }
        .item-detail-label {
            font-weight: bold;
            color: #00623b;
            display: inline-block;
            width: 160px;
        }
        .item-detail-value {
            color: #555;
        }
        .map-link {
            color: #00623b;
            text-decoration: underline;
            font-weight: 500;
        }
        .map-link:hover {
            color: #00594f;
        }
        .add-item-btn { padding: 12px 20px; background-color: #00623b; color: #fff; border: none; border-radius: 6px; font-size: 1em; cursor: pointer; text-decoration: none; transition: background-color 0.3s ease; } .add-item-btn:hover { background-color: #00594f; }
        
       
        @media (max-width: 1024px) {
            .rolex-container {
                padding: 20px;
            }
            .info-grid {
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            }
            .order-items-table {
                font-size: 0.9em;
            }
            .order-item-image {
                width: 150px;
                height: 150px;
            }
            .item-detail-label {
                width: 140px;
            }
        }
        @media (max-width: 768px) {
            .info-grid {
                grid-template-columns: 1fr;
            }
            button {
                width: 100%;
                align-self: stretch;
            }
            .payment-slips {
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            }
            .order-items-table {
                display: block;
                overflow-x: auto;
            }
            .order-items-table thead {
                display: none;
            }
            .order-items-table tr {
                display: block;
                margin-bottom: 20px;
                border: 0px solid #e0e0e0;
                border-radius: 0px;
                padding: 15px;
                background-color: #f9fcfb;
            }
            .order-items-table td {
                display: block;
                text-align: center;
                position: relative;
                padding: 10px 0;
                border: none;
            }
            .order-items-table td.image-cell {
                padding: 0 0 15px 0;
            }
            .order-item-image {
                width: 100%;
                max-width: 200px;
                height: 200px;
            }
            .item-detail-row {
                text-align: left;
                padding: 8px 0;
            }
            .item-detail-label {
                display: block;
                width: auto;
                margin-bottom: 4px;
                font-size: 0.95em;
            }
            .item-detail-value {
                display: block;
            }
        }

        /* === FIX FOR ORDER ITEMS ON MOBILE === */
            @media (max-width: 768px) {
                .order-items-table {
                    width: 100%;
                    border-spacing: 0;
                    display: flex;
                    flex-direction: column;
                    gap: 20px;
                }

                .order-items-table tbody, 
                .order-items-table tr, 
                .order-items-table td {
                    display: block;
                    width: 100%;
                }

                .order-items-table tr {
                    border: 1px solid #ddd;
                    border-radius: 10px;
                    padding: 10px;
                    background: #fff;
                }

                .order-items-table td {
                    text-align: left;
                    border: none;
                    padding: 6px 0;
                }

                .order-item-image {
                    width: 100%;
                    height: auto;
                    margin-bottom: 10px;
                }
            }

            /* === FIX FOR PAYMENT SLIP SPACING ON DESKTOP === */
            .payment-slips {
                display: flex;
                flex-wrap: wrap;
                justify-content: flex-start;
                align-items: center;
                gap: 12px;
            }

            .payment-slips img {
                width: 200px;
                height: auto;
                border-radius: 8px;
                object-fit: cover;
            }

        
    </style>
</head>
<body>
    <?php
        include "nav.php";
        $login = $_SESSION['login'] ?? false;  
        if($login == true) {
        echo "<div class='main-content'>";

          

            if (isset($_GET['error']) || isset($error)) {
                $errorCode = $_GET['error'] ?? $error;
                $errorMessages = [
                    'no_order_id' => 'No order ID provided. Please select or create an order first.',
                    'invalid_quantity' => 'Invalid quantity entered.',
                    'product_not_found' => 'Selected product not found.',
                    'insufficient_stock' => 'Insufficient stock for the selected item.',
                    'insert_failed' => 'Failed to add item to order.'
                ];
                $errorText = isset($errorMessages[$errorCode]) ? $errorMessages[$errorCode] : 'An unknown error occurred.';
                echo "<div class='alert alert-danger'>❌ {$errorText}</div>";
                
                // If no order ID, stop rendering the rest
                if ($errorCode === 'no_order_id') {
                    echo "</div>";
                    echo "</div>";
                    exit;
                }
            }

            if (isset($_GET['success']) && $_GET['success'] == 'deleted') {
                echo "<div class='alert alert-success'>Order item deleted successfully.</div>";
            } elseif (isset($_GET['error'])) {
                echo "<div class='alert alert-danger'>Error: " . $_GET['error'] . "</div>";
            }

            echo "<div class='rolex-container'>";
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {

                    echo "<h2 class='section-header'>Order Details</h2>";
                    echo "<div class='info-grid'>";
                    echo "<div class='info-card'><span class='info-label'>Order ID</span><span class='info-value'>". $row['orderID'] ."</span></div>";
                    echo "<div class='info-card'><span class='info-label'>Customer Name</span><span class='info-value'>". $row['name'] ."</span></div>";
                    echo "<div class='info-card'><span class='info-label'>Order Date</span><span class='info-value'>".$row['orderDate']. "</span></div>";
                    echo "<div class='info-card'><span class='info-label'>Sub Total</span><span class='info-value'>".$row['totalCost']." MMK</span></div>";
                    echo "<div class='info-card'><span class='info-label'>Payment Type</span><span class='info-value'>".$row['paymentType']."</span></div>";
                    echo "<div class='info-card'><span class='info-label'>Payment Valid</span><span class='info-value'>".( $row['paymentValid']==1 ? "Can pay" : "Cannot Pay")."</span></div>";
                    echo "</div>";

                    echo "<h2 class='section-header'>Update Status</h2>";
                    echo "<form method='POST' action='update_status.php'>";
                    echo "<label class='info-label'>Payment Valid:</label>";
                    echo "<select name='paymentValid'>";
                             echo "<option value='0'". ($row['paymentValid'] == 0 ? 'selected' : '').">Cannot pay anymore</option>";
                             echo "<option value='1'". ($row['paymentValid'] == 1 ? 'selected' : '').">Can Pay Now</option>";
                    echo "</select>";

                    if ($result_payment_status && $result_payment_status->num_rows > 0) {
                        echo "<label class='info-label'>Payment Status</label>";
                        echo "<select name='paymentStatus'>";

                        while ($row_payment_status = $result_payment_status->fetch_assoc()) {
                            echo "<option value='".$row_payment_status['paymentStatusID']."' ".($row_payment_status['paymentStatusID'] == $row['paymentStatus'] ? 'selected' : '').">".$row_payment_status['paymentStatus']."</option>";
                        }

                        echo "</select>";

                    }
   
                    
                    if ($result_order_status && $result_order_status->num_rows > 0) {
                        echo "<label class='info-label'>Order Status</label>";
                        echo "<select name='orderStatus'>";

                        while ($row_order_status = $result_order_status->fetch_assoc()) {
                            echo "<option value='".$row_order_status['orderStatusID']."' ".($row_order_status['orderStatusID'] == $row['orderStatus'] ? 'selected' : '').">".$row_order_status['orderStatus']."</option>";
                        }

                        echo "</select>";

                    }

                    
                    if ($result_tracking_status && $result_tracking_status->num_rows > 0) {
                        
                        echo "<label class='info-label'>Tracking Status</label>";
                        echo "<select name='trackingStatus'>";

                        while ($row_tracking_status = $result_tracking_status->fetch_assoc()) {
                            echo "<option value='".$row_tracking_status['trackingStatusID']."' ".($row_tracking_status['trackingStatusID'] == $row['trackingStatus'] ? 'selected' : '').">".$row_tracking_status['trackingStatus']."</option>";
                        }

                        echo "</select>";
                    }
                    
                    echo "<input type='hidden' name='orderID' value='".$row['orderID']."' ></input>";
                    echo "<button type='submit'>Update Status</button>";
                    echo "</form>";

                    echo "<h2 class='section-header'>Payment Slip</h2>";
                    echo "<div class='payment-slips'>";
                    if ($result_paymentSlip && $result_paymentSlip->num_rows > 0) {
                        while ($row_paymentSlip = $result_paymentSlip->fetch_assoc()) {
                            echo "<img src='../image/".$row_paymentSlip['photoName']."' alt='Payment Slip'>";
                        }
                    }else{
                        echo "<span class='no-slip'>Haven't submitted payment slip</span>";
                    }
                    echo "</div>";

                    echo "<h2 class='section-header'>Shipping Address</h2>";
                    echo "<div class='info-grid'>";

                    $query_address = "SELECT * FROM address WHERE addressID =".$row['addressID'];
                    $result_address = $conn->query($query_address);

                    if ($result_address && $result_address->num_rows > 0) {
                        while ($row_address = $result_address->fetch_assoc()) {
                            echo "<div class='info-card'><span class='info-label'>Street</span><span class='info-value'>".$row_address['street']."</span></div>";
                            echo "<div class='info-card'><span class='info-label'>City</span><span class='info-value'>".$row_address['city']."</span></div>";
                            echo "<div class='info-card'><span class='info-label'>State</span><span class='info-value'>".$row_address['state']."</span></div>";
                            echo "<div class='info-card'><span class='info-label'>Postal Code</span><span class='info-value'>".$row_address['postalCode']."</span></div>";
                            echo "<div class='info-card'><span class='info-label'>Country</span><span class='info-value'>".$row_address['country']."</span></div>";
                            
                            if($row_address['mapLink'] != null){
                            echo "<div class='info-card'><span class='info-label'>Map Link</span><span class='info-value'><a href='".$row_address['mapLink']."' target='_blank' class='map-link'>Open in Google Maps</a></span></div>";
                            }
                            
                            echo "<div class='info-card'><span class='info-label'>Complete Address</span><span class='info-value'>".$row_address['completeAddress']."</span></div>";
                        }
                    }

                    echo "</div>";


                    
                    echo "<h2 class='section-header'>Ordered Items</h2><br>";
                    
                    echo "<a class='add-item-btn' href='adding_new_order_items.php?orderID=".$orderID."'>Add New Order Items</a>";

                    echo "<table class='order-items-table'>";
                    echo "<thead><tr><th>Product Image</th><th>Product Details</th></tr></thead>";
                    echo "<tbody>";
                    
                    if ($result_items && $result_items->num_rows > 0) {
                        while ($row_items = $result_items->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td class='image-cell'>";
                            
                            $query_photo = "SELECT * FROM photo WHERE photo.productID=".$row_items['productID']." LIMIT 1";
                            $result_photo = $conn->query($query_photo);

                            if ($result_photo && $result_photo->num_rows > 0) {
                                    $row_photo = $result_photo ->fetch_assoc();
                                    echo "<img src='../image/".$row_photo['photoName']."' alt='Product Image' class='order-item-image'>";
                                
                            } else {
                                echo "<div style='width:180px;height:180px;background:#eee;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#999;'>No Image</div>";
                            }
                            echo "</td>";
                            echo "<td>";
                            echo "<div class='item-detail-row'><span class='item-detail-label'>Order Item ID</span><span class='item-detail-value'>".$row_items['orderItemID']."</span></div>";
                            echo "<div class='item-detail-row'><span class='item-detail-label'>Product ID</span><span class='item-detail-value'>".$row_items['productID']."</span></div>";
                            echo "<div class='item-detail-row'><span class='item-detail-label'>Product Name</span><span class='item-detail-value'>".$row_items['productName']."</span></div>";
                            echo "<div class='item-detail-row'><span class='item-detail-label'>Price</span><span class='item-detail-value'>".$row_items['price']." MMK / product</span></div>";

                            if($row_items['discountedPrice'] != null ){
                                $uncal = $row_items['discountedPrice'];
                                echo "<div class='item-detail-row'><span class='item-detail-label'>Discounted Price</span><span class='item-detail-value'>".$row_items['discountedPrice']." MMK / product</span></div>";
                            }
                            else{
                                $uncal = $row_items['price'];
                                echo "<div class='item-detail-row'><span class='item-detail-label'>Discounted Price</span><span class='item-detail-value'> - </span></div>";
                            }
                            
                            $quantity = $row_items['quantity'];
                            $discount = 0;

                            $query_discount = "SELECT * FROM discount WHERE discount.productID=".$row_items['productID'];
                            $result_discount = $conn->query($query_discount);

                            if ($result_discount && $result_discount->num_rows > 0) {
                                while($row_discount = $result_discount ->fetch_assoc()){
                                    
                                    if($quantity >= $row_discount['range1'] && $quantity <= $row_discount['range2'] && $row_discount['range2']!=NULL ){
                                        $discount = $row_discount['percentage'];
                                        break;
                                    }
                                    else if($quantity > $row_discount['range1'] && $row_discount['range2']===NULL){
                                        $discount = $row_discount['percentage'];
                                        break;
                                    }
                                    
                                }
                            }

                            $calculatedPrice = $uncal - ($uncal * $discount/100);

                            echo "<div class='item-detail-row'><span class='item-detail-label'>Quantity</span><span class='item-detail-value'>".$row_items['quantity']."</span></div>";
                            echo "<div class='item-detail-row'><span class='item-detail-label'>Discount</span><span class='item-detail-value'>".$discount." %</span></div>";

                            echo "<div class='item-detail-row'><span class='item-detail-label'>Original Total</span><span class='item-detail-value'>".$row_items['quantity'] * $uncal." MMK</span></div>";

                            echo "<div class='item-detail-row'><span class='item-detail-label'>Sub Total</span><span class='item-detail-value'>".$row_items['quantity'] * $calculatedPrice." MMK</span></div>";

                            echo "<div class='item-detail-row'><span class='item-detail-label'>Size</span><span class='item-detail-value'>".$row_items['sizeName']."</span></div>";
                            echo "<div class='item-detail-row'><span class='item-detail-label'>Color</span><span class='item-detail-value'>".$row_items['colorName']."</span></div>";
                            echo "<a href='delete_manual_order_items.php?orderItemID=" . $row_items['orderItemID'] . "&orderID=" . $orderID . "' class='btn btn-danger btn-sm' onclick=\"return confirm('Are you sure you want to delete this order item? This action cannot be undone.');\">Delete</a>";

                            // echo "<div class='item-detail-row'><span class='item-detail-label'>Color Code</span><span class='item-detail-value'><div style='width:40px; height:40px; background-color:".$row_items['colorCode']."; border-radius : 50%;'></div></span></div>";
                            echo "</td>";
                       
                   
                            echo "</tr>";

                        }
                    } else {
                        echo "<tr><td colspan='2' style='text-align:center;padding:30px;color:#888;'>No items found in this order.</td></tr>";
                    }
                    echo "</tbody>";
                    echo "</table>";

                }
            } else {
                echo "<p style='text-align:center;color:#888;padding:40px;font-size:1.1em;'>Order not found.</p>";
            }
            echo "</div>";
        echo "</div>";
        }
    ?>
</body>
</html>
<script>
    document.getElementById('browseBtn').addEventListener('click', function() {
        document.getElementById('slipFile').click();
    });
</script>