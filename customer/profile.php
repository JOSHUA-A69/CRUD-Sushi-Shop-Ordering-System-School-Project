<?php
require_once '../config/database.php';

$customerID = 13; // Assuming logged-in customer ID
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $mysqli->prepare("UPDATE customers SET name = ?, email = ?, contact = ? WHERE customerID = ?");
    $stmt->bind_param("sssi", $_POST['name'], $_POST['email'], $_POST['phonenumber'], $customerID);
    $stmt->execute();
    $message = "Profile updated successfully!";
}

$result = $mysqli->query("SELECT * FROM customers WHERE customerID = $customerID");
$customer = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Profile</title>
    <link rel="stylesheet" href="styles/profile.css">
</head>
<body>
    <h1>Your Profile</h1>
    <?php if (isset($message)): ?>
        <p class="success"><?php echo $message; ?></p>
    <?php endif; ?>
    <form method="POST">
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($customer['name']); ?>" required>
        
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($customer['email']); ?>" required>
        
        <label for="contact">Contact:</label>
        <input type="text" name="contact" id="contact" value="<?php echo htmlspecialchars($customer['phonenumber']); ?>" required>
        
        <button type="submit">Update Profile</button>
    </form>
</body>
</html>
