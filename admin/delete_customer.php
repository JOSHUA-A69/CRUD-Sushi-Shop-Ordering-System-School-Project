<?php
require_once '../controllers/CustomerController.php';

// Check if 'id' parameter is set in the URL
if (isset($_GET['id'])) {
    $customerID = $_GET['id'];
    $customerController = new CustomerController();

    // Attempt to delete the customer
    $deleteSuccess = $customerController->deleteCustomer($customerID);

    if ($deleteSuccess) {
        echo "<script>alert('Customer deleted successfully.'); window.location.href = 'manage_customers.php';</script>";
    } else {
        echo "<script>alert('Failed to delete customer.'); window.location.href = 'manage_customers.php';</script>";
    }
} else {
    echo "Customer ID not specified.";
}
