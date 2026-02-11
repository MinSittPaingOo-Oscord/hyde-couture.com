<?php 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../connection/connectdb.php';

include './layout/login_error_message.php';
$currentPage = "edit_admin_profile.php";
include './logInCheck.php'; 

$userID = $_GET['userID'] ?? $_SESSION['accountID'];

if (isset($_GET['delete_address'])) {
    $deleteID = $_GET['delete_address'];
    $deleteQuery = "DELETE FROM address WHERE addressID = $deleteID";
    $conn->query($deleteQuery);
    $_SESSION['success_message'][] = 'Address Deleted';
    $updated = true;
    header("Location: edit_admin_profile.php?userID=$userID");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    //Update Personal Detail
    if (isset($_POST['update_account'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phoneNumber = $_POST['phoneNumber'];
        $birthday = $_POST['birthday'];

        $updateQuery = "UPDATE account SET name = '$name', email = '$email', phoneNumber = '$phoneNumber', birthday = '$birthday' WHERE accountID = $userID";
        $conn->query($updateQuery);
        $_SESSION['success_message'][] = 'Profile Edit Success';
        $updated = true;

        if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] == 0) {
            $fileName = basename($_FILES['profile_photo']['name']);
            $targetPath = '../image/' . $fileName;
            move_uploaded_file($_FILES['profile_photo']['tmp_name'], $targetPath);

            $insertPhoto = "INSERT INTO photo (photoName) VALUES ('$fileName')";
            $conn->query($insertPhoto);
            $newPhotoID = $conn->insert_id;

            $updateProfile = "UPDATE account SET profile = $newPhotoID WHERE accountID = $userID";
            $conn->query($updateProfile);
            $_SESSION['success_message'][] = 'Profile Photo Updated';
            $updated = true;
        }

        header("Location: edit_admin_profile.php?userID=$userID");
        exit();
    }

    //Update Address and Add new Address
    if (isset($_POST['add_address'])) {
        $street = $_POST['street'];
        $township = $_POST['township'];
        $city = $_POST['city'];
        $state = $_POST['state'];
        $postalCode = $_POST['postalCode'];
        $country = $_POST['country'];
        $completeAddress = $_POST['completeAddress'];
        $mapLink = $_POST['mapLink'];

        $insertQuery = "INSERT INTO address (street, township, city, state, postalCode, country, completeAddress, mapLink, accountID) 
                        VALUES ('$street', '$township', '$city', '$state', '$postalCode', '$country', '$completeAddress', '$mapLink', $userID)";
        $conn->query($insertQuery);
        $_SESSION['success_message'][] = 'New Address Registered';
        $updated = true;

        header("Location: edit_admin_profile.php?userID=$userID");
        exit();
    }

    //Update Security
    if (isset($_POST['update_security'])) {
        $current_passcode = $_POST['current_passcode'];
        $new_passcode = $_POST['new_passcode'];
        $confirm_passcode = $_POST['confirm_passcode'];
        $new_pin = $_POST['new_pin'];
        $confirm_pin = $_POST['confirm_pin'];
    
        $securityQuery = "SELECT * FROM account WHERE accountID = $userID";
        $securityResult = $conn->query($securityQuery);
        $securityRow = $securityResult->fetch_assoc();
    
        if ($securityRow['passcode'] === $current_passcode) {
            if ($new_passcode === $confirm_passcode && !empty($new_passcode)) {
                $updatePasscode = "UPDATE account SET passcode = '$new_passcode' WHERE accountID = $userID";
                $conn->query($updatePasscode);
                $_SESSION['success_message'][] = 'New Passcode Updated';
                $updated = true;
            }
            if ($new_pin === $confirm_pin && !empty($new_pin)) {
                $updatePin = "UPDATE account SET pin = '$new_pin' WHERE accountID = $userID";
                $conn->query($updatePin);
                $_SESSION['success_message'][] = 'New Pin Updated';
                $updated = true;
            }
        }
    
        header("Location: edit_admin_profile.php?userID=$userID");
        exit();

    }

}

$query = "SELECT * FROM account JOIN photo ON account.profile = photo.photoID WHERE account.accountID = $userID";
$result = $conn->query($query);
$row = $result->fetch_assoc();

