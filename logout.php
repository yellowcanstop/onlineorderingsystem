<?php
session_start();
session_destroy();
// remove remember_me cookie
// set value to empty string
// set expiration to one hour in the past (-3600)
if (isset($_COOKIE['remember_me'])) {
    setcookie('remember_me', '', time() - 3600, '/');
}
// redirect to the login page:
header('Location: login.php');
?>