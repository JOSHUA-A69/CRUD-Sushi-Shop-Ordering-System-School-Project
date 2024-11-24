<?php
require_once '../controllers/SushiController.php';
require_once '../lib/logger.php';
require_once '../config/database.php';

$sushiItemController = new SushiItemController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Direct database connection for testing purposes
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    // Check connection
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    // Prepare test data
    $stmt = $mysqli->prepare("INSERT INTO sushi_item (itemName, description, price, availabilityStatus, category, ingredients) VALUES (?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        die("Prepare statement failed: " . $mysqli->error);
    }

    // Bind parameters
    $stmt->bind_param("ssdiss", 
        $_POST['itemName'],
        $_POST['description'],
        $_POST['price'],
        $_POST['availabilityStatus'],
        $_POST['category'],
        $_POST['ingredients']
    );

    // Execute the statement
    if ($stmt->execute()) {
        echo "<p>Sushi item added successfully.</p>";
    } else {
        echo "<p>Error: " . $stmt->error . "</p>";
    }

    // Close statement and connection
    $stmt->close();
    $mysqli->close();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Sushi Item</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/manage_sushi.css">
    <link rel="icon" href="../assets/images/altlogo.png" type="image/png">
</head>
<body>
    <div class="container my-5">
        <h1 class="text-center mb-4">Add New Sushi Item</h1>
        <div class="card shadow p-4">
            <form method="POST" action="add_sushi_item.php">
                <div class="mb-3">
                    <label for="itemName" class="form-label">Item Name:</label>
                    <input type="text" name="itemName" id="itemName" class="form-control" placeholder="Enter sushi item name" required>
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Description:</label>
                    <textarea name="description" id="description" class="form-control" rows="4" placeholder="Enter a brief description" required></textarea>
                </div>
                
                <div class="mb-3">
                    <label for="price" class="form-label">Price:</label>
                    <input type="number" step="0.01" name="price" id="price" class="form-control" placeholder="Enter price" required>
                </div>
                
                <div class="mb-3">
                    <label for="availabilityStatus" class="form-label">Availability Status:</label>
                    <select name="availabilityStatus" id="availabilityStatus" class="form-select" required>
                        <option value="1">Available</option>
                        <option value="0">Unavailable</option>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="category" class="form-label">Category:</label>
                    <input type="text" name="category" id="category" class="form-control" placeholder="Enter category (e.g., Nigiri, Sashimi)" required>
                </div>
                
                <div class="mb-3">
                    <label for="ingredients" class="form-label">Ingredients:</label>
                    <textarea name="ingredients" id="ingredients" class="form-control" rows="3" placeholder="Enter ingredients" required></textarea>
                </div>
                
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">Add Sushi Item</button>
                    <a href="manage_sushi.php" class="btn btn-secondary">Back to Manage Sushi Items</a>
                </div>
            </form>
        </div>
    </div>
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
