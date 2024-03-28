<?php
// confirmorder -> getinfo -> payment mockup -> place order

// server-side validation after form is submitted
// prevent user from disabling js on browser, bypassing any client-side validation

$msg = '';
// Check if POST data is not empty
// htmlspecialchars(): convert special characters to their HTML entities (prevent XSS attacks)
if (!empty($_POST)) {
    $first_name = isset($_POST['first_name']) ? htmlspecialchars($_POST['first_name']) : '';
    $last_name = isset($_POST['first_name']) ? htmlspecialchars($_POST['first_name']) : '';
    $email = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_SANITIZE_EMAIL) : '';
    $phone = isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '';
    $title = isset($_POST['title']) ? htmlspecialchars($_POST['title']) : '';
    $created = isset($_POST['created']) ? htmlspecialchars($_POST['created']) : date('Y-m-d H:i:s');

    $stmt = $pdo->prepare('INSERT INTO contacts VALUES (?, ?, ?, ?, ?, ?)');
    $stmt->execute([$id, $name, $email, $phone, $title, $created]);

    $msg = 'Created Successfully!';
}
?>

<?=template_header('Customer Details')?>

<div class="product content-wrapper">
    <h1>Customer Details</h1>
    <h2>Update your personal details:</h2>
    <div>
        <form action="index.php?page=getinfo" method="post">
            
            <input type="number" name="quantity" value="1" min="1" max="<?=$dish['quantity']?>" placeholder="Quantity" required>
            
            
            <input type="text" name="first_name" placeholder="First Name" id="first_name" required>
            <input type="text" name="last_name" placeholder="Last Name" id="last_name" required>
            <label for="email">Email:</label>
            <input type="email" name="email" placeholder="Email" id="email" required>

            <label for="confirm_email">Confirm Email:</label>
            <input type="email" name="confirm_email" placeholder="Confirm Email" id="confirm_email" oninput="validateEmail()" required>

            <label for="phone">Phone:</label>
            <input type="text" name="phone" placeholder="Phone" id="phone" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" title="Format: 123-456-7890" required>

            
            <input type="text" name="address" placeholder="Address" id="address" required>
            <input type="datetime-local" name="created" value="<?=date('Y-m-d\TH:i')?>" id="created">
            
            <input type="submit" value="Confirm payment details">
        </form>
    </div>
</div>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php endif; ?>

<script>
function validateEmail() {
    // variables not being reassigned after initial assignment
    const email = document.getElementById('email').value;
    const confirm_email = document.getElementById('confirm_email').value;

    if (email != confirm_email) {
        alert('Email Not Matching!');
    }
}
</script>



<?=template_footer('Customer Details')?>