<?php 
session_start();
require_once '../config/database.php';

$errorMessage = ""; // Initialize the error message variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the user is a Customer
    $stmt = $conn->prepare("SELECT CustomerID, Password FROM Customers WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($customerID, $hashedPassword);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();
        if (password_verify($password, $hashedPassword)) {
            $_SESSION['customerID'] = $customerID;
            header("Location: ../customer/index.php");
            exit();
        } else {
            $errorMessage = "Invalid password.";
        }
    } else {
        // Check if the user is an Administrator
        $stmt = $conn->prepare("SELECT AdminID, Password FROM Administrators WHERE Email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($adminID, $hashedPassword);

        if ($stmt->num_rows > 0) {
            $stmt->fetch();
            if (password_verify($password, $hashedPassword)) {
                $_SESSION['adminID'] = $adminID;
                header("Location: ../admin/index.php");
                exit();
            } else {
                $errorMessage = "Invalid password.";
            }
        } else {
            $errorMessage = "No account found with that email.";
        }
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        .error { color: red; }
    </style>
</head>
<body>
    <h2>Login</h2>
    
    <!-- Display the error message if it exists -->
    <?php if (!empty($errorMessage)): ?>
        <p class="error"><?php echo $errorMessage; ?></p>
    <?php endif; ?>
    
    <form action="login.php" method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
</body>
</html>
