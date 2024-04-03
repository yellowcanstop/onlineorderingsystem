<?php


if (isset($_SESSION['customer_id'])) {
	// retrieve order information from database
	if ($stmt = $pdo -> prepare('SELECT order_id, date_order_placed, payment_amount, order_status_code FROM customer_orders WHERE customer_id = :customer_id')) {
		$stmt->bindValue(':customer_id', $_SESSION['customer_id'], PDO::PARAM_INT);
		$stmt->execute();
		$orders = $stmt->fetchALL(PDO::FETCH_ASSOC);
        // foreach ($orders as $order) operates on a copy of the array, not the original array
        // so use key of $orders array to get reference to the original array
        // to directly modify $orders array (no need to use reference with & and unset())
        foreach ($orders as $key => $order):
            if ($stmt = $pdo ->prepare('SELECT dish_id, order_quantity FROM customer_orders_products WHERE order_id = :order_id')) {
                $stmt->bindValue(':order_id', $order['order_id'], PDO::PARAM_INT);
                $stmt->execute();
                // $orders[$key]['dishes'] is an array of associative arrays (used fetchAll), with
                // each associative array as a row from customer_orders_products table
                $orders[$key]['dishes'] = $stmt->fetchALL(PDO::FETCH_ASSOC);
            } else {
                error_log('Cannot prepare sql statement for customer_orders_products table.');
                exit();
            }
        endforeach;
        
	} else {
		error_log('Cannot prepare sql statement for customers table.');
		exit();
	}
}

foreach ($orders as $key => $order) {
    foreach ($order['dishes'] as $dish) {
        // img, price
        $stmt = $pdo->prepare('SELECT name FROM dishes WHERE id = :dish_id');
        $stmt->bindValue(':dish_id', $dish['dish_id'], PDO::PARAM_INT);
        $stmt->execute();
        $dish_name = $stmt->fetch(PDO::FETCH_ASSOC);
        $dish['name'] = $dish_name['name'];
    }
}


?>
<?= template_header('Orders') ?>
<div class="content-wrapper">
    <h1>Orders</h1>
    <p>View your past orders here:</p>
    <table>
            <thead>
                <tr>
                    <td>Order ID</td>
                    <td>Date Ordered</td>
                    <td>Amount</td>
                    <td>Status</td>
                </tr>
            </thead>
            
            <tbody>
                <?php if (empty($orders)): ?>
                <tr>
                    <td colspan="5" style="text-align:center;">You have made no orders.</td>
                </tr>
                <?php else: ?>
                <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?=$order['order_id']?></td>
                    <td><?=$order['date_order_placed']?></td>
                    <td><?=$order['payment_amount']?></td>
                    <td><?=$order['order_status_code']?></td>   
                </tr>
                <tr>
                    <table>
                        <thead>
                            <tr>
                                <td>Dish</td>
                                <td>Quantity</td>
                            </tr>
                        </thead>
                    <tbody>
                    <?php foreach ($order['dishes'] as $dish): ?>
                    <tr>
                        <td>
                            <a href="index.php?page=product&id=<?=$dish['dish_id']?>"><?=$dish['dish_id']?></a>
                        </td>
                        
                        <td><?=$dish['order_quantity']?></td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        
        </table>
   
</div>

<?= template_footer() ?>