<?php
// In the login page, only process login without redirecting back to itself
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verify credentials
    $_SESSION['admin_logged_in'] = true; // After successful verification
    header("Location: ../admin/index.php"); 
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h1 class="display-5 fw-bold">Divine Sushi Shop Admin Dashboard</h1>
        </div>
        
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark rounded shadow mb-5">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav" aria-controls="adminNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="adminNav">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" href="manage_customers.php">Manage Customers</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="manage_orders.php">Manage Orders</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="manage_sushi.php">Manage Sushi Items</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="profile.php">Update Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-danger" href="../auth/logout.php">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="card shadow p-4">
            <p class="text-muted text-center fs-5">Select a section to manage the sushi shop's data.</p>
        </div>
    </div>
 
    <footer class="footer">
    <p>&copy; 2024 Divine Sushi | <a href="#privacy">Privacy Policy</a> | <a href="#terms">Terms of Service</a></p>
</footer>


    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
