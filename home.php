<?php
// to get categories of dishes to display on home page
$stmt = $pdo->prepare('SELECT * FROM categories ORDER BY id');
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// for displaying the number of items in the cart upon login
$num_items_in_cart = 0;
$_SESSION['num_items_in_cart'] = 0;
// dish_id in cart_items table is a foreign key to dishes table's id
$stmt = $pdo->prepare("
    SELECT dishes.id, dishes.name, dishes.price, dishes.quantity, dishes.img, cart_items.quantity as cart_quantity 
    FROM dishes 
    INNER JOIN cart_items ON dishes.id = cart_items.dish_id 
    WHERE cart_items.account_id = :account_id
");
$stmt->execute(['account_id' => $_SESSION['account_id']]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
if ($products) {
    foreach ($products as $product) {
        $num_items_in_cart += (int)$product['cart_quantity'];
        $_SESSION['num_items_in_cart'] = $num_items_in_cart;
    }
}
?>

<?=template_header('Home')?>
<div class="featured">
    <h2>Categories</h2>
    <p>Our best offerings for you</p>
    <div class="background-shape"></div>
</div>
<div class="recentlyadded content-wrapper">
    <h2>Select your preference</h2>
    <div class="products">
        <?php foreach ($categories as $category): ?>
        <a href="index.php?page=products&cid=<?=$category['id']?>" class="product">
            <img src="imgs/<?=$category['img']?>" width="200" height="200" alt="<?=$category['name']?>">
            <span class="name"><?=$category['name']?></span>
        </a>
        <?php endforeach; ?>
    </div>
</div>

<?=template_footer()?>