<?php
require_once '../config/database.php';

$itemID = $_GET['id'] ?? null;
if (!$itemID) {
    die("Invalid item selected.");
}

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

$stmt = $mysqli->prepare("SELECT * FROM sushi_item WHERE itemID = ?");
$stmt->bind_param("i", $itemID);
$stmt->execute();
$item = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order - <?php echo htmlspecialchars($item['itemName']); ?></title>
    <link rel="stylesheet" href="styles/order.css">
</head>
<body>
    <h1>Order: <?php echo htmlspecialchars($item['itemName']); ?></h1>
    <form method="POST" action="place_order.php">
        <input type="hidden" name="itemID" value="<?php echo $itemID; ?>">
        <label for="customerName">Name:</label>
        <input type="text" name="customerName" id="customerName" required>
        
        <label for="customerContact">Contact:</label>
        <input type="text" name="customerContact" id="customerContact" required>
        
        <label for="quantity">Quantity:</label>
        <input type="number" name="quantity" id="quantity" min="1" required>
        
        <button type="submit">Place Order</button>
    </form>
</body>
</html>
