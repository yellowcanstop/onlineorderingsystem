<?php

if (isset($_POST['date_order_paid'])) {
    // update date_order_paid and order_status_code (default is unpaid '1') in customer_orders table
    if ($stmt = $pdo->prepare('UPDATE customer_orders SET date_order_paid = :date_order_paid, order_status_id = :order_status_id WHERE order_id = :order_id')) {
        $stmt->bindValue(':order_id', $_SESSION['order_id'], PDO::PARAM_INT);
        $date_order_paid = date('Y-m-d H:i:s', $_POST['date_order_paid']);
        $stmt->bindValue(':date_order_paid', $date_order_paid, PDO::PARAM_STR);
        $stmt->bindValue(':order_status_id', 2, PDO::PARAM_INT); // set to paid '2'
        if (!$stmt->execute()) {
            error_log("Cannot execute sql statement for customers_orders table.");
        } 
        header('Location: index.php?page=placeorder');
        exit();
        } 
    } else {
        error_log("Cannot prepare sql statement for customer_orders table.");
        $_SESSION['error'] = 'Failed to process payment. Please try again.';
        header('Location: index.php?page=getinfo');
        exit();
    }


