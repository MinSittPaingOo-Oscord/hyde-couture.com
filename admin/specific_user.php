<?php 

include "../connection/connectdb.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include './layout/login_error_message.php';
$currentPage = "specific_user.php";
include './logInCheck.php'; 

$userID = $_GET['userID'];

$query = "SELECT * FROM account JOIN photo ON account.profile = photo.photoID WHERE account.roleID = 1 and account.accountID = $userID";
$result = $conn->query($query);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User</title>
    <?php include "./layout/header.php"; ?>
    <style>
        style.css additions
/* Rolex green theme: Primary #00693E, Darker #004d2b, Light bg #f8f9fa, Text #333, Accent #ffffff */

/* General resets and typography */
body {
    font-family: 'Helvetica Neue', Arial, sans-serif;
    line-height: 1.6;
    color: #333;
    background-color: #f8f9fa;
}

/* Page title */
.page-title {
    font-size: 2.2rem;
    color: #00693E;
    text-align: center;
    margin-top : 40px;
    margin-bottom: 40px;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    position: relative;
}

.page-title::after {
    content: '';
    display: block;
    width: 60px;
    height: 3px;
    background-color: #00693E;
    margin: 10px auto 0;
}

/* Profile container */
.profile-container {
    display: flex;
    flex-wrap: wrap;
    max-width: 1200px;
    margin: 0 auto;
    background-color: #ffffff;
    border-radius: 0;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    overflow: hidden;
}

/* Sidebar */
.profile-sidebar {
    flex: 0 0 300px;
    padding: 40px 30px;
    background-color: #ffffff;
    color: #333333;
    text-align: center;
}

.profile-img {
    width: 200px;
    height: 200px;
    object-fit: cover;
    border-radius: 0; /* Fully square */
    border: 1px solid #e0e0e0;
    margin-bottom: 20px;
    transition: transform 0.4s ease, box-shadow 0.4s ease;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.profile-sidebar:hover .profile-img {
    transform: scale(1.05);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2);
}

.user-name {
    font-size: 1.8rem;
    margin: 0 0 10px;
    text-transform: capitalize;
    color: #00693E;
}

.user-id {
    font-size: 1.1rem;
    color: #666666;
}

/* Main content */
.profile-main {
    flex: 1;
    padding: 40px 40px;
}

/* Sections */
.info-section {
    margin-bottom: 40px;
}

.section-title {
    font-size: 1.5rem;
    color: #00693E;
    margin-bottom: 20px;
    text-transform: uppercase;
    letter-spacing: 1px;
    position: relative;
}

.section-title::after {
    content: '';
    display: block;
    width: 40px;
    height: 2px;
    background-color: #004d2b;
    margin-top: 8px;
}

/* Info grid */
.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.info-item {
    background-color: #f8f9fa;
    padding: 15px;
    border-radius: 0;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.info-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
}

.label {
    display: block;
    font-size: 0.9rem;
    color: #666;
    text-transform: uppercase;
    margin-bottom: 5px;
}

.value {
    font-size: 1.1rem;
    color: #333;
    word-break: break-word;
}

/* Map link */
.map-link {
    color: #00693E;
    text-decoration: none;
    transition: color 0.3s ease;
}

.map-link:hover {
    color: #004d2b;
    text-decoration: underline;
}

/* Order section */
.order-buttons {
    display: flex;
    justify-content: center;
    gap: 20px;
    flex-wrap: wrap;
}

.btn {
    padding: 12px 30px;
    text-decoration: none;
    border-radius: 0;
    font-size: 1rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: background-color 0.3s ease, transform 0.3s ease;
}

.btn-primary {
    background-color: #00693E;
    color: #ffffff;
}

.btn-primary:hover {
    background-color: #004d2b;
    transform: translateY(-3px);
}

.btn-secondary {
    background-color: #f8f9fa;
    color: #00693E;
    border: 2px solid #00693E;
}

.btn-secondary:hover {
    background-color: #00693E;
    color: #ffffff;
    transform: translateY(-3px);
}

/* No info / no user */
.no-info, .no-user {
    text-align: center;
    color: #666;
    font-size: 1.1rem;
    padding: 20px;
    background-color: #f8f9fa;
    border-radius: 0;
}

