<?php 
session_start();
include 'functions.php';
?>
<?= template_header('Register') ?>

<body>
    <!-- include jquery to do AJAX requests to server as user types in username field -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<div class="register">
		<h1>Register</h1>
        <div>
            <?php
            if (isset($_SESSION['error'])) {
                echo $_SESSION['error'];
                unset($_SESSION['error']); // unset error message after display
            }
            ?>
        </div>
		<form action="adduser.php" method="post" autocomplete="off">
            <label for="username"></label>
			<input type="text" name="username" placeholder="Username" id="username" oninput="checkUsername()" autocomplete="username" required>
            <p id="usernameError"></p>
            <br>
            <label for="name"></label>
			<input type="text" name="name" placeholder="Name" id="name" required>
            <br>
            
			<label for="password"></label>
            <input type="password" name="password" placeholder="Password" id="password" oninput="checkPassword()" autocomplete="new-password" required>
            <p id="passwordCheck"></p>
            
            <label for="confirm_password"></label>
            <input type="password" name="confirm_password" placeholder="Confirm Password" id="confirm_password" oninput="validatePassword()" autocomplete="new-password" required>
            <p id="passwordError"></p>

            <label for="email"></label>
            <input type="email" name="email" placeholder="Email" id="email" required>
            <br>
            <label for="confirm_email"></label>
            <input type="email" name="confirm_email" placeholder="Confirm Email" id="confirm_email" oninput="validateEmail()" required>
            <p id="emailError"></p>

            <label for="phone"></label>
            <input type="tel" name="phone" placeholder="Phone" id="phone" pattern="[0-9]{10}" title="Format: 0107998888" required>

            <input type="hidden" name="date_of_register" value="<?= time() ?>" id="date_of_register">

			<input type="submit" value="Register">
		</form>
	</div>
	<div class="login-link">
		<p>Already have an account? <a href="login.php">Login</a></p>
	</div>
</body>
<script>
// send AJAX request to server to check if username already exists
function checkUsername() {
    let username = $('#username').val();
    let usernameError = document.getElementById('usernameError');
    $.ajax({
        url: 'checkusername.php',
        type: 'POST',
        data: {username: username},
        dataType: 'text', // ensure jquery treats response as text (insead of JSON or XML)
        success: function(response) {
            response = response.trim(); // remove any whitespace
            if (response == 'available') {
                $('#username').css('border', '3px solid green');
                usernameError.textContent = 'Username is available!';
                usernameError.style.color = 'green';
                usernameError.style.fontSize = '12px';
            } else {
                $('#username').css('border', '3px solid red');
                usernameError.textContent = 'Username is already taken!';
                usernameError.style.color = 'red';
            }
        }
    });
}


// send AJAX request to server to check password
function checkPassword() {
    let password = $('#password').val();
    let passwordCheck = document.getElementById('passwordCheck');
    $.ajax({
        url: 'checkpassword.php',
        type: 'POST',
        data: {password: password},
        dataType: 'text',
        success: function(response) {
            response = response.trim(); // remove any whitespace
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


// check if matching emails are entered
function validateEmail() {
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


// check if matching passwords are entered
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

// call checkUsername() when username input field changes
$(document).ready(function() {
    $('#username').on('input', function() {
        checkUsername();
    });
});

</script>


<?= template_footer('Register') ?>