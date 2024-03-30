<?php 
session_start();
include 'functions.php';
?>
<?= template_header('Register') ?>
<body>
    <!-- include jquery to do AJAX requests to server as user types in username field -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<div>
		<h1>Register</h1>
        <div>
            <?php
            if (isset($_SESSION['error'])) {
                echo $_SESSION['error'];
                unset($_SESSION['error']); // unset error message after display
            }
            ?>
        </div>
		<!-- when the form is submitted, the data is sent to authenticate.php -->
		<form action="adduser.php" method="post" autocomplete="off">
            <label for="username">Username:</label>
			<input type="text" name="username" placeholder="Username" id="username" oninput="checkUsername()" required>
            <p id="usernameError"></p>
            <br>
            <label for="firstname">Name:</label>
			<input type="text" name="firstname" placeholder="First Name" id="firstname" required>
            <label for="lastname"></label>
			<input type="text" name="lastname" placeholder="Last Name" id="lastname" required>
            <br>
            
            
			<label for="password">Password:</label>
            <input type="password" name="password" placeholder="Password" id="password" oninput="checkPassword()" required>
            <p id="passwordCheck"></p>
            
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" name="confirm_password" placeholder="Confirm Password" id="confirm_password" oninput="validatePassword()" required>
            <p id="passwordError"></p>


            <label for="email">Email:</label>
            <input type="email" name="email" placeholder="Email" id="email" required>
            <br>
            <label for="confirm_email">Confirm Email:</label>
            <input type="email" name="confirm_email" placeholder="Confirm Email" id="confirm_email" oninput="validateEmail()" required>
            <p id="emailError"></p>

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
function checkUsername() {
    // send AJAX request to server to check if username already exists
    let username = $('#username').val();
    $.ajax({
        url: 'checkusername.php',
        type: 'POST',
        data: {username: username},
        success: function(response) {
            if (response == 'taken') {
                $('#username').css('border', '3px solid red');
                usernameError.textContent = 'Username is already taken!';
                usernameError.style.color = 'red';
            } else {
                $('#username').css('border', '3px solid green');
                usernameError.textContent = 'Username is available!';
                usernameError.style.color = 'green';
            }
        }
    });
}
function checkPassword() {
    // send AJAX request to server to check if username already exists
    let password = $('#password').val();
    $.ajax({
        url: 'checkpassword.php',
        type: 'POST',
        data: {password: password},
        success: function(response) {
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
function validateEmail() {
    // variables not being reassigned after initial assignment
    const email = document.getElementById('email').value;
    const confirm_email = document.getElementById('confirm_email').value;
    const emailError = document.getElementById('emailError');

    if (email != confirm_email) {
        emailError.textContent = 'Emails do not match!';
        emailError.style.color = 'red';
    } else {
        emailError.textContent = 'Emails match.';
        emailError.style.color = 'green';
    }
}
function validatePassword() {
    const password = document.getElementById('password').value;
    const confirm_password = document.getElementById('confirm_password').value;
    const passwordError = document.getElementById('passwordError');

    if (password !== confirm_password) {
        passwordError.textContent = 'Passwords do not match!';
        passwordError.style.color = 'red';
    } else {
        passwordError.textContent = 'Passwords match.';
        passwordError.style.color = 'green';
    }
}
</script>


<?= template_footer('Register') ?>