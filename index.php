<?php
// called once in index.php, don't need to call in other files (index.php?page=...)
include 'functions.php';
$pdo = pdo_connect_mysql();
start_session($pdo);

// redirect to different pages based on roles upon login
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