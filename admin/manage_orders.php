<?php
require_once '../controllers/OrderController.php';

$orderController = new OrderController();
$orders = $orderController->getOrders();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Orders</title>
    <link rel="stylesheet" href="styles/admin.css">
</head>
<body>
    <div class="admin-container">
        <h1>Manage Orders</h1>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer ID</th>
                    <th>Items</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= $order['orderID'] ?></td>
                        <td><?= $order['customerID'] ?></td>
                        <td><?= implode(', ', json_decode($order['items'], true)) ?></td>
                        <td><?= $order['total_price'] ?></td>
                        <td><?= $order['status'] ?></td>
                        <td>
                            <a href="edit_order.php?id=<?= $order['orderID'] ?>">Update Status</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>


