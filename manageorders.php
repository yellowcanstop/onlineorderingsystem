<?php
// number of orders to show per page
$num_orders_on_each_page = 10;
// show selected page otherwise default to 1
$current_page = isset($_GET['p']) && is_numeric($_GET['p']) ? (int)$_GET['p'] : 1;

// only get orders for the current month
// filter by order_status_code: unpaid, paid, fulfilled, cancelled
if (isset($_GET['order_status_code']) && !empty($_GET['order_status_code'])) {
    $stmt = $pdo->prepare('SELECT * FROM customer_orders WHERE order_status_code = :order_status_code AND MONTH(date_order_placed) = MONTH(CURDATE()) AND YEAR(date_order_placed) = YEAR(CURDATE()) ORDER BY date_order_placed DESC LIMIT :current_page, :record_per_page');
    $stmt->bindValue(':order_status_code', $_GET['order_status_code'], PDO::PARAM_STR);
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
        error_page('Orders not found', 'No orders found for the current month with the status code of ' . $_GET['order_status_code'] . '.');
        error_log('Cannot get orders with status code: ' . $_GET['order_status_code']);
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

// update order_status_code for specific order
if (isset($_POST['update_order_status_code']) && isset($_POST['order_id'])) {
    if($_POST['update_order_status_code'] == 'paid') {
        $stmt = $pdo->prepare('UPDATE customer_orders SET order_status_code = :order_status_code, date_order_paid = CURRENT_TIMESTAMP WHERE order_id = :order_id');
        $stmt->bindValue(':order_status_code', $_POST['update_order_status_code'], PDO::PARAM_STR);
        $stmt->bindValue(':order_id', $_POST['order_id'], PDO::PARAM_INT);
        if (!$stmt->execute()) {
            $_SESSION['update_message'] = '<p>Cannot update order status to paid. Please try again.</p>';
            error_log("Cannot execute sql statement to update customer_orders table.");
        }
        $_SESSION['update_message'] = "<p>Order ID {$_POST['order_id']} successfully updated to paid.</p>";
        header('Location: index.php?page=manageorders&p=' . $current_page);
        exit();
    }
    elseif ($_POST['update_order_status_code'] == 'fulfilled') {
        $stmt = $pdo->prepare('UPDATE customer_orders SET order_status_code = :order_status_code, date_order_fulfilled = CURRENT_TIMESTAMP WHERE order_id = :order_id');
        $stmt->bindValue(':order_status_code', $_POST['update_order_status_code'], PDO::PARAM_STR);
        $stmt->bindValue(':order_id', $_POST['order_id'], PDO::PARAM_INT);
        if (!$stmt->execute()) {
            $_SESSION['update_message'] = '<p>Cannot update order status to fulfilled. Please try again.</p>';
            error_log("Cannot execute sql statement to update customer_orders table.");
        }
        $_SESSION['update_message'] = "<p>Order ID {$_POST['order_id']} successfully updated to fulfilled.</p>";
        header('Location: index.php?page=manageorders&p=' . $current_page);
        exit();
    }
    elseif ($_POST['update_order_status_code'] == 'cancelled') {
        $stmt = $pdo->prepare('UPDATE customer_orders SET order_status_code = :order_status_code, date_order_cancelled = CURRENT_TIMESTAMP WHERE order_id = :order_id');
        $stmt->bindValue(':order_status_code', $_POST['update_order_status_code'], PDO::PARAM_STR);
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
    <?php if(isset($_GET['order_status_code']) && !empty($_GET['order_status_code'])): ?>
        <?=$_GET['order_status_code']?> 
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
        <!-- select view filtered by order_status_code -->
        <select name="order_status_code" onchange="this.form.submit()">
            <option value="">Choose View</option>
            <option value="">All - Default</option>
            <option value="unpaid">Unpaid</option>
            <option value="paid">Paid</option>
            <option value="fulfilled">Fulfilled</option>
            <option value="cancelled">Cancelled</option>
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
                    <td colspan="5" style="text-align:center;"><?='No orders found for the current month which are ' . $_GET['order_status_code'] . '.'?></td>
                </tr>
                <?php else: ?>
                <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?=$order['date_order_placed']?></td>
                    <td>
                        <?=$order['order_id']?>
                    </td>
                    <td><?=$order['customer_id']?></td>
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
                    
                    <td>
                    <?php if ($order['order_status_code'] == 'paid' || $order['order_status_code'] == 'fulfilled'): ?>
                        <?=$order['date_order_paid']?>
                    <?php else: ?>
                        <p>cash order pending payment</p>
                    <?php endif; ?>
                    </td>

                    <td><?=$order['payment_amount']?></td>
                    
                    <td><?=$order['order_status_code']?></td>

                    <td>
                    <?php if ($order['order_status_code'] == 'fulfilled'): ?>
                        <?=$order['date_order_fulfilled']?>
                    <?php else: ?>
                        <p>n/a</p>
                    <?php endif; ?>
                    </td>

                    <td>
                    <?php if ($order['order_status_code'] == 'cancelled'): ?>
                        <?=$order['date_order_cancelled']?>
                    <?php else: ?>
                        <p>n/a</p>
                    <?php endif; ?>
                    </td>

                    <td>
                    <?php if ($order['order_status_code'] == 'unpaid'): ?>
                        <a href="index.php?page=vieworder&order_id=<?=$order['order_id']?>" class="remove">View Order</a>
                        <br>
                        <form class="action-confirm" method="POST" action="index.php?page=manageorders">
                            <input type="hidden" name="page" value="manageorders">
                            <input type="hidden" name="update_order_status_code" value="paid">
                            <input type="hidden" name="order_id" value="<?=$order['order_id']?>">
                            <input type="hidden" name="p" value="<?=$current_page?>">
                            <input type="submit" value="Mark as Paid">
                        </form>
                        <br>
                        <form class="action-confirm" method="POST" action="index.php?page=manageorders">
                            <input type="hidden" name="page" value="manageorders">
                            <input type="hidden" name="update_order_status_code" value="cancelled">
                            <input type="hidden" name="order_id" value="<?=$order['order_id']?>">
                            <input type="hidden" name="p" value="<?=$current_page?>">
                            <input type="submit" value="Cancel Order">
                        </form>
                    <?php elseif ($order['order_status_code'] == 'paid'): ?>
                        <a href="index.php?page=vieworder&order_id=<?=$order['order_id']?>" class="remove">View Order</a>
                        <br>
                        <form class="action-confirm" method="POST" action="index.php?page=manageorders">
                            <input type="hidden" name="page" value="manageorders">
                            <input type="hidden" name="update_order_status_code" value="fulfilled">
                            <input type="hidden" name="order_id" value="<?=$order['order_id']?>">
                            <input type="hidden" name="p" value="<?=$current_page?>">
                            <input type="submit" value="Mark as Fulfilled">
                        </form>
                        <br>
                        <form class="action-confirm" method="POST" action="index.php?page=manageorders">
                            <input type="hidden" name="page" value="manageorders">
                            <input type="hidden" name="update_order_status_code" value="cancelled">
                            <input type="hidden" name="order_id" value="<?=$order['order_id']?>">
                            <input type="hidden" name="p" value="<?=$current_page?>">
                            <input type="submit" value="Cancel Order">
                        </form>
                    <?php elseif ($order['order_status_code'] == 'fulfilled' || $order['order_status_code'] == 'cancelled'): ?>
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
        <a href="index.php?page=manageorders<?=isset($_GET['order_status_code']) ? '&order_status_code='.$_GET['order_status_code'] : ''?>&p=<?=$current_page-1?>">Prev</a>
        <?php endif; ?>
        <?php if ($total_orders_for_month > ($current_page * $num_orders_on_each_page) - $num_orders_on_each_page + count($orders)): ?>
        <a href="index.php?page=manageorders<?=isset($_GET['order_status_code']) ? '&order_status_code='.$_GET['order_status_code'] : ''?>&p=<?=$current_page+1?>">Next</a>
        <?php endif; ?>
    </div>
</div>

<?=template_footer()?>

