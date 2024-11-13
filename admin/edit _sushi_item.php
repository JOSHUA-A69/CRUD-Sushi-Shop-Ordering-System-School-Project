<?php
ob_start(); // Start output buffering
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
            'itemname' => $name,  
            'description' => $description,
            'price' => $price,
            'availability' => $availability,
        ]);

        if ($updateSuccess) {
            // Direct PHP redirection
            header("Location: manage_sushi_item.php");
            exit();
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
            <input type="text" name="itemname" value="<?= htmlspecialchars($sushiItem['itemname']) ?>" required><br>

            <label for="description">Description:</label>
            <textarea name="description" required><?= htmlspecialchars($sushiItem['description']) ?></textarea><br>

            <label for="price">Price:</label>
            <input type="number" name="price" step="0.01" value="<?= htmlspecialchars($sushiItem['price']) ?>" required><br>

            <label for="availability">Available:</label>
            <input type="checkbox" name="availabilitystatus" <?= $sushiItem['availability'] ? 'checked' : '' ?>><br>

            <input type="submit" value="Update">
        </form>
    <?php else: ?>
        <p>Sushi item not found.</p>
    <?php endif; ?>
</body>
</html>
