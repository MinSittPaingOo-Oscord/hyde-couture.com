<?php 

include "../connection/connectdb.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include './layout/login_error_message.php';
$currentPage = "product.php";
include './logInCheck.php'; 

$query_parent_category = "SELECT * FROM category WHERE category.parentID IS NULL";
$result_parent_category = $conn->query($query_parent_category);

$query_child_category = "SELECT * FROM category WHERE category.parentID IS NOT NULL";
$result_child_category = $conn->query($query_child_category);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category</title>
    <?php include "./layout/header.php"; ?>
    <style>
        /* Rolex green theme: neat, clean, luxurious with dark green accents, adapted from active_order.php design structure */
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
        }

        .page-header h1 {
            
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

        .btn-add {
            background: rgba(255,255,255,0.15);
            border: none;
            color: white;
            padding: 8px 14px;
            border-radius: 0;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
        }

        .btn-add:hover {
            background: rgba(255,255,255,0.25);
            transform: translateY(-1px);
        }

        .section-title {
            color: #004d00;
            font-size: 1.4rem;
            margin: 30px 0 15px 0;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            /* border-bottom: 2px solid #e0f0e0; */
            padding-bottom: 10px;
            margin-left : 20px;
        }

        .categories-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            border-radius: 0;
            overflow: hidden;
            /* margin-bottom: 40px; */
        }

        .categories-table thead {
            background: #f8f9fa;
            border-bottom: 2px solid #e0f0e0;
        }

        .categories-table thead th {
            padding: 16px 15px;
            text-align: left;
            font-weight: 500;
            color: #004d00;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        .categories-table tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid #e9ecef;
        }

        .categories-table tbody tr:hover {
            background-color: #f8fff8;
            box-shadow: 0 2px 8px rgba(0,77,0,0.05);
        }

        .categories-table td {
            padding: 16px 15px;
            vertical-align: middle;
            font-size: 1rem;
            color: #333;
        }

        .category-name {
            font-family: 'Courier New', monospace;
            background: #e6f7e6;
            color: #004d00;
            padding: 6px 10px;
            border-radius: 0;
            font-weight: 600;
            font-size: 0.9rem;
            display: inline-block;
        }

        .parent-name {
            background: #f0f8f0;
            padding: 6px 10px;
            border-radius: 0;
            color: #004d00;
            font-weight: 500;
        }

        .actions {
            white-space: nowrap;
        }

        .btn-edit,
        .btn-delete {
            padding: 8px 16px;
            border: none;
            border-radius: 0;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            display: inline-block;
            margin-right: 5px;
        }

        .btn-edit {
            background: linear-gradient(45deg, #004d00, #006600);
            color: white;
        }

        .btn-edit:hover {
            background: linear-gradient(45deg, #006600, #008000);
            transform: translateY(-1px);
            box-shadow: 0 3px 8px rgba(0,102,0,0.3);
        }

        .btn-delete {
            background: linear-gradient(45deg, #cc0000, #ff4d4d);
            color: white;
        }

        .btn-delete:hover {
            background: linear-gradient(45deg, #ff4d4d, #ff6666);
            transform: translateY(-1px);
            box-shadow: 0 3px 8px rgba(204,0,0,0.3);
        }

        .no-items {
            text-align: center;
            padding: 40px 20px;
            color: #666;
            font-style: italic;
            background: #f8fff8;
            border: 1px dashed #e0f0e0;
            border-radius: 0;
            margin: 20px 0;
        }

        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                text-align: center;
            }

            .header-actions {
                justify-content: center;
            }

            .categories-table {
                border: 0;
            }

            .categories-table thead {
                display: none;
            }

            .categories-table tbody tr {
                display: block;
                /* margin-bottom: 24px; */
                border: 1px solid #e0f0e0;
                border-radius: 0;
                padding: 16px;
                background: white;
                box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            }

            .categories-table td {
                display: block;
                text-align: right;
                padding: 12px 10px;
                border: none;
                position: relative;
                padding-left: 50%;
                font-size: 0.95rem;
                color: #333;
                box-sizing: border-box;
            }

            .categories-table td:before {
                content: attr(data-label);
                position: absolute;
                left: 0;
                width: 50%;
                padding-left: 10px;
                padding-right: 10px;
                font-weight: 600;
                color: #004d00;
                text-transform: uppercase;
                font-size: 0.8rem;
                letter-spacing: 0.5px;
                top: 50%;
                transform: translateY(-50%);
                text-align: left;
                box-sizing: border-box;
            }

            .categories-table td .category-name,
            .categories-table td .parent-name {
                background: #f0f8f0;
                padding: 6px 10px;
                border-radius: 0;
                display: inline-block;
            }

            .categories-table td:last-child {
                text-align: center;
                padding-top: 15px;
            }

            .btn-edit,
            .btn-delete {
                width: 48%;
                padding: 12px;
                font-size: 1rem;
                margin-right: 4%;
            }

            .btn-delete {
                margin-right: 0;
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
            echo "<h1>Categories</h1>";
            echo "<div class='header-actions'>";
            echo "<a href='add_new_category.php' class='btn-add'><i class='bi bi-plus'></i> Add New Category</a>";
            echo "</div>";
            echo "</div>";

            echo "<h2 class='section-title'>Parent Categories</h2>";
        
            if ($result_parent_category && $result_parent_category->num_rows > 0) {
                echo "<table class='categories-table'>";
                echo "<thead><tr><th>Category Name</th><th>Actions</th></tr></thead>";
                echo "<tbody>";
                while ($row_parent_category = $result_parent_category->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td data-label='Category Name'><span class='category-name'>" . htmlspecialchars($row_parent_category['categoryName']) . "</span></td>";
                    echo "<td data-label='Actions' class='actions'>";
                    echo "<a href='edit_parent_category.php?categoryID=".$row_parent_category['categoryID']."' class='btn-edit'>Edit</a>";
                    echo "<a href='delete_parent_category.php?categoryID=".$row_parent_category['categoryID']."' class='btn-delete'>Delete</a>";
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</tbody>";
                echo "</table>";
            } else {
                echo "<div class='no-items'>No parent categories found.</div>";
            }

            //________________________________________

            echo "<h2 class='section-title'>Child Categories</h2>";

            if ($result_child_category && $result_child_category->num_rows > 0) {
                echo "<table class='categories-table'>";
                echo "<thead><tr><th>Category Name</th><th>Parent</th><th>Actions</th></tr></thead>";
                echo "<tbody>";
                while ($row_child_category = $result_child_category->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td data-label='Category Name'><span class='category-name'>" . htmlspecialchars($row_child_category['categoryName']) . "</span></td>";

                    $parent = $row_child_category['parentID'];

                    $query_child_parent_category = "SELECT * FROM category WHERE category.parentID IS NULL AND category.categoryID = $parent";
                    $result_child_parent_category = $conn->query($query_child_parent_category);

                    if ($result_child_parent_category && $result_child_parent_category->num_rows > 0 && $row_child_parent_category = $result_child_parent_category->fetch_assoc()) {
                        echo "<td data-label='Parent'><span class='parent-name'>" . htmlspecialchars($row_child_parent_category['categoryName']) . "</span></td>";
                    } else {
                        echo "<td data-label='Parent'><span class='parent-name'>-</span></td>";
                    }

                    echo "<td data-label='Actions' class='actions'>";
                    echo "<a href='edit_child_category.php?categoryID=".$row_child_category['categoryID']."' class='btn-edit'>Edit</a>";
                    echo "<a href='delete_child_category.php?categoryID=".$row_child_category['categoryID']."' class='btn-delete'>Delete</a>";
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</tbody>";
                echo "</table>";
            } else {
                echo "<div class='no-items'>No child categories found.</div>";
            }

            echo "</div>";
        }
    ?>
</body>
</html>