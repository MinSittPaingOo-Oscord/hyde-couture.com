<?php
  include '../connection/connectdb.php';
  include '../layout/nav.php';

  $orderID = $_GET['orderID'];

  $query = "SELECT * FROM orderr JOIN account ON orderr.accountID = account.accountID JOIN paymenttype ON orderr.paymentType = paymenttype.paymentTypeID WHERE orderr.orderID = $orderID";
  $result = $conn->query($query);

  $query2 = "SELECT * FROM orderr JOIN account ON orderr.accountID = account.accountID JOIN paymenttype ON orderr.paymentType = paymenttype.paymentTypeID WHERE orderr.orderID = $orderID";
  $result2 = $conn->query($query2);

  $query3 = " SELECT o.*, a.name AS customerName, pt.paymentType, ps.paymentStatus  AS paymentStatusText, os.orderStatus    AS orderStatusText, ts.trackingStatus AS trackingStatusText
  FROM orderr o
  JOIN account a ON a.accountID = o.accountID
  LEFT JOIN paymenttype pt   ON pt.paymentTypeID = o.paymentType
  LEFT JOIN paymentstatus ps ON ps.paymentStatusID = o.paymentStatus
  LEFT JOIN orderstatus os   ON os.orderStatusID = o.orderStatus
  LEFT JOIN trackingstatus ts ON ts.trackingStatusID = o.trackingStatus
  WHERE o.orderID = $orderID;";

  $result3 = $conn->query($query3);

  $query_address = "SELECT * FROM address JOIN orderr ON orderr.addressID = address.addressID where orderr.orderID = $orderID";
  $result_address = $conn->query($query_address);

  $query_items = "SELECT * FROM orderitem JOIN color ON orderitem.color = color.colorID JOIN size ON orderitem.size = size.sizeID JOIN product ON orderitem.productID = product.productID WHERE orderitem.orderID=".$orderID;
  $result_items = $conn->query($query_items);

  $query_paymentSlip = "SELECT * FROM paymentslip JOIN photo ON paymentslip.paymentSlip = photo.photoID WHERE paymentslip.orderID = $orderID";
  $result_paymentSlip = $conn->query($query_paymentSlip);

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Order Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <style>
        body {
          font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
          background-color: #f8f9fa;
          color: #333;
        }

        .order-header {
          background: linear-gradient(135deg, #0b6b3a 0%, #0a8c4d 100%);
          box-shadow: 0 4px 12px rgba(11, 107, 58, 0.2);
        }

        .order-title {
          font-family: "sans-serif";
          font-size: 50px;
          letter-spacing: 1.5px;
          text-transform: uppercase;
          font-weight: 300;
          text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.2);
        }

            .order-id {
              font-size: 14px;
              background: rgba(255, 255, 255, 0.2);
              padding: 8px 16px;
              border-radius: 20px;
              font-weight: 500;
            }

            /* Section titles */
            .section-title {
              font-family: "sans-serif";
              color: #0b6b3a;
              font-weight: 300;
              margin-bottom: 25px;
              text-transform: uppercase;
              letter-spacing: 1px;
              position: relative;
              padding-bottom: 10px;
            }

            .section-title::after {
              content: "";
              position: absolute;
              bottom: 0;
              left: 0;
              width: 60px;
              height: 3px;
              background: linear-gradient(to right, #0b6b3a, #0a8c4d);
              border-radius: 2px;
            }

            /* Cards */
            .summary-card {
              border-radius: 16px;
              border: none;
              box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
              transition: all 0.3s ease;
              overflow: hidden;
              margin-bottom: 25px;
            }

            .summary-card:hover {
              transform: translateY(-5px);
              box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
            }

            .summary-card .card-body {
              padding: 0;
            }

            /* Photo section styling */
            .photo-section {
              background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
              padding: 25px;
              border-radius: 16px 0 0 16px;
              height: 100%;
              display: flex;
              flex-direction: column;
              position: relative;
            }

            /* Carousel styling */
            .product-carousel {
              border-radius: 12px;
              overflow: hidden;
              box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            }

            .carousel-indicators button {
              width: 10px;
              height: 10px;
              border-radius: 50%;
              margin: 0 4px;
              background-color: rgba(11, 107, 58, 0.5);
              border: none;
            }

            .carousel-indicators button.active {
              background-color: #0b6b3a;
              transform: scale(1.2);
            }

            .carousel-control-prev,
            .carousel-control-next {
              width: 40px;
              height: 40px;
              background-color: rgba(255, 255, 255, 0.9);
              border-radius: 50%;
              top: 50%;
              transform: translateY(-50%);
              opacity: 0.8;
              transition: all 0.3s ease;
            }

            .carousel-control-prev:hover,
            .carousel-control-next:hover {
              opacity: 1;
              background-color: white;
              box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            }

            .carousel-control-prev {
              left: 15px;
            }

            .carousel-control-next {
              right: 15px;
            }

            .carousel-control-prev-icon,
            .carousel-control-next-icon {
              filter: invert(24%) sepia(89%) saturate(1422%) hue-rotate(136deg)
                brightness(95%) contrast(101%);
            }

            /* Carousel images */
            .carousel-inner img {
              height: 320px;
              object-fit: cover;
              border-radius: 12px;
              transition: transform 0.5s ease;
            }

            .carousel-item:hover img {
              transform: scale(1.02);
            }

            /* Product details section */
            .details-section {
              padding: 30px;
              background: white;
              border-radius: 0 16px 16px 0;
              height: 100%;
            }

            .product-title {
              font-family: "sans-serif";
              font-size: 30px;
              font-weight: 700;
              color: #0b6b3a;
              margin-bottom: 20px;
              padding-bottom: 15px;
              border-bottom: 2px solid #f0f0f0;
            }

            /* Product details styling */
            .detail-item {
              margin-bottom: 18px;
              padding-bottom: 18px;
              border-bottom: 1px solid #f5f5f5;
              display: flex;
              align-items: center;
            }

            .detail-item:last-child {
              border-bottom: none;
              margin-bottom: 0;
              padding-bottom: 0;
            }

            .detail-label {
              font-family: "sans-serif";
              color: #0b6b3a;
              font-weight: 500;
              min-width: 120px;
              font-size: 18px;
              display: flex;
              align-items: center;
            }

            .detail-label i {
              margin-right: 10px;
              width: 20px;
              text-align: center;
            }

            .detail-value {
              font-family: "sans-serif";
              color: #444;
              font-weight: 600;
              font-size: 17px;
              flex: 1;
            }

            /* Color dot */
            .color-dot {
              width: 18px;
              height: 18px;
              border-radius: 50%;
              display: inline-block;
              margin-right: 10px;
              border: 2px solid #fff;
              box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
              vertical-align: middle;
            }

            /* Badge for status */
            .status-badge {
              background: linear-gradient(to right, #0b6b3a, #0a8c4d);
              color: white;
              padding: 6px 15px;
              border-radius: 20px;
              font-size: 13px;
              font-weight: 600;
              display: inline-block;
              box-shadow: 0 3px 8px rgba(11, 107, 58, 0.2);
            }

            /* Price styling */
            .price-highlight {
              font-size: 24px;
              font-weight: 700;
              color: #0b6b3a;
              background: linear-gradient(135deg, #f0f9f4 0%, #e6f4ec 100%);
              padding: 10px 20px;
              border-radius: 10px;
              display: inline-block;
              margin-top: 5px;
            }

            /* Payment boxes */
            .payment-box {
              font-family: "sans-serif";
              background: white;
              border: 1px solid #e3e3e3;
              border-radius: 12px;
              padding: 20px;
              text-align: center;
              font-size: 17px;
              box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
              transition: all 0.3s ease;
              height: 100%;
            }

            .payment-box:hover {
              transform: translateY(-3px);
              box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
              border-color: #0b6b3a;
            }

            .payment-box strong {
              display: block;
              font-size: 18px;
              margin-top: 8px;
              color: #0b6b3a;
            }

            /* Slip preview styling */
            #slipPreview {
              max-height: 300px;
              object-fit: contain;
              border: 2px dashed #ddd;
              border-radius: 12px;
              padding: 15px;
              background-color: #f9f9f9;
              display: block;
              margin: 0 auto;
              transition: all 0.3s ease;
            }

            #slipPreview:hover {
              border-color: #0b6b3a;
              background-color: #f0f9f4;
            }

            /* Upload button */
            .btn-success {
              background: linear-gradient(135deg, #0b6b3a 0%, #0a8c4d 100%);
              border: none;
              padding: 12px 30px;
              border-radius: 8px;
              font-weight: 600;
              letter-spacing: 0.5px;
              transition: all 0.3s ease;
              box-shadow: 0 4px 12px rgba(11, 107, 58, 0.2);
            }

            .btn-success:hover {
              transform: translateY(-2px);
              box-shadow: 0 8px 20px rgba(11, 107, 58, 0.3);
              background: linear-gradient(135deg, #0a8c4d 0%, #0b6b3a 100%);
            }

            /* Address card */
            .address-card {
              border-radius: 16px;
              border: none;
              box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
              background: white;
              padding: 30px;
            }

            .address-title {
              font-family: "sans-serif";
              font-size: 20px;
              font-weight: 700;
              color: #0b6b3a;
              margin-bottom: 25px;
              padding-bottom: 15px;
              border-bottom: 2px solid #f0f0f0;
            }

            .address-item {
              margin-bottom: 18px;
              padding-bottom: 18px;
              border-bottom: 1px solid #f5f5f5;
              display: flex;
            }

            .address-item:last-child {
              border-bottom: none;
              margin-bottom: 0;
              padding-bottom: 0;
            }

            .address-label {
              font-family: "sans-serif";
              color: #0b6b3a;
              font-weight: 600;
              min-width: 180px;
              font-size: 17px;
            }

            .address-value {
              font-family: "sans-serif";
              font-size: 17px;
              color: #444;
              font-weight: 500;
              flex: 1;
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

              .payment-slips img {
                  display: inline-block;
                  margin-right: 10px;
              }

              .payment-slips {
                  display: flex;
                  flex-wrap: wrap;
              }

              .slip-gallery{
                display: flex;
                flex-direction: row;
                flex-wrap: wrap;              /* wraps to next row if many images */
                gap: 15px;
                justify-content: center;      /* center the row */
                align-items: flex-start;
              }

              .slip-preview{
                width: 220px;                 /* adjust size */
                height: auto;
                object-fit: contain;
                border: 2px dashed #ddd;
                border-radius: 12px;
                padding: 10px;
                background: #f9f9f9;

                display: inline-block;        /* important: not block */
                margin: 0;                    /* remove auto-centering block behavior */
              }


      @media (max-width: 768px) {
        body {
          padding: 0;
          margin: 0;
        }

        /* Header adjustments */
        .order-title {
          font-size: 28px;
          letter-spacing: 1px;
          text-align: center;
        }

        .order-id {
          font-size: 12px;
          padding: 6px 12px;
          display: inline-block;
          margin-top: 10px;
        }

        /* Section titles */
        .section-title {
          font-size: 22px;
          margin-bottom: 20px;
        }

        .section-title::after {
          width: 40px;
          height: 2px;
        }

        /* Summary card - stack photo and details vertically */
        .summary-card {
          margin-bottom: 20px;
          border-radius: 12px;
        }

        .photo-section {
          border-radius: 12px 12px 0 0;
          padding: 15px;
          height: auto;
        }

        .details-section {
          border-radius: 0 0 12px 12px;
          padding: 20px;
        }

        /* Product carousel adjustments */
        .carousel-inner img {
          height: 250px;
        }

        .carousel-control-prev,
        .carousel-control-next {
          width: 30px;
          height: 30px;
        }

        .carousel-control-prev {
          left: 5px;
        }

        .carousel-control-next {
          right: 5px;
        }

        /* Product title */
        .product-title {
          font-size: 24px;
          margin-bottom: 15px;
          text-align: center;
        }

        /* Detail items - stack vertically */
        .detail-item {
          flex-direction: column;
          align-items: flex-start;
          margin-bottom: 15px;
          padding-bottom: 15px;
        }

        .detail-label {
          min-width: auto;
          font-size: 16px;
          margin-bottom: 5px;
        }

        .detail-value {
          font-size: 15px;
          width: 100%;
        }

        /* Color dot */
        .color-dot {
          width: 16px;
          height: 16px;
        }

        /* Price highlight */
        .price-highlight {
          font-size: 20px;
          padding: 8px 16px;
          text-align: center;
          display: block;
          margin: 10px auto;
        }

        /* Payment boxes - stack vertically */
        .payment-box {
          margin-bottom: 15px;
          padding: 15px;
          font-size: 15px;
        }

        .payment-box strong {
          font-size: 16px;
          margin-top: 5px;
        }

        /* Slip preview */
        #slipPreview {
          max-height: 250px;
          width: 100%;
        }

        /* Address card */
        .address-card {
          padding: 20px;
          margin-bottom: 20px;
        }

        .address-title {
          font-size: 18px;
          text-align: center;
        }

        /* Address items - stack vertically */
        .address-item {
          flex-direction: column;
          align-items: flex-start;
          margin-bottom: 15px;
          padding-bottom: 15px;
        }

        .address-label {
          min-width: auto;
          font-size: 15px;
          margin-bottom: 5px;
        }

        .address-value {
          font-size: 14px;
          width: 100%;
        }

        /* Container padding adjustments */
        .container {
          padding-left: 15px;
          padding-right: 15px;
        }

        /* Margin adjustments for mobile */
        .mb-4 {
          margin-bottom: 1.5rem !important;
        }

        .mb-5 {
          margin-bottom: 2rem !important;
        }

        /* Button adjustments */
        .btn-success {
          padding: 10px 20px;
          font-size: 14px;
          width: 100%;
          max-width: 300px;
          display: block;
          margin: 0 auto;
        }

        /* Carousel indicators */
        .carousel-indicators {
          bottom: 10px;
        }

        .carousel-indicators button {
          width: 8px;
          height: 8px;
          margin: 0 3px;
        }
      }

      /* For very small screens (phones) */
      @media (max-width: 480px) {
        .order-title {
          font-size: 24px;
        }

        .product-title {
          font-size: 20px;
        }

        .carousel-inner img {
          height: 200px;
        }

        .detail-label {
          font-size: 15px;
        }

        .detail-value {
          font-size: 14px;
        }

        .address-label {
          font-size: 14px;
        }

        .address-value {
          font-size: 13px;
        }

        .price-highlight {
          font-size: 18px;
          padding: 6px 12px;
        }

        /* Smaller section titles */
        .section-title {
          font-size: 20px;
        }
      }

      @media (min-width: 769px) and (max-width: 1024px) {
        .order-title {
          font-size: 36px;
        }

        .product-title {
          font-size: 26px;
        }

        .carousel-inner img {
          height: 280px;
        }

        .detail-label {
          min-width: 100px;
          font-size: 17px;
        }

        .detail-value {
          font-size: 16px;
        }

        .address-label {
          min-width: 150px;
          font-size: 16px;
        }

        .address-value {
          font-size: 16px;
        }

        /* Adjust padding for tablet */
        .photo-section,
        .details-section {
          padding: 20px;
        }
      }

      /* Large mobile landscape mode */
      @media (max-width: 768px) and (orientation: landscape) {
        .carousel-inner img {
          height: 180px;
        }

        .product-title {
          font-size: 20px;
        }

        .detail-item {
          flex-direction: row;
          align-items: center;
        }

        .detail-label {
          min-width: 120px;
          margin-bottom: 0;
        }

        .address-item {
          flex-direction: row;
          align-items: center;
        }

        .address-label {
          min-width: 150px;
          margin-bottom: 0;
        }
      }

      /* Fix for Bootstrap columns on mobile */
      @media (max-width: 768px) {
        .row {
          margin-left: -8px;
          margin-right: -8px;
        }

        .col-md-6,
        .col-md-4,
        .col-md-8,
        .col-md-12 {
          padding-left: 8px;
          padding-right: 8px;
        }
      }

      /* Prevent horizontal scrolling on mobile */
      @media (max-width: 768px) {
        html,
        body {
          max-width: 100%;
          overflow-x: hidden;
        }

        .container,
        .container-fluid {
          overflow-x: hidden;
        }
      }

      /* Animation for page load */
      @keyframes fadeInUp {
        from {
          opacity: 0;
          transform: translateY(20px);
        }
        to {
          opacity: 1;
          transform: translateY(0);
        }
      }

      .summary-card,
      .payment-box,
      .address-card {
        animation: fadeInUp 0.6s ease forwards;
      }

      .summary-card:nth-child(2) {
        animation-delay: 0.1s;
      }

      .summary-card:nth-child(3) {
        animation-delay: 0.2s;
      }
    </style>
  </head>
  <body>

    <header class="order-header text-white py-4">
      <div class="container">
          <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
              <h1 class="order-title mb-3 mb-md-0">ORDER DETAILS</h1>
              <?php if ($result && $result->num_rows > 0 && $row = $result->fetch_assoc()) { ?>
              <div class="d-flex align-items-center"> <span class="order-id me-3"> Order ID : <?php echo $row['orderID'] ?> </span> </div>
              <?php } ?>
          </div>
      </div>
    </header>
    
    <section class="container my-5">
      <h4 class="section-title">ORDER SUMMARY</h4>
      <?php if ($result2 && $result2->num_rows > 0 && $row2 = $result2->fetch_assoc()) { ?>
     
      <div class="card summary-card">
        <div class="card-body p-4">
          <div class="row">
            <div class="col-12">
              <div class="row">
                <div class="col-md-6">

                
                  <div class="detail-item">
                    <div class="detail-label">ORDER ID</div>
                    <div class="detail-value"><?php echo $row2['orderID']?></div>
                  </div>

                  <div class="detail-item">
                    <div class="detail-label">Customer Name   </div>
                    <div class="detail-value"><?php echo $row2['name'] ?></div>
                  </div>

                  <div class="detail-item">
                    <div class="detail-label">ORDER DATE </div>
                    <div class="detail-value"><?php echo $row2['orderDate'] ?></div>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="detail-item">
                    <div class="detail-label">SUB TOTAL  </div>
                    <div class="detail-value"><?php echo number_format($row2['totalCost'])." MMK"?></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
  
      <?php } ?>
    </section>

    <section class="container my-5">
    <h4 class="section-title">ORDER ITEM(S)</h4>
    <?php
     
        echo "<table class='order-items-table'>";
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
                // echo "<div class='item-detail-row'><span class='item-detail-label'>Order Item ID</span><span class='item-detail-value'>".$row_items['orderItemID']."</span></div>";
                // echo "<div class='item-detail-row'><span class='item-detail-label'>Product ID</span><span class='item-detail-value'>".$row_items['productID']."</span></div>";
                echo "<div class='item-detail-row'><span class='item-detail-label'>Product Name</span><span class='item-detail-value'>".$row_items['productName']."</span></div>";

                if($row_items['discountedPrice'] != null ){
                    $uncal = $row_items['discountedPrice'];
                    echo "<div class='item-detail-row'><span class='item-detail-label'>Discounted Price</span><span class='item-detail-value'>".$row_items['discountedPrice']." MMK / product</span></div>";
                }
                else{
                    $uncal = $row_items['price'];
                    // echo "<div class='item-detail-row'><span class='item-detail-label'>Discounted Price</span><span class='item-detail-value'> - </span></div>";
                    echo "<div class='item-detail-row'><span class='item-detail-label'>Price</span><span class='item-detail-value'>".$row_items['price']." MMK / product</span></div>";
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
                if($discount != 0){

                echo "<div class='item-detail-row'><span class='item-detail-label'>Discount</span><span class='item-detail-value'>".$discount." %</span></div>";
                }

                echo "<div class='item-detail-row'><span class='item-detail-label'>Sub Total</span><span class='item-detail-value'>".$row_items['quantity'] * $uncal." MMK</span></div>";
                
                if($discount != 0){

                    echo "<div class='item-detail-row'><span class='item-detail-label'>Whole Sale Price</span><span class='item-detail-value'>".$row_items['quantity'] * $calculatedPrice." MMK</span></div>";
                
                }
                echo "<div class='item-detail-row'><span class='item-detail-label'>Size</span><span class='item-detail-value'>".$row_items['sizeName']."</span></div>";
                echo "<div class='item-detail-row'><span class='item-detail-label'>Color</span><span class='item-detail-value'>".$row_items['colorName']."</span></div>";
                // echo "<div class='item-detail-row'><span class='item-detail-label'>Color Code</span><span class='item-detail-value'><div style='width:40px; height:40px; background-color:".$row_items['colorCode']."; border-radius : 50%;'></div></span></div>";
                echo "</td>";
                echo "</tr>";

            }
        } else {
            echo "<tr><td colspan='2' style='text-align:center;padding:30px;color:#888;'>No items found in this order.</td></tr>";
        }
        echo "</tbody>";
        echo "</table>";
    ?>
    </section>

    <!-- Order Payment -->
    <section class="container my-5">
      <h4 class="section-title"> <i class="fas fa-credit-card me-3"></i>Order Payment </h4>
      <?php if ($result3 && $result3->num_rows > 0 && $row3 = $result3->fetch_assoc()) { ?>

      <div class="row g-4 mb-4">
        <div class="col-md-4">
          <div class="payment-box">
            <i
              class="fas fa-money-bill-wave fa-2x mb-3"
              style="color: #0b6b3a"
            ></i
            ><br />
            Payment Type<br /><strong><?php echo $row3['paymentType'] ?></strong>
          </div>
        </div>
        <div class="col-md-4">
          <div class="payment-box">
            <i class="fas <?php echo($row['paymentValid'] == 1 ? 'fa-check-circle' : 'fa-circle-xmark') ?> fa-2x mb-3" style="color: <?php echo ($row['paymentValid'] == 1 ? '#0b6b3a' : 'rgb(231, 8, 19)') ?>"></i
            ><br />
            Payment Valid<br /><strong><?php echo ($row3['paymentValid'] == 1 ? 'Can Pay Now' : 'Cannot pay anymore') ?></strong>
          </div>
        </div>
        <div class="col-md-4">
          <div class="payment-box">
            <i class="fas fa-clock fa-2x mb-3" style="color: #0b6b3a"></i><br />
            Payment Status<br /><strong> <?php echo $row3['paymentStatusText'] ?></strong>
          </div>
        </div>
        <div class="col-md-4">
          <div class="payment-box">
            <i
              class="fas fa-shopping-cart fa-2x mb-3"
              style="color: #0b6b3a"
            ></i
            ><br />
            Order Status<br /><strong><?php echo $row3['orderStatusText'] ?></strong>
          </div>
        </div>
        <div class="col-md-4">
          <div class="payment-box">
            <i class="fas fa-box fa-2x mb-3" style="color: #0b6b3a"></i><br />
            Tracking Status<br /><strong><?php echo $row3['trackingStatusText'] ?></strong>
          </div>
        </div>
      </div>

      <?php } ?>

      <div class="card p-4 address-card">
        <h5 class="address-title mb-4">
          <i class="fas fa-receipt me-2"></i>Payment Slip Upload
        </h5>

        <!-- Preview -->
      <div class='payment-slips'>
            <?php
                echo "<div class='slip-gallery'>";

                while ($row_paymentSlip = $result_paymentSlip->fetch_assoc()) {
                  echo "<img src='../image/".$row_paymentSlip['photoName']."' alt='Payment Slip' class='slip-preview img-fluid'>";
                }
                
                echo "</div>";
                
            ?>
        </div>

        <p class="text-muted mt-2 small">
            <i class="fas fa-info-circle me-1"></i>Preview will appear here
            after selecting an image
        </p>

        <div id="uploadForm">
                <div class="mb-4">

                  <label for="slipFile" class="form-label fw-bold">
                    <i class="fas fa-file-upload me-2"></i>Choose payment slip image
                  </label>

                  <form method="POST" method="POST" enctype="multipart/form-data" action="./upload_payment_slip.php">
                      
                      <div class="input-group">

                          <input type='text' name='orderID' hidden value='<?php echo $orderID?>'></input>
                    
                          <input type="file" name="payment_slip" class="form-control" id="slipFile" accept="image/*" />
                    
                          <button class="btn btn-outline-secondary" type="button" id="browseBtn"> <i class="fas fa-folder-open"></i> </button>

                      </div>


                      <div class="form-text mt-2">
                          <i class="fas fa-file-image me-1"></i>Accepts: JPG, PNG, GIF, WebP(Max: 5MB)
                      </div>

                      <div class="d-flex flex-wrap gap-3">
                          <button type="submit" id="submitBtn" class="btn btn-success px-4"> <i class="fas fa-paper-plane me-2"></i>Submit Slip</button>
                      </div>

                    </form>
        </div>

        
        </div>

      </div>
    </section>

    <!-- Shipping Address -->
    <section class="container my-5">
      <h4 class="section-title">
        <i class="fas fa-map-marker-alt me-3"></i>Shipping Address
      </h4>
      <div class="address-card">
        <h5 class="address-title mb-4">Delivery Information</h5>

        <?php 
            if ($result_address && $result_address->num_rows > 0 && $row_address = $result_address->fetch_assoc()) {
        ?>

        <div class="address-item">
          <div class="address-label">Street</div>
          <div class="address-value"><?php echo $row_address['street']?></div>
        </div>

        <div class="address-item">
          <div class="address-label">Township</div>
          <div class="address-value"><?php echo $row_address['township']?></div>
        </div>

        <div class="address-item">
          <div class="address-label">City</div>
          <div class="address-value"><?php echo $row_address['city']?></div>
        </div>

        <div class="address-item">
          <div class="address-label">Country</div>
          <div class="address-value"><?php echo $row_address['country']?></div>
        </div>

        <div class="address-item">
          <div class="address-label">POSTAL CODE</div>
          <div class="address-value"><?php echo $row_address['postalCode']?></div>
        </div>

        <div class="address-item">
          <div class="address-label">Complete Address</div>
          <div class="address-value"><?php echo $row_address['completeAddress']?></div>
        </div>

        <?php if(isset($row_address['mapLink'])) { ?> 
        <div class="address-item">
          <div class="address-label">Map Link</div>
          <div class="address-value"><a class="map-link" href='<?php echo $row_address['mapLink']?>'>View On Map</a></div>
        </div>
        <?php } ?>

        <?php } ?>
      </div>
    </section>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


  </body>
</html>
<?php
    include '../layout/footer.php';
?>