<?php
require_once '../controllers/CustomerController.php';

$customerController = new CustomerController();
$customers = $customerController->getAllCustomers();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Customers</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/manage_customers.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-light">
    <div class="container py-5">
        <h1 class="text-center mb-4">Manage Customers</h1>
        
        <div class="mb-4">
            <a href="./index.php" class="btn btn-secondary">Go back to options</a>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Customer ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($customers as $customer): ?>
                        <tr>
                            <td><?= $customer['CustomerID'] ?></td>
                            <td><?= htmlspecialchars(($customer['FirstName'] ?? '') . ' ' . ($customer['LastName'] ?? '')) ?></td>
                            <td><?= htmlspecialchars($customer['Email'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($customer['PhoneNumber'] ?? 'N/A') ?></td>
                            <td>
                                <a href="view_customer.php?id=<?= $customer['CustomerID'] ?>" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                                <a href="edit_customer.php?id=<?= $customer['CustomerID'] ?>" class="btn btn-warning btn-sm"><i class="fas fa-pencil-alt"></i></a>
                                <a href="delete_customer.php?id=<?= $customer['CustomerID'] ?>" class="btn btn-danger btn-sm"> <i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

