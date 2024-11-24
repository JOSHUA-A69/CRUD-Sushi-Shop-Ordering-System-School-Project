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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="../assets/images/altlogo.png" type="image/png">
    <link rel="stylesheet" href="../assets/css/profile.css">
</head>
<body class="custom-bg">
    <div class="container py-5">
        <h1 class="text-center mb-4">Your Profile</h1>

        <!-- Display message -->
        <?php if (!empty($message)): ?>
            <div class="alert <?php echo strpos($message, 'successfully') !== false ? 'alert-success' : 'alert-danger'; ?>" role="alert">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="row g-3 bg-white p-4 rounded shadow">
            <div class="col-md-6">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" class="form-control" name="first_name" id="first_name" value="<?php echo htmlspecialchars($customer['FirstName']); ?>" required>
            </div>
            
            <div class="col-md-6">
                <label for="middle_initial" class="form-label">Middle Initial</label>
                <input type="text" class="form-control" name="middle_initial" id="middle_initial" value="<?php echo htmlspecialchars($customer['MiddleInitial']); ?>" maxlength="1">
            </div>
            
            <div class="col-md-6">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" class="form-control" name="last_name" id="last_name" value="<?php echo htmlspecialchars($customer['LastName']); ?>" required>
            </div>
            
            <div class="col-md-6">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" name="email" id="email" value="<?php echo htmlspecialchars($customer['Email']); ?>" required>
            </div>
            
            <div class="col-md-6">
                <label for="phone_number" class="form-label">Phone Number</label>
                <input type="text" class="form-control" name="phone_number" id="phone_number" value="<?php echo htmlspecialchars($customer['PhoneNumber']); ?>" required>
            </div>
            
            <div class="col-md-6">
                <label for="city_town" class="form-label">City/Town</label>
                <input type="text" class="form-control" name="city_town" id="city_town" value="<?php echo htmlspecialchars($customer['CityTown']); ?>">
            </div>
            
            <div class="col-md-6">
                <label for="street" class="form-label">Street</label>
                <input type="text" class="form-control" name="street" id="street" value="<?php echo htmlspecialchars($customer['Street']); ?>">
            </div>
            
            <div class="col-md-6">
                <label for="house_number" class="form-label">House Number</label>
                <input type="number" class="form-control" name="house_number" id="house_number" value="<?php echo htmlspecialchars($customer['HouseNumber']); ?>">
            </div>
            
            <div class="col-12 text-center">
                <button type="submit" class="btn btn-primary">Update Profile</button>
                <a href="./index.php" class="btn btn-primary">Back to Dashboard</a></
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
