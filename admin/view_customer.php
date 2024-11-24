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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Customer</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/manage_customers.css">
    <link rel="icon" href="../assets/images/altlogo.png" type="image/png">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="card shadow p-4">
            <h1 class="text-center mb-4">Customer Details</h1>
            
            <?php if ($customer): ?>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><strong>Customer ID:</strong> <?= htmlspecialchars($customer['CustomerID']) ?></li>
                    <li class="list-group-item"><strong>Name:</strong> <?= htmlspecialchars($customer['firstName'] . ' ' . $customer['middleInitial'] . ' ' . $customer['lastName']) ?></li>
                    <li class="list-group-item"><strong>Email:</strong> <?= htmlspecialchars($customer['email']) ?></li>
                    <li class="list-group-item"><strong>Phone Number:</strong> <?= htmlspecialchars($customer['phoneNumber']) ?></li>
                    <li class="list-group-item"><strong>Address:</strong> <?= htmlspecialchars($customer['street'] . ', ' . $customer['citytown'] . ', House Number ' . $customer['houseNumber']) ?></li>
                </ul>
                <div class="mt-4">
                    <a href="manage_customers.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Customer List
                    </a>
                </div>
            <?php else: ?>
                <p class="text-danger">Customer not found.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
