<?php
/*  when we make an AJAX request to checkusername.php, 
the entire output of the script (including included files)
is returned as a response. This means without output buffering functions,
the response will include the html code from template_footer().
Hence use ob_start() and ob_end_clean() to buffer the output of the script,
so that only 'available' or 'taken' is returned as response, and nothing else.
*/
ob_start();
include 'functions.php';
$pdo = pdo_connect_mysql();

if (isset($_POST['username'])) {
    if ($stmt = $pdo->prepare('SELECT username FROM customer_accounts WHERE username = :username')) {
        $stmt->bindValue(':username', $_POST['username'], PDO::PARAM_STR);
        $stmt->execute();
        $account = $stmt->fetch(PDO::FETCH_ASSOC);
        if (empty($account)) {
            ob_end_clean();
            echo 'available';
        } else {
            ob_end_clean();
            echo 'taken';
        }     
    } else {
        error_log("checkusername.php: pdo->prepare failed");
    }
} else {
    error_log("username not set in POST request to checkusername.php");
}
?>