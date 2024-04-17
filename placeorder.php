<?=template_header('Place Order')?>

<div class="placeorder content-wrapper">
    <h1 style="color: white;">You have placed an order.</h1>
    <p style="color: white;">Thank you for ordering with us!</p>
</div>

<?php 
$stmt = $pdo->prepare("DELETE FROM cart_items WHERE account_id = :account_id");
$stmt->execute(['account_id' => $_SESSION['account_id']]);
unset($_SESSION['products']); 
unset($_SESSION['cart_subtotal']); 
unset($_SESSION['num_items_in_cart']); 
?>

<?=template_footer()?>