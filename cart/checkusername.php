<?php
include 'functions.php';
$pdo = pdo_connect_mysql();

// check_username.php
if (isset($_POST['username'])) {
    if ($stmt = $pdo->prepare('SELECT username FROM accounts WHERE username = :username')) {
        $stmt->bindValue(':username', $_POST['username'], PDO::PARAM_STR);
        $stmt->execute();
        $account = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($_POST['username'] == $account['username']) {
            echo 'taken';
        }
    }
}
?>