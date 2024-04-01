<?php
$products = $_SESSION['cart']['products'];
$subtotal = $_SESSION['cart']['subtotal'];

// validate name
if (preg_match('/^[a-zA-Z]+$/', $_POST['name']) == 0) {
    $_SESSION['error'] = 'Invalid name';
    header('Location: index.php?page=getinfo');
	exit();
}

// sanitize phone number input by removing any non-digit characters
$_POST['phone'] = preg_replace('/[^0-9]/', '', $_POST['phone']);

// get customer_id as session variable if not already stored from profile.php
if (($_SESSION['role'] == 'customer') && (!isset($_SESSION['customer_id']))) {
	$stmt = $pdo -> prepare('SELECT id FROM customers WHERE account_id = :account_id'); 
    $stmt->bindValue(':account_id', $_SESSION['account_id'], PDO::PARAM_INT);
    $stmt->execute();
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);
    $_SESSION['customer_id'] = $customer['id'];
}

// customer_payment_method_id: 1 for cash, 2 for credit card or ewallet
if (isset($_POST['customer_payment_method_id'], $_POST['date_order_placed'])) {
    // insert new order into customer_orders table
    if ($stmt = $pdo->prepare('INSERT INTO customer_orders (customer_id, customer_payment_method_id, date_order_placed, payment_amount) VALUES (:customer_id, :customer_payment_method_id, :date_order_placed, :payment_amount)')) {
        $stmt->bindValue(':customer_id', $_SESSION['customer_id'], PDO::PARAM_INT);
        $stmt->bindValue(':customer_payment_method_id', $_POST['customer_payment_method_id'], PDO::PARAM_INT);
        $date_order_placed = date('Y-m-d H:i:s', $_POST['date_order_placed']);
        $stmt->bindValue(':date_order_placed', $date_order_placed, PDO::PARAM_STR);
        // payment_amount is of decimal type in database. use PDO::PARAM_STR for all column types which are not of type int or bool
        $stmt->bindValue(':payment_amount', $subtotal, PDO::PARAM_STR);
        if (!$stmt->execute()) {
            error_log("Cannot execute sql statement for customers_orders table.");
        } else {
            // use lastInsertId() to get order_id which is a foreign key in customer_orders_products table
            $order_id = $pdo->lastInsertId();
            $_SESSION['order_id'] = $order_id;
            // insert new order into customer_orders_products table
            foreach ($products as $product):
                if ($stmt = $pdo->prepare('INSERT INTO customer_orders_products (dish_id, order_id, order_quantity) VALUES (:dish_id, :order_id, :order_quantity)')) {
                    $stmt->bindValue(':dish_id', $product['id'], PDO::PARAM_INT);
                    $stmt->bindValue(':order_id', $order_id, PDO::PARAM_INT);
                    $stmt->bindValue(':order_quantity', $_SESSION['cart'][$product['id']], PDO::PARAM_INT);
                    $stmt->execute();
                    if (!$stmt->execute()) {
                        error_log("Cannot execute sql statement for order id: $order_id with product id: " . $product['id']);
                    }
                } else {
                    error_log("Cannot prepare sql statement for customers_orders_products table.");
                }
            endforeach;
            // redirect to different page based on payment method
            if ($_POST['customer_payment_method_id'] == 1) {
                // if payment method is cash, redirect to placeorder page
                header('Location: index.php?page=placeorder');
                exit();
            } else {
                // if payment method is credit card or ewallet, redirect to payment page
                header('Location: index.php?page=payment');
                exit();
            }
        } 
    } else {
        error_log("Cannot prepare sql statement for customer_orders table.");
        $_SESSION['error'] = 'Failed to process order. Please try again.';
        header('Location: index.php?page=confirmorder');
        exit();
    }
    // prevent form resubmission
    header('location: index.php?page=getinfo');
    exit;
}



?>

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
    <form action="index.php?page=getinfo" method="post">
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