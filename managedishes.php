<?php
// number of dishes to show per page
$num_dishes_on_page = 10;
// show selected page otherwise default to 1
$current_page = isset($_GET['p']) && is_numeric($_GET['p']) ? (int)$_GET['p'] : 1;
// get selected category option from the GET parameters
$selected_category = isset($_GET['category_id']) ? $_GET['category_id'] : false;

// get dishes from database
if ($selected_category) {
    $stmt = $pdo->prepare('SELECT dish_id, name, quantity FROM dishes WHERE category_id = :category_id LIMIT :current_page, :record_per_page');
    $stmt->bindValue(':category_id', $_GET['category_id'], PDO::PARAM_STR);
    $stmt->bindValue(':current_page', ($current_page - 1) * $num_dishes_on_page, PDO::PARAM_INT);
    $stmt->bindValue(':record_per_page', $num_dishes_on_page, PDO::PARAM_INT);
    $stmt->execute();
    $dishes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (!$dishes) {
        error_page('Dishes not found', 'No dishes loaded');
        error_log('Cannot get dishes from database.');
        exit();
    }
}
else {
    $stmt = $pdo->prepare('SELECT dish_id, name, quantity FROM dishes ORDER BY quantity LIMIT :current_page, :record_per_page');
    $stmt->bindValue(':current_page', ($current_page - 1) * $num_dishes_on_page, PDO::PARAM_INT);
    $stmt->bindValue(':record_per_page', $num_dishes_on_page, PDO::PARAM_INT);
    $stmt->execute();
    $dishes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (!$dishes) {
        error_page('Dishes not found', 'No dishes loaded');
        error_log('Cannot get dishes from database.');
        exit();
    }
} 

// update quantity for specific dish
if (isset($_POST['update_quantity']) && isset($_POST['id']) && is_numeric($_POST['update_quantity']) && isset($_POST['quantity']) && isset($_POST['name'])) {
    $stmt = $pdo->prepare('UPDATE dishes SET quantity = :quantity WHERE dish_id = :id');
    $stmt->bindValue(':quantity', $_POST['update_quantity'], PDO::PARAM_INT);
    $stmt->bindValue(':id', $_POST['id'], PDO::PARAM_INT);
    if (!$stmt->execute()) {
        $_SESSION['update_message'] = "<p>Cannot update quantity for " . $_POST['name'] . ". Please try again.</p>";
        error_log("Cannot execute sql statement to update dishes table.");
    }
    $_SESSION['update_message'] = "<p>Successfully updated quantity for " . $_POST['name'] . " from " . $_POST['quantity'] . " to " . $_POST['update_quantity'] . "</p>";
    header('Location: index.php?page=managedishes&p=' . $current_page);
    exit();
}   

$total_dishes = count($dishes);

?>

<?=template_header('Manage Inventory')?>

<div class="cart content-wrapper">
    <h1>Manage Inventory</h1>
    <div>
        <?php
        if (isset($_SESSION['update_message'])) {
            echo $_SESSION['update_message'];
            unset($_SESSION['update_message']); 
        }
        ?>
    </div>
    <form method="get" action="index.php?page=managedishes">
        <input type="hidden" name="page" value="managedishes">
        <!-- show only if there are GET parameters -->
        <?php if(isset($_GET['p'])): ?>
            <input type="hidden" name="p" value="<?=$_GET['p']?>">
        <?php endif; ?>
        <!-- select view filtered by category -->
        <select name="category_id" onchange="this.form.submit()">
            <option value="">Choose Category</option>
            <option value="">All - Default</option>
            <option value="1">Appetizer</option>
            <option value="2">Main Course</option>
            <option value="3">Dessert</option>
            <option value="4">Beverage</option>
        </select>
    </form>
    <table>
            <thead>
                <tr>
                    <td>Dish Name</td>
                    <td>Current Quantity</td>
                    <td>Update Quantity</td>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dishes as $dish): ?>
                <tr>
                    <td style="color: white";><?=$dish['name']?></td>
                    <td style="color: white";><?=$dish['quantity']?></td>
                    <td>
                        <form method="post" action="index.php?page=managedishes">
                            <input type="hidden" name="page" value="managedishes">
                            <input type="hidden" name="id" value="<?=$dish['dish_id']?>">
                            <input type="hidden" name="name" value="<?=$dish['name']?>">
                            <input type="hidden" name="quantity" value="<?=$dish['quantity']?>">
                            <input type="number" name="update_quantity" value="<?=$dish['quantity']?>" min="0" placeholder="New Qty" required>
                            <input type="submit" value="Update" class="update-quantity">
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                
            </tbody>
        
        </table>
 
    <div class="buttons">
        <?php if ($current_page > 1): ?>
        <a href="index.php?page=managedishes<?=isset($_GET['category_id']) ? '&category_id='.$_GET['category_id'] : ''?>&p=<?=$current_page-1?>">Prev</a>
        <?php endif; ?>
        <?php if ($total_dishes > ($current_page * $num_dishes_on_page) - $num_dishes_on_page + count($dishes)): ?>
        <a href="index.php?page=managedishes<?=isset($_GET['category_id']) ? '&category_id='.$_GET['category_id'] : ''?>&p=<?=$current_page+1?>">Next</a>
        <?php endif; ?>
    </div>
</div>

<?=template_footer()?>

