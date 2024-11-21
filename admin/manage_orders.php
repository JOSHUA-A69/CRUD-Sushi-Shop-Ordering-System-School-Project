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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/manage_orders.css">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="card shadow p-4">
            <h1 class="mb-4 text-center">Manage Orders</h1>

            <?php if (empty($orders)): ?>
                <p class="text-center text-muted">No orders available.</p>
            <?php else: ?>
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
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
                                <td>$<?= number_format($order['totalPrice'], 2) ?></td>
                                <td><?= htmlspecialchars($order['orderStatus']) ?></td>
                                <td><?= htmlspecialchars($order['paymentStatus']) ?></td>
                                <td>
                                    <a href="edit_order.php?id=<?= $order['orderID'] ?>" 
                                       class="btn btn-primary btn-sm">
                                       <i class="fas fa-pencil-alt"></i> Edit
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>

            <!-- Back to Dashboard Button -->
            <div class="text-center mt-4">
                <a href="index.php" class="btn btn-secondary">
                    Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
