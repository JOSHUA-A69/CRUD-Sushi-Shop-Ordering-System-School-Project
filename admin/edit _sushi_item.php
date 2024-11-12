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
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $availability = isset($_POST['availability']) ? 1 : 0;

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
    <link rel="stylesheet" href="styles/admin.css">
</head>
<body>
    <div class="admin-container">
        <h1>Edit Sushi Item</h1>

        <?php if ($sushiItem): ?>
            <form method="post" action="">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?= $sushiItem['name'] ?>" required>

                <label for="description">Description:</label>
                <textarea id="description" name="description" required><?= $sushiItem['description'] ?></textarea>

                <label for="price">Price:</label>
                <input type="number" id="price" name="price" value="<?= $sushiItem['price'] ?>" required>

                <label for="availability">Availability:</label>
                <input type="checkbox" id="availability" name="availability" <?= $sushiItem['availability'] ? 'checked' : '' ?>>

                <button type="submit">Update Item</button>
            </form>
        <?php else: ?>
            <p>Sushi item not found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
