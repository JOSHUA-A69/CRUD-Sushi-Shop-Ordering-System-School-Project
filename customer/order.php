<?php
session_start();  // Ensure the session is started at the top of the file

require_once '../config/database.php';

// Retrieve item details
$itemID = $_GET['id'] ?? null;
if (!$itemID) {
    die("Invalid item selected.");
}

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check if the customer is logged in
$customerID = $_SESSION['customerID'] ?? null;
if (!$customerID) {
    die("Invalid CustomerID. Please log in or register.");
}

$stmt = $mysqli->prepare("SELECT * FROM sushi_item WHERE itemID = ?");
$stmt->bind_param("i", $itemID);
$stmt->execute();
$item = $stmt->get_result()->fetch_assoc();

if (!$item) {
    die("Item not found.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quantity = intval($_POST['quantity']);

    if ($quantity <= 0) {
        die("Invalid quantity.");
    }

    $totalPrice = $item['Price'] * $quantity;

    // Insert into Orders table
    $stmt = $mysqli->prepare("
        INSERT INTO orders (CustomerID, ItemID, Quantity, TotalPrice, OrderStatus, PaymentStatus) 
        VALUES (?, ?, ?, ?, 'Pending', 'Unpaid')
    ");
    $stmt->bind_param("iiid", $customerID, $itemID, $quantity, $totalPrice);

    if ($stmt->execute()) {
        echo "<p>Order placed successfully!</p>";
        echo '<a href="index.php">Back to Dashboard</a>';
    } else {
        echo "<p>Failed to place order. Please try again later.</p>";
        error_log("Order Insert Error: " . $stmt->error);
    }

    $stmt->close();
    $mysqli->close();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order - <?php echo htmlspecialchars($item['ItemName']); ?></title>
    <link rel="stylesheet" href="styles/order.css">
</head>
<body>
    <h1>Order: <?php echo htmlspecialchars($item['ItemName']); ?></h1>
    <p>Price per piece: $<?php echo number_format($item['Price'], 2); ?></p>

    <form method="POST" action="order.php?id=<?php echo $itemID; ?>">
        <!-- Assuming CustomerID is stored in a session after login -->
        <input type="hidden" name="customerID" value="<?php echo $_SESSION['customerID'] ?? 1; ?>">
        <label for="quantity">Quantity:</label>
        <input type="number" name="quantity" id="quantity" min="1" required>
        <button type="submit">Place Order</button>
    </form>
</body>
</html>
