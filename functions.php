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
if (isset($_COOKIE['remember_me'])) {
    $userId = $_COOKIE['remember_me'];
}
$num_items_in_cart = isset($_SESSION['cart']) ? ($_SESSION['cart']['num_items_in_cart']) : 0;
$name = isset($_SESSION['username']) ? (htmlspecialchars($_SESSION['username'], ENT_QUOTES)) : "";
$greeting = isset($_SESSION['loggedin']) && ($_SESSION['role'] == 'customer') ? "<h2>Hi $name, what are you craving?</h2>" : "";
$home = isset($_SESSION['loggedin']) && ($_SESSION['role'] == 'customer') ? "<a href=\"index.php\">Menu</a>" : "";
$products = isset($_SESSION['loggedin']) && ($_SESSION['role'] == 'customer') ? "<a href=\"index.php?page=products\">Items</a>" : "";
$profile = isset($_SESSION['loggedin']) && ($_SESSION['role'] == 'customer') ? "<a href=\"index.php?page=profile\">Profile</a>" : "";
$orders = isset($_SESSION['loggedin']) && ($_SESSION['role'] == 'customer') ? "<a href=\"index.php?page=orders\">Orders</a>" : "";
$register = isset($_SESSION['loggedin']) ? "" : "<a href=\"register.php\">Register</a>";
$login = isset($_SESSION['loggedin']) ? "" : "<a href=\"login.php\">Login</a>";
$logout = isset($_SESSION['loggedin']) ? "<a href=\"logout.php\"><i class=\"fas fa-sign-out-alt\"></i> Logout</a>" : "";
$cart = isset($_SESSION['loggedin']) && ($_SESSION['role'] == 'customer') ? "<a href=\"index.php?page=cart\"><i class=\"fas fa-shopping-cart\"></i><span>$num_items_in_cart</span></a>" : "";
$manageorders = isset($_SESSION['loggedin']) && ($_SESSION['role'] == 'employee') ? "<a href=\"index.php?page=manageorders\">Manage Orders</a>" : "";
$managedishes = isset($_SESSION['loggedin']) && ($_SESSION['role'] == 'employee') ? "<a href=\"index.php?page=managedishes\">Manage Inventory</a>" : "";

echo <<<EOT
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>$title</title>
		<link href="style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
        <script src="main.js" defer></script>
    </head>
	<body>
        <header>
            <div class="content-wrapper">
                <img src="imgs/logo.png" alt="Logo" style="height: 80px;">
                <h1>Very Good Food Inc</h1>
                <nav>
                    $home
                    $products
                    $profile
                    $orders
                    $login
                    $register
                    $manageorders
                    $managedishes
                </nav>
                <div class="link-icons">$cart</div>
                <div class="link-icons">$logout</div>
            </div>
            <div class="content-wrapper">
                <div class="greeting">
                    $greeting
                </div>
                <a href="https://www.google.com/maps/place/2%C2%B056'37.7%22N+101%C2%B052'24.2%22E/@2.9438054,101.8708251,17z/data=!3m1!4b1!4m4!3m3!8m2!3d2.9438!4d101.8734?hl=en&entry=ttu" class="location-link" id="locationLink" target="_blank"><i class="fas fa-map-marker-alt"></i>Location</a>
                <a href="" class="phone-link"><i class="fas fa-phone"></i>+60 38924 8000</a>
            </div>
        </header>
        <main>
EOT;
}


function template_footer() {
$about = "<a href=\"about.php\">About</a>";
$year = date('Y');
echo <<<EOT
        </main>
        <footer>
            <div class="content-wrapper">
                <nav>
                    <div class="social-icons">
                        <i class="fab fa-facebook"></i>
                        <i class="fab fa-twitter"></i>
                        <i class="fab fa-instagram"></i>
                    </div>
                    <p>|</p>
                    $about
                </nav>
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