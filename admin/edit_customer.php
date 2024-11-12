<?php
require_once '../controllers/CustomerController.php';

$customerController = new CustomerController();
$customer = null;

// Check if 'id' parameter is set in the URL
if (isset($_GET['id'])) {
    $customerID = $_GET['id'];
    $customer = $customerController->getProfile($customerID);

    if (!$customer) {
        echo "Customer not found.";
        exit;
    }
} else {
    echo "Customer ID not specified.";
    exit;
}

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data safely
    $firstName = $_POST['firstName'] ?? null;
    $middleInitial = $_POST['middleInitial'] ?? null;
    $lastName = $_POST['lastName'] ?? null;
    $email = $_POST['email'] ?? null;
    $phoneNumber = $_POST['phoneNumber'] ?? null;
    $street = $_POST['street'] ?? null;
    $citytown = $_POST['citytown'] ?? null;
    $houseNumber = $_POST['houseNumber'] ?? null;

    // Update customer profile
    $updateSuccess = $customerController->updateProfile($customerID, $firstName, $middleInitial, $lastName, $email, $phoneNumber, $street, $citytown, $houseNumber);

    if ($updateSuccess) {
        echo "Customer profile updated successfully!";
        // Optionally, redirect to another page here
    } else {
        echo "Failed to update customer profile.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Customer</title>
</head>
<body>
    <h1>Edit Customer Profile</h1>
    <form method="POST" action="">
        <label for="firstName">First Name:</label>
        <input type="text" name="firstName" id="firstName" value="<?= htmlspecialchars($customer['firstName']) ?>" required><br>

        <label for="middleInitial">Middle Initial:</label>
        <input type="text" name="middleInitial" id="middleInitial" value="<?= htmlspecialchars($customer['middleInitial']) ?>"><br>

        <label for="lastName">Last Name:</label>
        <input type="text" name="lastName" id="lastName" value="<?= htmlspecialchars($customer['lastName']) ?>" required><br>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" value="<?= htmlspecialchars($customer['email']) ?>" required><br>

        <label for="phoneNumber">Phone Number:</label>
        <input type="text" name="phoneNumber" id="phoneNumber" value="<?= htmlspecialchars($customer['phoneNumber']) ?>"><br>

        <label for="street">Street:</label>
        <input type="text" name="street" id="street" value="<?= htmlspecialchars($customer['street']) ?>"><br>

        <label for="citytown">City/Town:</label>
        <input type="text" name="citytown" id="citytown" value="<?= htmlspecialchars($customer['citytown']) ?>"><br>

        <label for="houseNumber">House Number:</label>
        <input type="text" name="houseNumber" id="houseNumber" value="<?= htmlspecialchars($customer['houseNumber']) ?>"><br>

        <input type="submit" value="Update Profile">
    </form>
</body>
</html>
