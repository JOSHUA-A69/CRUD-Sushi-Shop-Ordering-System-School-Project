<?php
session_start();
require_once '../controllers/SushiController.php';
require_once '../lib/logger.php';
require_once '../config/database.php';

// Check if item ID is provided
if (!isset($_GET['id'])) {
    echo "<p>Sushi item ID not specified.</p>";
    exit;
}

$itemID = $_GET['id'];

// Database connection
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Retrieve sushi item data
$stmt = $mysqli->prepare("SELECT itemName, description, price, availabilityStatus, category, ingredients FROM sushi_item WHERE itemID = ?");
if (!$stmt) {
    die("Prepare statement failed: " . $mysqli->error);
}
$stmt->bind_param("i", $itemID);
$stmt->execute();
$stmt->bind_result($itemName, $description, $price, $availabilityStatus, $category, $ingredients);
$stmt->fetch();
$stmt->close();

// Check if form is submitted for update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Prepare the UPDATE statement
    $stmt = $mysqli->prepare("UPDATE sushi_item SET itemName = ?, description = ?, price = ?, availabilityStatus = ?, category = ?, ingredients = ? WHERE itemID = ?");
    
    if (!$stmt) {
        die("Prepare statement failed: " . $mysqli->error);
    }

    // Bind parameters
    $stmt->bind_param("ssdissi", 
        $_POST['itemName'], 
        $_POST['description'], 
        $_POST['price'], 
        $_POST['availabilityStatus'], 
        $_POST['category'], 
        $_POST['ingredients'], 
        $itemID
    );

    // Execute and check if update was successful
    if ($stmt->execute()) {
        echo "<p>Update successful. Redirecting to Manage Sushi page...</p>";
        echo '<meta http-equiv="refresh" content="2;url=./manage_sushi.php?message=updated">';
        exit;
    } else {
        die("Update failed: " . $stmt->error);
    }
    
}

// Close the database connection
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Sushi Item</title>
    <link rel="stylesheet" href="styles/admin.css">
</head>
<body>
    <div class="admin-container">
        <h1>Edit Sushi Item</h1>
        <form method="POST" action="edit_sushi_item.php?id=<?php echo htmlspecialchars($itemID); ?>">
            <label for="itemName">Item Name:</label>
            <input type="text" name="itemName" id="itemName" value="<?php echo htmlspecialchars($itemName); ?>" required>

            <label for="description">Description:</label>
            <textarea name="description" id="description" required><?php echo htmlspecialchars($description); ?></textarea>

            <label for="price">Price:</label>
            <input type="number" step="0.01" name="price" id="price" value="<?php echo htmlspecialchars($price); ?>" required>

            <label for="availabilityStatus">Availability Status:</label>
            <select name="availabilityStatus" id="availabilityStatus" required>
                <option value="1" <?php if ($availabilityStatus == 1) echo 'selected'; ?>>Available</option>
                <option value="0" <?php if ($availabilityStatus == 0) echo 'selected'; ?>>Unavailable</option>
            </select>

            <label for="category">Category:</label>
            <input type="text" name="category" id="category" value="<?php echo htmlspecialchars($category); ?>" required>

            <label for="ingredients">Ingredients:</label>
            <textarea name="ingredients" id="ingredients" required><?php echo htmlspecialchars($ingredients); ?></textarea>

            <button type="submit">Save Changes</button>
        </form>
        <a href="manage_sushi.php" class="back-link">Back to Manage Sushi Items</a>
    </div>
</body>
</html>
