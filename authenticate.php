<?php
    session_start(); // preserve account details to remember logged-in users
    
    // variables reflect mysql database credentials
    require_once 'protected/logindbconfig.php';
    $con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
    if ( mysqli_connect_errno() ) {
        // If there is an error with the connection, stop the script and display the error.
        exit('Failed to connect to MySQL: ' . mysqli_connect_error());
    }
    
    
    // ensure form data exists
    if ( !isset($_POST['username'], $_POST['password']) ) {
        exit('Reenter username and password fields');
    }

    // todo check password hashing
    // prepare sql statement to prevent sql injection
    if ($stmt = $con->prepare('SELECT id, password FROM accounts WHERE username = ?')) {
        // Bind parameters (s = string, i = int, b = blob, etc)
        $stmt->bind_param('s', $_POST['username']);
        $stmt->execute();
        // Store the result so we can check if the account exists in the database.
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $password);
            $stmt->fetch();
            // Account exists, now we verify the password.
            // Note: remember to use password_hash in your registration file to store the hashed passwords.
            // only passwords created with password_hash function will work
            if (password_verify($_POST['password'], $password)) {
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
                $_SESSION['id'] = $id;
                // redirect to home page
                header('Location: home.php');
            } else {
                // Incorrect password
                header('Location: error.php');
            }
        } else {
            // Incorrect username
            header('Location: error.php');
        }

        $stmt->close();
    }
        
    