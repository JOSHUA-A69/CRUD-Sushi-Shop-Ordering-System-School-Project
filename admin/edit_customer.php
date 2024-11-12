<?php
require_once '../controllers/CustomerController.php';

// Check if 'id' parameter is set in the URL
if (isset($_GET['id'])) {
    $customerID = $_GET['id'];
    $customerController = new CustomerController();
    $customer = $customerController->getProfile($customerID);

    // If form is submitted, update customer details
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $updatedData = [
            'firstName' => $_POST['firstName'],
            'middleInitial' => $_POST['middleInitial'],
            'lastName' => $_POST['lastName'],
            'email' => $_POST['email'],
            'phoneNumber' => $_POST['phoneNumber'],
            'city' => $_POST['city'],
            'street' => $_POST['street'],
            'houseNumber' => $_POST['houseNumber']
        ];

        $updateSuccess = $customerController->updateProfile($customerID, $updatedData);

        if ($updateSuccess) {
            echo "<p>Customer updated successfully.</p>";
        } else {
            echo "<p>Failed to update customer.</p>";
        }
    }
} else {
    echo "Customer ID not specified.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Customer</title>
    <link rel="stylesheet" href="styles/admin.css">
</head>
<body>
    <div class="admin-container">
        <h1>Edit Customer</h1>
        <?php if ($customer): ?>
            <form method="post">
                <label>First Name:</label>
                <input type="text" name="firstName" value="<?= $customer['firstName'] ?>" required>
                
                <label>Middle Initial:</label>
                <input type="text" name="middleInitial" value="<?= $customer['middleInitial'] ?>">

                <label>Last Name:</label>
                <input type="text" name="lastName" value="<?= $customer['lastName'] ?>" required>

                <label>Email:</label>
                <input type="email" name="email" value="<?= $customer['email'] ?>" required>

                <label>Phone Number:</label>
                <input type="text" name="phoneNumber" value="<?= $customer['phoneNumber'] ?>" required>

                <label>City:</label>
                <input type="text" name="city" value="<?= $customer['city'] ?>" required>

                <label>Street:</label>
                <input type="text" name="street" value="<?= $customer['street'] ?>" required>

                <label>House Number:</label>
                <input type="text" name="houseNumber" value="<?= $customer['houseNumber'] ?>" required>

                <button type="submit">Update Customer</button>
                <a href="manage_customers.php">Cancel</a>
            </form>
        <?php else: ?>
            <p>Customer not found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
