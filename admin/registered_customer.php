<?php 

include "../connection/connectdb.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include './layout/login_error_message.php';
$currentPage = "manual_order.php";
include './logInCheck.php';

$queryAccount = "SELECT * FROM account";
$resultAccount = $conn->query($queryAccount);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>For Registered Orders</title>
    <?php include "./layout/header.php"; ?>
    <style>
 

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

        .selection-form {
            background: white;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            border-radius: 0;
            padding: 30px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .selection-form label {
            font-weight: 500;
            color: #004d00;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        .selection-form select {
            background: #006400;           /* Rolex dark green */
            color: #fff;
            border: 0px solid #b8860b;     /* Gold border */
            padding: 12px 16px;
            border-radius: 0;
            font-size: 1rem;
            font-weight: 500;
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3e%3cpath fill='%23b8860b' d='M0 0l6 8 6-8z'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 16px center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .selection-form select:hover,
        .selection-form select:focus {
            background-color: #004d00;
            outline: none;
            box-shadow: 0 0 0 3px rgba(184, 134, 11, 0.3); /* Gold glow */
        }

        .selection-form select option {
            background: #e6f3e6;           /* Light green background */
            color: #004d00;
            font-weight: 600;
        }

        .selection-form select option:checked,
        .selection-form select option:hover {
            background: #006400;
            color: #b8860b;                /* Gold text when selected/hovered */
        }

        .selection-form button {
            background: linear-gradient(45deg, #004d00, #006600);
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 0;
            font-size: 1rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: all 0.3s ease;
            align-self: flex-start;
        }

        .selection-form button:hover {
            background: linear-gradient(45deg, #006600, #008000);
            transform: translateY(-1px);
            box-shadow: 0 3px 8px rgba(0,102,0,0.3);
        }

        .no-customers {
            text-align: center;
            padding: 40px 20px;
            color: #666;
            font-style: italic;
            background: #f8fff8;
            border: 1px dashed #e0f0e0;
            border-radius: 0;
            margin: 20px 0;
        }

        .btn-back {
            background: #f8f9fa;
            color: #004d00;
            padding: 8px 16px;
            text-decoration: none;
            font-weight: 500;
            border: 1px solid #e0f0e0;
        }

        .btn-back:hover {
            background: #e6f7e6;
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 15px;
            }

            .page-header {
                flex-direction: column;
                text-align: center;
            }

            .selection-form {
                padding: 20px;
            }

            .selection-form button {
                width: 100%;
                align-self: stretch;
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
            
            echo "<div class='page-header'>";
            echo "<h1>Select Registered Customer</h1>";
            echo "</div>";

            if ($resultAccount->num_rows > 0) {
                echo "<form class='selection-form' method='POST' action='registered_customer_step2.php'>";
                echo "<label for='accountID'>Customer</label>";
                echo "<select name='accountID' id='accountID' required>";
                echo "<option value=''>Select customer</option>";
                while ($rowAccount = $resultAccount->fetch_assoc()) {
                    echo "<option value='" . htmlspecialchars($rowAccount['accountID']) . "'>" . htmlspecialchars($rowAccount['name']) . "</option>";
                }
                echo "</select>";
                echo "<button type='submit'>Continue</button>";
                echo "<a href='manual_order.php' class='btn-back'> Back </a>";
                echo "</form>";
            } else {
                echo "<div class='no-customers'>No registered customers found</div>";
            }

            echo "</div>"; // .main-content
        }        
    ?>
</body>
</html>