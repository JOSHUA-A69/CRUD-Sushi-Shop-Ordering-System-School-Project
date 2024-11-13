<?php
// Start the session to access session variables
session_start();

// Debugging session to check if AdminID is available
var_dump($_SESSION);  // Debugging line to check session

// Include the AdminController
require_once '../controllers/AdminController.php';

$adminController = new AdminController();

// Check if AdminID is set in the session
if (!isset($_SESSION['AdminID'])) {
    // Redirect to login page if AdminID is not found in the session
    header("Location: ../auth/login.php");
    exit();
}

// Get Admin profile data
$adminID = $_SESSION['AdminID'];
$admin = $adminController->getProfile($adminID);

// Debugging admin data
var_dump($admin);  // Check if admin data is being fetched correctly

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Debugging posted data
    var_dump($_POST);  // Check if form data is posted correctly

    $updatedData = [
        'name' => $_POST['name'],
        'email' => $_POST['email'],
        'contactNumber' => $_POST['contactNumber']
    ];
    $adminController->updateProfile($adminID, $updatedData);

    // Redirect to the profile page with success message
    header("Location: profile.php?success=1");
    exit(); // Make sure the script stops after redirection
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Profile</title>
    <link rel="stylesheet" href="styles/admin.css">
</head>
<body>
    <div class="admin-container">
        <h1>Update Profile</h1>
        
        <!-- Display success message if redirection has success query parameter -->
        <?php if (isset($_GET['success'])): ?>
            <p class="success">Profile updated successfully.</p>
        <?php endif; ?>
        
        <?php if ($admin): ?>
            <form method="POST">
                <label>Name:</label>
                <input type="text" name="name" value="<?= htmlspecialchars($admin['name']) ?>" required>
                
                <label>Email:</label>
                <input type="email" name="email" value="<?= htmlspecialchars($admin['email']) ?>" required>
                
                <label>Contact Number:</label>
                <input type="text" name="contactNumber" value="<?= htmlspecialchars($admin['contactNumber']) ?>" required>
                
                <button type="submit">Update Profile</button>
            </form>
        <?php else: ?>
            <p>Unable to fetch profile data. Please try again later.</p>
        <?php endif; ?>
    </div>
</body>
</html>
