<?php
// <?= is a shorthand for <?php echo, to output result directly to page
// tweak this to order by popularity? (most orders?)
// since products.php will show all dishes
$stmt = $pdo->prepare('SELECT * FROM categories ORDER BY id LIMIT 3');
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?=template_header('Home')?>

<div class="featured">
    <h2>Categories</h2>
    <p>Our best offerings for you</p>
</div>
<div class="recentlyadded content-wrapper">
    <h2>Categories</h2>
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