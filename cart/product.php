<?php
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare('SELECT * FROM dishes WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $dish = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$dish) {
        error_page('Dish not found', 'The dish with the ID of ' . $_GET['id'] . ' does not exist.');
        error_log('Dish selected does not exist. ID: ' . $_GET['id']);
        exit();
    }
} else {
    // Simple error to display if the id wasn't specified
    exit('ID was not specified');
}
?>
<?=template_header('Product')?>

<div class="product content-wrapper">
    <img src="imgs/<?=$dish['img']?>" width="500" height="500" alt="<?=$dish['name']?>">
    <div>
        <h1 class="name"><?=$dish['name']?></h1>
        <span class="price">
            &dollar;<?=$dish['price']?>
            <?php if ($dish['quantity'] === 0): ?>
            <p>Dish is <span style="font-style:oblique">unavailable</span>.</p>
            <?php endif; ?>
        </span>
        <form action="index.php?page=cart" method="post">
            <input type="number" name="quantity" value="1" min="1" max="<?=$dish['quantity']?>" placeholder="Quantity" required>
            <input type="hidden" name="id" value="<?=$dish['id']?>">
            <input type="submit" value="Add To Cart">
        </form>
        <div class="description">
            <?=$dish['description']?>
        </div>
    </div>
</div>

<?=template_footer()?>