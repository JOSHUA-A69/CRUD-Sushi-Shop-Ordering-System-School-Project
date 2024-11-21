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
        header("Location: order_status.php?status=success&message=Order placed successfully!");
        exit();
    } else {
        header("Location: order_status.php?status=failure&message=Failed to place order. Please try again later.");
        error_log("Order Insert Error: " . $stmt->error);
        exit();
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order - <?php echo htmlspecialchars($item['ItemName']); ?></title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/order.css">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="card shadow p-4 bg-white text-center text-dark ">
            <h1 class="text-center mb-4">
                Order: <?php echo htmlspecialchars($item['ItemName']); ?>
            </h1>
            <p class="text-muted text-center mb-4">
                Price per piece: $<?php echo number_format($item['Price'], 2); ?>
            </p>

            <form method="POST" action="order.php?id=<?php echo $itemID; ?>" class="needs-validation" novalidate>
                <input type="hidden" name="customerID" value="<?php echo $_SESSION['customerID'] ?? 1; ?>">

                <div class="mb-3">
                    <label for="quantity" class="form-label">Quantity:</label>
                    <input type="number" class="form-control" name="quantity" id="quantity" min="1" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Place Order</button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>