<?php
require_once '../config/database.php';

// Check if order ID is provided
if (isset($_GET['id'])) {
    $orderID = intval($_GET['id']); // Sanitize input

    // Database connection
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    // Check connection
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    // Fetch current order details
    $stmt = $mysqli->prepare("
        SELECT o.orderID, o.customerID, o.totalPrice, o.orderStatus, o.paymentStatus, 
               o.quantity, s.ItemName
        FROM orders AS o
        JOIN sushi_item AS s ON o.itemID = s.itemID
        WHERE o.orderID = ?
    ");

    if (!$stmt) {
        die("Prepare statement failed: " . $mysqli->error);
    }

    $stmt->bind_param("i", $orderID);
    $stmt->execute();
    $stmt->bind_result($orderID, $customerID, $totalPrice, $orderStatus, $paymentStatus, $quantity, $itemName);

    $order = [];
    if ($stmt->fetch()) {
        $order = [
            'orderID' => $orderID,
            'customerID' => $customerID,
            'totalPrice' => $totalPrice,
            'orderStatus' => $orderStatus,
            'paymentStatus' => $paymentStatus,
            'quantity' => $quantity,
            'itemName' => $itemName
        ];
    }

    $stmt->close();

    // Handle form submission to update the order
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $newOrderStatus = $_POST['orderStatus'] ?? $order['orderStatus'];
        $newPaymentStatus = $_POST['paymentStatus'] ?? $order['paymentStatus'];
        $newQuantity = intval($_POST['quantity'] ?? $order['quantity']);

        // Update order in the database
        $updateStmt = $mysqli->prepare("
            UPDATE orders 
            SET orderStatus = ?, paymentStatus = ?, quantity = ?
            WHERE orderID = ?
        ");

        if (!$updateStmt) {
            die("Prepare statement failed: " . $mysqli->error);
        }

        $updateStmt->bind_param("ssii", $newOrderStatus, $newPaymentStatus, $newQuantity, $orderID);

        if ($updateStmt->execute()) {
            $successMessage = "Order updated successfully!";
            // Refresh order details
            $order['orderStatus'] = $newOrderStatus;
            $order['paymentStatus'] = $newPaymentStatus;
            $order['quantity'] = $newQuantity;
        } else {
            $errorMessage = "Failed to update the order. Please try again.";
        }

        $updateStmt->close();
    }

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
    <title>Edit Order</title>
    <link rel="stylesheet" href="styles/admin.css">
</head>
<body>
    <div class="admin-container">
        <h1>Edit Order</h1>

        <?php if (!empty($order)): ?>
            <!-- Display success or error messages -->
            <?php if (isset($successMessage)): ?>
                <p class="success"><?= htmlspecialchars($successMessage) ?></p>
            <?php elseif (isset($errorMessage)): ?>
                <p class="error"><?= htmlspecialchars($errorMessage) ?></p>
            <?php endif; ?>

            <!-- Edit form -->
            <form action="" method="POST">
                <label for="orderStatus">Order Status:</label>
                <select name="orderStatus" id="orderStatus">
                    <option value="Pending" <?= $order['orderStatus'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="Completed" <?= $order['orderStatus'] === 'Completed' ? 'selected' : '' ?>>Completed</option>
                    <option value="Cancelled" <?= $order['orderStatus'] === 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
                </select>

                <label for="paymentStatus">Payment Status:</label>
                <select name="paymentStatus" id="paymentStatus">
                    <option value="Unpaid" <?= $order['paymentStatus'] === 'Unpaid' ? 'selected' : '' ?>>Unpaid</option>
                    <option value="Paid" <?= $order['paymentStatus'] === 'Paid' ? 'selected' : '' ?>>Paid</option>
                </select>

                <label for="quantity">Quantity:</label>
                <input type="number" name="quantity" id="quantity" value="<?= htmlspecialchars($order['quantity']) ?>" required>

                <button type="submit" class="btn">Update Order</button>
            </form>
        <?php else: ?>
            <p>No details available for this order.</p>
        <?php endif; ?>

        <!-- Add a back button for better navigation -->
        <div class="back-button">
            <a href="manage_orders.php" class="btn">Back to Orders</a>
        </div>
    </div>
</body>
</html>
