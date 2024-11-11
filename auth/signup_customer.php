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
            $successMessage = "Customer registration successful! You can now log in.";
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
    <style>
        .error { color: red; font-size: 14px; }
        .success { color: green; font-size: 14px; }
    </style>
</head>
<body>
    <h2>Customer Registration</h2>
    
    <?php if (!empty($successMessage)) : ?>
        <p class="success"><?php echo $successMessage; ?></p>
    <?php endif; ?>

    <form action="" method="POST">
        <label>
            First Name:
            <input type="text" name="firstName" required>
            <span class="error"><?php echo $errors['firstName'] ?? ''; ?></span>
        </label><br>

        <label>
            Middle Initial:
            <input type="text" name="middleInitial">
        </label><br>

        <label>
            Last Name:
            <input type="text" name="lastName" required>
            <span class="error"><?php echo $errors['lastName'] ?? ''; ?></span>
        </label><br>

        <label>
            Email:
            <input type="email" name="email" required>
            <span class="error"><?php echo $errors['email'] ?? ''; ?></span>
        </label><br>

        <label>
            Phone Number:
            <input type="text" name="phoneNumber" required>
            <span class="error"><?php echo $errors['phoneNumber'] ?? ''; ?></span>
        </label><br>

        <label>
            City/Town:
            <input type="text" name="cityTown">
            <span class="error"><?php echo $errors['cityTown'] ?? ''; ?></span>
        </label><br>

        <label>
            Street:
            <input type="text" name="street">
            <span class="error"><?php echo $errors['street'] ?? ''; ?></span>
        </label><br>

        <label>
            House Number:
            <input type="number" name="houseNumber">
        </label><br>

        <label>
            Password:
            <input type="password" name="password" required>
            <span class="error"><?php echo $errors['password'] ?? ''; ?></span>
        </label><br>

        <button type="submit">Signup</button>
        <button><a href="../public/index.php">Go back to options</a></button>
    </form>
</body>
</html>
