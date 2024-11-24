<?php 
require_once '../config/database.php';

// Validation Functions
function validateName($name) {
    return preg_match("/^[a-zA-Z]+$/", $name);
}
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}
function validatePhone($phone) {
    return preg_match("/^[0-9]{11}$/", $phone);
}
function validateCityStreet($str) {
    return preg_match("/^[a-zA-Z\s]+$/", $str);
}
function validatePassword($password) {
    return preg_match("/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/", $password);
}

// Process form data when POST request is made
$errors = [];
$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST['firstName'];
    $middleInitial = $_POST['middleInitial'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $phoneNumber = $_POST['phoneNumber'];
    $cityTown = $_POST['cityTown'];
    $street = $_POST['street'];
    $houseNumber = $_POST['houseNumber'];
    $password = $_POST['password'];

    // Validate fields and add errors to $errors array if validation fails
    if (empty($firstName) || !validateName($firstName)) {
        $errors['firstName'] = "First name is required and should contain only letters!";
    }
    if (empty($lastName) || !validateName($lastName)) {
        $errors['lastName'] = "Last name is required and should contain only letters!";
    }
    if (empty($email) || !validateEmail($email)) {
        $errors['email'] = "Invalid email format!";
    }
    if (empty($phoneNumber) || !validatePhone($phoneNumber)) {
        $errors['phoneNumber'] = "Phone number must be 11 digits!";
    }
    if (!empty($cityTown) && !validateCityStreet($cityTown)) {
        $errors['cityTown'] = "City/Town should contain only letters and spaces!";
    }
    if (!empty($street) && !validateCityStreet($street)) {
        $errors['street'] = "Street should contain only letters and spaces!";
    }
    if (empty($password) || !validatePassword($password)) {
        $errors['password'] = "Password must be at least 8 characters long and contain at least one letter and one number!";
    }

    // If no errors, proceed to database insertion
    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO Customers (FirstName, MiddleInitial, LastName, Email, PhoneNumber, CityTown, Street, HouseNumber, Password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssis", $firstName, $middleInitial, $lastName, $email, $phoneNumber, $cityTown, $street, $houseNumber, $hashedPassword);

        if ($stmt->execute()) {
            $successMessage = "Customer registration successful!";
        } else {
            $errors['database'] = "Database error: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Register</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/auth.css">
    <link rel="icon" href="../assets/images/altlogo.png" type="image/png">
</head>
<body class="bg-light">
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-6 col-lg-4">
                <h2 class="text-center mb-4">Customer Registration</h2>

                <?php if (!empty($successMessage)) : ?>
                    <div class="alert alert-success text-center">
                        <?php echo $successMessage; ?>
                    </div>
                <?php endif; ?>

                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="firstName" class="form-label">First Name:</label>
                        <input type="text" id="firstName" name="firstName" class="form-control" required>
                        <div class="text-danger small"><?php echo $errors['firstName'] ?? ''; ?></div>
                    </div>

                    <div class="mb-3">
                        <label for="middleInitial" class="form-label">Middle Initial:</label>
                        <input type="text" id="middleInitial" name="middleInitial" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="lastName" class="form-label">Last Name:</label>
                        <input type="text" id="lastName" name="lastName" class="form-control" required>
                        <div class="text-danger small"><?php echo $errors['lastName'] ?? ''; ?></div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                        <div class="text-danger small"><?php echo $errors['email'] ?? ''; ?></div>
                    </div>

                    <div class="mb-3">
                        <label for="phoneNumber" class="form-label">Phone Number:</label>
                        <input type="text" id="phoneNumber" name="phoneNumber" class="form-control" required>
                        <div class="text-danger small"><?php echo $errors['phoneNumber'] ?? ''; ?></div>
                    </div>

                    <div class="mb-3">
                        <label for="cityTown" class="form-label">City/Town:</label>
                        <input type="text" id="cityTown" name="cityTown" class="form-control">
                        <div class="text-danger small"><?php echo $errors['cityTown'] ?? ''; ?></div>
                    </div>

                    <div class="mb-3">
                        <label for="street" class="form-label">Street:</label>
                        <input type="text" id="street" name="street" class="form-control">
                        <div class="text-danger small"><?php echo $errors['street'] ?? ''; ?></div>
                    </div>

                    <div class="mb-3">
                        <label for="houseNumber" class="form-label">House Number:</label>
                        <input type="number" id="houseNumber" name="houseNumber" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password:</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                        <div class="text-danger small"><?php echo $errors['password'] ?? ''; ?></div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary mb-2">Signup</button>
                        <a href="../public/index.php" class="btn btn-secondary">Go back to options</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
