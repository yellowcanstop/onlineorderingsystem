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
if ($stmt = $pdo->prepare('SELECT customer_id, password, email, account_status_id FROM customer_accounts WHERE username = :username')) {
    $stmt->bindValue(':username', $_POST['username'], PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    // check if account exists and is active
    if ($user && $user['account_status_id'] == 1) {
        // verify password using password_verify (corresponding: used password_hash to store hashed passwords)
        if (password_verify($_POST['password'], $user['password'])) {
            // session variables preserved until logout or session expiring
            // session variables stored on server, associated with session ID stored in user's browser              
            // use session_regenerate_id() to prevent session fixation attacks
            // see: https://stackoverflow.com/a/22965580
            session_regenerate_id();
            // // only save less-sensitive information in session variables to maintain state between requests
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['username'] = $_POST['username'];
            $_SESSION['customer_id'] = $user['customer_id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role_id'] = 1;
            // set cookie if remember me is checked
            // cookie will be available across entire site (path: /)
            // cookie will expire after 30 days (86400 seconds = 1 day)
            // since I am using localhost, setcookie() has no additional parameter (specific to https)
            if (isset($_POST['remember_me'])) {
                // store random token in cookie, associate this random token with user in database
                // which is more secure than storing user ID in the cookie
                // cookie will be available across entire site (path: /)
                // cookie will expire after 30 days (86400 seconds = 1 day)
                // since I am using localhost, setcookie() has no additional parameter (specific to https)
                $token = bin2hex(random_bytes(24));
                setcookie('remember_me', $token, time() + (86400 * 30), "/");
                $stmt = $pdo->prepare("UPDATE customer_accounts SET remember_me_token = :token WHERE customer_id = :customer_id");
                $stmt->execute(['token' => $token, 'customer_id' => $user['customer_id']]);
            }
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
} else if ($stmt = $pdo->prepare('SELECT employee_id, password, email, account_status_id FROM employee_accounts WHERE username = :username')) {
    $stmt->bindValue(':username', $_POST['username'], PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user && $user['account_status_id'] == 1) {
        if (password_verify($_POST['password'], $user['password'])) {
            session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['username'] = $_POST['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role_id'] = 2;
            if (isset($_POST['remember_me'])) {
                $token = bin2hex(random_bytes(24));
                setcookie('remember_me', $token, time() + (86400 * 30), "/");
                $stmt = $pdo->prepare("UPDATE employee_accounts SET remember_me_token = :token WHERE employee_id = :employee_id");
                $stmt->execute(['token' => $token, 'employee_id' => $user['employee_id']]);
            }
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
    error_log("Cannot prepare sql statement for accounts tables to authenticate login.");
    exit();
}