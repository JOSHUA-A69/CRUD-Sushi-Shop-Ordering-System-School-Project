<?php
require_once '../controllers/CustomerController.php';

$customerController = new CustomerController();
$customers = $customerController->getAllCustomers();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Customers</title>
    <link rel="stylesheet" href="styles/admin.css">
</head>
<body>
    <div class="admin-container">
        <h1>Manage Customers</h1>
        <table>
            <thead>
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
                        <td><?= $customer['customer_id'] ?? 'N/A' ?></td>
                        <td><?= ($customer['first_name'] ?? '') . ' ' . ($customer['last_name'] ?? '') ?></td>
                        <td><?= $customer['email_address'] ?? 'N/A' ?></td>
                        <td><?= $customer['phone_number'] ?? 'N/A' ?></td>
                        <td>
                            <a href="view_customer.php?id=<?= $customer['customer_id'] ?>">View</a> |
                            <a href="edit_customer.php?id=<?= $customer['customer_id'] ?>">Edit</a> |
                            <a href="delete_customer.php?id=<?= $customer['customer_id'] ?>">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
