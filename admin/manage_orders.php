<?php
require_once '../controllers/OrderController.php';
require_once '../config/database.php';

// Check if order ID is provided
if (isset($_GET['id'])) {
    $orderID = $_GET['id'];

    // Database connection
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    // Check connection
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    // Retrieve order data
    $stmt = $mysqli->prepare("
        SELECT o.orderID, o.customerID, o.totalPrice, o.orderStatus, i.ItemName, oi.quantity
        FROM orders AS o
        JOIN order_items AS oi ON o.orderID = oi.orderID
        JOIN sushi_item AS i ON oi.itemID = i.itemID
        WHERE o.orderID = ?
    ");

    if (!$stmt) {
        die("Prepare statement failed: " . $mysqli->error);
    }

    // Bind parameters
    if (!$stmt->bind_param("i", $orderID)) {
        die("Bind parameter failed: " . $stmt->error);
    }

    // Execute statement
    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }

    // Bind result variables
    if (!$stmt->bind_result($orderID, $customerID, $totalPrice, $orderStatus, $itemName, $quantity)) {
        die("Bind result failed: " . $stmt->error);
    }

    // Fetch data and store in array for display
    $orderDetails = [];
    while ($stmt->fetch()) {
        $orderDetails[] = [
            'orderID' => $orderID,
            'customerID' => $customerID,
            'totalPrice' => $totalPrice,
            'orderStatus' => $orderStatus,
            'itemName' => $itemName,
            'quantity' => $quantity
        ];
    }

    // Close the statement and connection
    $stmt->close();
    $mysqli->close();
} else {
    echo "<p>Order ID not specified.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Order Details</title>
    <link rel="stylesheet" href="styles/admin.css">
</head>
<body>
    <div class="admin-container">
        <h1>Order Details</h1>

        <?php if (empty($orderDetails)): ?>
            <p>No details available for this order.</p>
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
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orderDetails as $order): ?>
                        <tr>
                            <td><?= htmlspecialchars($order['orderID']) ?></td>
                            <td><?= htmlspecialchars($order['customerID']) ?></td>
                            <td><?= htmlspecialchars($order['itemName']) ?></td>
                            <td><?= htmlspecialchars($order['quantity']) ?></td>
                            <td><?= number_format($order['totalPrice'], 2) ?></td>
                            <td><?= htmlspecialchars($order['orderStatus']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <!-- Add a back button for better navigation -->
        <div class="back-button">
            <a href="manage_orders.php" class="btn">Back to Orders</a>
        </div>
    </div>
</body>
</html>
