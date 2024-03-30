<?php
include 'functions.php';
$pdo = pdo_connect_mysql();

if (preg_match('/^(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[!@#$%^&*]).{8,20}$/', $_POST['password']) == 0) {
    echo 'invalid';
}
?>