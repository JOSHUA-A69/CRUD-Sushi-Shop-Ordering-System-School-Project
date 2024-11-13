<?php
require_once '../controllers/SushiController.php';

if (isset($_GET['id'])) {
    $itemID = $_GET['id'];
    $sushiItemController = new SushiItemController();

    // Delete the sushi item
    if ($sushiItemController->deleteSushiItem($itemID)) {
        echo "<script>alert('Sushi item deleted successfully.'); window.location.href = 'manage_sushi_items.php';</script>";
    } else {
        echo "<script>alert('Failed to delete sushi item.'); window.location.href = 'manage_sushi_items.php';</script>";
    }
} else {
    echo "Sushi item ID not specified.";
    exit;
}
?>
