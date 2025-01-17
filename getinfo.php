<?php
// retrieve personal info of customer from database only as needed 
if ($stmt = $pdo -> prepare('SELECT name, phone, email FROM customer_accounts WHERE customer_id = :customer_id')) {
    $stmt->bindValue(':customer_id', $_SESSION['customer_id'], PDO::PARAM_INT);
    $stmt->execute();
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    error_log('Cannot prepare sql statement for customer_accounts table.');
    exit();
}

// retrieve address if there exists one which was set as default
if ($stmt = $pdo->prepare("
    SELECT a.* 
    FROM delivery_addresses a
    INNER JOIN customer_accounts ca ON a.address_id = ca.default_address_id
    WHERE ca.customer_id = :customer_id
")) {
	$stmt->bindValue(':customer_id', $_SESSION['customer_id'], PDO::PARAM_INT);
	$stmt->execute();
    $address = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($address) {
        $_SESSION['address_id'] = $address['address_id'];
    } 
} else {
	error_log('Cannot prepare sql statement to retrieve default address.');
	exit();
}

?>

<?=template_header('Order Details')?>

<div class="cart content-wrapper">
    <h1>Finalize Order Details:</h1>
    <div style="color: white;">
        <?php
        if (isset($_SESSION['error'])) {
            echo $_SESSION['error'];
            unset($_SESSION['error']); // unset error message after display
        }
        ?>
    </div>
    <form id="getinfo" action="index.php?page=checkout" method="post">
        <div class="customer-details">
            <h2>Customer Details:</h2>
            <label for="name">Name:</label>
            <input type="text" id="name" placeholder="Name" name="name" value="<?=$customer['name'] ?>" required>
            <br>
            <label for="phone">Phone:</label>
            <input type="tel" name="phone" placeholder="e.g. 01146138711" id="phone" pattern="[0-9]{10}" title="Format: 0107998888" value="<?=$customer['phone'] ?>" required>
            <br>
            <label for="email">Email:</label>
            <input type="text" id="email" name="email" value="<?=$customer['email'] ?>" required>
            <br>
            <label for="line_1">Address Line 1:</label>
            <input type="text" id="line_1" placeholder="Address Line 1" name="line_1" value="<?=isset($_SESSION['address_id']) ? $address['line_1'] : ''?>"  required>
            <br>
            <label for="line_2">Address Line 2:</label>
            <input type="text" id="line_2" placeholder="Address Line 2" name="line_2" value="<?=isset($_SESSION['address_id']) ? $address['line_2'] : ''?>">
            <br>
            <label for="city_state">State/City:</label>
            <select id="city_state" name="city_state">
                <option value="">Select State/City:</option>
                <?php if (isset($_SESSION['address_id'])): ?>
                    <option value="<?=$address['city_state']?>" selected><?=$address['city_state']?></option>
                <?php endif; ?>
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
            <span class="country-label">Country : Malaysia</span>
            <img src="imgs/myflag.png" alt="Malaysia Flag" class="flag">
            <br>
            <label for="zip_postcode">Zip/Postcode:</label>
            <input type="text" id="zip_postcode" placeholder="e.g. 43500" name="zip_postcode" value="<?=isset($_SESSION['address_id']) ? $address['zip_postcode'] : ''?>" required>
            <input type="hidden" name="save_default" value="0">
            <!-- if radio button is selected, will override above hidden input -->
            <label for="save_address">
                <input type="radio" id="save_address" name="save_default" value="1">
                Save as default address
            </label> 
        </div>
        <div class="payment-method">
            <h2>Payment Method</h2>
            <label for="cash">
                <input type="radio" id="cash" name="customer_payment_method_id" value="1" required>
                Cash on Delivery
            </label>
            <br>
            <label for="credit-card">
                <input type="radio" id="credit-card" name="customer_payment_method_id" value="2" required>
                Credit Card
            </label>
            <img src="imgs/creditcard.jpg" alt="Credit Card" class="credit-card">
            <br>
            <label for="ewallet">
                <input type="radio" id="ewallet" name="customer_payment_method_id" value="3" required>
                E-wallet
            </label>
            <img src="imgs/tng.png" alt="TnG" class="tng">
        </div>
        <input type="hidden" name="date_order_placed" value="<?= time() ?>" id="date_order_placed">
        <input type="submit" value="Confirm order details" class="submit-button">
    </form>
    <a href="index.php?page=confirmorder" class="return-cart-button">Return to order</a>
</div>

<?=template_footer()?>
