<?php
require_once '../controllers/CustomerController.php';

// Check if 'id' parameter is set in the URL
if (isset($_GET['id'])) {
    $customerID = $_GET['id'];
    $customerController = new CustomerController();
    $customer = $customerController->getProfile($customerID);
} else {
    echo "Customer ID not specified.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Customer</title>
    <link rel="stylesheet" href="styles/admin.css">
</head>
<body>
    <div class="admin-container">
        <h1>Customer Details</h1>
        <?php if ($customer): ?>
            <p><strong>Customer ID:</strong> <?= $customer['CustomerID'] ?></p>
            <p><strong>Name:</strong> <?= $customer['firstName'] . ' ' . $customer['middleInitial'] . ' ' . $customer['lastName'] ?></p>
            <p><strong>Email:</strong> <?= $customer['email'] ?></p>
            <p><strong>Phone Number:</strong> <?= $customer['phoneNumber'] ?></p>
            <p><strong>Address:</strong> <?= $customer['street'] . ', ' . $customer['citytown'] . ', House Number ' . $customer['houseNumber'] ?></p>
            <a href="manage_customers.php">Back to Customer List</a>
        <?php else: ?>
            <p>Customer not found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
