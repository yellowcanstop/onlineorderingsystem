<?=template_header('Order Details')?>

<div class="cart content-wrapper">
    <h1>Finalize Order Details</h1>
    <div>
        <?php
        if (isset($_SESSION['error'])) {
            echo $_SESSION['error'];
            unset($_SESSION['error']); // unset error message after display
        }
        ?>
    </div>
    <form action="index.php?page=checkout" method="post">
        <div class="customer-details">
            <h2>Customer Details</h2>
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
            <br>
            <label for="phone">Phone Number:</label>
            <input type="tel" name="phone" placeholder="Phone" id="phone" pattern="[0-9]{10}" title="Format: 0107998888" required>
            <br>
            <label for="line_1">Address Line 1:</label>
            <input type="text" id="line_1" name="line_1" required>
            <br>
            <label for="line_2">Address Line 2:</label>
            <input type="text" id="line_2" name="line_2">
            <br>
            <label for="state">State/City:</label>
            <select id="state" name="state" required>
                <option value="">Select State/City:</option>
                <option value="Johor">Johor</option>
                <option value="Kedah">Kedah</option>
                <option value="Kelantan">Kelantan</option>
                <option value="Kuala Lumpur">Kuala Lumpur</option>
                <option value="Labuan">Labuan</option>
                <option value="Melaka">Melaka</option>
                <option value="Negeri Sembilan">Negeri Sembilan</option>
                <option value="Pahang">Pahang</option>
                <option value="Penang">Penang</option>
                <option value="Perak">Perak</option>
                <option value="Perlis">Perlis</option>
                <option value="Putrajaya">Putrajaya</option>
                <option value="Sabah">Sabah</option>
                <option value="Sarawak">Sarawak</option>
                <option value="Selangor">Selangor</option>
                <option value="Terengganu">Terengganu</option>
            </select>
            <br>
            Country : Malaysia
            <br>
            <label for="zip_postcode">Zip/Postcode:</label>
            <input type="text" id="zip_postcode" name="zip_postcode" required>
        </div>
        <div>
            <input type="hidden" name="is_saved" value="0">
            <!-- if radio button is selected, will override above hidden input -->
            <label for="save_address">
                <input type="radio" id="save_address" name="is_saved" value="1">
                Save as default address
            </label>
        </div>
        <div class="payment-method">
            <h2>Payment Method</h2>
            <label for="cash">
                <input type="radio" id="cash" name="customer_payment_method_id" value="1" required>
                Cash
            </label>
            <br>
            <label for="credit-card">
                <input type="radio" id="credit-card" name="customer_payment_method_id" value="2" required>
                Credit Card / Ewallet
            </label>
        </div>
        <input type="hidden" name="date_order_placed" value="<?= time() ?>" id="date_order_placed">
        <input type="submit" value="Confirm order details">
    </form>
</div>




<?=template_footer()?>