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
    
    

    // admin1, test
    // test2, test2
    // prepare sql statement to prevent sql injection
    // PDO supports named parameters which makes code more readable and maintainable.
    // Hence our choice of using PDO instead of MySQLi to interact with our database.
    if ($stmt = $pdo->prepare('SELECT account_id, password FROM accounts WHERE username = :username')) {
        $stmt->bindValue(':username', $_POST['username'], PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // returns an array of rows. each row is an associative array.
        

        //if ($user && (array_key_exists('password', $user)))
        //if ($user && $user['status'] == 'active') {
        if ($user) {
        //if ($stmt->num_rows > 0) {
            //$stmt->bind_result($account_id, $password);
            //$stmt->fetch();
            // Account exists, now we verify the password.
            // Note: remember to use password_hash in your registration file to store the hashed passwords.
            // only passwords created with password_hash function will work
            if (password_verify($_POST['password'], $user[0]['password'])) {
                // Verification success! User has logged-in!
                // Create sessions, so we know the user is logged in, 
                // they basically act like cookies but remember the data on the server.
                // session variables preserved until logout or session expiring
                // session variables stored on server, associated with session ID stored in user's browser              
                
                // use session_regenerate_id() to prevent session fixation attacks
                // see: https://stackoverflow.com/a/22965580
                // regenerates user's session ID that is stored on the server
                // and as a cookie in the browser.
                // user cannot change session variables in their browser
                // only variable user can change is the encrypted session ID,
                // which associates the user with the server sessions
                session_regenerate_id();
                
                $_SESSION['loggedin'] = TRUE;
                $_SESSION['name'] = $_POST['username'];
                $_SESSION['account_id'] = $user[0]['account_id'];
                //$_SESSION['role'] = $role;
                header('Location: index.php');
            } else {
                // Incorrect password
                $_SESSION['error'] = 'Incorrect credentials!';
                header('Location: login.php');
                exit();
                
            }
        } else {
            // Incorrect username
            
            $_SESSION['error'] = 'Incorrect credentials!';
            header('Location: login.php');
            exit();
            
        }
        //$stmt->close();
    }
        
    