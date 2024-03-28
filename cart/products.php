<?php

// The amounts of products to show on each page
$num_products_on_each_page = 4;
// The current page - in the URL, will appear as index.php?page=products&p=1, index.php?page=products&p=2, etc...
// otherwise default to first page?
$current_page = isset($_GET['p']) && is_numeric($_GET['p']) ? (int)$_GET['p'] : 1;
// get selected sort option from the GET parameters
$selected_sort = isset($_GET['sort']) ? $_GET['sort'] : false;

if (isset($_GET['cid'])) {
    if ($selected_sort && ($_GET['sort'] == 'price_asc')) {
        $stmt = $pdo->prepare('SELECT * FROM dishes WHERE category_id = :id ORDER BY price ASC LIMIT :current_page, :record_per_page');
    }
    else {
        $stmt = $pdo->prepare('SELECT * FROM dishes WHERE category_id = :id ORDER BY price DESC LIMIT :current_page, :record_per_page');
    }
    $stmt->bindValue(':id', $_GET['cid'], PDO::PARAM_INT);
    $stmt->bindValue(':current_page', ($current_page - 1) * $num_products_on_each_page, PDO::PARAM_INT);
    $stmt->bindValue(':record_per_page', $num_products_on_each_page, PDO::PARAM_INT);
    $stmt->execute();
    $dishes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (!$dishes) {
        exit('Dishes in this category do not exist!');
    }
}
else {
    if ($selected_sort && ($_GET['sort'] == 'price_asc')) {
        $stmt = $pdo->prepare('SELECT * FROM dishes ORDER BY price ASC LIMIT :current_page, :record_per_page');
    }
    else {
        $stmt = $pdo->prepare('SELECT * FROM dishes ORDER BY price DESC LIMIT :current_page, :record_per_page');
    }
    $stmt->bindValue(':current_page', ($current_page - 1) * $num_products_on_each_page, PDO::PARAM_INT);
    $stmt->bindValue(':record_per_page', $num_products_on_each_page, PDO::PARAM_INT);
    $stmt->execute();
    $dishes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (!$dishes) {
        exit('Dishes in this category do not exist!');
    }
} 

// Get the total number of products
$total_products = count($dishes);

?>

<?=template_header('Products')?>

<div class="products content-wrapper">
    <h1>Products</h1>
    <p><?=$total_products?> Products</p>
    <form method="get" action="index.php?page=products">
        <!-- better practice to not include parameter at all if no data to send -->
        <input type="hidden" name="page" value="products">
        <?php if(isset($_GET['cid'])): ?>
        <input type="hidden" name="cid" value="<?=$_GET['cid']?>">
        <?php endif; ?>
        <?php if(isset($_GET['p'])): ?>
            <input type="hidden" name="p" value="<?=$_GET['p']?>">
        <?php endif; ?>
        <select name="sort" onchange="this.form.submit()">
            <option value="">Sort By</option>
            <option value="price_asc">Price (Low to High)</option>
            <option value="price_desc">Price (High to Low)</option>
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

