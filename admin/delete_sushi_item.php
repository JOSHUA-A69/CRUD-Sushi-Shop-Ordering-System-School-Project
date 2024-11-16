<?php
// Start output buffering
ob_start();

require_once '../lib/logger.php';
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Database connection details
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    // Check connection
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    // Get the itemID from POST data
    $itemID = $_POST['itemID'];

    // Prepare the DELETE statement
    $stmt = $mysqli->prepare("DELETE FROM sushi_item WHERE itemID = ?");
    if (!$stmt) {
        die("Prepare statement failed: " . $mysqli->error);
    }

    // Bind the itemID parameter
    $stmt->bind_param("i", $itemID);

    // Execute the statement
    if ($stmt->execute()) {
        header("Location: manage_sushi.php?message=deleted");
        exit;
    } else {
        echo "<p>Error: " . $stmt->error . "</p>";
    }

    // Close statement and connection
    $stmt->close();
    $mysqli->close();
} elseif (isset($_GET['id'])) {
    // Show confirmation if itemID is passed in GET request
    $itemID = $_GET['id'];
    echo "<p>Are you sure you want to delete this sushi item?</p>";
    echo "<form method='POST' action='delete_sushi_item.php'>";
    echo "<input type='hidden' name='itemID' value='$itemID'>";
    echo "<button type='submit'>Yes</button>";
    echo "<a href='manage_sushi.php'>No</a>";
    echo "</form>";
} else {
    echo "<p>Sushi item ID not specified.</p>";
}

ob_end_flush();
?>
