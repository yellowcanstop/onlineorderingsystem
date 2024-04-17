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

if (preg_match('/^(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[!@#$%^&*]).{8,20}$/', $_POST['password']) == 0) {
    ob_end_clean();
    echo 'invalid';
}
?>