$query_address = "SELECT * FROM address WHERE accountID = $userID";
$result_address = $conn->query($query_address);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Admin Profile</title>
    <?php include "./layout/header.php"; ?>
    <style>
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
                border-radius: 0; 
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

            .profile-main {
                flex: 1;
                padding: 40px 40px;
            }

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

            .map-link {
                color: #00693E;
                text-decoration: none;
                transition: color 0.3s ease;
            }

            .map-link:hover {
                color: #004d2b;
                text-decoration: underline;
            }

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
                margin-top : 40px;
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

            .no-info, .no-user {
                text-align: center;
                color: #666;
                font-size: 1.1rem;
                padding: 20px;
                background-color: #f8f9fa;
                border-radius: 0;
            }

            .edit-button-container {
                margin-top: 20px;
                text-align: left;
            }

            .btn-edit {
                background-color: #00693E;
                color: #ffffff;
                padding: 12px 30px;
                text-decoration: none;
                border-radius: 0;
                font-size: 1rem;
                text-transform: uppercase;
                letter-spacing: 1px;
                transition: background-color 0.3s ease, transform 0.3s ease;
                margin-top : 40px;
            }

            .btn-edit:hover {
                background-color: #004d2b;
                transform: translateY(-3px);
                color : #ffffff !important;
            }

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
            .form-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 20px;
            }

            .form-item {
                display: flex;
                flex-direction: column;
            }

            .form-item label {
                font-size: 0.9rem;
                color: #666;
                text-transform: uppercase;
                margin-bottom: 5px;
            }

            .form-item input {
                padding: 10px;
                border: 1px solid #e0e0e0;
                border-radius: 0;
                font-size: 1rem;
                transition: border-color 0.3s ease;
            }

            .form-item input:focus {
                border-color: #00693E;
                outline: none;
            }

            .edit-form {
                margin-bottom: 30px;
            }

            .address-card {
                background-color: #f8f9fa;
                padding: 20px;
                border-radius: 0;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
                margin-bottom: 20px;
                position: relative;
            }

            .btn-delete {
                background-color: #dc3545;
                color: #ffffff;
                padding: 10px 20px;
                text-decoration: none;
                border-radius: 0;
                font-size: 1rem;
                text-transform: uppercase;
                letter-spacing: 1px;
                transition: background-color 0.3s ease, transform 0.3s ease;
                display: inline-block;
                margin-top: 40px;
            }

            .btn-delete:hover {
                background-color: #c82333;
                transform: translateY(-3px);
            }

            .sub-title {
                font-size: 1.3rem;
                color: #00693E;
                margin: 30px 0 15px;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }
    </style>
