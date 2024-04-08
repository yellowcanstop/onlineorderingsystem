<?php
// number of orders to show per page
$num_orders_on_each_page = 10;
// show selected page otherwise default to 1
$current_page = isset($_GET['p']) && is_numeric($_GET['p']) ? (int)$_GET['p'] : 1;

// only get orders for the current month
// filter by order_status_code: unpaid, paid, fulfilled, cancelled
if (isset($_GET['order_status_code'])) {
    $stmt = $pdo->prepare('SELECT * FROM customer_orders WHERE order_status_code = :order_status_code AND MONTH(date_order_placed) = MONTH(CURDATE()) AND YEAR(date_order_placed) = YEAR(CURDATE()) ORDER BY date_order_placed DESC LIMIT :current_page, :record_per_page');
    $stmt->bindValue(':order_status_code', $_GET['order_status_code'], PDO::PARAM_STR);
    $stmt->bindValue(':current_page', ($current_page - 1) * $num_orders_on_each_page, PDO::PARAM_INT);
    $stmt->bindValue(':record_per_page', $num_orders_on_each_page, PDO::PARAM_INT);
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (!$orders) {
        error_page('Orders not found', 'No orders found for the current month with the status code of ' . $_GET['order_status_code'] . '.');
        error_log('Cannot get orders with status code: ' . $_GET['order_status_code']);
        exit();
    }
}
else {
    error_page('Orders not found', 'No order status code selected.');
    error_log('No order status code selected');
    exit();
} 

// update order_status_code for specific order
if (isset($_GET['update_order_status_code']) && isset($_GET['order_id'])) {
    $stmt = $pdo->prepare('UPDATE customer_orders SET order_status_code = :order_status_code WHERE order_id = :order_id');
    $stmt->bindValue(':order_status_code', $_GET['update_order_status_code'], PDO::PARAM_STR);
    $stmt->bindValue(':order_id', $_GET['order_id'], PDO::PARAM_INT);
    $stmt->execute();
    header('Location: index.php?page=manageorders&order_status_code=' . $_GET['order_status_code'] . '&p=' . $current_page);
    exit();
}   

// get the total number of orders for the current month
$total_orders_for_month = count($orders);

?>

<?=template_header('Dishes')?>

<div class="products content-wrapper">
    <h1>Manage Orders</h1>
    <p><?=$total_orders_for_month?> orders for the current month</p>
    <form method="get" action="index.php?page=manageorders">
        <input type="hidden" name="page" value="products">
        <!-- show category id and page only if there are GET parameters -->
        <?php if(isset($_GET['order_status_code'])): ?>
            <input type="hidden" name="order_status_code" value="<?=$_GET['order_status_code']?>">
        <?php endif; ?>
        <?php if(isset($_GET['update_order_status_code'])): ?>
            <input type="hidden" name="update_order_status_code" value="<?=$_GET['update_order_status_code']?>">
        <?php endif; ?>
        <?php if(isset($_GET['order_id'])): ?>
            <input type="hidden" name="order_id" value="<?=$_GET['order_id']?>">
        <?php endif; ?>
        <?php if(isset($_GET['p'])): ?>
            <input type="hidden" name="p" value="<?=$_GET['p']?>">
        <?php endif; ?>
        <!-- sort by price -->
        <select name="sort" onchange="this.form.submit()">
            <option value="">Sort By</option>
            <option value="price_asc">Price (Low to High)</option>
            <option value="price_desc">Price (High to Low) - Default</option>
        </select>
    </form>
    <div class="products-wrapper">
        <?php foreach ($dishes as $dish): ?>
        <a href="index.php?page=product&id=<?=$dish['id']?>" class="product">
            <img src="imgs/<?=$dish['img']?>" width="200" height="200" alt="<?=$dish['name']?>">
            <span class="name"><?=$dish['name']?></span>
            <span class="price">
                &dollar;<?=$dish['price']?>
                <?php if ($dish['quantity'] === 0): ?>
                <p>Dish is <span style="font-style:oblique">unavailable</span>.</p>
                <?php endif; ?>
            </span>
        </a>
        <?php endforeach; ?>
    </div>
    <div class="buttons">
        <?php if ($current_page > 1): ?>
        <a href="index.php?page=products<?=isset($_GET['cid']) ? '&cid='.$_GET['cid'] : ''?>&p=<?=$current_page-1?><?=isset($_GET['sort']) ? '&sort='.$_GET['sort'] : ''?>">Prev</a>
        <?php endif; ?>
        <?php if ($total_products > ($current_page * $num_products_on_each_page) - $num_products_on_each_page + count($dishes)): ?>
        <a href="index.php?page=products<?=isset($_GET['cid']) ? '&cid='.$_GET['cid'] : ''?>&p=<?=$current_page+1?><?=isset($_GET['sort']) ? '&sort='.$_GET['sort'] : ''?>">Next</a>
        <?php endif; ?>
    </div>
</div>

<?=template_footer()?>

