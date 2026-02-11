<?php
include "../connection/connectdb.php";
session_start();
if (!($_SESSION['login'] ?? false)) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['orderID'])) {
    $orderID = (int)$_POST['orderID'];
    $paymentStatus = (int)$_POST['paymentStatus'];
    $orderStatus = (int)$_POST['orderStatus'];
    $trackingStatus = (int)$_POST['trackingStatus'];
    $paymentValid = (int)$_POST['paymentValid'];

    // Fetch old order details before update
    $stmt_old = $conn->prepare("SELECT paymentStatus, paymentType, trackingStatus FROM orderr WHERE orderID = ?");
    $stmt_old->bind_param("i", $orderID);
    $stmt_old->execute();
    $result_old = $stmt_old->get_result();
    $old_order = $result_old->fetch_assoc();
    $stmt_old->close();

    // Perform the update
    $stmt = $conn->prepare("UPDATE orderr SET paymentStatus = ?, orderStatus = ?, trackingStatus = ?, paymentValid = ? WHERE orderID = ?");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("iiiii", $paymentStatus, $orderStatus, $trackingStatus, $paymentValid, $orderID);
    
    if ($stmt->execute()) {
        $stmt->close();

        // Fetch updated order details
        $stmt_new = $conn->prepare("SELECT paymentStatus, paymentType, trackingStatus FROM orderr WHERE orderID = ?");
        $stmt_new->bind_param("i", $orderID);
        $stmt_new->execute();
        $result_new = $stmt_new->get_result();
        $new_order = $result_new->fetch_assoc();
        $stmt_new->close();

        // Check if stock reduction condition is met after update
        $should_reduce_stock = false;
        if (($new_order['paymentStatus'] == 2 && $new_order['paymentType'] == 2) || 
            ($new_order['paymentType'] == 1 && $new_order['trackingStatus'] == 2)) {
            $should_reduce_stock = true;
        }

        $stock_reduced = false;
        $error_code = null;

        if ($should_reduce_stock) {
            // Fetch order items that haven't had stock reduced yet
            $stmt_items = $conn->prepare("SELECT oi.orderItemID, oi.quantity, oi.productID, oi.size, oi.color, oi.isStockReduce 
                                          FROM orderitem oi 
                                          WHERE oi.orderID = ? AND (oi.isStockReduce IS NULL OR oi.isStockReduce = 0)");
            $stmt_items->bind_param("i", $orderID);
            $stmt_items->execute();
            $result_items = $stmt_items->get_result();

            $items_to_reduce = [];
            while ($item = $result_items->fetch_assoc()) {
                $items_to_reduce[] = $item;
            }
            $stmt_items->close();

            if (!empty($items_to_reduce)) {
                // Start transaction for atomicity
                $conn->begin_transaction();

                try {
                    // Check stock for all items first
                    foreach ($items_to_reduce as $item) {
                        $stmt_stock_check = $conn->prepare("SELECT quantity FROM stock 
                                                            WHERE productID = ? AND sizeID = ? AND colorID = ? FOR UPDATE");
                        $stmt_stock_check->bind_param("iii", $item['productID'], $item['size'], $item['color']);
                        $stmt_stock_check->execute();
                        $result_stock = $stmt_stock_check->get_result();
                        $stock_row = $result_stock->fetch_assoc();
                        $stmt_stock_check->close();

                        if (!$stock_row || $stock_row['quantity'] < $item['quantity']) {
                            throw new Exception('insufficient_stock');
                        }
                    }

                    // If all checks pass, reduce stock
                    foreach ($items_to_reduce as $item) {
                        // Reduce stock
                        $stmt_stock_update = $conn->prepare("UPDATE stock SET quantity = quantity - ? 
                                                             WHERE productID = ? AND sizeID = ? AND colorID = ?");
                        $stmt_stock_update->bind_param("iiii", $item['quantity'], $item['productID'], $item['size'], $item['color']);
                        $stmt_stock_update->execute();
                        $stmt_stock_update->close();

                        // Update orderitem isStockReduce to 1
                        $stmt_item_update = $conn->prepare("UPDATE orderitem SET isStockReduce = 1 WHERE orderItemID = ?");
                        $stmt_item_update->bind_param("i", $item['orderItemID']);
                        $stmt_item_update->execute();
                        $stmt_item_update->close();
                    }

                    $conn->commit();
                    $stock_reduced = true;

                } catch (Exception $e) {
                    $conn->rollback();
                    $error_code = $e->getMessage();
                }
            }
        }

        // Redirect with appropriate message
        $redirect_url = "specific_order.php?orderID=$orderID";
        if ($error_code) {
            $redirect_url .= "&error=$error_code";
        } else {
            $redirect_url .= "&success=1";
            if ($stock_reduced) {
                $redirect_url .= "&stock_reduced=1";
            }
        }
        header("Location: $redirect_url");
        exit;

    } else {
        $stmt->close();
        header("Location: specific_order.php?orderID=$orderID&error=update_failed");
        exit;
    }
}
$conn->close();
?>