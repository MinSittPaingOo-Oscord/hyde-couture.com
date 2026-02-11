<?php
include "../connection/connectdb.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include './layout/login_error_message.php';
$currentPage = "active_order.php";
include './logInCheck.php'; 

$orderInsert = false;
$orderID = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['paymentStatus'], $_POST['orderStatus'], $_POST['trackingStatus'], $_POST['paymentType'], $_POST['paymentValid'], $_POST['accountID'])) {
        header("Location: registered_customer_step3.php?error=missing_fields");
        exit;
    }

    $paymentStatus = $_POST['paymentStatus'] ?? null;
    $orderStatus = $_POST['orderStatus'] ?? null;
    $trackingStatus = $_POST['trackingStatus'] ?? null;
    $paymentType = $_POST['paymentType'] ?? null;
    $paymentValid = $_POST['paymentValid'] ?? null;
    $accountID = $_POST['accountID'] ?? null;
    $addressID = $_POST['addressID'] ?? 0;

    if ($accountID && $paymentStatus && $orderStatus && $trackingStatus && $paymentType && $paymentValid) {
        if (isset($_POST['country']) && isset($_POST['street']) && isset($_POST['city']) && isset($_POST['township']) && isset($_POST['state']) && isset($_POST['postalCode']) && isset($_POST['completeAddress']) && isset($_POST['addressID'])) {
           
            // New address
            if($_POST['addressID']==0){
                        $country = $_POST['country'];
                        $street = $_POST['street'];
                        $city = $_POST['city'];
                        $township = $_POST['township'];
                        $state = $_POST['state'];
                        $postalCode = $_POST['postalCode'];
                        $completeAddress = $_POST['completeAddress'];
                        $mapLink = $_POST['mapLink'] ?? '';
            
                        $queryRegisterAddress = "INSERT INTO address (street, township, city, state, postalCode, country, completeAddress, mapLink, accountID) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        $stmt = $conn->prepare($queryRegisterAddress);
                        $stmt->bind_param("sssssssss", $street, $township, $city, $state, $postalCode, $country, $completeAddress, $mapLink, $accountID);
                        if ($stmt->execute()) {
                            $addressID = $conn->insert_id;
            
                            $queryInsertOrder = "INSERT INTO orderr (paymentValid, orderDate, paymentStatus, orderStatus, trackingStatus, accountID, paymentType, addressID, isManual) VALUES (?, CURDATE(), ?, ?, ?, ?, ?, ?, 1)";
                            $stmtOrder = $conn->prepare($queryInsertOrder);
                            $stmtOrder->bind_param("iiiiiii", $paymentValid, $paymentStatus, $orderStatus, $trackingStatus, $accountID, $paymentType, $addressID);
                            if ($stmtOrder->execute()) {
                                $orderID = $conn->insert_id;
                                $orderInsert = true;
                                header("Location: specific_manual_order.php?orderID=" . $orderID);
                                exit;
                            }
                            $stmtOrder->close();
                        }
                        $stmt->close();
            }
           
        } elseif (isset($_POST['addressID']) && $_POST['addressID']!=0) {
            // Old address
            $addressID = $_POST['addressID'];

            $queryInsertOrder = "INSERT INTO orderr (paymentValid, orderDate, paymentStatus, orderStatus, trackingStatus, accountID, paymentType, addressID, isManual) VALUES (?, CURDATE(), ?, ?, ?, ?, ?, ?, 1)";
            $stmt = $conn->prepare($queryInsertOrder);
            $stmt->bind_param("iiiiiii", $paymentValid, $paymentStatus, $orderStatus, $trackingStatus, $accountID, $paymentType, $addressID);
            if ($stmt->execute()) {
                $orderID = $conn->insert_id;
                $orderInsert = true;
                header("Location: specific_manual_order.php?orderID=" . $orderID);
                exit;
            }
            $stmt->close();
        }
    }
}

if (!$orderID && isset($_GET['orderID'])) {
    $orderID = intval($_GET['orderID']);
}

if (!$orderID) {
    header("Location: manual_order.php?error=no_order_id");
    exit;
}

?>