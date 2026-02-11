<?php 

include "../connection/connectdb.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include './layout/login_error_message.php';
$currentPage = "active_order.php";
include './logInCheck.php'; 

$filter = $_GET['filter'] ?? 'all';
$export = $_GET['export'] ?? false;

$whereDate = "";
if ($filter === 'this_week') {
    $whereDate = " AND orderr.orderDate >= DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY)";
} elseif ($filter === 'this_month') {
    $whereDate = " AND YEAR(orderr.orderDate) = YEAR(CURDATE()) AND MONTH(orderr.orderDate) = MONTH(CURDATE())";
}

$query = "SELECT * FROM orderr JOIN account ON orderr.accountID = account.accountID WHERE orderr.orderStatus = 1 $whereDate ORDER BY orderr.orderDate DESC";
$result = $conn->query($query);

if ($export === 'csv') {
    $csvResult = $conn->query($query);

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=active_orders_' . date('Ymd_His') . '.csv');

    $out = fopen('php://output', 'w');
    fputcsv($out, ['Order ID', 'Customer Name', 'Sub Total (MMK)', 'Order Date', 'Status']);

    while ($row = $csvResult->fetch_assoc()) {
        fputcsv($out, [
            $row['orderID'],
            $row['name'],
            $row['totalCost'],
            date('M j, Y h:i A', strtotime($row['orderDate'])),
            'New Order'
        ]);
    }
    fclose($out);
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Active Order</title>
    <?php include "./layout/header.php"; ?>
    <style>
        .main-content {
            padding: 20px;
        }

        .page-header {
            background: linear-gradient(135deg, #004d00, #002600);
            color: white;
            padding: 20px 25px;
            border-radius: 0;
            margin: 0 0 20px 0;
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

        .header-actions {
            display: flex;
            gap: 12px;
            align-items: center;
            flex-wrap: wrap;
        }

        .header-actions button {
            background: rgba(255,255,255,0.15);
            border: none;
            color: white;
            padding: 8px 14px;
            border-radius: 0;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .header-actions button:hover {
            background: rgba(255,255,255,0.25);
            transform: translateY(-1px);
        }

        .header-actions select {
            background: rgba(255,255,255,0.15);
            border: none;
            color: white;
            padding: 8px 14px;
            border-radius: 0;
            font-size: 0.9rem;
        }

        .orders-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            border-radius: 0;
            overflow: hidden;
        }

        .orders-table thead {
            background: #f8f9fa;
            border-bottom: 2px solid #e0f0e0;
        }

        .orders-table thead th {
            padding: 16px 15px;
            text-align: left;
            font-weight: 500;
            color: #004d00;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        .orders-table tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid #e9ecef;
        }

        .orders-table tbody tr:hover {
            background-color: #f8fff8;
            box-shadow: 0 2px 8px rgba(0,77,0,0.05);
        }

        .orders-table td {
            padding: 16px 15px;
            vertical-align: middle;
            font-size: 1rem;
            color: #333;
        }

        .order-id {
            font-family: 'Courier New', monospace;
            background: #e6f7e6;
            color: #004d00;
            padding: 6px 10px;
            border-radius: 0;
            font-weight: 600;
            font-size: 0.9rem;
            display: inline-block;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 0;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-new {
            background: #e6f7e6;
            color: #004d00;
        }

        .status-accepted {
            background: #fff4e6;
            color: #cc7a00;
        }

        .status-prepared {
            background: #e6f7ff;
            color: #0066cc;
        }

        .status-rejected {
            background: #ffe6e6;
            color: #cc0000;
        }

        .btn-view {
            background: linear-gradient(45deg, #004d00, #006600);
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 0;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .btn-view:hover {
            background: linear-gradient(45deg, #006600, #008000);
            transform: translateY(-1px);
            box-shadow: 0 3px 8px rgba(0,102,0,0.3);
        }

        .no-orders {
            text-align: center;
            padding: 40px 20px;
            color: #666;
            font-style: italic;
            background: #f8fff8;
            border: 1px dashed #e0f0e0;
            border-radius: 0;
            margin: 20px 0;
        }

        /* -------------------------------------------------
   Rolex-Green Select Box Styling
   ------------------------------------------------- */
.header-actions select {
    /* Base box */
    background: #006400 !important;           /* Rolex dark green */
    color: #fff !important;
    border: 0px solid #b8860b !important;     /* Gold border */
    padding: 8px 14px !important;
    border-radius: 0 !important;
    font-size: 0.9rem !important;
    font-weight: 500 !important;
    /* appearance: none !important;             Remove default arrow */
    background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3e%3cpath fill='%23b8860b' d='M0 0l6 8 6-8z'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 12px center;
    cursor: pointer;
    transition: all 0.3s ease;
}

/* Hover & focus */
.header-actions select:hover,
.header-actions select:focus {
    background-color: #004d00 !important;     /* Slightly darker on hover */
    outline: none;
    box-shadow: 0 0 0 3px rgba(184, 134, 11, 0.3); /* Gold glow */
}

/* Dropdown options */
.header-actions select option {
    background: #e6f3e6 !important;           /* Light green background */
    color: #004d00 !important;
    font-weight: 600;
}

.header-actions select option:checked,
.header-actions select option:hover {
    background: #006400 !important;
    color: #b8860b !important;                /* Gold text when selected/hovered */
}

        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                text-align: center;
            }

            .header-actions {
                justify-content: center;
            }

            .orders-table {
                border: 0;
            }

            .orders-table thead {
                display: none;
            }

            .orders-table tbody tr {
                display: block;
                margin-bottom: 24px;
                border: 1px solid #e0f0e0;
                border-radius: 0;
                padding: 16px;
                background: white;
                box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            }

            .orders-table td {
                display: block;
                text-align: right;
                padding: 12px 10px;
                border: none;
                position: relative;
                padding-left: 50%;
                font-size: 0.95rem;
                color: #333;
                box-sizing: border-box;
            }

            .orders-table td:before {
                content: attr(data-label);
                position: absolute;
                left: 0;
                width: 50%;
                padding-left: 10px;
                padding-right: 10px;
                font-weight: 600;
                color: #004d00;
                text-transform: uppercase;
                font-size: 0.8rem;
                letter-spacing: 0.5px;
                top: 50%;
                transform: translateY(-50%);
                text-align: left;
                box-sizing: border-box;
            }

            .orders-table td .order-id,
            .orders-table td .status-badge {
                background: #f0f8f0;
                padding: 6px 10px;
                border-radius: 0;
                display: inline-block;
            }

            .orders-table td strong {
                color: #004d00;
            }

            .orders-table td:last-child {
                text-align: center;
                padding-top: 15px;
            }

            .btn-view {
                width: 100%;
                padding: 12px;
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <?php
        include "nav.php";
        $login = $_SESSION['login'] ?? false;  
        if($login == true) {
        echo "<div class='main-content'>";
        
        echo "<form id='filterForm' method='get' style='display:inline;'>";
            echo "<div class='page-header'>";
            echo "<h1>Active Orders</h1>";
            echo "<div class='header-actions'>";

            echo "<button type='submit' name='export' value='csv'><i class='bi bi-download'></i> Export</button>";

            echo "<select name='filter' onchange='document.getElementById(\"filterForm\").submit();'>";
            echo "<option value='all'"      . ($filter === 'all'      ? ' selected' : '') . ">All Time</option>";
            echo "<option value='this_week'". ($filter === 'this_week'? ' selected' : '') . ">This Week</option>";
            echo "<option value='this_month'".($filter === 'this_month'? ' selected' : '') . ">This Month</option>";
            echo "</select>";

            echo "</div></div></form>";

        if ($result && $result->num_rows > 0) {
            echo "<table class='orders-table'>";
            echo "<thead><tr>";
            echo "<th>Order ID</th>";
            echo "<th>Name</th>";
            echo "<th>Sub Total</th>";
            echo "<th>Order Date</th>";
            echo "<th>Status</th>";
            echo "<th>Action</th>";
            echo "</tr></thead>";
            echo "<tbody>";

            while ($row = $result->fetch_assoc()) {
                $statusClass = 'status-new';
                $statusText = 'New Order';
                
                echo "<tr>";
                echo "<td data-label='Order ID'><span class='order-id'>" . htmlspecialchars($row['orderID']) . "</span></td>";
                echo "<td data-label='Name'>" . htmlspecialchars($row['name']) . "</td>";
                echo "<td data-label='Total Cost'><strong>" . number_format($row['totalCost']) . " MMK</strong></td>";
                echo "<td data-label='Order Date'>" . date('M j, Y', strtotime($row['orderDate'])) . "</td>";
                echo "<td data-label='Status'><span class='status-badge $statusClass'>$statusText</span></td>";
                echo "<td data-label='Action'><a href='specific_order.php?orderID=" . urlencode($row['orderID']) . "' class='btn-view'>View</a></td>";
                echo "</tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<div class='no-orders'>No active orders at the moment.</div>";
        }

        echo "</div>"; // .main-content
        }
    ?>
</body>
</html>