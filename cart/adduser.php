<?php
include 'functions.php';
$pdo = pdo_connect_mysql();

// Check if the form data exists
if (!isset($_POST['username'], $_POST['password'], $_POST['email'], $_POST['firstname'], $_POST['lastname'], $_POST['phone'], $_POST['date_of_register'])) {
    echo('Please complete the registration form!');
    header('Location: register.php');
	exit();
}
// Make sure the submitted registration values are not empty.
if (empty($_POST)) {
    echo('Please complete the registration form!');
    header('Location: register.php');
	exit();
}   

// Validate email address
if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    echo('Invalid email');
    header('Location: register.php');
	exit();
}
// Validate username
if (preg_match('/^[a-zA-Z0-9]+$/', $_POST['username']) == 0) {
    echo('Invalid username');
    header('Location: register.php');
	exit();
}
// Validate password (8-20 characters long, contains letters, numbers and at least one special character)
if (preg_match('/^(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[!@#$%^&*]).{8,20}$/', $_POST['password']) == 0) {
    exit('Password is not valid! It must be between 8 and 20 characters long, contain letters, numbers and at least one special character!');
}

// fetch_assoc: returns an array indexed by column name
// check if username already exists
if ($stmt = $pdo->prepare('SELECT username FROM accounts WHERE username = :username')) {
	$stmt->bindValue(':username', $_POST['username'], PDO::PARAM_STR);
	$stmt->execute();
	$account = $stmt->fetch(PDO::FETCH_ASSOC);
	if ($account['username'] == $_POST['username']) {
		echo('Username already exists.');
        header('Location: register.php');
	    exit();
    }
}
// check if email already exists
if ($stmt = $pdo->prepare('SELECT email FROM accounts WHERE email = :email')) {
	$stmt->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
	$stmt->execute();
	$account = $stmt->fetch(PDO::FETCH_ASSOC);
	if ($account['email'] == $_POST['email']) {
		echo('Email already exists.');
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
    if ($stmt->execute()) {
        echo("Account created successfully!");
        // use lastInsertId() to get the ID of the last inserted row
        $account_id = $pdo->lastInsertId();
        // insert new customer into customers table
        if ($stmt = $pdo->prepare('INSERT INTO customers (account_id, customer_first_name, customer_last_name, customer_phone, date_of_register) VALUES (:account_id, :firstname, :lastname, :phone, :date_of_register)')) {
            $stmt->bindValue(':account_id', $account_id, PDO::PARAM_INT);
            $stmt->bindValue(':firstname', $_POST['firstname'], PDO::PARAM_STR);
            $stmt->bindValue(':lastname', $_POST['lastname'], PDO::PARAM_STR);
            $stmt->bindValue(':phone', $_POST['phone'], PDO::PARAM_STR);
            $stmt->bindValue(':date_of_register', $_POST['date_of_register'], PDO::PARAM_INT);
            if ($stmt->execute()) {
                echo("Customer created successfully!");
            } else {
                echo("Failed to create customer!");
            }
        } else {
            echo("Could not prepare statement!");
        }
        header('Location: login.php');
        exit();
    } else {
        echo("Failed to create account!");
        header('Location: register.php');
        exit();
    }
} else {
    echo("Could not prepare statement!");
}

?>