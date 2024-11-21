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
        $newTotalPrice = floatval($_POST['totalPrice'] ?? $order['totalPrice']); // New field

        // Update order in the database
        $updateStmt = $mysqli->prepare("
            UPDATE orders 
            SET orderStatus = ?, paymentStatus = ?, quantity = ?, totalPrice = ?
            WHERE orderID = ?
        ");

        if (!$updateStmt) {
            die("Prepare statement failed: " . $mysqli->error);
        }

        $updateStmt->bind_param("ssidi", $newOrderStatus, $newPaymentStatus, $newQuantity, $newTotalPrice, $orderID);

        if ($updateStmt->execute()) {
            $successMessage = "Order updated successfully!";
            // Refresh order details
            $order['orderStatus'] = $newOrderStatus;
            $order['paymentStatus'] = $newPaymentStatus;
            $order['quantity'] = $newQuantity;
            $order['totalPrice'] = $newTotalPrice;
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Order</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/manage_orders.css">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="card shadow p-4">
            <h1 class="mb-4 text-center">Edit Order</h1>

            <?php if (!empty($order)): ?>
                <!-- Display success or error messages -->
                <?php if (isset($successMessage)): ?>
                    <div class="alert alert-success">
                        <?= htmlspecialchars($successMessage) ?>
                    </div>
                <?php elseif (isset($errorMessage)): ?>
                    <div class="alert alert-danger">
                        <?= htmlspecialchars($errorMessage) ?>
                    </div>
                <?php endif; ?>

                <!-- Edit form -->
                <form action="" method="POST" class="row g-3">
                    <div class="col-md-6">
                        <label for="orderStatus" class="form-label">Order Status:</label>
                        <select name="orderStatus" id="orderStatus" class="form-select">
                            <option value="Pending" <?= $order['orderStatus'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="Completed" <?= $order['orderStatus'] === 'Completed' ? 'selected' : '' ?>>Completed</option>
                            <option value="Cancelled" <?= $order['orderStatus'] === 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="paymentStatus" class="form-label">Payment Status:</label>
                        <select name="paymentStatus" id="paymentStatus" class="form-select">
                            <option value="Unpaid" <?= $order['paymentStatus'] === 'Unpaid' ? 'selected' : '' ?>>Unpaid</option>
                            <option value="Paid" <?= $order['paymentStatus'] === 'Paid' ? 'selected' : '' ?>>Paid</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="quantity" class="form-label">Quantity:</label>
                        <input type="number" name="quantity" id="quantity" 
                               class="form-control" 
                               value="<?= htmlspecialchars($order['quantity']) ?>" 
                               required>
                    </div>

                    <div class="col-md-6">
                        <label for="totalPrice" class="form-label">Total Price:</label>
                        <input type="number" step="0.01" name="totalPrice" id="totalPrice" 
                               class="form-control" 
                               value="<?= htmlspecialchars($order['totalPrice']) ?>" 
                               required>
                    </div>

                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary px-4">Update Order</button>
                    </div>
                </form>
            <?php else: ?>
                <p class="text-muted text-center">No details available for this order.</p>
            <?php endif; ?>

            <!-- Back to Orders Button -->
            <div class="text-center mt-4">
                <a href="manage_orders.php" class="btn btn-secondary">Back to Orders</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
