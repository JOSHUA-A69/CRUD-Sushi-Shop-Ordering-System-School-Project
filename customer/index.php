<?php
// In the login page, only process login without redirecting back to itself
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verify credentials
    $_SESSION['customer_logged_in'] = true; // After successful verification
    header("Location: ../customer/index.php"); 
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sushi Shop - Welcome</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/customer.css">
</head>
<body>
    <!-- Background Image -->
    <div class="background-image"></div>

    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="container">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="order_history.php">Order History</a></li>
                <li class="nav-item"><a class="nav-link" href="profile.php">Profile</a></li>
                <li class="nav-item"><a class="nav-link text-danger" href="../auth/logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <!-- Header Section -->
    <main class="header-content">
        <h1>Welcome to Divine Sushi Shop!</h1>
        <p class="lead">Explore our menu and enjoy the best sushi experience!</p>
        <a href="menu.php" class="btn btn-primary">View Menu</a>
    </main>

    <footer class="footer">
    <p>&copy; 2024 Divine Sushi | <a href="#privacy">Privacy Policy</a> | <a href="#terms">Terms of Service</a></p>
</footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
