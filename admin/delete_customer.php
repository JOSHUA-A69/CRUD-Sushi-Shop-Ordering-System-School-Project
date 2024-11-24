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
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/manage_customers.css">
    <link rel="icon" href="../assets/images/altlogo.png" type="image/png">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="card shadow p-4 text-center">
            <h1 class="mb-4">Confirm Delete</h1>
            <p class="text-danger">Are you sure you want to delete this customer?</p>
            
            <form method="post" class="d-flex justify-content-center gap-3 mt-4">
                <button type="submit" name="confirm_delete" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this customer?');">
                    Confirm Delete
                </button>
                <a href="manage_customers.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
