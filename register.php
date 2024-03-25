<?php include 'protected/header.php'; ?>
<body>	
	<!-- change class to register -->
	<div class="login">
		<h1>Register</h1>
		<form action="adduser.php" method="post" autocomplete="off">
			<label for="username">
				<i class="fas fa-user"></i>
			</label>
			<input type="text" name="username" placeholder="Username" id="username" required>
			<label for="password">
				<i class="fas fa-lock"></i>
			</label>
			<input type="password" name="password" placeholder="Password" id="password" required>
			<label for="email">
				<i class="fas fa-envelope"></i>
			</label>
			<input type="email" name="email" placeholder="Email" id="email" required>
			<input type="submit" value="Register">
		</form>
	</div>
</body>
<?php include 'protected/footer.php'; ?>