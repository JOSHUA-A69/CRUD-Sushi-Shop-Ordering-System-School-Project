<?php
require_once '../config/database.php';

// Database connection
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Retrieve all orders
$stmt = $mysqli->prepare("
    SELECT o.orderID, o.customerID, o.totalPrice, o.orderStatus, o.paymentStatus, 
           s.ItemName, o.quantity
    FROM orders AS o
    JOIN sushi_item AS s ON o.itemID = s.itemID
");

if (!$stmt) {
    die("Prepare statement failed: " . $mysqli->error);
}

$stmt->execute();
$stmt->bind_result($orderID, $customerID, $totalPrice, $orderStatus, $paymentStatus, $itemName, $quantity);

// Store data in an array
$orders = [];
while ($stmt->fetch()) {
    $orders[] = [
        'orderID' => $orderID,
        'customerID' => $customerID,
        'totalPrice' => $totalPrice,
        'orderStatus' => $orderStatus,
        'paymentStatus' => $paymentStatus,
        'itemName' => $itemName,
        'quantity' => $quantity
    ];
}

// Close the statement and connection
$stmt->close();
$mysqli->close();
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

        <?php if (empty($orders)): ?>
            <p>No orders available.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer ID</th>
                        <th>Item</th>
                        <th>Quantity</th>
                        <th>Total Price</th>
                        <th>Status</th>
                        <th>Payment Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?= htmlspecialchars($order['orderID']) ?></td>
                            <td><?= htmlspecialchars($order['customerID']) ?></td>
                            <td><?= htmlspecialchars($order['itemName']) ?></td>
                            <td><?= htmlspecialchars($order['quantity']) ?></td>
                            <td><?= number_format($order['totalPrice'], 2) ?></td>
                            <td><?= htmlspecialchars($order['orderStatus']) ?></td>
                            <td><?= htmlspecialchars($order['paymentStatus']) ?></td>
                            <td>
                                <a href="edit_order.php?id=<?= $order['orderID'] ?>" class="btn">Edit</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <!-- Add a navigation button -->
        <div class="add-button">
            <a href="index.php" class="btn">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
