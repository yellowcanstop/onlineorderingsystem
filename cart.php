<?php
// validate form fields
if (isset($_POST['id'], $_POST['quantity']) && is_numeric($_POST['id']) && is_numeric($_POST['quantity'])) {
    $id = (int)$_POST['id'];
    $quantity = (int)$_POST['quantity'];
    // check if dish exists in database
    $stmt = $pdo->prepare('SELECT * FROM dishes WHERE id = ?');
    $stmt->execute([$_POST['id']]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($product && $quantity > 0) {
        // check if dish already in cart
        $stmt = $pdo->prepare("SELECT * FROM cart_items WHERE account_id = :account_id AND dish_id = :dish_id");
        $stmt->execute(['account_id' => $_SESSION['account_id'], 'dish_id' => $id]);
        $item = $stmt->fetch();
        if ($item) {
            // if already exists, update quantity using ON DUPLICATE KEY UPDATE
            $stmt = $pdo->prepare("INSERT INTO cart_items (account_id, dish_id, quantity, date_added) VALUES (:account_id, :dish_id, :quantity, CURRENT_TIMESTAMP) ON DUPLICATE KEY UPDATE quantity = quantity + :quantity");
            $stmt->execute(['account_id' => $_SESSION['account_id'], 'dish_id' => $id, 'quantity' => $quantity]);
        } else {
            // add dish to cart if not already in it
            $stmt = $pdo->prepare("INSERT INTO cart_items (account_id, dish_id, quantity, date_added) VALUES (:account_id, :dish_id, :quantity, CURRENT_TIMESTAMP");
            $stmt->execute(['account_id' => $_SESSION['account_id'], 'dish_id' => $id, 'quantity' => $quantity]);
        }
    }
    // prevent form resubmission
    header('location: index.php?page=cart');
    exit;
}

// remove dish in cart by its id. example url: index.php?page=cart&remove=1
if (isset($_GET['remove']) && is_numeric($_GET['remove'])) {
    $stmt = $pdo->prepare("DELETE FROM cart_items WHERE account_id = :account_id AND dish_id = :dish_id");
    $stmt->execute(['account_id' => $_SESSION['account_id'], 'dish_id' => $_GET['remove']]);
}

// update quantities in cart
if (isset($_POST['update'])) {
    // loop through the post data so we can update the quantities for every product in cart
    foreach ($_POST as $k => $v) {
        // check if the key is quantity and the value is numeric
        if (strpos($k, 'quantity') !== false && is_numeric($v)) {
            // get cleaned up id by removing 'quantity-' from the key
            $id = str_replace('quantity-', '', $k);
            $quantity = (int)$v;
            if (is_numeric($id) && $quantity > 0) {
                // update new quantity
                $stmt = $pdo->prepare("UPDATE cart_items SET quantity = :quantity WHERE account_id = :account_id AND dish_id = :dish_id");
                $stmt->execute(['account_id' => $_SESSION['account_id'], 'dish_id' => $id, 'quantity' => $quantity]);
            }
        }
    }
    // prevent form resubmission
    header('location: index.php?page=cart');
    exit;
}

// render dishes on page
$subtotal = 0.00;
$num_items_in_cart = 0;
// dish_id in cart_items table is a foreign key to dishes table's id
$stmt = $pdo->prepare("
    SELECT dishes.id, dishes.name, dishes.price, dishes.quantity, dishes.img, cart_items.quantity as cart_quantity 
    FROM dishes 
    INNER JOIN cart_items ON dishes.id = cart_items.dish_id 
    WHERE cart_items.account_id = :account_id
");
$stmt->execute(['account_id' => $_SESSION['account_id']]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
// note that $products assoc array's keys are column names
// so don't need to include table name (hence $product['price'] and not $product['dishes.price'])
if ($products) {
    foreach ($products as $product) {
        $subtotal += (float)$product['price'] * (int)$product['id'];
        $_SESSION['cart_subtotal'] = $subtotal;
    }
    $num_items_in_cart = count($products);
    $_SESSION['num_items_in_cart'] = $num_items_in_cart;
}

// when click place order button, redirect to confirmorder page
if (isset($_POST['confirmorder']) && !empty($products)) {
    $_SESSION['products'] = $products;
    header('Location: index.php?page=confirmorder');
    exit;
}
?>

<?=template_header('Cart')?>
<div class="cart content-wrapper">
    <h1>Order:</h1>
    <form action="index.php?page=cart" method="post">
        <table>
            <thead>
                <tr>
                    <td colspan="2">Item</td>
                    <td>Price</td>
                    <td>Quantity</td>
                    <td>Total</td>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($products)): ?>
                <tr>
                    <td colspan="5" style="text-align:center; color: white;">You have no dishes added in your cart</td>
                </tr>
                <?php else: ?>
                <?php foreach ($products as $product): ?>
                <tr>
                    <td class="img">
                        <a href="index.php?page=product&id=<?=$product['id']?>">
                            <img src="imgs/<?=$product['img']?>" width="50" height="50" alt="<?=$product['name']?>">
                        </a>
                    </td>
                    <td class="product-name">
                        <a href="index.php?page=product&id=<?=$product['id']?>"><?=$product['name']?></a>
                        <br>
                        <a href="index.php?page=cart&remove=<?=$product['id']?>" class="remove">Remove</a>
                    </td>
                    <td class="price">&dollar;<?=$product['price']?></td>
                    <td class="quantity">
                        <input type="number" name="quantity-<?=$product['id']?>" value="<?=$product['cart_quantity']?>" min="1" max="<?=$product['quantity']?>" placeholder="Quantity" required>
                    </td>
                    <td class="price">&dollar;<?=$product['price'] * $product['cart_quantity']?></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="subtotal">
            <span class="text">Subtotal:</span>
            <span class="price">&dollar;<?=$subtotal?></span>
        </div>
        <div class="buttons">
            <input type="submit" value="Update" name="update">
            <input type="submit" value="Confirm Order" name="confirmorder">
        </div>
    </form>
</div>
<?=template_footer()?>