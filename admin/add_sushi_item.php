<?php
require_once '../controllers/SushiController.php';
require_once '../lib/logger.php';

$sushiItemController = new SushiItemController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newSushiData = [
        'itemName' => $_POST['itemName'],
        'description' => $_POST['description'],
        'price' => $_POST['price'],
        'availabilityStatus' => $_POST['availabilityStatus'],
        'category' => $_POST['category'],
        'ingredients' => $_POST['ingredients']
    ];

    $sushiItemController->addSushiItem($newSushiData);
    header("Location: manage_sushi.php?success=1");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Sushi Item</title>
    <link rel="stylesheet" href="styles/admin.css">
</head>
<body>
    <div class="admin-container">
        <h1>Add New Sushi Item</h1>
     <form method="POST" action="add_sushi_item.php">
    <label for="itemName">Item Name:</label>
    <input type="text" name="itemName" id="itemName" required>

    <label for="description">Description:</label>
    <textarea name="description" id="description" required></textarea>

    <label for="price">Price:</label>
    <input type="number" step="0.01" name="price" id="price" required>

    <label for="availabilityStatus">Availability Status:</label>
    <select name="availabilityStatus" id="availabilityStatus" required>
        <option value="1">Available</option>
        <option value="0">Unavailable</option>
    </select>

    <label for="category">Category:</label>
    <input type="text" name="category" id="category" required>

    <label for="ingredients">Ingredients:</label>
    <textarea name="ingredients" id="ingredients" required></textarea>

    <button type="submit">Add Sushi Item</button>
    </form>

        <a href="manage_sushi.php" class="back-link">Back to Manage Sushi Items</a>
    </div>
</body>
</html>