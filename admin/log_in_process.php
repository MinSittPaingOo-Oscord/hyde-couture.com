<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once('../connection/connectdb.php');

$email = $_POST['email'] ?? '';
$passcode = $_POST['passcode'] ?? '';
$pin = $_POST['pin'] ?? '';
$currentPage = $_POST['currentPage'] ?? 'profile.php';
$login = false;

$stmt = $conn->prepare("SELECT * FROM account WHERE email = ? AND passcode = ? AND pin = ?");
$stmt->bind_param("sss", $email, $passcode, $pin);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if ($row['roleID'] == 2) {
        $_SESSION['accountID'] = $row['accountID'];  // Store in session
        $_SESSION['login'] = true;  // Store in session
        $_SESSION['show_login_modal'] = true;
        $login = true;
        header("Location: ./" . $currentPage);  // Redirect without GET params
        exit();
    } else {
        $_SESSION['alert'] = "You are not admin";
        $_SESSION['show_alert'] = true;
        header("Location: ./" . $currentPage);
        exit();
    }
} else {
    $_SESSION['alert'] = "Invalid Login";
    $_SESSION['show_alert'] = true;
    header("Location: ./" . $currentPage);
    exit();
}

$stmt->close();
?>