<?php

// retrieve orders and order details from database
if (isset($_SESSION['customer_id'])) {
    if ($stmt = $pdo->prepare("
        SELECT co.order_id, co.date_order_placed, co.payment_amount, co.order_status_id, cop.dish_id, cop.order_quantity 
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
if ($stmt = $pdo->prepare('SELECT dish_id, name FROM dishes')) {
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($results as $result) {
        $names[$result['dish_id']] = $result['name'];
    }
} else {
    error_log('Cannot prepare sql statement for dishes table.');
    exit();
}

// simple array: unpaid '1', paid '2', fulfilled '3', cancelled '4'
$status_name = ['', 'Unpaid', 'Paid', 'Fulfilled', 'Cancelled'];

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
                            <td><?= $order_id ?></td>
                            <?php $first_detail = reset($order_details); ?>
                            <td><?= $first_detail['date_order_placed'] ?></td>
                            <td><?= $first_detail['payment_amount'] ?></td>
                            <td><?= $status_name[$first_detail['order_status_id']] ?></td>
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
                        <?php foreach ($order_details as $detail): ?>
                            <tr>
                                <td>
                                    <a href="index.php?page=product&id=<?= $detail['dish_id'] ?>"><?= $names[$detail['dish_id']] ?></a>
                                </td>
                                <td><?= $detail['order_quantity'] ?></td>
                                <td>
                                    <form action="index.php?page=cart" method="post">
                                        <input type="hidden" name="id" value="<?= $detail['dish_id'] ?>">
                                        <input type="hidden" name="quantity" value="<?= $detail['order_quantity'] ?>">
                                        <input type="submit" class="order-again-button" value="Order Again">
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
<?= template_footer() ?>
