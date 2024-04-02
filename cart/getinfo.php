<?=template_header('Order Details')?>

<div class="product content-wrapper">
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
            <label for="phone">Phone Number:</label>
            <input type="tel" name="phone" placeholder="Phone" id="phone" pattern="[0-9]{10}" title="Format: 0107998888" required>
            <label for="line_1">Address Line 1:</label>
            <input type="text" id="line_1" name="line_1" required>

            <label for="line_2">Address Line 2:</label>
            <input type="text" id="line_2" name="line_2">

            <label for="state">State/City:</label>
            <select id="state" name="state" required>
                <option value="">Select State/City:</option>
                <option value="state1">Johor</option>
                <option value="state2">Kedah</option>
                <option value="state3">Kelantan</option>
                <option value="state4">Kuala Lumpur</option>
                <option value="state5">Labuan</option>
                <option value="state6">Melaka</option>
                <option value="state7">Negeri Sembilan</option>
                <option value="state8">Pahang</option>
                <option value="state9">Penang</option>
                <option value="state10">Perak</option>
                <option value="state11">Perlis</option>
                <option value="state12">Putrajaya</option>
                <option value="state13">Sabah</option>
                <option value="state14">Sarawak</option>
                <option value="state15">Selangor</option>
                <option value="state16">Terengganu</option>
            </select>

            <label for="zip_postcode">Zip/Postcode:</label>
            <input type="text" id="zip_postcode" name="zip_postcode" required>
        </div>
        <div class="payment-method">
            <h2>Payment Method</h2>
            <label for="cash">
                <input type="radio" id="cash" name="customer_payment_method_id" value="1" required>
                Cash
            </label>
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