<?php
// Start output buffering
ob_start();

// Include dependencies
require_once '../lib/logger.php';  
require_once '../config/database.php';  

// Check if item ID is provided and valid
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: manage_sushi.php?error=invalid_id");
    exit();
}

$itemID = intval($_GET['id']); // Sanitize the ID

// Database connection
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($mysqli->connect_error) {
    die("Database connection failed: " . $mysqli->connect_error);
}

// Validate if the sushi item exists
$stmt = $mysqli->prepare("SELECT COUNT(*) FROM sushi_item WHERE itemID = ?");
if (!$stmt) {
    die("Error preparing statement: " . $mysqli->error);
}
$stmt->bind_param("i", $itemID);
$stmt->execute();
$stmt->bind_result($count);
$stmt->fetch();
$stmt->close();

if ($count === 0) {
    // Redirect if the item doesn't exist
    ob_end_clean();
    header("Location: manage_sushi.php?error=item_not_found");
    exit();
}

// Retrieve sushi item data
$stmt = $mysqli->prepare("SELECT itemName, description, price, availabilityStatus, category, ingredients FROM sushi_item WHERE itemID = ?");
if (!$stmt) {
    die("Error preparing statement: " . $mysqli->error);
}
$stmt->bind_param("i", $itemID);
$stmt->execute();
$stmt->bind_result($itemName, $description, $price, $availabilityStatus, $category, $ingredients);
$stmt->fetch();
$stmt->close();

// Escape the values to prevent XSS when rendering in the form
$itemName = htmlspecialchars($itemName, ENT_QUOTES);
$description = htmlspecialchars($description, ENT_QUOTES);
$category = htmlspecialchars($category, ENT_QUOTES);
$ingredients = htmlspecialchars($ingredients, ENT_QUOTES);

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input
    $itemName = htmlspecialchars(trim($_POST['itemName']), ENT_QUOTES);
    $description = htmlspecialchars(trim($_POST['description']), ENT_QUOTES);
    $price = filter_var($_POST['price'], FILTER_VALIDATE_FLOAT);
    $availabilityStatus = filter_var($_POST['availabilityStatus'], FILTER_VALIDATE_INT);
    $category = htmlspecialchars(trim($_POST['category']), ENT_QUOTES);
    $ingredients = htmlspecialchars(trim($_POST['ingredients']), ENT_QUOTES);

    // Validate required fields
    if (!$itemName || !$description || $price === false || $availabilityStatus === false || !$category || !$ingredients) {
        echo "Error: All fields are required and must be valid.";
        exit();
    }

    // Prepare and execute update query
    $stmt = $mysqli->prepare("UPDATE sushi_item SET itemName = ?, description = ?, price = ?, availabilityStatus = ?, category = ?, ingredients = ? WHERE itemID = ?");
    if (!$stmt) {
        die("Error preparing update statement: " . $mysqli->error);
    }
    $stmt->bind_param("ssdissi", $itemName, $description, $price, $availabilityStatus, $category, $ingredients, $itemID);

    if ($stmt->execute()) {
        // Clear output buffer and redirect
        ob_end_clean();
        header("Location: edit_sushi_item.php?id=" . urlencode($itemID) . "&message=updated");
        exit();
    } else {
        error_log("Update failed: " . $stmt->error); // Log error for debugging
        echo "Update failed: " . $stmt->error;
    }
    $stmt->close();
}

// Close database connection
$mysqli->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Sushi Item</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/manage_sushi.css">
    <link rel="icon" href="../assets/images/altlogo.png" type="image/png">
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h1 class="text-center mb-4">Edit Sushi Item</h1>
                
                <!-- Success message display -->
                <?php if (isset($_GET['message']) && $_GET['message'] === 'updated'): ?>
                    <div class="alert alert-success text-center">
                        Sushi item updated successfully!
                    </div>
                <?php endif; ?>

                <!-- Display the form only if the request method is not POST -->
                <?php if ($_SERVER['REQUEST_METHOD'] !== 'POST'): ?>
                    <form method="POST" action="edit_sushi_item.php?id=<?php echo htmlspecialchars($itemID); ?>" class="p-4 border rounded-3 bg-light shadow-sm">
                        <div class="mb-3">
                            <label for="itemName" class="form-label">Item Name:</label>
                            <input type="text" class="form-control" name="itemName" id="itemName" value="<?php echo htmlspecialchars($itemName); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description:</label>
                            <textarea class="form-control" name="description" id="description" rows="3" required><?php echo htmlspecialchars($description); ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="price" class="form-label">Price:</label>
                            <input type="number" step="0.01" class="form-control" name="price" id="price" value="<?php echo htmlspecialchars($price); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="availabilityStatus" class="form-label">Availability Status:</label>
                            <select class="form-select" name="availabilityStatus" id="availabilityStatus" required>
                                <option value="1" <?php if ($availabilityStatus == 1) echo 'selected'; ?>>Available</option>
                                <option value="0" <?php if ($availabilityStatus == 0) echo 'selected'; ?>>Unavailable</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="category" class="form-label">Category:</label>
                            <input type="text" class="form-control" name="category" id="category" value="<?php echo htmlspecialchars($category); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="ingredients" class="form-label">Ingredients:</label>
                            <textarea class="form-control" name="ingredients" id="ingredients" rows="3" required><?php echo htmlspecialchars($ingredients); ?></textarea>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                <?php endif; ?>

                <div class="text-center mt-4">
                    <a href="manage_sushi.php" class="btn btn-outline-secondary">Back to Manage Sushi Items</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
