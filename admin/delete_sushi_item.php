<?php
require_once '../controllers/SushiController.php';

if (isset($_GET['id'])) {
    $itemID = $_GET['id'];
    $sushiItemController = new SushiItemController();

    // Check if confirmation parameter is set
    if (isset($_GET['confirm']) && $_GET['confirm'] === 'yes') {
        // Delete the sushi item
        if ($sushiItemController->deleteSushiItem($itemID)) {
            // Set a success message and redirect
            header("Location: manage_sushi_items.php?message=success");
            exit;
        } else {
            // Set an error message and redirect
            header("Location: manage_sushi_items.php?message=error");
            exit;
        }
    } else {
        // Prompt for confirmation by redirecting to a confirmation page
        echo "Are you sure you want to delete this sushi item? ";
        echo "<a href='manage_sushi.php?id=$itemID&confirm=yes'>Yes</a> | ";
        echo "<a href='manage_sushi.php'>No</a>";
        exit;
    }
} else {
    echo "Sushi item ID not specified.";
    exit;
}
?>
