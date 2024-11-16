<?php
// Start output buffering
ob_start();

require_once '../lib/logger.php';
require_once '../config/database.php';

// Check if item ID is provided
if (!isset($_GET['id'])) {
    header("Location: manage_sushi.php?error=no_id");
    exit();
}

$itemID = intval($_GET['id']); // Sanitize ID

// Database connection
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Validate itemID exists
$stmt = $mysqli->prepare("SELECT COUNT(*) FROM sushi_item WHERE itemID = ?");
$stmt->bind_param("i", $itemID);
$stmt->execute();
$stmt->bind_result($count);
$stmt->fetch();
$stmt->close();

if ($count === 0) {
    die("Error: Sushi item with ID $itemID does not exist.");
}

// Retrieve sushi item data
$stmt = $mysqli->prepare("SELECT itemName, description, price, availabilityStatus, category, ingredients FROM sushi_item WHERE itemID = ?");
$stmt->bind_param("i", $itemID);
$stmt->execute();
$stmt->bind_result($itemName, $description, $price, $availabilityStatus, $category, $ingredients);
$stmt->fetch();
$stmt->close();

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $itemName = htmlspecialchars(trim($_POST['itemName']));
    $description = htmlspecialchars(trim($_POST['description']));
    $price = floatval($_POST['price']);
    $availabilityStatus = intval($_POST['availabilityStatus']);
    $category = htmlspecialchars(trim($_POST['category']));
    $ingredients = htmlspecialchars(trim($_POST['ingredients']));

    $stmt = $mysqli->prepare("UPDATE sushi_item SET itemName = ?, description = ?, price = ?, availabilityStatus = ?, category = ?, ingredients = ? WHERE itemID = ?");
    $stmt->bind_param("ssdissi", $itemName, $description, $price, $availabilityStatus, $category, $ingredients, $itemID);

    if ($stmt->execute()) {
        header("Location: edit_sushi_item.php?id=" . urlencode($itemID) . "&message=updated");
        exit();
    } else {
        error_log("Update failed: " . $stmt->error);
        echo "Update failed: " . $stmt->error;
    }
    $stmt->close();
}

$mysqli->close();
ob_end_flush();
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
        
        <!-- Success message display -->
        <?php if (isset($_GET['message']) && $_GET['message'] === 'updated'): ?>
            <div class="alert alert-success">
                Sushi item updated successfully!
            </div>
        <?php endif; ?>

        <!-- Display the form only if the request method is not POST -->
        <?php if ($_SERVER['REQUEST_METHOD'] !== 'POST'): ?>
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
        <?php endif; ?>

        <a href="manage_sushi.php" class="back-link">Back to Manage Sushi Items</a>
    </div>
</body>
</html>