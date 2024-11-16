<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['customerID'])) {
    // Redirect to login if not logged in
    header("Location: ../auth/login.php");
    exit();
}

$customerID = $_SESSION['customerID']; // Get the logged-in user's customer ID
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $mysqli->prepare("UPDATE Customers 
        SET FirstName = ?, MiddleInitial = ?, LastName = ?, Email = ?, PhoneNumber = ?, CityTown = ?, Street = ?, HouseNumber = ? 
        WHERE CustomerID = ?");
    $stmt->bind_param(
        "sssssssii",
        $_POST['first_name'],
        $_POST['middle_initial'],
        $_POST['last_name'],
        $_POST['email'],
        $_POST['phone_number'],
        $_POST['city_town'],
        $_POST['street'],
        $_POST['house_number'],
        $customerID
    );
    if ($stmt->execute()) {
        $message = "Profile updated successfully!";
    } else {
        $message = "Error updating profile: " . $stmt->error;
    }
    $stmt->close();
}

$result = $mysqli->query("SELECT * FROM Customers WHERE CustomerID = $customerID");
$customer = $result->fetch_assoc();
$mysqli->close();
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
    <?php if (!empty($message)): ?>
        <p class="<?php echo strpos($message, 'successfully') !== false ? 'success' : 'error'; ?>">
            <?php echo htmlspecialchars($message); ?>
        </p>
    <?php endif; ?>
    <form method="POST">
        <label for="first_name">First Name:</label>
        <input type="text" name="first_name" id="first_name" value="<?php echo htmlspecialchars($customer['FirstName']); ?>" required>
        
        <label for="middle_initial">Middle Initial:</label>
        <input type="text" name="middle_initial" id="middle_initial" value="<?php echo htmlspecialchars($customer['MiddleInitial']); ?>" maxlength="1">
        
        <label for="last_name">Last Name:</label>
        <input type="text" name="last_name" id="last_name" value="<?php echo htmlspecialchars($customer['LastName']); ?>" required>
        
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($customer['Email']); ?>" required>
        
        <label for="phone_number">Phone Number:</label>
        <input type="text" name="phone_number" id="phone_number" value="<?php echo htmlspecialchars($customer['PhoneNumber']); ?>" required>
        
        <label for="city_town">City/Town:</label>
        <input type="text" name="city_town" id="city_town" value="<?php echo htmlspecialchars($customer['CityTown']); ?>">
        
        <label for="street">Street:</label>
        <input type="text" name="street" id="street" value="<?php echo htmlspecialchars($customer['Street']); ?>">
        
        <label for="house_number">House Number:</label>
        <input type="number" name="house_number" id="house_number" value="<?php echo htmlspecialchars($customer['HouseNumber']); ?>">
        
        <button type="submit">Update Profile</button>
    </form>
</body>
</html>
