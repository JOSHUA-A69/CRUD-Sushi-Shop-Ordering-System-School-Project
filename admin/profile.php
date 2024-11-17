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
    <link rel="stylesheet" href="styles/admin.css">
</head>
<body>
    <div class="admin-container">
        <h1>Update Profile</h1>
        
        <!-- Display success or error message -->
        <?php if (isset($_GET['success'])): ?>
            <p class="success">Profile updated successfully.</p>
        <?php elseif (isset($_GET['error'])): ?>
            <p class="error">Failed to update profile. Please try again.</p>
        <?php endif; ?>
        
        <?php if ($admin): ?>
            <form method="POST">
                <label>Name:</label>
                <input type="text" name="name" value="<?= htmlspecialchars($admin['name'] ?? '') ?>" required>
                
                <label>Email:</label>
                <input type="email" name="email" value="<?= htmlspecialchars($admin['email'] ?? '') ?>" required>
                
                <label>Contact Number:</label>
                <input type="text" name="contactNumber" value="<?= htmlspecialchars($admin['contactNumber'] ?? '') ?>" required>
                
                <label>Username:</label>
                <input type="text" name="username" value="<?= htmlspecialchars($admin['username'] ?? '') ?>" required>
                
                <label>Role:</label>
                <input type="text" name="role" value="<?= htmlspecialchars($admin['role'] ?? '') ?>" required>
                
                <label>Password (leave blank to keep current password):</label>
                <input type="password" name="password" placeholder="New Password">
                
                <button type="submit">Update Profile</button>
            </form>
        <?php else: ?>
            <p>Unable to fetch profile data. Please try again later.</p>
        <?php endif; ?>
    </div>
</body>
</html>