</head>
<body>
    <?php
        include "nav.php";
        $login = $_SESSION['login'] ?? false;  
        if($login == true) {
        echo "<div class='main-content'>";

        if (isset($_SESSION['success_message'])) {
            foreach ($_SESSION['success_message'] as $msg) {
                echo "<script>alert('$msg');</script>";
            }
            unset($_SESSION['success_message']);
        }
        
        echo "<h1 class='page-title'>Edit Profile</h1>";

        echo "<div class='profile-container'>";
        echo "<div class='profile-sidebar'>";
        echo "<img src='../image/".$row['photoName']."' alt='Profile Photo' class='profile-img'>";
        echo "<h2 class='user-name'>".$row['name']."</h2>";
        // echo "<p class='user-id'>ID: ".$row['accountID']."</p>";
        echo "</div>";
        echo "<div class='profile-main'>";
        echo "<section class='info-section'>";
        echo "<h3 class='section-title'>Personal Details</h3>";
        echo "<form method='POST' enctype='multipart/form-data' class='edit-form'>";
        echo "<div class='form-grid'>";
        echo "<div class='form-item'><label>Name</label><input type='text' name='name' value='".$row['name']."' required></div>";
        echo "<div class='form-item'><label>Email</label><input type='email' name='email' value='".$row['email']."' required></div>";
        echo "<div class='form-item'><label>Birthday</label><input type='date' name='birthday' value='".$row['birthday']."' required></div>";
        echo "<div class='form-item'><label>Phone Number</label><input type='text' name='phoneNumber' value='".$row['phoneNumber']."' required></div>";
        echo "<div class='form-item'><label>Profile Photo</label><input type='file' name='profile_photo' accept='image/*'></div>";
        echo "</div>";
        echo "<button type='submit' name='update_account' class='btn btn-primary'>Update Profile</button>";
        echo "</form>";
        echo "</section>";
        ?>

        <section class='info-section'>
            <h3 class='section-title'>Update Passcode and PIN</h3>
            <form method='POST' class='edit-form'>
            <div class='form-grid'>
                <div class='form-item'><label>Current Passcode</label><input type='password' name='current_passcode' required></div>
                <div class='form-item'><label>New Passcode</label><input type='password' name='new_passcode'></div>
                <div class='form-item'><label>Confirm New Passcode</label><input type='password' name='confirm_passcode'></div>
                <div class='form-item'><label>New PIN (6 digits)</label><input type='password' name='new_pin' maxlength='6' pattern='\d{6}'></div>
                <div class='form-item'><label>Confirm New PIN</label><input type='password' name='confirm_pin' maxlength='6' pattern='\d{6}'></div>
            </div>
            <button type='submit' name='update_security' class='btn btn-primary'>Update Security</button>
        </form>
        </section>

        <?php 
        echo "<section class='info-section address-section'>";
        echo "<h3 class='section-title'>Addresses</h3>";
        if ($result_address && $result_address->num_rows > 0) {
            while ($row_address = $result_address->fetch_assoc()) {
                echo "<div class='address-card'>";
                echo "<div class='info-grid'>";
                echo "<div class='info-item'><span class='label'>Street</span><span class='value'>".$row_address['street']."</span></div>";
                echo "<div class='info-item'><span class='label'>Township</span><span class='value'>".$row_address['township']."</span></div>";
                echo "<div class='info-item'><span class='label'>City</span><span class='value'>".$row_address['city']."</span></div>";
                echo "<div class='info-item'><span class='label'>State</span><span class='value'>".$row_address['state']."</span></div>";
                echo "<div class='info-item'><span class='label'>Postal Code</span><span class='value'>".$row_address['postalCode']."</span></div>";
                echo "<div class='info-item'><span class='label'>Country</span><span class='value'>".$row_address['country']."</span></div>";
                echo "<div class='info-item'><span class='label'>Complete Address</span><span class='value'>".$row_address['completeAddress']."</span></div>";
                if (!empty($row_address['mapLink'])) {
                    echo "<div class='info-item'><span class='label'>Map Link</span><span class='value'><a href='".$row_address['mapLink']."' class='map-link' target='_blank'>View on Map</a></span></div>";
                }
                echo "</div>";
                echo "<a href='edit_admin_profile.php?userID=$userID&delete_address=".$row_address['addressID']."' class='btn btn-delete' onclick='return confirm(\"Are you sure you want to delete this address?\");'>Delete Address</a>";
                echo "</div>";
            }
        } else {
            echo "<p class='no-info'>No addresses available.</p>";
        }

        echo "<h4 class='sub-title'>Add New Address</h4>";
        echo "<form method='POST' class='edit-form'>";
        echo "<div class='form-grid'>";
        echo "<div class='form-item'><label>Street</label><input type='text' name='street' required></div>";
        echo "<div class='form-item'><label>Township</label><input type='text' name='township' required></div>";
        echo "<div class='form-item'><label>City</label><input type='text' name='city' required></div>";
        echo "<div class='form-item'><label>State</label><input type='text' name='state' required></div>";
        echo "<div class='form-item'><label>Postal Code</label><input type='text' name='postalCode' required></div>";
        echo "<div class='form-item'><label>Country</label><input type='text' name='country' required></div>";
        echo "<div class='form-item'><label>Complete Address</label><input type='text' name='completeAddress' required></div>";
        echo "<div class='form-item'><label>Map Link</label><input type='url' name='mapLink'></div>";
        echo "</div>";
        echo "<button type='submit' name='add_address' class='btn btn-primary'>Add Address</button>";
        echo "</form>";
        echo "</section>";

        echo "</div>";
        echo "</div>";

        echo "</div>";
        }
    ?>
</body>
</html>