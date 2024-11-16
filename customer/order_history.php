<?php 
session_start(); // Start the session to access session variables
require_once '../config/database.php';

// Check if the user is logged in
if (!isset($_SESSION['customerID'])) {
    // Redirect to login page if not logged in
    header("Location: ../auth/login.php");
    exit;
}

// Retrieve the logged-in customer's ID from session
$customerID = $_SESSION['customerID'];

// Database connection
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Prepare the query to fetch order history with item details
$query = "
    SELECT o.orderID, o.OrderTime, o.orderStatus, s.ItemName 
    FROM orders AS o
    JOIN sushi_item AS s ON o.itemID = s.itemID
    WHERE o.customerID = ?
    ORDER BY o.OrderTime DESC
";

// Prepare and execute the statement
$stmt = $mysqli->prepare($query);
if (!$stmt) {
    die("Prepare statement failed: " . $mysqli->error);
}
$stmt->bind_param("i", $customerID);
$stmt->execute();
$result = $stmt->get_result();
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
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="order-item">
                    <p><strong>Order ID:</strong> <?php echo htmlspecialchars($row['orderID']); ?></p>
                    <p><strong>Item:</strong> <?php echo htmlspecialchars($row['ItemName']); ?></p>
                    <p><strong>Date:</strong> <?php echo htmlspecialchars($row['OrderTime']); ?></p>
                    <p><strong>Status:</strong> <?php echo htmlspecialchars($row['orderStatus']); ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No orders found in your history.</p>
        <?php endif; ?>
    </div>

    <!-- Optional back button -->
    <div class="back-button">
        <a href="index.php" class="btn">Back to Dashboard</a>
    </div>
</body>
</html>
<?php
// Close the database connection
$stmt->close();
$mysqli->close();
?>
