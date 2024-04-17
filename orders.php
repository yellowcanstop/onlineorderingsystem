<?php
// get customer_id session variable if not already set
if (!isset($_SESSION['customer_id'])) {
    if ($stmt = $pdo -> prepare('SELECT id, customer_first_name, customer_last_name, customer_phone, date_of_register FROM customers WHERE account_id = :account_id')) {
		$stmt->bindValue(':account_id', $_SESSION['account_id'], PDO::PARAM_INT);
		$stmt->execute();
		$customer = $stmt->fetch(PDO::FETCH_ASSOC);
		$_SESSION['customer_id'] = $customer['id'];
	} else {
		error_log('Cannot prepare sql statement for customers table.');
		exit();
	}
}

// retrieve orders and order details from database
if (isset($_SESSION['customer_id'])) {
    if ($stmt = $pdo->prepare("
        SELECT co.order_id, co.date_order_placed, co.payment_amount, co.order_status_code, cop.dish_id, cop.order_quantity 
        FROM customer_orders co 
        INNER JOIN customer_orders_products cop ON co.order_id = cop.order_id 
        WHERE co.customer_id = :customer_id 
        ORDER BY co.date_order_placed DESC
        ")) {
        $stmt->bindValue(':customer_id', $_SESSION['customer_id'], PDO::PARAM_INT);
        $stmt->execute();
        // $orders is an associative array with
        // key: order_id (group by order_id)
        // value ($order_details): array of associative arrays
        // each inner assoc array is a row from co and cop tables for a specific order_id
        // access via foreach ($order_details as $detail) then $detail[]
        $orders = $stmt->fetchAll(PDO::FETCH_GROUP|PDO::FETCH_ASSOC);
    } else {
        error_log('Cannot prepare sql statement to get orders and order details.');
        exit();
    }
}

// store all dish names in associative array with key as dish id and value as dish name
// so display dish names by referencing dish id in orders array
$names = array();
if ($stmt = $pdo->prepare('SELECT id, name FROM dishes')) {
    
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($results as $result) {
        $names[$result['id']] = $result['name'];
    }
} else {
    error_log('Cannot prepare sql statement for dishes table.');
    exit();
}


?>
<?= template_header('Orders') ?>
<div class="orders">
    <h1>Orders</h1>
    <p>View your past orders here:</p>
    <?php if (empty($orders)): ?>
        <p style="color: white; text-align: center;">You have made no orders.</p>
    <?php else: ?>
        <?php foreach ($orders as $order_id => $order_details): ?>
        <div class="order-details">
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
                <tr>
                    <td><?=$order_id?></td>
                    <?php foreach ($order_details as $detail): ?>
                    <td><?=$detail['date_order_placed']?></td>
                    <td><?=$detail['payment_amount']?></td>
                    <td><?=$detail['order_status_code']?></td>   
                </tr>
            </tbody>
        </table>
                    <table>
                        <thead>
                            <tr>
                                <td>Item</td>
                                <td>Quantity</td>
                                <td>Action</td>
                            </tr>
                        </thead>
                    <tbody>
                    
                    <tr>
                        <td>
                            <a href="index.php?page=product&id=<?=$detail['dish_id']?>"><?=$names[$detail['dish_id']]?></a>
                        </td>
                        <td><?=$detail['order_quantity']?></td>
                        <td>
                            <form action="index.php?page=cart" method="post">
                                <input type="hidden" name="id" value="<?=$detail['dish_id']?>">
                                <input type="hidden" name="quantity" value="<?=$detail['order_quantity']?>">
                                <input type="submit" class="remove" value="Order Again">
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                    </table>
                    </div>
                <?php endforeach; ?>
                <?php endif; ?>
                </div>
            </div>

<?= template_footer() ?>