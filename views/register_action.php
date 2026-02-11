<?php

include "../connection/connectdb.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ./register.php'); 
    exit;
}
else{
    
    if(isset($_FILES['profile']) && isset($_POST['name']) && isset($_POST['email']) && isset($_POST['birthday']) && isset($_POST['passcode']) && isset($_POST['confirmPasscode']) && isset($_POST['phoneNumber']) && isset($_POST['country']) && isset($_POST['street']) && isset($_POST['city']) && isset($_POST['township']) && isset($_POST['state']) && isset($_POST['postalCode']) && isset($_POST['completeAddress']) && isset($_POST['mapLink']) && isset($_POST['termAccept'])){
        
        $profile = $_FILES['profile']['name'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $birthday = $_POST['birthday'];
        $passcode = $_POST['passcode'];
        $phoneNumber = $_POST['phoneNumber'];
        $confirmPasscode = $_POST['confirmPasscode'];
        $country = $_POST['country'];
        $street = $_POST['street'];
        $city = $_POST['city'];
        $township = $_POST['township'];
        $state = $_POST['state'];
        $postalCode = $_POST['postalCode'];
        $completeAddress = $_POST['completeAddress'];
        $mapLink = $_POST['mapLink'];
        $termAccept = $_POST['termAccept'];

        // echo $name."<br>".$email."<br>".$birthday."<br>".$passcode."<br>".$country."<br>".$street."<br>".$city."<br>".$township."<br>".$state."<br>".$postalCode."<br>".$completeAddress."<br>".$mapLink."<br>".$termAccept."<br>";

        if($passcode!=$confirmPasscode){
            echo "Passcode Unmatch";
            header('Location: ./register.php');
            exit;
        }
        else{
            
            if (!isset($_FILES['profile']) || $_FILES['profile']['error'] !== UPLOAD_ERR_OK) {
                echo "Please choose a profile photo.";
                header ('Location: ./register.php');
                exit;
            }

            $allowedExt = ['jpg','jpeg','png','webp'];
            $ext = strtolower(pathinfo($_FILES['profile']['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $allowedExt, true)) {
                echo "Invalid file type. Please upload jpg, jpeg, png, or webp.";
                header ('Location: ./register.php');
                exit;
            }

            if ($_FILES['profile']['size'] > 5 * 1024 * 1024) {
                echo "File is too large (max 5MB).";
                header ('Location: ./register.php');
                exit;
            }

            $uploadDir = __DIR__ . '/../image/'; 
            if (!is_dir($uploadDir)) {
                die("Image folder not found: " . $uploadDir);
            }
            if (!is_writable($uploadDir)) {
                die("Image folder not writable: " . $uploadDir);
            }  
        
            $profileFileName = "profile_" . uniqid("", true) . "." . $ext;
            $targetPath = $uploadDir . $profileFileName;

            if (!move_uploaded_file($_FILES['profile']['tmp_name'], $targetPath)) {
                echo "Failed to upload profile photo.";
                header ('Location: ./register.php');
                exit;
            }

            $profile = $profileFileName;

            $queryRegisterProfile = "INSERT INTO photo (photoName) VALUES ('$profile');";

            if($conn->query($queryRegisterProfile) === TRUE){
                $profileID = $conn->insert_id;
                
                $queryRegisterAccount = "INSERT INTO account (profile, name, email, passcode, phoneNumber, birthday, roleID, registerDate)
                VALUES
                ('$profileID','$name', '$email', '$passcode', '$phoneNumber', '$birthday', 1, CURDATE());";
                
                if ($conn->query($queryRegisterAccount) === TRUE) {
    
                    $accountID = $conn->insert_id;
    
                    if(empty($mapLink)){
                        $queryRegisterAddress = "INSERT INTO address
                        (street, township, city, state, postalCode, country, completeAddress,mapLink, accountID)
                        VALUES
                        ('$street', '$township', '$city', '$state', '$postalCode', '$country', '$completeAddress','','$accountID');";
                    }
                    else{
                        $queryRegisterAddress = "INSERT INTO address
                        (street, township, city, state, postalCode, country, completeAddress, mapLink, accountID)
                        VALUES
                        ('$street', '$township', '$city', '$state', '$postalCode', '$country', '$completeAddress', '$mapLink', '$accountID');";
                    }
    
                    if ($conn->query($queryRegisterAddress) === TRUE) {
                       header ('Location: ./register.php');
                       exit;
                    }
                    else{
                        echo "ERROR REGISTRATION ADDRESS";
                        header ('Location: ./register.php');
                        exit;
                    }
                }
                else{
                    echo "Error Regsistration Account";
                    header ('Location: ./register.php');
                    exit;
                }
            }
            else{
                echo "Error Regsitration Profile Photo";
                header ('Location: ./register.php');
                exit;
            }
        }


    }
    else{
        echo "Data Required";
        header ('Location: ./register.php');
        exit;
    }

}

?>