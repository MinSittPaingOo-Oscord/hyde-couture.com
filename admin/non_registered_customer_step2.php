<?php

include "../connection/connectdb.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include './layout/login_error_message.php';
include './logInCheck.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $street = mysqli_real_escape_string($conn, $_POST['street']);
    $township = mysqli_real_escape_string($conn, $_POST['township']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $state = mysqli_real_escape_string($conn, $_POST['state']);
    $postalCode = mysqli_real_escape_string($conn, $_POST['postalCode']);
    $country = mysqli_real_escape_string($conn, $_POST['country']);
    $completeAddress = mysqli_real_escape_string($conn, $_POST['completeAddress']);
    $mapLink = mysqli_real_escape_string($conn, $_POST['mapLink'] ?? '');

    $insertAddress = "INSERT INTO address (street, township, city, `state`, postalCode, country, completeAddress, mapLink )
                      VALUES ('$street', '$township', '$city', '$state', '$postalCode', '$country', '$completeAddress', '$mapLink')";

    if (mysqli_query($conn, $insertAddress)) {
        $addressID = mysqli_insert_id($conn); // Step 2: Get addressID

        // Step 3: Save to orderr
        $paymentValid = (int)$_POST['paymentValid'];
        $orderDate = date('Y-m-d');
        $paymentStatus = (int)$_POST['paymentStatus'];
        $orderStatus = (int)$_POST['orderStatus'];
        $trackingStatus = (int)$_POST['trackingStatus'];
        $paymentType = (int)$_POST['paymentType'];
        $isManual = 1;
        $manualCustomerName = mysqli_real_escape_string($conn, $_POST['customer_name']);
        $manualNote = mysqli_real_escape_string($conn, $_POST['note']);

        $insertOrder = "INSERT INTO orderr (paymentValid, orderDate, paymentStatus, orderStatus, trackingStatus, paymentType, addressID, isManual, manualCustomerName, manualNote)
                        VALUES ($paymentValid, '$orderDate', $paymentStatus, $orderStatus, $trackingStatus, $paymentType, $addressID, $isManual, '$manualCustomerName', '$manualNote')";

        if (mysqli_query($conn, $insertOrder)) {
            $orderID = mysqli_insert_id($conn);

            header("Location: specific_manual_order_non_registered.php?orderID=" . $orderID);
            exit;
        } else {
            // Handle order insert error
            echo "Error inserting order: " . mysqli_error($conn);
            echo "<script>
            alert('Error saving order: " . addslashes(mysqli_error($conn)) . " Please try again.');
            window.location.href = 'non_registered_customer.php';
          </script>";
            exit; 
        }
    } else {
        // Handle address insert error
        echo "Error inserting address: " . mysqli_error($conn);
        echo "Error inserting order: " . mysqli_error($conn);
        echo "<script>
        alert('Error saving order: " . addslashes(mysqli_error($conn)) . " Please try again.');
        window.location.href = 'non_registered_customer.php';
      </script>";
        exit; 
    }
} else {
    // Not POST, perhaps redirect back
    echo "Error inserting order: " . mysqli_error($conn);
    echo "<script>
    alert('Error saving order: " . addslashes(mysqli_error($conn)) . " Please try again.');
    window.location.href = 'non_registered_customer.php';
    </script>";
    exit; 
}

mysqli_close($conn);

?>