/* Responsive adjustments */
@media (max-width: 1024px) {
    .profile-container {
        flex-direction: column;
    }
    
    .profile-sidebar {
        flex: 0 0 auto;
        padding: 30px;
    }
    
    .profile-main {
        padding: 30px;
    }
}

@media (max-width: 768px) {
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .order-buttons {
        flex-direction: column;
    }
    
    .btn {
        width: 100%;
        margin-bottom: 10px;
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
        echo "<h1 class='page-title'>User Profile</h1>";

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='profile-container'>";
                echo "<div class='profile-sidebar'>";
                echo "<img src='../image/".$row['photoName']."' alt='Profile Photo' class='profile-img'>";
                echo "<h2 class='user-name'>".$row['name']."</h2>";
                // echo "<p class='user-id'>ID: ".$row['accountID']."</p>";
                echo "</div>";
                echo "<div class='profile-main'>";
                echo "<section class='info-section'>";
                echo "<h3 class='section-title'>Personal Details</h3>";
                echo "<div class='info-grid'>";
                echo "<div class='info-item'><span class='label'>User ID</span><span class='value'>".$row['accountID']."</span></div>";
                echo "<div class='info-item'><span class='label'>Registration Date</span><span class='value'>".$row['registerDate']."</span></div>";
                echo "<div class='info-item'><span class='label'>Email</span><span class='value'>".$row['email']."</span></div>";
                echo "<div class='info-item'><span class='label'>Birthday</span><span class='value'>".$row['birthday']."</span></div>";
                echo "<div class='info-item'><span class='label'>Phone Number</span><span class='value'>".$row['phoneNumber']."</span></div>";
                // echo "<div class='info-item'><span class='label'>Profile Photo</span><span class='value'>".$row['photoName']."</span></div>";
                echo "</div>";
                echo "</section>";

                //__________
                $query_address = "SELECT * FROM address WHERE address.accountID = ".$row['accountID'];
                $result_address = $conn->query($query_address);

                echo "<section class='info-section address-section'>";
                echo "<h3 class='section-title'>Address</h3>";
                if ($result_address && $result_address->num_rows > 0) {
                    while ($row_address = $result_address->fetch_assoc()) {
                        echo "<div class='info-grid'>";
                        echo "<div class='info-item'><span class='label'>Street</span><span class='value'>".$row_address['street']."</span></div>";
                        echo "<div class='info-item'><span class='label'>Township</span><span class='value'>".$row_address['township']."</span></div>";
                        echo "<div class='info-item'><span class='label'>City</span><span class='value'>".$row_address['city']."</span></div>";
                        echo "<div class='info-item'><span class='label'>State</span><span class='value'>".$row_address['state']."</span></div>";
                        echo "<div class='info-item'><span class='label'>Postal Code</span><span class='value'>".$row_address['country']."</span></div>";
                        echo "<div class='info-item'><span class='label'>Complete Address</span><span class='value'>".$row_address['completeAddress']."</span></div>";
                        if (!empty($row_address['mapLink'])) {
                            echo "<div class='info-item'><span class='label'>Map Link</span><span class='value'><a href='".$row_address['mapLink']."' class='map-link' target='_blank'>View on Map</a></span></div>";
                        }
                        echo "</div>";
                    }
                } else {
                    echo "<p class='no-info'>No address information available.</p>";
                }
                echo "</section>";

                echo "<section class='order-section'>";
                echo "<h3 class='section-title'>Orders</h3>";
                echo "<div class='order-buttons'>";
                echo "<a href='user_active_order.php?userID=".$row['accountID']."' class='btn btn-primary'>Active Orders</a>";
                echo "<a href='user_completed_order.php?userID=".$row['accountID']."' class='btn btn-secondary'>Completed Orders</a>";
                echo "<a href='user_failed_order.php?userID=".$row['accountID']."' class='btn btn-secondary'>Failed Orders</a>";
                echo "<a href='user_return_order.php?userID=".$row['accountID']."' class='btn btn-secondary'>Return Orders</a>";
                echo "<a href='user_cancal_order.php?userID=".$row['accountID']."' class='btn btn-secondary'>Cancal Orders</a>";
                echo "<a href='user_manual_order.php?userID=".$row['accountID']."' class='btn btn-secondary'>Manual Orders</a>";
                echo "</div>";
                echo "</section>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<p class='no-user'>No user found with the provided ID.</p>";
        }

        echo "</div>";
        }
    ?>
</body>
</html>