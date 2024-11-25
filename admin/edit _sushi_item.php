<?php
// Start output buffering
ob_start();

// Include required dependencies
require_once '../config/database.php'; // Database connection

// Database connection
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($mysqli->connect_error) {
    die("Database connection failed: " . $mysqli->connect_error);
}

// Get the Item ID from the URL and validate
$itemID = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($itemID <= 0) {
    die("Error: Item ID is missing or invalid.");
}

// Fetch sushi item details
$stmt = $mysqli->prepare("SELECT * FROM Sushi_Item WHERE ItemID = ?");
if (!$stmt) {
    die("Error preparing statement: " . $mysqli->error);
}

$stmt->bind_param('i', $itemID);
$stmt->execute();
$result = $stmt->get_result();
$sushiItem = $result->fetch_assoc();
$stmt->close();

if (!$sushiItem) {
    die("Error: Sushi item not found.");
}

// Initialize error message
$errorMessage = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data with default values
    $itemName = $_POST['itemName'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = isset($_POST['price']) && is_numeric($_POST['price']) ? floatval($_POST['price']) : null;
    $availabilityStatus = isset($_POST['availabilityStatus']) ? intval($_POST['availabilityStatus']) : null;
    $category = $_POST['category'] ?? '';
    $ingredients = $_POST['ingredients'] ?? '';

    // Validate input
    if (!empty($itemName) && !empty($description) && $price !== null && $availabilityStatus !== null && !empty($category) && !empty($ingredients)) {
        // Update the sushi item
        $updateStmt = $mysqli->prepare("UPDATE Sushi_Item SET ItemName = ?, Description = ?, Price = ?, AvailabilityStatus = ?, Category = ?, Ingredients = ? WHERE ItemID = ?");
        if (!$updateStmt) {
            die("Error preparing update statement: " . $mysqli->error);
        }

        $updateStmt->bind_param('ssdissi', $itemName, $description, $price, $availabilityStatus, $category, $ingredients, $itemID);
        $updateStmt->execute();

        if ($updateStmt->affected_rows > 0) {
            // Redirect to show success message
            header("Location: manage_sushi.php?id=$itemID&message=updated");
            exit;
        } else {
            $errorMessage = "Failed to update the sushi item. No changes were made.";
        }

        $updateStmt->close();
    } else {
        $errorMessage = "Please fill out all fields correctly.";
    }
}

// Close the database connection
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

                <!-- Error message display -->
                <?php if (!empty($errorMessage)): ?>
                    <div class="alert alert-danger text-center">
                        <?php echo htmlspecialchars($errorMessage); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="./edit _sushi_item.php?id=<?php echo htmlspecialchars($itemID); ?>" class="p-4 border rounded-3 bg-light shadow-sm">
                    <div class="mb-3">
                        <label for="itemName" class="form-label">Item Name:</label>
                        <input type="text" class="form-control" name="itemName" id="itemName" value="<?php echo htmlspecialchars($sushiItem['ItemName']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description:</label>
                        <textarea class="form-control" name="description" id="description" rows="3" required><?php echo htmlspecialchars($sushiItem['Description']); ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="price" class="form-label">Price:</label>
                        <input type="number" step="0.01" class="form-control" name="price" id="price" value="<?php echo htmlspecialchars($sushiItem['Price']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="availabilityStatus" class="form-label">Availability Status:</label>
                        <select class="form-select" name="availabilityStatus" id="availabilityStatus" required>
                            <option value="1" <?php if ($sushiItem['AvailabilityStatus'] == 1) echo 'selected'; ?>>Available</option>
                            <option value="0" <?php if ($sushiItem['AvailabilityStatus'] == 0) echo 'selected'; ?>>Unavailable</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="category" class="form-label">Category:</label>
                        <input type="text" class="form-control" name="category" id="category" value="<?php echo htmlspecialchars($sushiItem['Category']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="ingredients" class="form-label">Ingredients:</label>
                        <textarea class="form-control" name="ingredients" id="ingredients" rows="3" required><?php echo htmlspecialchars($sushiItem['Ingredients']); ?></textarea>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>

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
