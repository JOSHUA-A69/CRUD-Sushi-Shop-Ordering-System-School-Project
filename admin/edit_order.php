<?php
require_once '../controllers/OrderController.php';

// Check if 'id' parameter is set in the URL
if (isset($_GET['id'])) {
    $orderID = $_GET['id'];
    $orderController = new OrderController();

    // Get the order details
    $order = $orderController->getOrderById($orderID);

    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get the new status from the form
        $newStatus = $_POST['status'];

        // Update the order status
        $updateSuccess = $orderController->updateOrderStatus($orderID, $newStatus);

        if ($updateSuccess) {
            echo "<script>alert('Order status updated successfully.'); window.location.href = 'manage_orders.php';</script>";
        } else {
            echo "<script>alert('Failed to update order status.'); window.location.href = 'manage_orders.php';</script>";
        }
    }
} else {
    echo "Order ID not specified.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Order Status</title>
    <link rel="stylesheet" href="styles/admin.css">
</head>
<body>
    <div class="admin-container">
        <h1>Edit Order Status</h1>

        <?php if ($order): ?>
            <form method="post" action="">
                <label for="status">Order Status:</label>
                <select name="status" id="status">
                    <option value="Pending" <?= $order['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="Processing" <?= $order['status'] == 'Processing' ? 'selected' : '' ?>>Processing</option>
                    <option value="Completed" <?= $order['status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
                    <option value="Cancelled" <?= $order['status'] == 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
                </select>

                <button type="submit">Update Status</button>
            </form>
        <?php else: ?>
            <p>Order not found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
