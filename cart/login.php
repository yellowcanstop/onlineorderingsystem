<?php include 'functions.php' ?>
<?= template_header('Login') ?>
<body>
	<div class="login">
		<h1>Login</h1>
		<!-- when the form is submitted, the data is sent to authenticate.php -->
		<form action="authenticate.php" method="post">
			<label for="username">
				<i class="fas fa-user"></i>
			</label>
			<!-- in auth file use $_POST['username'] to get the data -->
			<input type="text" name="username" placeholder="Username" id="username" required>
			<label for="password">
				<i class="fas fa-lock"></i>
			</label>
			<input type="password" name="password" placeholder="Password" id="password" required>
			<input type="submit" value="Login">
		</form>
	</div>
	<div>
		<p>Don't have an account? <a href="register.php">Register</a></p>
	</div>
</body>
<?= template_footer('Login') ?>