<?php 
require_once '../config/database.php';

// Validation functions
function validateName($name) {
    return preg_match("/^[a-zA-Z\s]+$/", $name);
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validateUsername($username) {
    return preg_match("/^[a-zA-Z0-9]+$/", $username);
}

function validateContactNumber($contactNumber) {
    return preg_match("/^[0-9]{10,11}$/", $contactNumber);
}

function validatePassword($password) {
    return preg_match("/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/", $password);
}

// Initialize error messages
$nameError = $emailError = $usernameError = $roleError = $contactNumberError = $passwordError = "";
$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $role = $_POST['role'];
    $contactNumber = $_POST['contactNumber'];
    $password = $_POST['password'];

    $isValid = true;

    // Validate inputs
    if (empty($name) || !validateName($name)) {
        $nameError = "Name should only contain letters and spaces!";
        $isValid = false;
    }
    if (empty($email) || !validateEmail($email)) {
        $emailError = "Invalid email format!";
        $isValid = false;
    }
    if (empty($username) || !validateUsername($username)) {
        $usernameError = "Username should only contain alphanumeric characters!";
        $isValid = false;
    }
    if (empty($role)) {
        $roleError = "Role is required!";
        $isValid = false;
    }
    if (empty($contactNumber) || !validateContactNumber($contactNumber)) {
        $contactNumberError = "Contact number should be 10 or 11 digits!";
        $isValid = false;
    }
    if (empty($password) || !validatePassword($password)) {
        $passwordError = "Password must be at least 8 characters long and contain at least one letter and one number!";
        $isValid = false;
    }

    // Insert data if valid
    if ($isValid) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO Administrators (Name, Email, Username, Password, Role, ContactNumber) VALUES (?, ?, ?, ?, ?, ?)");
        
        if ($stmt) { // Check if statement is prepared
            $stmt->bind_param("ssssss", $name, $email, $username, $hashedPassword, $role, $contactNumber);

            if ($stmt->execute()) {
                $successMessage = "Admin registration successful! You can now log in.";
            } else {
                // Output SQL error if execution fails
                $successMessage = "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $successMessage = "Error preparing statement: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Register</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/auth.css">
</head>
<body>
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="container">
            <h2 class="text-center mb-4">Admin Registration</h2>
            
            <?php if (!empty($successMessage)) : ?>
                <p class="success text-center"><?php echo $successMessage; ?></p>
            <?php endif; ?>

            <form action="" method="POST">
                <!-- Name -->
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Name" value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>">
                    <span class="error"><?php echo $nameError ?? ''; ?></span>
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
                    <span class="error"><?php echo $emailError ?? ''; ?></span>
                </div>

                <!-- Username -->
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Username" value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>">
                    <span class="error"><?php echo $usernameError ?? ''; ?></span>
                </div>

                <!-- Role -->
                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <input type="text" class="form-control" id="role" name="role" placeholder="Role" value="<?php echo isset($role) ? htmlspecialchars($role) : ''; ?>">
                    <span class="error"><?php echo $roleError ?? ''; ?></span>
                </div>

                <!-- Contact Number -->
                <div class="mb-3">
                    <label for="contactNumber" class="form-label">Contact Number</label>
                    <input type="text" class="form-control" id="contactNumber" name="contactNumber" placeholder="Contact Number" value="<?php echo isset($contactNumber) ? htmlspecialchars($contactNumber) : ''; ?>">
                    <span class="error"><?php echo $contactNumberError ?? ''; ?></span>
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                    <span class="error"><?php echo $passwordError ?? ''; ?></span>
                </div>

                <!-- Buttons -->
                <button type="submit" class="btn btn-primary w-100 mb-2">Signup</button>
                <a href="../public/index.php" class="btn btn-secondary w-100">Go back to options</a>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
