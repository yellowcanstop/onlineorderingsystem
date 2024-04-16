<?php
$products = $_SESSION['cart']['products'];
$subtotal = $_SESSION['cart']['subtotal'];

// validate name: string start with at least one alphabet character
// with zero or more alphabet characters or spaces following it
if (preg_match('/^[a-zA-Z]+[a-zA-Z ]*$/', $_POST['name']) == 0) {
    $_SESSION['error'] = 'Invalid name';
    header('Location: index.php?page=getinfo');
	exit();
}

// sanitize phone number input by removing any non-digit characters
$_POST['phone'] = preg_replace('/[^0-9]/', '', $_POST['phone']);

// validate phone number: exactly 10 digits
if (strlen($_POST['phone']) != 10) {
    $_SESSION['error'] = 'Invalid phone number';
    header('Location: index.php?page=getinfo');
    exit();
}

// get customer_id as session variable if not already stored from profile.php
if (($_SESSION['role'] == 'customer') && (!isset($_SESSION['customer_id']))) {
	$stmt = $pdo -> prepare('SELECT id FROM customers WHERE account_id = :account_id'); 
    $stmt->bindValue(':account_id', $_SESSION['account_id'], PDO::PARAM_INT);
    $stmt->execute();
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);
    $_SESSION['customer_id'] = $customer['id'];
}

// insert address details into addresses table
if (isset($_POST['line_1'], $_POST['state'], $_POST['zip_postcode']) && !empty($_POST['line_1']) && !empty($_POST['state']) && !empty($_POST['zip_postcode']) && (strlen($_POST['zip_postcode']) == 5)){
    if ($stmt = $pdo->prepare('INSERT INTO addresses (line_1, line_2, state, zip_postcode) VALUES (:line_1, :line_2, :state, :zip_postcode)')) {
        $stmt->bindValue(':line_1', $_POST['line_1'], PDO::PARAM_STR);
        $stmt->bindValue(':line_2', $_POST['line_2'], PDO::PARAM_STR);
        $stmt->bindValue(':state', $_POST['state'], PDO::PARAM_STR);
        $stmt->bindValue(':zip_postcode', $_POST['zip_postcode'], PDO::PARAM_STR);
        if (!$stmt->execute()) {
            error_log("Cannot execute sql statement for addresses table.");
        } else {
            // get address_id as session variable
            $address_id = $pdo->lastInsertId();
            $_SESSION['address_id'] = $address_id;
            // insert customer_address into customer_addresses table
            if ($stmt = $pdo->prepare('INSERT INTO customer_addresses (customer_id, address_id, is_default) VALUES (:customer_id, :address_id, :is_default)')) {
                $stmt->bindValue(':customer_id', $_SESSION['customer_id'], PDO::PARAM_INT);
                $stmt->bindValue(':address_id', $address_id, PDO::PARAM_INT);
                $stmt->bindValue(':is_default', $_POST['is_default'], PDO::PARAM_INT);
                if (!$stmt->execute()) {
                    error_log("Cannot execute sql statement for customer_addresses table.");
                }
            } else {
                error_log("Cannot prepare sql statement for customer_addresses table.");
            }
        }
    } else {
        error_log("Cannot prepare sql statement for addresses table.");
    }
} else {
    $_SESSION['error'] = 'Invalid address details';
    header('Location: index.php?page=getinfo');
    exit();
}  


// customer_payment_method_id: 1 for cash, 2 for credit card, 3 for ewallet
if (isset($_POST['customer_payment_method_id'], $_POST['date_order_placed'])) {
    // insert new order into customer_orders table
    if ($stmt = $pdo->prepare('INSERT INTO customer_orders (customer_id, customer_payment_method_id, date_order_placed, payment_amount, name, phone, email, address_id) VALUES (:customer_id, :customer_payment_method_id, :date_order_placed, :payment_amount, :name, :phone, :email, :address_id)')) {
        $stmt->bindValue(':customer_id', $_SESSION['customer_id'], PDO::PARAM_INT);
        $stmt->bindValue(':customer_payment_method_id', $_POST['customer_payment_method_id'], PDO::PARAM_INT);
        $date_order_placed = date('Y-m-d H:i:s', $_POST['date_order_placed']);
        $stmt->bindValue(':date_order_placed', $date_order_placed, PDO::PARAM_STR);
        // payment_amount is of decimal type in database. use PDO::PARAM_STR for all column types which are not of type int or bool
        $stmt->bindValue(':payment_amount', $subtotal, PDO::PARAM_STR);
        $stmt->bindValue(':name', $_POST['name'], PDO::PARAM_STR);
        $stmt->bindValue(':phone', $_POST['phone'], PDO::PARAM_STR);
        $stmt->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
        $stmt->bindValue(':address_id', $_SESSION['address_id'], PDO::PARAM_INT);
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
                    if (!$stmt->execute()) {
                        error_log("Cannot execute sql statement for order id: $order_id with product id: " . $product['id']);
                    }
                } else {
                    error_log("Cannot prepare sql statement for customers_orders_products table.");
                }
            endforeach;
            // update quantities for each dish in dishes table
            foreach ($products as $product):
                if ($stmt = $pdo->prepare('UPDATE dishes SET quantity = quantity - :order_quantity WHERE id = :dish_id')) {
                    $stmt->bindValue(':order_quantity', $_SESSION['cart'][$product['id']], PDO::PARAM_INT);
                    $stmt->bindValue(':dish_id', $product['id'], PDO::PARAM_INT);
                    if (!$stmt->execute()) {
                        error_log("Cannot execute sql statement for updating quantity in dishes table for dish id: " . $product['id']);
                    }
                } else {
                    error_log("Cannot prepare sql statement for updating quantity in dishes table.");
                }
            endforeach;
            // redirect to different page based on payment method
            if ($_POST['customer_payment_method_id'] == 1) {
                // if payment method is cash, redirect to placeorder page
                header('Location: index.php?page=placeorder');
                exit();
            } else if ($_POST['customer_payment_method_id'] == 2) {
                // if payment method is credit card, redirect to credit card mock payment page
                header('Location: index.php?page=cardpayment');
                exit();
            } else if ($_POST['customer_payment_method_id'] == 3) {
                // if payment method is ewallet, redirect to ewallet mock payment page
                header('Location: index.php?page=ewalletpayment');
                exit();
            }
        } 
    } else {
        error_log("Cannot prepare sql statement for customer_orders table.");
        $_SESSION['error'] = 'Failed to process order. Please try again.';
        header('Location: index.php?page=confirmorder');
        exit();
    }
}
