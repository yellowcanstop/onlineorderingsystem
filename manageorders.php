<?php
// number of orders to show per page
$num_orders_on_each_page = 10;
// show selected page otherwise default to 1
$current_page = isset($_GET['p']) && is_numeric($_GET['p']) ? (int)$_GET['p'] : 1;

// only get orders for the current month
// filter by order_status_id: unpaid '1', paid '2', fulfilled '3', cancelled '4'
if (isset($_GET['order_status_id']) && !empty($_GET['order_status_id'])) {
    $stmt = $pdo->prepare('SELECT * FROM customer_orders WHERE order_status_id = :order_status_id AND MONTH(date_order_placed) = MONTH(CURDATE()) AND YEAR(date_order_placed) = YEAR(CURDATE()) ORDER BY date_order_placed DESC LIMIT :current_page, :record_per_page');
    $stmt->bindValue(':order_status_id', $_GET['order_status_id'], PDO::PARAM_INT);
    $stmt->bindValue(':current_page', ($current_page - 1) * $num_orders_on_each_page, PDO::PARAM_INT);
    $stmt->bindValue(':record_per_page', $num_orders_on_each_page, PDO::PARAM_INT);
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // fetchAll() returns null if there is an error in the query
    // fetchAll() returns empty array if there are no results
    // so instead of using !$orders which is true when $orders is empty or null,
    // separate into is_null($orders) and empty($orders)
    // the former will log error and exit, the latter will display html text
    if (is_null($orders)) {
        error_page('Orders not found', 'No orders found for the current month with the status code of ' . $_GET['order_status_id'] . '.');
        error_log('Cannot get orders with status code: ' . $_GET['order_status_id']);
        exit();
    }
}
else {
    $stmt = $pdo->prepare('SELECT * FROM customer_orders WHERE MONTH(date_order_placed) = MONTH(CURDATE()) AND YEAR(date_order_placed) = YEAR(CURDATE()) ORDER BY date_order_placed DESC LIMIT :current_page, :record_per_page');
    $stmt->bindValue(':current_page', ($current_page - 1) * $num_orders_on_each_page, PDO::PARAM_INT);
    $stmt->bindValue(':record_per_page', $num_orders_on_each_page, PDO::PARAM_INT);
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (is_null($orders)) {
        error_page('Orders not found', 'No orders found for the current month.');
        error_log('Cannot get orders.');
        exit();
    }
} 

// update order_status_id for specific order
if (isset($_POST['update_order_status_id']) && isset($_POST['order_id'])) {
    if($_POST['update_order_status_id'] == 2) {
        $stmt = $pdo->prepare('UPDATE customer_orders SET order_status_id = :order_status_id, date_order_paid = CURRENT_TIMESTAMP WHERE order_id = :order_id');
        $stmt->bindValue(':order_status_id', $_POST['update_order_status_id'], PDO::PARAM_INT);
        $stmt->bindValue(':order_id', $_POST['order_id'], PDO::PARAM_INT);
        if (!$stmt->execute()) {
            $_SESSION['update_message'] = '<p>Cannot update order status to paid. Please try again.</p>';
            error_log("Cannot execute sql statement to update customer_orders table.");
        }
        $_SESSION['update_message'] = "<p>Order ID {$_POST['order_id']} successfully updated to paid.</p>";
        header('Location: index.php?page=manageorders&p=' . $current_page);
        exit();
    }
    else if ($_POST['update_order_status_id'] == 3) {
        $stmt = $pdo->prepare('UPDATE customer_orders SET order_status_id = :order_status_id, date_order_fulfilled = CURRENT_TIMESTAMP WHERE order_id = :order_id');
        $stmt->bindValue(':order_status_id', $_POST['update_order_status_id'], PDO::PARAM_INT);
        $stmt->bindValue(':order_id', $_POST['order_id'], PDO::PARAM_INT);
        if (!$stmt->execute()) {
            $_SESSION['update_message'] = '<p>Cannot update order status to fulfilled. Please try again.</p>';
            error_log("Cannot execute sql statement to update customer_orders table.");
        }
        $_SESSION['update_message'] = "<p>Order ID {$_POST['order_id']} successfully updated to fulfilled.</p>";
        header('Location: index.php?page=manageorders&p=' . $current_page);
        exit();
    }
    else if ($_POST['update_order_status_id'] == 4) {
        $stmt = $pdo->prepare('UPDATE customer_orders SET order_status_id = :order_status_id, date_order_cancelled = CURRENT_TIMESTAMP WHERE order_id = :order_id');
        $stmt->bindValue(':order_status_id', $_POST['update_order_status_id'], PDO::PARAM_INT);
        $stmt->bindValue(':order_id', $_POST['order_id'], PDO::PARAM_INT);
        if (!$stmt->execute()) {
            $_SESSION['update_message'] = '<p>Cannot update order status to cancelled. Please try again.</p>';
            error_log("Cannot execute sql statement to update customer_orders table.");
        }
        $_SESSION['update_message'] = "<p>Order ID {$_POST['order_id']} successfully updated to cancelled.</p>";
        header('Location: index.php?page=manageorders&p=' . $current_page);
        exit();
    }
}   

