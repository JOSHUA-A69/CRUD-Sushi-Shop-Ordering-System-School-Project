<?php
require_once '../controllers/SushiController.php';

// Check if 'id' parameter is set in the URL
if (isset($_GET['id'])) {
    $itemID = $_GET['id'];
    $sushiItemController = new SushiItemController();

    // Fetch the existing item details
    $sushiItem = $sushiItemController->getSushiItemById($itemID);

    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['itemname'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $availability = isset($_POST['availabilitystatus']) ? 1 : 0;

        // Update the sushi item in the database
        $updateSuccess = $sushiItemController->updateSushiItem($itemID, [
            'name' => $name,
            'description' => $description,
            'price' => $price,
            'availability' => $availability,
        ]);

        if ($updateSuccess) {
            echo "<script>alert('Sushi item updated successfully.'); window.location.href = 'manage_sushi_items.php';</script>";
        } else {
            echo "<script>alert('Failed to update sushi item.');</script>";
        }
    }
} else {
    echo "Sushi item ID not specified.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Sushi Item</title>
</head>
<body>
    <?php if ($sushiItem): ?>
        <h1>Edit Sushi Item</h1>
        <form method="POST">
            <label for="name">Name:</label>
            <input type="text" name="name" value="<?= $sushiItem['name'] ?>" required><br>

            <label for="description">Description:</label>
            <textarea name="description" required><?= $sushiItem['description'] ?></textarea><br>

            <label for="price">Price:</label>
            <input type="number" name="price" step="0.01" value="<?= $sushiItem['price'] ?>" required><br>

            <label for="availability">Available:</label>
            <input type="checkbox" name="availability" <?= $sushiItem['availability'] ? 'checked' : '' ?>><br>

            <input type="submit" value="Update">
        </form>
    <?php else: ?>
        <p>Sushi item not found.</p>
    <?php endif; ?>
</body>
</html>
