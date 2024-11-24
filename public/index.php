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
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="../assets/images/altlogo.png" type="image/png">
    <link rel="stylesheet" href="../assets/css/welcome.css">   
</head>
<body>
    <!-- Background image and overlay -->
    <div class="background-image"></div>
    <div class="overlay"></div>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="navbar-nav">
            <a href="../auth/signup_customer.php" class="nav-link">Customer Signup</a>
            <a href="../auth/login.php" class="nav-link">Customer Login</a>
            <a href="../auth/signup_admin.php" class="nav-link">Admin Signup</a>
            <a href="../auth/login.php" class="nav-link">Admin Login</a>
        </div>
    </nav>

    <!-- Header Content -->
    <div class="header-content">
        <h1>WELCOME TO DIVINE SUSHI</h1>
        <p>Discover the finest sushi crafted with passion and served fresh.</p>
    </div>

    <footer class="footer">
    <p>&copy; 2024 Divine Sushi | <a href="#privacy">Privacy Policy</a> | <a href="#terms">Terms of Service</a></p>
</footer>


    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>