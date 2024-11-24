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

// Prepare the query to fetch order history with feedback
$query = "
    SELECT o.orderID, o.OrderTime, o.orderStatus, s.ItemName, o.Feedback
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/order_history.css">
    <link rel="icon" href="../assets/images/altlogo.png" type="image/png">
</head>
<body class="bg-light">

    <div class="container mt-5">
        <h1 class="text-center mb-4">Your Order History</h1>
        <div class="order-history row gy-4">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="order-item col-md-6 col-lg-4 p-3">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                <p><strong>Order ID:</strong> <?php echo htmlspecialchars($row['orderID']); ?></p>
                                <p><strong>Item:</strong> <?php echo htmlspecialchars($row['ItemName']); ?></p>
                                <p><strong>Date:</strong> <?php echo htmlspecialchars($row['OrderTime']); ?></p>
                                <p><strong>Status:</strong> <?php echo htmlspecialchars($row['orderStatus']); ?></p>
                                
                                <?php if (!empty($row['Feedback'])): ?>
                                    <p><strong>Your Feedback:</strong> <?php echo htmlspecialchars($row['Feedback']); ?></p>
                                <?php else: ?>
                                    <form action="submit_feedback.php" method="POST" class="feedback-form mt-3">
                                        <input type="hidden" name="orderID" value="<?php echo htmlspecialchars($row['orderID']); ?>">
                                        <textarea name="feedback" class="form-control mb-2" placeholder="Leave your feedback here..." required></textarea>
                                        <button type="submit" class="btn btn-primary w-100">Submit Feedback</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-center">No orders found in your history.</p>
            <?php endif; ?>
        </div>

        <!-- Back to Dashboard button -->
        <div class="text-center mt-4">
            <a href="index.php" class="btn btn-primary">Back to Dashboard</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Close the database connection
$stmt->close();
$mysqli->close();
?>
