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
                        <td>
                            <?php
                            // Display items for the order
                            $items = array_map(function($item) {
                                return $item['ItemName'] . " (x" . $item['quantity'] . ")";
                            }, $order['items']);
                            echo implode(', ', $items);
                            ?>
                        </td>
                        <td><?= number_format($order['TotalPrice'], 2) ?></td>  <!-- Format total price -->
                        <td><?= $order['OrderStatus'] ?></td>  
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
