<?=template_header('Place Order')?>

<div class="placeorder content-wrapper">
    <h1 style="color: white;">You have placed an order.</h1>
    <p style="color: white;">Thank you for ordering with us!</p>
</div>

<?php unset($_SESSION['cart']); ?>

<?=template_footer()?>