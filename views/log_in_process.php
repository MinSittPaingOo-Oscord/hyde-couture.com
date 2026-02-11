<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once('../connection/connectdb.php');

$email = $_POST['email'] ?? '';
$passcode = $_POST['passcode'] ?? '';

$stmt = $conn->prepare("SELECT * FROM account WHERE email = ? AND passcode = ?");
$stmt->bind_param("ss", $email, $passcode);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['accountID'] = $row['accountID']; 
        $_SESSION['login'] = true;  
        $_SESSION['show_login_modal'] = true;
        header("Location: /hyde.com/views/profile.php");
        exit();        
        exit();

} else {
    $_SESSION['alert'] = "Invalid Login";
    $_SESSION['show_alert'] = true;
    $_SESSION['login'] = false;
    header("Location: /hyde.com/views/profile.php");
    exit();
}

$stmt->close();
?>