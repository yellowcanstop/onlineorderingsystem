<?php
include 'functions.php';
start_session();
$pdo = pdo_connect_mysql();

// upon successful login, page is set to home by default
$page = isset($_GET['page']) && file_exists($_GET['page'] . '.php') ? $_GET['page'] : 'home';
// include and show the requested page
include $page . '.php';
?>