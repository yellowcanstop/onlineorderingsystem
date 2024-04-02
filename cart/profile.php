<?php
if ($_SESSION['loggedin'] != TRUE) {
	$_SESSION['error'] = 'You are not logged in.';
	header('Location: login.php');
	exit();
}

// only save less-sensitive information in session variables.
// hence retrieve more sensitive information from database only when needed
// customer_id is stored as a session variable to maintain state between requests.
if ($_SESSION['role'] == 'customer') {
	// retrieve customer information from database
	if ($stmt = $pdo -> prepare('SELECT id, customer_first_name, customer_last_name, customer_phone, date_of_register FROM customers WHERE account_id = :account_id')) {
		$stmt->bindValue(':account_id', $_SESSION['account_id'], PDO::PARAM_INT);
		$stmt->execute();
		$customer = $stmt->fetch(PDO::FETCH_ASSOC);
		$_SESSION['customer_id'] = $customer['id'];
	} else {
		error_log('Cannot prepare sql statement for customers table.');
		exit();
	}
}

if (isset($_POST['new_email'])) {
	$new_email = $_POST['new_email'];
	// check if email already exists
	if ($new_email == $_SESSION['email']) {
		$_SESSION['error'] = 'Email is the same as your old one. Please try again.';
		header('Location: index.php?page=profile');
		exit();
	}
	if ($stmt = $pdo->prepare('SELECT email FROM accounts WHERE email = :email')) {
		$stmt->bindValue(':email', $_POST['new_email'], PDO::PARAM_STR);
		$stmt->execute();
		$email = $stmt->fetch(PDO::FETCH_ASSOC);
		if (!empty($email)) {
			$_SESSION['error'] = 'Email already exists.';
			header('Location: index.php/page=profile');
			exit();
		} else {
			if ($stmt = $pdo -> prepare('UPDATE accounts SET email = :email WHERE account_id = :account_id')) {
				$stmt->bindValue(':email', $new_email, PDO::PARAM_STR);
				$stmt->bindValue(':account_id', $_SESSION['account_id'], PDO::PARAM_INT);
				$stmt->execute();
				$_SESSION['email'] = $new_email;
			} else {
				error_log('Cannot prepare sql statement for accounts table.');
				exit();
			}
		}
	} else {
		error_log('Cannot prepare sql statement for accounts table.');
		exit();
	}
}

if (isset($_POST['new_password'])) {
	$current_password = $_POST['current_password'];
	$new_password = $_POST['new_password'];
	if ($stmt = $pdo -> prepare('SELECT password FROM accounts WHERE account_id = :account_id')) {
		$stmt->bindValue(':account_id', $_SESSION['account_id'], PDO::PARAM_INT);
		$stmt->execute();
		$password = $stmt->fetch(PDO::FETCH_ASSOC);
		if (password_verify($current_password, $password['password']) && ($current_password != $new_password)) {
			if ($stmt = $pdo -> prepare('UPDATE accounts SET password = :password WHERE account_id = :account_id')) {
				$stmt->bindValue(':password', password_hash($new_password, PASSWORD_DEFAULT), PDO::PARAM_STR);
				$stmt->bindValue(':account_id', $_SESSION['account_id'], PDO::PARAM_INT);
				$stmt->execute();
			} else {
				$_SESSION['error'] = 'Cannot update password. Please try again.';
				header('Location: index.php?page=profile');
				error_log('Cannot prepare sql statement for accounts table.');
				exit();
			}
		} else {
			$_SESSION['error'] = 'Your current password is incorrect or your new password is the same as your old one. Please try again.';
			header('Location: index.php?page=profile');
			exit();
		}
	}
}

if (isset($_POST['new_phone'])) {
	$new_phone = $_POST['new_phone'];
	if ($stmt = $pdo -> prepare('UPDATE customers SET customer_phone = :phone WHERE account_id = :account_id')) {
		$stmt->bindValue(':phone', $new_phone, PDO::PARAM_STR);
		$stmt->bindValue(':account_id', $_SESSION['account_id'], PDO::PARAM_INT);
		$stmt->execute();
		$customer['customer_phone'] = $new_phone;
	} else {
		error_log('Cannot prepare sql statement for customers table.');
		exit();
	}
}
?>

<?=template_header('Profile')?>
<div class="placeorder content-wrapper">
	<div class="content">
		<h2>Profile Page</h2>
		<div>
			<h3>Personal information:</h3>
			<table>
				<tr>
					<td>Username:</td>
					<td><?=htmlspecialchars($_SESSION['username'], ENT_QUOTES)?></td>
				</tr>
				<tr>
					<td>Email:
					</td>
					<td><?=htmlspecialchars($_SESSION['email'], ENT_QUOTES)?>
						<br>
						<form action="index.php?page=profile" method="post">
							<input type="email" name="new_email" placeholder="New Email" id="new_email">
							<div class="buttons"><input type="submit" value="Change Email"></div>
						</form>					
					</td>
				</tr>
				<tr>
					<td>Password:
					</td>
					<td>
						<form action="index.php?page=profile" method="post">
							<input type="password" name="current_password" placeholder="Current Password" id="current_password">
							<br>
							<input type="password" name="new_password" placeholder="New Password" id="new_password" oninput="checkPassword()">
							<p id="passwordCheck"></p>
							<div class="buttons"><input type="submit" value="Change Password"></div>
						</form>
					</td>
					<td>
					<?php
						if (isset($_SESSION['error'])) {
							echo $_SESSION['error'];
							unset($_SESSION['error']); // unset error message after display
						}
					?>
					</td>
				</tr>
				<?php if ($_SESSION['role'] == 'customer'): ?>
				<tr>
					<td>Name:</td>
					<td><?=htmlspecialchars($customer['customer_first_name'], ENT_QUOTES)?>
					<?=htmlspecialchars($customer['customer_last_name'], ENT_QUOTES)?></td>
				</tr>
				<tr>
					<td>Phone:</td>
					<td><?=htmlspecialchars($customer['customer_phone'], ENT_QUOTES)?>
					<br>
						<form action="index.php?page=profile" method="post">
							<input type="text" name="new_phone" placeholder="New Phone" id="new_phone">
							<div class="buttons"><input type="submit" value="Change Phone"></div>
						</form>					
					</td>
				</tr>
				<tr>
					<td>Date of Registration:</td>
					<td><?=htmlspecialchars($customer['date_of_register'], ENT_QUOTES)?></td>
				</tr>
				<?php endif; ?>
			</table>
		</div>
</div>
<script>
// send AJAX request to server to check if username already exists
function checkPassword() {
    let password = $('#password').val();
    $.ajax({
        url: 'checkpassword.php',
        type: 'POST',
        data: {password: password},
		dataType: 'text',
        success: function(response) {
            response = response.trim();
			if (response == 'invalid') {
                $('#password').css('border', '3px solid red');
                passwordCheck.textContent = 'Password must be between 8 and 20 characters long, contain letters, numbers and at least one special character!';
                passwordCheck.style.color = 'red';
            } else {
                $('#password').css('border', '3px solid green');
                passwordCheck.textContent = 'Valid password!';
                passwordCheck.style.color = 'green';
            }
        }
    });
}
</script>
<?=template_footer()?>