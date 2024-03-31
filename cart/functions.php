<?php
function pdo_connect_mysql() {
    $DATABASE_HOST = 'localhost';
    $DATABASE_USER = 'root';
    $DATABASE_PASS = '';
    $DATABASE_NAME = 'cart';
    try {
    	return new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME . ';charset=utf8', $DATABASE_USER, $DATABASE_PASS);
    } catch (PDOException $exception) {
    	error_log('Failed to connect to database!');
        exit();
    }
}


// start session when already logged in
function start_session() {
    session_start();
    if (!isset($_SESSION['loggedin'])) {
        header('Location: login.php');
        exit();
    }
}


function template_header($title) {
$num_items_in_cart = isset($_SESSION['cart']) ? ($_SESSION['cart']['num_items_in_cart']) : 0;
$name = isset($_SESSION['username']) ? (htmlspecialchars($_SESSION['username'], ENT_QUOTES)) : "";
$greeting = isset($_SESSION['loggedin']) ? "<h2>Hi $name, what are you craving?</h2>" : "";
$home = isset($_SESSION['loggedin']) ? "<a href=\"index.php\">Home</a>" : "";
$products = isset($_SESSION['loggedin']) ? "<a href=\"index.php?page=products\">Products</a>" : "";
$profile = isset($_SESSION['loggedin']) ? "<a href=\"index.php?page=profile\">Profile</a>" : "";
$register = isset($_SESSION['loggedin']) ? "" : "<a href=\"register.php\">Register</a>";
$login = isset($_SESSION['loggedin']) ? "" : "<a href=\"login.php\">Login</a>";
$logout = isset($_SESSION['loggedin']) ? "<a href=\"logout.php\"><i class=\"fas fa-sign-out-alt\"></i>Logout</a>" : "";
$cart = isset($_SESSION['loggedin']) ? "<a href=\"index.php?page=cart\"><i class=\"fas fa-shopping-cart\"></i><span>$num_items_in_cart</span></a>" : "";
echo <<<EOT
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>$title</title>
		<link href="cartstyle.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body>
        <header>
            <div class="content-wrapper">
                <h1>Very Good Food Inc</h1>
                <nav>
                    $home
                    $products
                    $profile
                    $login
                    $register
                </nav>
                <div class="link-icons">$cart</div>
                <div class="link-icons">$logout</div>
            </div>
            <div class="content-wrapper">$greeting</div>
        </header>
        <main>
EOT;
}


function template_footer() {
$year = date('Y');
echo <<<EOT
        </main>
        <footer>
            <div class="content-wrapper">
                <p>&copy; $year, Very Good Food Inc, Online Food Ordering System</p>
            </div>
        </footer>
    </body>
</html>
EOT;
}


function error_page($title, $message) {
template_header('Error');
echo <<<EOT
    <div class="content-wrapper">
        <h1>Error:</h1>
        <h2>$title</h2>
        <p>$message</p>
    </div>
EOT;
}
template_footer();
  
?>