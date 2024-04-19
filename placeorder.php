<?=template_header('Place Order')?>

<div class="placeorder content-wrapper">
    <h1 style="color: white;">You have placed an order.</h1>
    <p style="color: white;">Thank you for ordering with us!</p>
</div>

<?php 
// clear cart upon successful order placement
$stmt = $pdo->prepare("DELETE FROM cart_items WHERE customer_id = :customer_id");
$stmt->execute(['customer_id' => $_SESSION['customer_id']]);
unset($_SESSION['products']); 
unset($_SESSION['cart_subtotal']); 
unset($_SESSION['num_items_in_cart']); 
?>

<?=template_footer()?>