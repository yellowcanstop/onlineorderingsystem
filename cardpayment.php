<?=template_header('Card Payment')?>
<div class="product content-wrapper">
    <h1 style="color: white; font-size:24px;">Payment Details</h1>
    
    <form action="index.php?page=processpayment" method="post">
        <label for="card_number" style="color: white;">Card Number:</label>
        <input type="text" id="card_number" name="card_number" required><br>
        
        <label for="expiry_date" style="color: white;">Expiry Date:</label>
        <input type="text" id="expiry_date" name="expiry_date" required><br>
        
        <label for="cvv" style="color: white;">CVV:</label>
        <input type="text" id="cvv" name="cvv" required><br>
        
        <input type="hidden" name="date_order_paid" value="<?= time() ?>" id="date_order_paid">

        <input type="submit" value="Pay Now">
    </form>
</div>
<?=template_footer()?>