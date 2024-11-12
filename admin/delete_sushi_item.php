<?php
require_once '../controllers/SushiController.php';

// Check if 'id' parameter is set in the URL
if (isset($_GET['id'])) {
    $itemID = $_GET['id'];
    $sushiItemController = new SushiItemController();

    // Attempt to delete the sushi item
    $deleteSuccess = $sushiItemController->deleteSushiItem($itemID);

    if ($deleteSuccess) {
        echo "<script>alert('Sushi item deleted successfully.'); window.location.href = 'manage_sushi_items.php';</script>";
    } else {
        echo "<script>alert('Failed to delete sushi item.'); window.location.href = 'manage_sushi_items.php';</script>";
    }
} else {
    echo "Sushi item ID not specified.";
}