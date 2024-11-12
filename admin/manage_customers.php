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
       <button><a href="./index.php">Go back to options</a></button>
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
                        <td><?= $customer['CustomerID'] ?></td>
                        <td><?= ($customer['FirstName'] ?? '') . ' ' . ($customer['LastName'] ?? '') ?></td>
                        <td><?= $customer['Email'] ?? 'N/A' ?></td>
                        <td><?= $customer['PhoneNumber'] ?? 'N/A' ?></td>
                        <td>
                            <a href="view_customer.php?id=<?= $customer['CustomerID'] ?>">View</a> |
                            <a href="edit_customer.php?id=<?= $customer['CustomerID'] ?>">Edit</a> |
                            <a href="delete_customer.php?id=<?= $customer['CustomerID'] ?>">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
