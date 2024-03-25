<?php
// Check to make sure the id parameter is specified in the URL
if (isset($_GET['id'])) {
    // Prepare statement and execute, prevents SQL injection
    $stmt = $pdo->prepare('SELECT * FROM dishes WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    // Fetch the product from the database and return the result as an Array
    $dish = $stmt->fetch(PDO::FETCH_ASSOC);
    // Check if the product exists (array is not empty)
    if (!$dish) {
        // Simple error to display if the id for the product doesn't exists (array is empty)
        exit('Dish does not exist!');
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
            <input type="hidden" name="product_id" value="<?=$dish['id']?>">
            <input type="submit" value="Add To Cart">
        </form>
        <div class="description">
            <?=$dish['description']?>
        </div>
    </div>
</div>

<?=template_footer()?>