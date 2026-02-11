<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
        session_destroy();  // Destroy all session data
        header("Location: profile.php");  // Redirect after logout
        exit();
    } else {
        // If no, redirect back to profile or previous page
        header("Location: profile.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout Confirmation</title>
    <?php include "./layout/header.php"; ?>
    <style>
        body {
            background-color: #ffffff; /* Clean white background */
            font-family: 'Arial', sans-serif; /* Simple, clean font */
            color: #333333; /* Dark text for readability */
        }
        .logout-container {
            background-color: #f8f9fa; /* Light gray for subtle contrast */
            color: #333333;
            padding: 40px;
            text-align: center;
            width: 100%; /* Full width */
            max-width: none; /* No max width restriction */
            border: 1px solid #dee2e6; /* Subtle border */
        }
        h2 {
            color: #333333; /* Neutral dark color */
            font-size: 1.8rem;
            margin-bottom: 20px;
        }
        .btn-confirm-yes {
            background-color: #dc3545; /* Standard red for Yes */
            border-color: #dc3545;
            color: #ffffff;
            padding: 10px 20px;
        }
        .btn-confirm-yes:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }
        .btn-confirm-no {
            background-color: #6c757d; /* Standard gray for No */
            border-color: #6c757d;
            color: #ffffff;
            padding: 10px 20px;
        }
        .btn-confirm-no:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }
        form {
            justify-content: center;
            width: 100%; /* Form takes full width */
        }
    </style>
</head>
<body>
    <?php include "nav.php"; ?>
    
    <div class="main-content d-flex justify-content-center align-items-center flex-column vh-100">
        <div class="logout-container">
            <h2>Are you sure you want to log out?</h2>
            <form method="POST" action="log_out.php" class="d-flex gap-3">
                <button type="submit" name="confirm" value="yes" class="btn btn-confirm-yes">Yes</button>
                <button type="submit" name="confirm" value="no" class="btn btn-confirm-no">No</button>
            </form>
        </div>
    </div>
</body>
</html>