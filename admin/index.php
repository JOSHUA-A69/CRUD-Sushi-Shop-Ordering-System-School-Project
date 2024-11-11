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
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles/admin.css">
</head>
<body>
    <div class="admin-container">
        <h1>Welcome to the Sushi Shop Admin Dashboard</h1>
        
        <nav class="admin-nav">
            <ul>
                <li><a href="manage_customers.php">Manage Customers</a></li>
                <li><a href="manage_orders.php">Manage Orders</a></li>
                <li><a href="manage_sushi.php">Manage Sushi Items</a></li>
                <li><a href="profile.php">Update Profile</a></li>
                <li><a href="../auth/login.php">Logout</a></li>
            </ul>
        </nav>

        <div class="content">
            <p>Select a section to manage the sushi shop's data.</p>
        </div>
    </div>
</body>
</html>