// get the total number of orders for the current month
$total_orders_for_month = count($orders);

// simple array: unpaid '1', paid '2', fulfilled '3', cancelled '4'
$status_name = ['', 'Unpaid', 'Paid', 'Fulfilled', 'Cancelled'];

?>

<?=template_header('Manage Orders')?>

<div class="cart content-wrapper">
    <h1>Manage Orders</h1>
    <div>
        <?php
        if (isset($_SESSION['update_message'])) {
            echo $_SESSION['update_message'];
            unset($_SESSION['update_message']); 
        }
        ?>
    </div>
    <p><?=$total_orders_for_month?> 
    <?php if(isset($_GET['order_status_id']) && !empty($_GET['order_status_id'])): ?>
        <?=$_GET['order_status_id']?> 
    <?php else: ?>
        total
    <?php endif; ?>
    <?php if($total_orders_for_month == 1): ?>
        order
    <?php else: ?>
        orders
    <?php endif; ?>
    for the current month</p>
    <form method="get" action="index.php?page=manageorders">
        <input type="hidden" name="page" value="manageorders">
        <!-- show only if there are GET parameters -->
        <?php if(isset($_GET['p'])): ?>
            <input type="hidden" name="p" value="<?=$_GET['p']?>">
        <?php endif; ?>
        <!-- select view filtered by order_status_id -->
        <select name="order_status_id" onchange="this.form.submit()">
            <option value="">Choose View</option>
            <option value="">All - Default</option>
            <option value="1">Unpaid</option>
            <option value="2">Paid</option>
            <option value="3">Fulfilled</option>
            <option value="4">Cancelled</option>
        </select>
    </form>
    <table>
            <thead>
                <tr>
                    <td>Date Ordered</td>
                    <td>Order ID</td>
                    <td>Customer ID</td>
                    <td>Payment Method</td>
                    <td>Date Paid</td>
                    <td>Amount</td>
                    <td>Status</td>
                    <td>Date Fulfilled</td>
                    <td>Date Cancelled</td>
                    <td>Action</td>
                </tr>
            </thead>
            
            <tbody>
                <?php if (empty($orders)): ?>
                <tr>
                    <td colspan="5" style="text-align:center; color: white;"><?='No orders found for the current month with selected status code.'?></td>
                </tr>
                <?php else: ?>
                <?php foreach ($orders as $order): ?>
                <tr>
                    <td style="color: white";><?=$order['date_order_placed']?></td>
                    <td style="color: white";>
                        <?=$order['order_id']?>
                    </td>
                    <td style="color: white";><?=$order['customer_id']?></td>
                    <td>
                        <?php if ($order['customer_payment_method_id'] == 1): ?>
                            <p>cash</p>
                        <?php endif; ?>
                        <?php if ($order['customer_payment_method_id'] == 2): ?>
                            <p>credit card</p>
                        <?php endif; ?>
                        <?php if ($order['customer_payment_method_id'] == 3): ?>
                            <p>e-wallet</p>
                        <?php endif; ?>
                    </td>
                    
                    <td style="color: white";>
                    <?php if ($order['order_status_id'] == 2 || $order['order_status_id'] == 3): ?>
                        <?=$order['date_order_paid']?>
                    <?php else: ?>
                        <p>cash order pending payment</p>
                    <?php endif; ?>
                    </td>

                    <td style="color: white";><?=$order['payment_amount']?></td>
                    
                    <td style="color: white";><?=$status_name[$order['order_status_id']]?></td>

                    <td>
                    <?php if ($order['order_status_id'] == 3): ?>
                        <?=$order['date_order_fulfilled']?>
                    <?php else: ?>
                        <p>n/a</p>
                    <?php endif; ?>
                    </td>

                    <td>
                    <?php if ($order['order_status_id'] == 4): ?>
                        <?=$order['date_order_cancelled']?>
                    <?php else: ?>
                        <p>n/a</p>
                    <?php endif; ?>
                    </td>

                    <td>
                    <?php if ($order['order_status_id'] == 1): ?>
                        <a href="index.php?page=vieworder&order_id=<?=$order['order_id']?>" class="remove">View Order</a>
                        <br>
                        <form class="action-confirm" method="POST" action="index.php?page=manageorders">
                            <input type="hidden" name="page" value="manageorders">
                            <input type="hidden" name="update_order_status_id" value="2">
                            <input type="hidden" name="order_id" value="<?=$order['order_id']?>">
                            <input type="hidden" name="p" value="<?=$current_page?>">
                            <input type="submit" value="Mark as Paid" class="mark-as-paid-button">
                        </form>
                        <br>
                        <form class="action-confirm" method="POST" action="index.php?page=manageorders">
                            <input type="hidden" name="page" value="manageorders">
                            <input type="hidden" name="update_order_status_id" value="4">
                            <input type="hidden" name="order_id" value="<?=$order['order_id']?>">
                            <input type="hidden" name="p" value="<?=$current_page?>">
                            <input type="submit" value="Cancel Order">
                        </form>
                    <?php elseif ($order['order_status_id'] == 2): ?>
                        <a href="index.php?page=vieworder&order_id=<?=$order['order_id']?>" class="remove">View Order</a>
                        <br>
                        <form class="action-confirm" method="POST" action="index.php?page=manageorders">
                            <input type="hidden" name="page" value="manageorders">
                            <input type="hidden" name="update_order_status_id" value="3">
                            <input type="hidden" name="order_id" value="<?=$order['order_id']?>">
                            <input type="hidden" name="p" value="<?=$current_page?>">
                            <input type="submit" value="Mark as Fulfilled" class="mark-as-fulfilled-button">
                        </form>
                        <br>
                        <form class="action-confirm" method="POST" action="index.php?page=manageorders">
                            <input type="hidden" name="page" value="manageorders">
                            <input type="hidden" name="update_order_status_id" value="4">
                            <input type="hidden" name="order_id" value="<?=$order['order_id']?>">
                            <input type="hidden" name="p" value="<?=$current_page?>">
                            <input type="submit" value="Cancel Order">
                        </form>
                    <?php elseif ($order['order_status_id'] == 3 || $order['order_status_id'] == 4): ?>
                        <p>no action available</p>
                    <?php endif; ?>
                    </td>
                </tr>
                
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        
        </table>
 
    <div class="buttons">
        <?php if ($current_page > 1): ?>
        <a href="index.php?page=manageorders<?=isset($_GET['order_status_id']) ? '&order_status_id='.$_GET['order_status_id'] : ''?>&p=<?=$current_page-1?>">Prev</a>
        <?php endif; ?>
        <?php if ($total_orders_for_month > ($current_page * $num_orders_on_each_page) - $num_orders_on_each_page + count($orders)): ?>
        <a href="index.php?page=manageorders<?=isset($_GET['order_status_id']) ? '&order_status_id='.$_GET['order_status_id'] : ''?>&p=<?=$current_page+1?>">Next</a>
        <?php endif; ?>
    </div>
</div>

<?=template_footer()?>

