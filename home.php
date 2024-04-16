<?php
$stmt = $pdo->prepare('SELECT * FROM categories ORDER BY id');
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
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