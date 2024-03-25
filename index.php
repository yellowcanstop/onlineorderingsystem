<?php include 'protected/header.php'; ?>
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
</body>
<?php include 'protected/footer.php'; ?>