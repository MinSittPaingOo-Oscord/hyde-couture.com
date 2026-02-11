<?php

include "../connection/connectdb.php";

if(isset($_FILES['payment_slip']) && isset($_POST['orderID'])){

    $payment_slip = $_FILES['payment_slip']['name'];
    $orderID = $_POST['orderID'];

    if (!isset($_FILES['payment_slip']) || $_FILES['payment_slip']['error'] !== UPLOAD_ERR_OK) {
        echo "Please choose a paymentslip";
        header ('Location: ./specific_manual_order_non_registered?orderID='.$orderID);
        exit;
    }

    $allowedExt = ['jpg','jpeg','png','webp'];
    $ext = strtolower(pathinfo($_FILES['payment_slip']['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedExt, true)) {
        echo "Invalid file type. Please upload jpg, jpeg, png, or webp.";
        header ('Location: ./specific_manual_order_non_registered?orderID='.$orderID);
        exit;
    }

    if ($_FILES['payment_slip']['size'] > 50 * 1024 * 1024) {
        echo "File is too large (max 50MB).";
        header ('Location: ./specific_manaul_order_non_registered.php?orderID='.$orderID);
        exit;
    }

    $uploadDir = __DIR__ . '/../image/'; 
    if (!is_dir($uploadDir)) {
        die("Image folder not found: " . $uploadDir);
        header ('Location: ./specific_manual_order_non_registered.php?orderID='.$orderID);
        exit;
    }
    if (!is_writable($uploadDir)) {
        die("Image folder not writable: " . $uploadDir);
        header ('Location: ./specific_manual_order_non_registered.php?orderID='.$orderID);
        exit;
    }  
        
    $paymentFileName = "paymentSlip_" . uniqid("", true) . "." . $ext;
    $targetPath = $uploadDir . $paymentFileName;

    if (!move_uploaded_file($_FILES['payment_slip']['tmp_name'], $targetPath)) {
        echo "Failed to upload paymentslip photo in the directory folder.";
        header ('Location: ./specific_manual_order_non_registered.php?orderID='.$orderID);
        exit;
    }

    $payment = $paymentFileName;

    $queryRegisterPayment = "INSERT INTO photo (photoName) VALUES ('$payment');";

    if($conn->query($queryRegisterPayment) === TRUE){

        $photoID = $conn->insert_id;

        $queryRegsiterPaymentSlip = "INSERT INTO paymentslip (paymentSlip,orderID) VALUES ('$photoID','$orderID');";

        if($conn->query($queryRegsiterPaymentSlip)===TRUE){
            echo "Everything is ok now";
            header ('Location: ./specific_manual_order_non_registered.php?orderID='.$orderID);
            exit;
        }
    }  
    else{
        echo "Error Regsitration Payment Slip";
        header ('Location: ./specific_manual_order_non_registered.php?orderID='.$orderID);
        exit;
    }
}

?>