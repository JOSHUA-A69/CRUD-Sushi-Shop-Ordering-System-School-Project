<?php
require_once '../controllers/CustomerController.php';

// Check if 'id' parameter is set in the URL
if (isset($_GET['id'])) {
    $customerID = $_GET['id'];
    $customerController = new CustomerController();

    // Only delete if confirmed by the user
    if (isset($_POST['confirm_delete'])) {
        // Attempt to delete the customer
        $deleteSuccess = $customerController->deleteCustomer($customerID);

        if ($deleteSuccess) {
            echo "<script>alert('Customer deleted successfully.'); window.location.href = 'manage_customers.php';</script>";
        } else {
            echo "<script>alert('Failed to delete customer.'); window.location.href = 'manage_customers.php';</script>";
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Delete Customer</title>
    <link rel="stylesheet" href="styles/admin.css">
</head>
<body>
    <div class="admin-container">
        <h1>Confirm Delete</h1>
        <p>Are you sure you want to delete this customer?</p>
        
        <form method="post">
            <button type="submit" name="confirm_delete" onclick="return confirm('Are you sure you want to delete this customer?');">
                Confirm Delete
            </button>
            <a href="manage_customers.php">Cancel</a>
        </form>
    </div>
</body>
</html>
