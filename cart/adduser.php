<?php
session_start();
include 'functions.php';
$pdo = pdo_connect_mysql();

// Check if the form data exists
if (!isset($_POST['username'], $_POST['password'], $_POST['email'], $_POST['firstname'], $_POST['lastname'], $_POST['phone'], $_POST['date_of_register'])) {
    $_SESSION['error'] = 'Please complete the registration form!';
    header('Location: register.php');
	exit();
}
// Make sure the submitted registration values are not empty.
if (empty($_POST)) {
    $_SESSION['error'] ='Please complete the registration form!';
    header('Location: register.php');
	exit();
}   

// Validate email address
if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = 'Invalid email';
    header('Location: register.php');
	exit();
}
// Validate username
if (preg_match('/^[a-zA-Z0-9]+$/', $_POST['username']) == 0) {
    $_SESSION['error'] = 'Invalid username';
    header('Location: register.php');
	exit();
}
// Validate password (8-20 characters long, contains letters, numbers and at least one special character)
if (preg_match('/^(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[!@#$%^&*]).{8,20}$/', $_POST['password']) == 0) {
    $_SESSION['error'] = 'Password is not valid! It must be between 8 and 20 characters long, contain letters, numbers and at least one special character!';
    header('Location: register.php');
    exit();
}

// fetch_assoc: returns an array indexed by column name
// check if username already exists
if ($stmt = $pdo->prepare('SELECT username FROM accounts WHERE username = :username')) {
	$stmt->bindValue(':username', $_POST['username'], PDO::PARAM_STR);
	$stmt->execute();
	$account = $stmt->fetch(PDO::FETCH_ASSOC);
	if ($_POST['username'] == $account['username']) {
		$_SESSION['error'] = 'Username already exists.';
        header('Location: register.php');
	    exit();
    }
}
// check if email already exists
if ($stmt = $pdo->prepare('SELECT email FROM accounts WHERE email = :email')) {
	$stmt->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
	$stmt->execute();
	$account = $stmt->fetch(PDO::FETCH_ASSOC);
	if ($_POST['email'] == $account['email']) {
		$_SESSION['error'] = 'Email already exists.';
        header('Location: register.php');
	    exit();
    }
}

// Insert new account into accounts table
if ($stmt = $pdo->prepare('INSERT INTO accounts (username, email, password) VALUES (:username, :email, :password)')) {
    // We do not want to expose passwords in our database, so hash the password and use password_verify when a user logs in.
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $stmt->bindValue(':username', $_POST['username'], PDO::PARAM_STR);
    $stmt->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
    $stmt->bindValue(':password', $password, PDO::PARAM_STR);
    if (!$stmt->execute()) {
        error_log("Cannot execute sql statement for accounts table.");
    } else {
        // use lastInsertId() to get the ID of the last inserted row
        $account_id = $pdo->lastInsertId();
        // insert new customer into customers table
        if ($stmt = $pdo->prepare('INSERT INTO customers (account_id, customer_first_name, customer_last_name, customer_phone, date_of_register) VALUES (:account_id, :firstname, :lastname, :phone, :date_of_register)')) {
            $stmt->bindValue(':account_id', $account_id, PDO::PARAM_INT);
            $stmt->bindValue(':firstname', $_POST['firstname'], PDO::PARAM_STR);
            $stmt->bindValue(':lastname', $_POST['lastname'], PDO::PARAM_STR);
            $stmt->bindValue(':phone', $_POST['phone'], PDO::PARAM_STR);
            // time() returns the current time in seconds since Jan 1, 1970
            // date_of_register is stored as a DATETIME type in the database
            // hence use date() to format the timestamp as a string in 'Y-m-d H:i:s' format
            $date_of_register = date('Y-m-d H:i:s', $_POST['date_of_register']);
            $stmt->bindValue(':date_of_register', $date_of_register, PDO::PARAM_STR);
            $stmt->execute();
            if (!$stmt->execute()) {
                error_log("Cannot execute sql statement for customers table.");
            }
        } else {
            error_log("Cannot prepare sql statement for customers table.");
        }
        header('Location: login.php');
        exit();
    } 
} else {
    error_log("Cannot prepare sql statement for accounts table.");
    $_SESSION['error'] = 'Failed to create account. Please try again.';
    header('Location: register.php');
    exit();
}

?>