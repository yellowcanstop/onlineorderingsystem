<?php 
session_start();
include 'functions.php'; 
?>
<?= template_header('Login') ?>

<body>
	<div class="login">
		<h1>Login</h1>
		<div>
		<?php
		if (isset($_SESSION['error'])) {
			echo $_SESSION['error'];
			unset($_SESSION['error']); // unset error message after display
		}
		?>
		</div>
		<form action="authenticate.php" method="post">
			<label for="username">
				<i class="fas fa-user"></i>
			</label>
			<input type="text" name="username" placeholder="Username" id="username" required>
			<label for="password">
				<i class="fas fa-lock"></i>
			</label>
			<input type="password" name="password" placeholder="Password" id="password" required>
			<label for="remember_me">
				<input type="checkbox" name="remember_me" id="remember_me">
				Remember me
			</label>
			<input type="submit" value="Login">
		</form>
	</div>
	<div class="register-link">
		<p>Don't have an account? <a href="register.php">Register</a></p>
	</div>
</body>
<?= template_footer('Login') ?>