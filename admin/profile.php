<?php
session_start();

// Debugging session to check if AdminID is available
//var_dump($_SESSION); 

require_once '../controllers/AdminController.php';

$adminController = new AdminController();

// Check if AdminID is set in the session
if (!isset($_SESSION['adminID'])) {
    // Redirect to login page if AdminID is not found in the session
    header("Location: ../auth/login.php");
    exit();
}

// Get Admin profile data
$adminID = $_SESSION['adminID'];
$admin = $adminController->getProfile($adminID);

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate inputs
    $updatedData = [
        'name' => htmlspecialchars(trim($_POST['name'])),
        'email' => filter_var($_POST['email'], FILTER_VALIDATE_EMAIL),
        'contactNumber' => htmlspecialchars(trim($_POST['contactNumber'])),
        'username' => htmlspecialchars(trim($_POST['username'])),
        'role' => htmlspecialchars(trim($_POST['role'])),
    ];

    // Handle password update securely
    if (!empty($_POST['password'])) {
        $updatedData['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
    }

    // Update the admin profile
    $updateSuccess = $adminController->updateProfile($adminID, $updatedData);

    // Redirect with success or error message
    if ($updateSuccess) {
        header("Location: profile.php?success=1");
    } else {
        header("Location: profile.php?error=1");
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/adminProfile.css">
</head>
<body>
    <div class="container my-5">
        <div class="card shadow p-4">
            <h1 class="text-center mb-4">Update Profile</h1>

            <!-- Display success or error message -->
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success text-center">
                    Profile updated successfully.
                </div>
            <?php elseif (isset($_GET['error'])): ?>
                <div class="alert alert-danger text-center">
                    Failed to update profile. Please try again.
                </div>
            <?php endif; ?>
            
            <?php if ($admin): ?>
                <form method="POST" class="row g-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Name:</label>
                        <input type="text" id="name" name="name" class="form-control" 
                               value="<?= htmlspecialchars($admin['name'] ?? '') ?>" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" id="email" name="email" class="form-control" 
                               value="<?= htmlspecialchars($admin['email'] ?? '') ?>" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="contactNumber" class="form-label">Contact Number:</label>
                        <input type="text" id="contactNumber" name="contactNumber" class="form-control" 
                               value="<?= htmlspecialchars($admin['contactNumber'] ?? '') ?>" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="username" class="form-label">Username:</label>
                        <input type="text" id="username" name="username" class="form-control" 
                               value="<?= htmlspecialchars($admin['username'] ?? '') ?>" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="role" class="form-label">Role:</label>
                        <input type="text" id="role" name="role" class="form-control" 
                               value="<?= htmlspecialchars($admin['role'] ?? '') ?>" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="password" class="form-label">Password (leave blank to keep current password):</label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="New Password">
                    </div>
                    
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary">Update Profile</button>
                    </div>
                </form>
            <?php else: ?>
                <div class="alert alert-warning text-center mt-4">
                    Unable to fetch profile data. Please try again later.
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

