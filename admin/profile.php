<?php
require_once '../controllers/AdminController.php';

$adminController = new AdminController();
$adminID = $_SESSION['admin_id'];
$admin = $adminController->getProfile($adminID);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $updatedData = [
        'name' => $_POST['name'],
        'email' => $_POST['email'],
        'contactNumber' => $_POST['contactNumber']
    ];
    $adminController->updateProfile($adminID, $updatedData);
    header("Location: update_profile.php?success=1");
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
        <?php if (isset($_GET['success'])): ?>
            <p class="success">Profile updated successfully.</p>
        <?php endif; ?>
        <form method="POST">
            <label>Name:</label>
            <input type="text" name="name" value="<?= $admin['name'] ?>" required>
            <label>Email:</label>
            <input type="email" name="email" value="<?= $admin['email'] ?>" required>
            <label>Contact Number:</label>
            <input type="text" name="contactNumber" value="<?= $admin['contactNumber'] ?>" required>
            <button type="submit">Update Profile</button>
        </form>
    </div>
</body>
</html>