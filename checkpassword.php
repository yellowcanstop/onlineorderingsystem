<?php
ob_start();
include 'functions.php';
$pdo = pdo_connect_mysql();

if (preg_match('/^(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[!@#$%^&*]).{8,20}$/', $_POST['password']) == 0) {
    ob_end_clean();
    echo 'invalid';
}
?>