<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['show_alert']) && isset($_SESSION['alert'])) {
    $msg = addslashes($_SESSION['alert']);
    echo "<script>alert('$msg');</script>";
    unset($_SESSION['alert']);  // Fixed typo: was ($_SESSION['alert']); should be unset
    unset($_SESSION['show_alert']);
}
?>