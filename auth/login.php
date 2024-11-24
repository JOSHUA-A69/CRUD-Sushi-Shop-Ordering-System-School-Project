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
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/auth.css">
    <link rel="icon" href="../assets/images/altlogo.png" type="image/png">
</head>
<body>
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="container">
            <h2 class="text-center mb-4">Login</h2>
            
            <!-- Display the error message if it exists -->
            <?php if (!empty($errorMessage)): ?>
                <p class="error text-center"><?php echo $errorMessage; ?></p>
            <?php endif; ?>
            
            <form action="login.php" method="POST">
                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                </div>

                <!-- Login Button -->
                <button type="submit" class="btn btn-primary w-100 mb-2">Login</button>

                <!-- Forgot Password -->
                <p class="text-center"><a href="forgot_password.php" class="text-decoration-none text-light">Forgot Password?</a></p>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
