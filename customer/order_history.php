<?php
require_once '../config/database.php';

$customerID = 13; // Assuming logged-in customer ID is available
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

$result = $mysqli->query("SELECT * FROM orders WHERE customerID = $customerID ORDER BY orderDate DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order History</title>
    <link rel="stylesheet" href="styles/order_history.css">
</head>
<body>
    <h1>Your Order History</h1>
    <div class="order-history">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="order-item">
                <p><strong>Item:</strong> <?php echo htmlspecialchars($row['itemName']); ?></p>
                <p><strong>Date:</strong> <?php echo htmlspecialchars($row['orderDate']); ?></p>
                <p><strong>Status:</strong> <?php echo htmlspecialchars($row['status']); ?></p>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>
