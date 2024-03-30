<?php
session_start();
include 'functions.php';
$pdo = pdo_connect_mysql();

// ensure form data exists
if ( !isset($_POST['username'], $_POST['password']) ) {
    $_SESSION['error'] = 'Missing credentials!';
    header('Location: login.php');
    exit();
}

// PDO supports named parameters which makes code more readable and maintainable.
// Hence our choice of using PDO instead of MySQLi to interact with our database.
if ($stmt = $pdo->prepare('SELECT account_id, password, email, role, status FROM accounts WHERE username = :username')) {
    $stmt->bindValue(':username', $_POST['username'], PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user && $user['status'] == 'active') {
        // verify password using password_verify (corresponding: used password_hash to store hashed passwords)
        if (password_verify($_POST['password'], $user['password'])) {
            // session variables preserved until logout or session expiring
            // session variables stored on server, associated with session ID stored in user's browser              
            // use session_regenerate_id() to prevent session fixation attacks
            // see: https://stackoverflow.com/a/22965580
            session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['username'] = $_POST['username'];
            $_SESSION['account_id'] = $user['account_id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            header('Location: index.php');
        } else {
            $_SESSION['error'] = 'Incorrect credentials!';
            header('Location: login.php');
            exit();
        }
    } else {
        $_SESSION['error'] = 'Account is not active or does not exist!';
        header('Location: login.php');
        exit();  
    }
} else {
    error_log("Cannot prepare sql statement for accounts table.");
    exit();
}
        
    