<?php
include 'functions.php';
$pdo = pdo_connect_mysql();
start_session($pdo);

if ($_SESSION['role'] == 'customer') {
    // upon successful login, page is set to home by default
    $page = isset($_GET['page']) && file_exists($_GET['page'] . '.php') ? $_GET['page'] : 'home';
    // include and show the requested page
    include $page . '.php';
}
elseif ($_SESSION['role'] == 'employee') {
    // upon successful login, page is set to manageorders by default
    $page = isset($_GET['page']) && file_exists($_GET['page'] . '.php') ? $_GET['page'] : 'manageorders';
    // include and show the requested page
    include $page . '.php';
}

?>