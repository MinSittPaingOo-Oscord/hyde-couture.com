<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$accountID = $_SESSION['accountID'] ?? 0;  // Get from session, default 0
$login = $_SESSION['login'] ?? false;  // Get from session, default false

if (is_null($accountID) || $accountID <= 0) {
    include './log_in_modal.php';
}
?>