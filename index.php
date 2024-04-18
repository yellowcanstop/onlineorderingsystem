<?php
// called once in index.php, don't need to call in other files (index.php?page=...)
include 'functions.php';
$pdo = pdo_connect_mysql();
start_session($pdo);

// redirect to different pages based on roles upon login
// customer
if ($_SESSION['role_id'] == 1) {
    // upon successful login, page is set to home by default
    $page = isset($_GET['page']) && file_exists($_GET['page'] . '.php') ? $_GET['page'] : 'home';
    // include and show the requested page
    include $page . '.php';
}
// employee
elseif ($_SESSION['role_id'] == 2) {
    // upon successful login, page is set to manageorders by default
    $page = isset($_GET['page']) && file_exists($_GET['page'] . '.php') ? $_GET['page'] : 'manageorders';
    // include and show the requested page
    include $page . '.php';
}

?>