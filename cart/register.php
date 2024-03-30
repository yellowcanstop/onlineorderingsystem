<?php include 'functions.php' ?>
<?= template_header('Register') ?>
<body>
	<div>
		<h1>Register</h1>
		<!-- when the form is submitted, the data is sent to authenticate.php -->
		<form action="adduser.php" method="post" autocomplete="off">
            <label for="username">Username:</label>
			<input type="text" name="username" placeholder="Username" id="username" required>
            <br>
            <label for="firstname">Name:</label>
			<input type="text" name="firstname" placeholder="First Name" id="firstname" required>
            <label for="lastname"></label>
			<input type="text" name="lastname" placeholder="Last Name" id="lastname" required>
            <br>
            <p> Password must be between 8 and 20 characters long, contain letters, numbers and at least one special character!</p>
			<label for="password">Password:</label>
            <input type="password" name="password" placeholder="Password" id="password" required>
            <br>
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" name="confirm_password" placeholder="Confirm Password" id="confirm_password" oninput="validatePassword()" required>
            <p id="passwordError" style="color:red;"></p>


            <label for="email">Email:</label>
            <input type="email" name="email" placeholder="Email" id="email" required>
            <br>
            <label for="confirm_email">Confirm Email:</label>
            <input type="email" name="confirm_email" placeholder="Confirm Email" id="confirm_email" oninput="validateEmail()" required>
            <p id="emailError" style="color:red;"></p>

            <label for="phone">Phone:</label>
            <input type="text" name="phone" placeholder="Phone" id="phone" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" title="Format: 123-456-7890" required>

            <input type="hidden" name="date_of_register" value="<?= time() ?>" id="date_of_register">

			<input type="submit" value="Register">
		</form>
	</div>
	<div>
		<p>Already have an account? <a href="login.php">Login</a></p>
	</div>
</body>
<script>
function validateEmail() {
    // variables not being reassigned after initial assignment
    const email = document.getElementById('email').value;
    const confirm_email = document.getElementById('confirm_email').value;
    const emailError = document.getElementById('emailError');

    if (email != confirm_email) {
        emailError.textContent = 'Emails do not match!';
    } else {
        emailError.textContent = '';
    }
}
function validatePassword() {
    const password = document.getElementById('password').value;
    const confirm_password = document.getElementById('confirm_password').value;
    const passwordError = document.getElementById('passwordError');

    if (password !== confirm_password) {
        passwordError.textContent = 'Passwords do not match!';
    } else {
        passwordError.textContent = '';
    }
}
</script>


<?= template_footer('Register') ?>