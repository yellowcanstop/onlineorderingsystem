<?php


// Include functions and connect to the database using PDO MySQL
session_start();
include 'functions.php';
$pdo = pdo_connect_mysql();

/*
// if user not logged in, redirect to login page
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}
*/

// to access products page
// can navigate to http://localhost/cart/index.php?page=dishes
// Page is set to home (home.php) by default, so when the visitor visits, that will be the page they see.
$page = isset($_GET['page']) && file_exists($_GET['page'] . '.php') ? $_GET['page'] : 'home';
// Include and show the requested page
include $page . '.php';
?>