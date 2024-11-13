<?php 

session_start();

require_once '../config/database.php';
require_once '../lib/Logger.php';
require_once '../lib/Exceptions.php';

require_once '../lib/BaseModel.php';
require_once '../lib/BaseController.php';

// Set error handling
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    Logger::error("$errstr in $errfile on line $errline");
    return true;
});

// Initialize session security
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 1);

// Add CSRF protection
if (empty($_SESSION[CSRF_TOKEN_NAME])) {
    $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Divine Sushi Shop!</title>
</head>
<body>
<h1>Welcome to Divine Sushi Shop</h1>
<p>Discover the finest sushi items crafted with passion and served fresh.</p>
<p><a href="../auth/signup_customer.php">Customer Signup</a> | <a href="../auth/signup_admin.php">Admin Signup</a></p>
<p><a href="../auth/login.php">Customer Login</a> | <a href="../auth/login.php">Admin Login</a></p>
</body>
</html>



