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
        // use session variable to remember items in cart
        // session variable cart is an associative array with key as product id and value as quantity
        if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
            if (array_key_exists($id, $_SESSION['cart'])) {
                // if dish already in cart, update the quantity
                $_SESSION['cart'][$id] += $quantity;
            } else {
                // if dish not in cart, add it to cart
                $_SESSION['cart'][$id] = $quantity;
            }
        } else {
            // add first dish to cart
            $_SESSION['cart'] = array($id => $quantity);
        }
    }
    // prevent form resubmission
    header('location: index.php?page=cart');
    exit;
}

// remove dish in cart by its id. example url: index.php?page=cart&remove=1
if (isset($_GET['remove']) && is_numeric($_GET['remove']) && isset($_SESSION['cart']) && isset($_SESSION['cart'][$_GET['remove']])) {
    unset($_SESSION['cart'][$_GET['remove']]);
}

// update quantities in cart
if (isset($_POST['update']) && isset($_SESSION['cart'])) {
    // loop through the post data so we can update the quantities for every product in cart
    foreach ($_POST as $k => $v) {
        // check if the key is quantity and the value is numeric
        if (strpos($k, 'quantity') !== false && is_numeric($v)) {
            // get cleaned up id by removing 'quantity-' from the key
            $id = str_replace('quantity-', '', $k);
            $quantity = (int)$v;
            if (is_numeric($id) && isset($_SESSION['cart'][$id]) && $quantity > 0) {
                // update new quantity
                $_SESSION['cart'][$id] = $quantity;
            }
        }
    }
    // prevent form resubmission
    header('location: index.php?page=cart');
    exit;
}

// check the session variable for products in cart
$dishes_in_cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
$products = array();
$subtotal = 0.00;
$num_items_in_cart = 0;
if ($dishes_in_cart) {
    // create array of '?' based on the number of dishes in cart
    // then use implode() to convert an array into a comma separated string
    $array_to_question_marks = implode(',', array_fill(0, count($dishes_in_cart), '?'));
    $stmt = $pdo->prepare('SELECT * FROM dishes WHERE id IN (' . $array_to_question_marks . ')');
    // execute the query using dish ids (key)
    $stmt->execute(array_keys($dishes_in_cart));
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $_SESSION['cart']['products'] = $products;
    // calculate the subtotal
    foreach ($products as $product) {
        $subtotal += (float)$product['price'] * (int)$dishes_in_cart[$product['id']];
    }
    $_SESSION['cart']['subtotal'] = $subtotal;
    // calculate total quantity (shown in cart icon in header)
    foreach ($products as $product) {
        $num_items_in_cart += (int)$dishes_in_cart[$product['id']];
    }
    $_SESSION['cart']['num_items_in_cart'] = $num_items_in_cart;
}

// when click place order button, redirect to confirmorder page
if (isset($_POST['confirmorder']) && isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
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
                        <input type="number" name="quantity-<?=$product['id']?>" value="<?=$dishes_in_cart[$product['id']]?>" min="1" max="<?=$product['quantity']?>" placeholder="Quantity" required>
                    </td>
                    <td class="price">&dollar;<?=$product['price'] * $dishes_in_cart[$product['id']]?></td>
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