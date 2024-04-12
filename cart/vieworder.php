<?php
if (isset($_GET['order_id'])) {
    $stmt = $pdo->prepare('SELECT dish_id, order_quantity FROM customer_orders_products WHERE order_id = ?');
    $stmt->execute([$_GET['order_id']]);
    $customer_order = $stmt->fetchALL(PDO::FETCH_ASSOC);
    if (!$customer_order) {
        error_page('customer order not found', 'The customer_order with the ID of ' . $_GET['order_id'] . ' does not exist.');
        exit();
    }
} else {
    error_page('customer order ID not specified', 'The customer_order ID is required to display a customer order.');
    exit();
}

// store all dish names in associative array with key as dish id and value as dish name
// so display dish names by referencing dish id in orders array
$names = array();
if ($stmt = $pdo->prepare('SELECT id, name FROM dishes')) {
    
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($results as $result) {
        $names[$result['id']] = $result['name'];
    }
} else {
    error_log('Cannot prepare sql statement for dishes table.');
    exit();
}

?>

<?=template_header('View Order')?>
<div class="cart content-wrapper">
    <div>
        
        <h1 class="name">Order ID: <?=$_GET['order_id']?></h1>
        <a href="index.php?page=manageorders" class="btn">Back to Manage Orders</a>
        <table>
        <thead>
            <tr>
                <td>Dish</td>
                <td>Quantity</td>
                <td>Ready from Kitchen?</td>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($customer_order as $item): ?>
        <tr>
            <td><?=$names[$item['dish_id']]?></td>
            <td><?=$item['order_quantity']?></td>
            <!-- checkbox's checked attribute is set based on $_SESSION['selected_dishes'] -->
            <!-- to get checkbox state from local storage when page loads, use js -->
            <td><input type="checkbox" class="dish-checkbox" value="<?=$item['dish_id']?>" onchange="updateLocalStorage(this)" <?php echo (isset($_SESSION['selected_dishes'][$item['dish_id']]) && $_SESSION['selected_dishes'][$item['dish_id']] === true) ? 'checked' : '' ?>></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
        </table>
    </div>
</div>
<?=template_footer()?>
<script>
// update local storage when checkbox is checked
// store dish id as key and boolean isChecked as value
// when page is loaded, retrieve checkbox states from local storage
// if no checkbox states are stored, all checkboxes are unchecked by default
// local storage is purely client-side and persists, local to the user's browser
// which I think is acceptable for insensitive data like checkbox states 
// for employees to track progress of individual dishes in an order (communication with kitchen staff)

// set local storage key-value pair based on checkbox state
function updateLocalStorage(element) {
    const dishId = element.value;
    const isChecked = element.checked;
    localStorage.setItem(dishId, isChecked);
}
// js code that runs when page loads to update checkbox states based on values in local storage
// get from local storage and set checkbox states
window.onload = function() {
    const checkboxes = document.querySelectorAll('.dish-checkbox');
    checkboxes.forEach((checkbox) => {
        const dishId = checkbox.value;
        // set 'checked' property based on string value retrieved from local storage
        const isChecked = localStorage.getItem(dishId);
        checkbox.checked = isChecked === 'true';
    });
};
</script>