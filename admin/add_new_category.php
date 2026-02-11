<?php 

include "../connection/connectdb.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include './layout/login_error_message.php';
$currentPage = "add_new_category.php";
include './logInCheck.php'; 

$query_parent_category = "SELECT * FROM category WHERE parentID IS NULL";
$result_parent_category = $conn->query($query_parent_category);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Category</title>
    <?php include "./layout/header.php"; ?>
    <style>
            body {
                background-color: #f8f8f8;
                font-family: 'Arial', sans-serif;
                color: #333;
            }

            .page-header {
                background: linear-gradient(135deg, #004d00, #002600);
                color: white;
                padding: 20px 25px;
                border-radius: 0;
                margin: 0 0 20px 0;
                display: flex;
                justify-content: space-between;
                align-items: center;
                flex-wrap: wrap;
                gap: 15px;
                margin-bottom:0px;
            
            }

            .page-header h1 {
                margin: 0;
                font-size: 1.6rem;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 1px;
            }

            .header-actions {
                display: flex;
                gap: 12px;
                align-items: center;
                flex-wrap: wrap;
            }

            .section-title {
                color: #004d00;
                font-size: 1.4rem;
                margin: 30px 0 15px 0;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 1px;
                border-bottom: 2px solid #e0f0e0;
                padding-bottom: 10px;
            
            }

            .add-form {
                background: white;
                padding: 30px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.08);
                border-radius: 0;
                width: 100%;
                margin: 0 auto;

            }

            .form-group {
                margin-bottom: 20px;
            }

            .form-group label {
                display: block;
                margin-bottom: 8px;
                font-weight: 500;
                color: #004d00;
                font-size: 1rem;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            .form-input,
            .form-select {
                width: 100%;
                padding: 12px 15px;
                margin-bottom: 5px;
                border: 1px solid #ddd;
                border-radius: 0;
                box-sizing: border-box;
                font-size: 1rem;
                color: #333;
                background: #f8fff8;
                transition: border-color 0.3s ease;
            }

            .form-input:focus,
            .form-select:focus {
                border-color: #004d00;
                outline: none;
                box-shadow: 0 0 0 3px rgba(0,77,0,0.1);
            }

            .btn-submit {
                padding: 12px 24px;
                background: linear-gradient(45deg, #004d00, #006600);
                color: white;
                border: none;
                border-radius: 0;
                font-size: 1rem;
                font-weight: 500;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                transition: all 0.3s ease;
                cursor: pointer;
                display: block;
                width: 100%;
                margin-bottom: 20px;
            }

            .btn-submit:hover {
                background: linear-gradient(45deg, #006600, #008000);
                transform: translateY(-1px);
                box-shadow: 0 3px 8px rgba(0,102,0,0.3);
            }

            @media (max-width: 768px) {
                .add-form {
                    padding: 20px;
                }

                .btn-submit {
                    font-size: 0.95rem;
                }
            }
    </style>
</head>
<body>
    <?php
        include "nav.php";
        $login = $_SESSION['login'] ?? false;
        if($login == true) {
            echo "<div class='main-content'>";

            echo "<div class='page-header'>";
            echo "<h1>Add New Category</h1>";
            echo "<div class='header-actions'>";
            echo "</div>";
            echo "</div>";

            echo "<form action='create_category.php' method='POST' class='add-form'>";

            echo "<div class='form-group'>";
            echo "<label for='categoryName'>Category Name:</label>";
            echo "<input type='text' id='categoryName' name='categoryName' required class='form-input'>";
            echo "</div>";

            echo "<div class='form-group'>";
            echo "<label for='parentCategory'>Parent Category:</label>";
            echo "<select id='parentCategory' name='parentCategory' class='form-select'>";
                echo "<option value=''>No Parent (Top-Level Category)</option>";
                if ($result_parent_category && $result_parent_category->num_rows > 0) {
                    while ($row_parent_category = $result_parent_category->fetch_assoc()) {
                        echo "<option value='".$row_parent_category['categoryID']."'>".$row_parent_category['categoryName']."</option>";
                    }
                }
            echo "</select>";
            echo "</div>";

            echo "<button type='submit' class='btn-submit'>Add Category</button>";
            echo "<button type='button' class='btn-submit' onclick=\"location.href='category.php'\">Back</button>";

            echo "</form>";

            echo "</div>";
        }
    ?>
</body>
</html>