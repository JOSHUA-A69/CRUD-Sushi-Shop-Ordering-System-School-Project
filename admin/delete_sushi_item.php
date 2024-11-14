<?php
ob_start();
require_once '../controllers/SushiController.php';


if (isset($_GET['id'])) {
    $itemID = $_GET['id'];
    $sushiItemController = new SushiItemController();

    if (isset($_GET['confirm']) && $_GET['confirm'] === 'yes') {
        if ($sushiItemController->deleteSushiItem($itemID)) {
            header("Location: manage_sushi_item.php?message=success");
            exit;
        } else {
            header("Location: manage_sushi_item.php?message=error");
            exit;
        }
    } else {
        echo "Are you sure you want to delete this sushi item? ";
        echo "<a href='../admin/manage_sushi.php?id=$itemID&confirm=yes'>Yes</a> | ";
        echo "<a href='../admin/manage_sushi.php'>No</a>";
        exit;
    }
} else {
    echo "Sushi item ID not specified.";
    exit;
}

ob_end_flush();
?>
