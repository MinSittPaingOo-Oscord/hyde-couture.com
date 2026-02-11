<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include './log_in_check.php'; 

$login = $_SESSION['login'] ?? false;  
if($login == true) {
    $userID = $_SESSION['accountID'];
}

include '../connection/connectdb.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['passcode_change'])) {

    $oldPasscode = $_POST['oldPasscode'];
    $newPasscode1 = $_POST['newPasscode1'];
    $newPasscode2 = $_POST['newPasscode2'];

    if($newPasscode1!=$newPasscode2){
        echo "Passcode Unmatch";
        header("Location: profile.php");
        exit;
    }
    else{
        $queryAccount = "SELECT * FROM account WHERE accountID = $userID";
        $resultAccount = $conn->query($queryAccount);

        if($resultAccount && $resultAccount->num_rows > 0 && $rowAccount = $resultAccount->fetch_assoc()) {
            if($rowAccount['passcode']===$oldPasscode){

                $updateQuery = "UPDATE account SET passcode = '$newPasscode1' WHERE accountID = $userID";
                $conn->query($updateQuery);
                $_SESSION['success_message'][] = 'Profile Edit Success';
                $updated = true;
                header("Location: profile.php");
                exit;
            }
            else{
                echo "Old Passcode Wrong";
                header("Location: profile.php");
                exit;
            }
        }


    }

    }
}
?>