<?php 

include "../connection/connectdb.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include './layout/login_error_message.php';
$currentPage = "edit_parent_category.php";
include './logInCheck.php'; 

$categoryID = $_GET['categoryID'];

$query = "SELECT * FROM category WHERE category.categoryID = $categoryID";
$result = $conn->query($query);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Child Category</title>
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
            /* margin: 0 0 20px 0; */
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            /* gap: 15px; */
            margin-bottom : 0px !important;
            
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

        .edit-form {
            background: white;
            padding: 30px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            border-radius: 0;
            /* max-width: 600px; */
            margin: 0 auto;
            width: 100%;

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
            margin-bottom : 20px;
        }

        .btn-submit:hover {
            background: linear-gradient(45deg, #006600, #008000);
            transform: translateY(-1px);
            box-shadow: 0 3px 8px rgba(0,102,0,0.3);
        }

        .no-items {
            text-align: center;
            padding: 40px 20px;
            color: #666;
            font-style: italic;
            background: #f8fff8;
            border: 1px dashed #e0f0e0;
            border-radius: 0;
            margin: 20px auto;
            max-width: 600px;
        }

        @media (max-width: 768px) {
            .edit-form {
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
        echo "<h1>Edit Child Category</h1>";
        echo "<div class='header-actions'>";
        echo "</div>";
        echo "</div>";

        if ($result && $result->num_rows > 0 && $row = $result->fetch_assoc()) {
            echo "<form action='update_child_category.php' method='POST' class='edit-form'>";

            echo "<div class='form-group'>";
            echo "<label for='categoryName'>Category Name:</label>";
            echo "<input type='text' id='categoryName' value='".$row['categoryName']."' name='categoryName' required class='form-input'>";
            echo "</div>";

    
                $query_child_parent_category = "SELECT * FROM category WHERE category.parentID IS NULL";
                $result_child_parent_category = $conn->query($query_child_parent_category);
                    
                if ($result_child_parent_category && $result_child_parent_category->num_rows > 0 ) {

                    echo "<div class='form-group'>";
                    echo "<label for='parentCategory'>Parent Category:</label>";
                        
                    echo "<select name='parentCategory' id='parentCategory' class='form-select'>";

                    while ($row_child_parent_category = $result_child_parent_category->fetch_assoc()) {
                        echo "<option value='".$row_child_parent_category['categoryID']."' ".($row_child_parent_category['categoryID'] == $row['parentID'] ? 'selected' : '').">".$row_child_parent_category['categoryName']."</option>";
                    }

                    echo "</select>";
                    echo "</div>";
                }
                
                echo "<input type='hidden' value='".$row['categoryID']."' name='categoryID'>";

            echo "<button type='submit' class='btn-submit'>Update Category</button>";
            echo "<button type='button' class='btn-submit' onclick=\"location.href='category.php'\">Back</button>";

            echo "</form>";
        } else {
            echo "<div class='no-items'>Category not found.</div>";
        }

        echo "</div>";
        
        }
    ?>
</body>
</html>