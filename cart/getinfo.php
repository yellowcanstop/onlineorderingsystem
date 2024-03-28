<?php
// confirmorder -> getinfo -> payment mockup -> place order
?>
<?=template_header('Customer Details')?>

<div class="product content-wrapper">
    <h1>Customer Details</h1>
    <div>
        <form action="index.php?page=customerinfo" method="post">
            <input type="number" name="quantity" value="1" min="1" max="<?=$dish['quantity']?>" placeholder="Quantity" required>
            <input type="text" name="username" placeholder="Username" id="username" required>
            
            
            <input type="submit" value="Confirm payment details">
        </form>
    </div>
</div>

<?=template_footer('Customer Details')?>