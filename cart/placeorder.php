<?=template_header('Place Order')?>

<div class="placeorder content-wrapper">
    <h1>You have placed an order.</h1>
    <p>Thank you for ordering with us! We'll contact you by email with your order details.</p>
</div>

<?php unset($_SESSION['cart']); ?>

<?=template_footer()?>