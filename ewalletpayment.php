<?=template_header('E-Wallet Payment')?>
<div class="product content-wrapper">
    <h1>Please scan below QR Code</h1>
    <div>
        <img src="imgs/tngQR.png" alt="TnG-QR" class="tng-QR">
    </div>
    <br>
    <form action="index.php?page=processpayment" method="post">       
        <input type="hidden" name="date_order_paid" value="<?= time() ?>" id="date_order_paid">
        <input type="submit" value="Continue">
    </form>
    <a href="index.php?page=checkout" class="submit-button">Return to Checkout</a>
</div>
<?=template_footer()?>