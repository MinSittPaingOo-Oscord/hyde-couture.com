<?php
  include '../connection/connectdb.php';
  include '../layout/nav.php';
  $userID = isset($_GET['userID']) ? intval($_GET['userID']) : 1;

  $query = "SELECT * FROM orderr JOIN account ON orderr.accountID = account.accountID JOIN orderStatus ON orderr.orderStatus = orderStatus.orderStatusID WHERE orderr.accountID = $userID ORDER BY orderr.orderDate DESC";
  $result = $conn->query($query);

  $queryActive = "SELECT * FROM orderr JOIN account ON orderr.accountID = account.accountID JOIN orderStatus ON orderr.orderStatus = orderStatus.orderStatusID WHERE orderr.orderStatus = 1 AND orderr.accountID = $userID ORDER BY orderr.orderDate DESC";
  $resultActive = $conn->query($queryActive);

  $queryCompleted = "SELECT * FROM orderr JOIN account ON orderr.accountID = account.accountID JOIN orderStatus ON orderr.orderStatus = orderStatus.orderStatusID WHERE orderr.orderStatus = 2 AND orderr.accountID = $userID ORDER BY orderr.orderDate DESC";
  $resultCompleted = $conn->query($queryCompleted);

  $queryFailed = "SELECT * FROM orderr JOIN account ON orderr.accountID = account.accountID JOIN orderStatus ON orderr.orderStatus = orderStatus.orderStatusID WHERE orderr.orderStatus = 3 AND orderr.accountID = $userID ORDER BY orderr.orderDate DESC";
  $resultFailed = $conn->query($queryFailed);

  $queryReturn = "SELECT * FROM orderr JOIN account ON orderr.accountID = account.accountID JOIN orderStatus ON orderr.orderStatus = orderStatus.orderStatusID WHERE orderr.orderStatus = 5 AND orderr.accountID = $userID ORDER BY orderr.orderDate DESC";
  $resultReturn = $conn->query($queryReturn);

  $queryCancal = "SELECT * FROM orderr JOIN account ON orderr.accountID = account.accountID JOIN orderStatus ON orderr.orderStatus = orderStatus.orderStatusID WHERE orderr.orderStatus = 6 AND orderr.accountID = $userID ORDER BY orderr.orderDate DESC";
  $resultCancal = $conn->query($queryCancal);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>ORDER LIST</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <style>
      body {
        font-family: sans-serif;
        min-height: 100vh;
        /* background: #333
          url("https://images.unsplash.com/photo-1523731407965-2430cd12f5e4")
          no-repeat center/cover; */
      }

      .orders-wrapper {
        max-width: 100%;
        margin: 0px auto;
        background: #fff;
        border-radius: 0px;
        overflow: hidden;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.35);
      }

      .orders-header {
        font-family: "sans-serif";
        background: linear-gradient(135deg, #0b6b3a, #0f8a4f);
        color: #fff;
        padding: 30px 40px;
      }
      .orders-header h1 {
        font-size: 45px;
        letter-spacing: 3px;
      }
      .shop-now {
        color: #fff;
        text-decoration: none;
        border-bottom: 1px solid #fff;
        font-size: 14px;
      }

      .nav-tabs {
        font-family: "sans-serif";
        font-size: 17px;
        border-bottom: none;
        padding-left: 30px;
        background: linear-gradient(135deg, #0b6b3a, #0f8a4f);
      }
      .nav-tabs .nav-link {
        border: none;
        color: #fff !important; /* Force white text */
        font-size: 18px;
        font-weight: 400;
        margin-right: 20px;
        padding: 12px 20px;
        background: transparent !important; /* Keep green background */
      }
      .nav-tabs .nav-link.active {
        background: rgba(
          255,
          255,
          255,
          0.3
        ) !important; /* Semi-transparent white overlay */
        color: #fff !important; /* Keep white text */
        border-radius: 6px 6px 0 0;
        border-bottom: 3px solid #fff; /* Add bottom border for active state */
      }
      .nav-tabs .nav-link:hover {
        background: rgba(255, 255, 255, 0.2) !important;
      }

      .table thead {
        background: #ededed;
      }
      .table th,
      .table td {
        font-family: "sans-serif";
        padding: 16px;
        font-size: 16px;
        vertical-align: middle;
      }

      .check-detail {
        font-family: "sans-serif";
        color: #0b6b3a;
        font-weight: 600;
        text-decoration: none;
        border-bottom: 1px solid #0b6b3a;
      }

      .status-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
      }
      .status-active {
        background: #d4edda;
        color: #155724;
      }
      .status-completed {
        background: #d1ecf1;
        color: #0c5460;
      }
      .status-failed {
        background: #f8d7da;
        color: #721c24;
      }

      .table-scroll {
        max-height: 420px;
        overflow-y: auto;
      }
      .table-scroll::-webkit-scrollbar {
        width: 6px;
      }
      .table-scroll::-webkit-scrollbar-thumb {
        background: #0b6b3a;
        border-radius: 5px;
      }

      .tab-pane {
        min-height: 300px;
      }
      .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #666;
      }
      .empty-state i {
        font-size: 48px;
        margin-bottom: 20px;
        color: #ccc;
      }
   
      @media (max-width: 768px) {
        .orders-wrapper {
          margin: 20px 10px;
        }

        .orders-header {
          flex-direction: column;
          text-align: center;
          gap: 10px;
          padding: 20px;
        }

        .orders-header h1 {
          font-size: 26px;
          letter-spacing: 1px;
        }

        .nav-tabs {
          padding-left: 10px;
          overflow-x: auto;
          flex-wrap: nowrap;
          white-space: nowrap;
        }

        .nav-tabs .nav-link {
          font-size: 14px;
          padding: 8px 14px;
          margin-right: 8px;
        }

        .table thead {
          display: none;
        }

        .table,
        .table tbody,
        .table tr,
        .table td {
          display: block;
          width: 100%;
        }

        .table tr {
          margin-bottom: 15px;
          background: #fff;
          border-radius: 8px;
          padding: 10px;
          box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
        }

        .table td {
          text-align: right;
          padding: 10px 12px;
          font-size: 14px;
          position: relative;
          border: none;
        }

        .table td::before {
          content: attr(data-label);
          position: absolute;
          left: 12px;
          font-weight: 600;
          color: #333;
          text-align: left;
        }
      }
    </style>
  </head>
  <body>
    <div class="orders-wrapper">
      <div class="orders-header d-flex justify-content-between align-items-center">
        <h1>MY ORDERS</h1>
        <a href="./index.php" class="shop-now">SHOP NOW</a>
      </div>

      <ul class="nav nav-tabs" role="tablist">
              <li class="nav-item">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#all"> ALL ORDERS </button>
              </li>
              <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#active"> ACTIVE </button>
              </li>
              <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#completed"> COMPLETED </button>
              </li>
              <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#failed"> FAILED </button>
              </li>
              <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#return"> RETURNED </button>
              </li>
              <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#cancal"> CANCALED </button>
              </li>
      </ul>

      <div class="tab-content p-4">

        <div class="tab-pane fade show active" id="all">

          <div class="table-scroll">
            <table class="table align-middle mb-0">
              <thead>
                <tr>
                  <th>ORDER ID</th>
                  <th>ORDER DATE</th>
                  <th>STATUS</th>
                  <th>SUB TOTAL</th>
                  <th></th>
                </tr>
              </thead>
              <?php if ($result && $result->num_rows > 0) { ?>
              <tbody>
             <?php while ($row = $result->fetch_assoc()) { ?>

                <tr>
                  <td data-label="Order ID"> <?php echo $row['orderID'] ?></td>
                  <td data-label="Order Date"><?php echo $row['orderDate'] ?></td>
                  <td data-label="Status">
                    <span class="status-badge"><?php echo $row['orderStatus'] ?></span>
                  </td>
                  <td data-label="Sub Total"><?php echo number_format($row['totalCost']) . " MMK"?></td>
                  <td data-label="Action">
                    <a href="order_details.php?orderID=<?php echo $row['orderID']?>" class="check-detail">↘ CHECK DETAIL</a>
                  </td>
                </tr>

                <?php } ?>

                
              </tbody>
              <?php } ?>
            </table>
          </div>


        </div>
    

        <div class="tab-pane fade" id="active">
        <div class="table-scroll">
            <table class="table align-middle mb-0">
              <thead>
                <tr>
                  <th>ORDER ID</th>
                  <th>ORDER DATE</th>
                  <th>STATUS</th>
                  <th>SUB TOTAL</th>
                  <th></th>
                </tr>
              </thead>
              <?php if ($resultActive && $resultActive->num_rows > 0) { ?>
              <tbody>
             <?php while ($rowActive = $resultActive->fetch_assoc()) { ?>

                <tr>
                  <td data-label="Order ID"> <?php echo $rowActive['orderID'] ?></td>
                  <td data-label="Order Date"><?php echo $rowActive['orderDate'] ?></td>
                  <td data-label="Status">
                    <span class="status-badge"><?php echo $rowActive['orderStatus'] ?></span>
                  </td>
                  <td data-label="Sub Total"><?php echo number_format($rowActive['totalCost'])." MMK"?></td>
                  <td data-label="Action">
                    <a href="order_details.php?orderID=<?php echo $rowActive['orderID']?>" class="check-detail">↘ CHECK DETAIL</a>
                  </td>
                </tr>

                <?php } ?>

                
              </tbody>
              <?php } ?>
            </table>
          </div>


        </div>

        <div class="tab-pane fade" id="completed">
        <div class="table-scroll">
            <table class="table align-middle mb-0">
              <thead>
                <tr>
                  <th>ORDER ID</th>
                  <th>ORDER DATE</th>
                  <th>STATUS</th>
                  <th>SUB TOTAL</th>
                  <th></th>
                </tr>
              </thead>
              <?php if ($resultCompleted && $resultCompleted->num_rows > 0) { ?>
              <tbody>
             <?php while ($rowCompleted = $resultCompleted->fetch_assoc()) { ?>

                <tr>
                  <td data-label="Order ID"> <?php echo $rowCompleted['orderID'] ?></td>
                  <td data-label="Order Date"><?php echo $rowCompleted['orderDate'] ?></td>
                  <td data-label="Status">
                    <span class="status-badge"><?php echo $rowCompleted['orderStatus'] ?></span>
                  </td>
                  <td data-label="Sub Total"><?php echo number_format($rowCompleted['totalCost'] )." MMK"?></td>
                  <td data-label="Action">
                    <a href="order_details.php?orderID=<?php echo $rowCompleted['orderID']?>" class="check-detail">↘ CHECK DETAIL</a>
                  </td>
                </tr>

                <?php } ?>

                
              </tbody>
              <?php } ?>
            </table>
          </div>


        </div>

        <div class="tab-pane fade" id="failed">
        <div class="table-scroll">
            <table class="table align-middle mb-0">
              <thead>
                <tr>
                  <th>ORDER ID</th>
                  <th>ORDER DATE</th>
                  <th>STATUS</th>
                  <th>SUB TOTAL</th>
                  <th></th>
                </tr>
              </thead>
              <?php if ($resultFailed && $resultFailed->num_rows > 0) { ?>
              <tbody>
             <?php while ($rowFailed = $resultFailed->fetch_assoc()) { ?>

                <tr>
                  <td data-label="Order ID"> <?php echo $rowFailed['orderID'] ?></td>
                  <td data-label="Order Date"><?php echo $rowFailed['orderDate'] ?></td>
                  <td data-label="Status">
                    <span class="status-badge"><?php echo $rowFailed['orderStatus'] ?></span>
                  </td>
                  <td data-label="Sub Total"><?php echo number_format($rowFailed['totalCost'])." MMK"?></td>
                  <td data-label="Action">
                    <a href="order_details.php?orderID=<?php echo $rowFailed['orderID']?>" class="check-detail">↘ CHECK DETAIL</a>
                  </td>
                </tr>

                <?php } ?>

                
              </tbody>
              <?php } ?>
            </table>
          </div>


        </div>

        <div class="tab-pane fade" id="return">
        <div class="table-scroll">
            <table class="table align-middle mb-0">
              <thead>
                <tr>
                  <th>ORDER ID</th>
                  <th>ORDER DATE</th>
                  <th>STATUS</th>
                  <th>SUB TOTAL</th>
                  <th></th>
                </tr>
              </thead>
              <?php if ($resultReturn && $resultReturn->num_rows > 0) { ?>
              <tbody>
             <?php while ($rowReturn = $resultReturn->fetch_assoc()) { ?>

                <tr>
                  <td data-label="Order ID"> <?php echo $rowReturn['orderID'] ?></td>
                  <td data-label="Order Date"><?php echo $rowReturn['orderDate'] ?></td>
                  <td data-label="Status">
                    <span class="status-badge"><?php echo $rowReturn['orderStatus'] ?></span>
                  </td>
                  <td data-label="Sub Total"><?php echo number_fomat($rowReturn['totalCost']). "MMK"?></td>
                  <td data-label="Action">
                    <a href="order_details.php?orderID=<?php echo $rowReturn['orderID']?>" class="check-detail">↘ CHECK DETAIL</a>
                  </td>
                </tr>

                <?php } ?>

                
              </tbody>
              <?php } ?>
            </table>
          </div>


        </div>

        <div class="tab-pane fade" id="cancal">
        <div class="table-scroll">
            <table class="table align-middle mb-0">
              <thead>
                <tr>
                  <th>ORDER ID</th>
                  <th>ORDER DATE</th>
                  <th>STATUS</th>
                  <th>SUB TOTAL</th>
                  <th></th>
                </tr>
              </thead>
              <?php if ($resultCancal && $resultCancal->num_rows > 0) { ?>
              <tbody>
             <?php while ($rowCancal = $resultCancal->fetch_assoc()) { ?>

                <tr>
                  <td data-label="Order ID"> <?php echo $rowCancal['orderID'] ?></td>
                  <td data-label="Order Date"><?php echo $rowCancal['orderDate'] ?></td>
                  <td data-label="Status">
                    <span class="status-badge"><?php echo $rowCancal['orderStatus'] ?></span>
                  </td>
                  <td data-label="Sub Total"><?php echo number_format($rowCancal['totalCost'])." MMK"?></td>
                  <td data-label="Action">
                    <a href="order_details.php?orderID=<?php echo $rowCancal['orderID']?>" class="check-detail">↘ CHECK DETAIL</a>
                  </td>
                </tr>

                <?php } ?>

                
              </tbody>
              <?php } ?>
            </table>
          </div>


        </div>

      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
<?php
  include '../layout/footer.php';
?>