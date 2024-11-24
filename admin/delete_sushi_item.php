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
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }

    // Close statement and connection
    $stmt->close();
    $mysqli->close();
} elseif (isset($_GET['id'])) {
    // Get the itemID
    $itemID = $_GET['id'];
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Delete Sushi Item</title>
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="icon" href="../assets/images/altlogo.png" type="image/png">
    </head>
    <body>
        <div class="container mt-5">
            <div class="card shadow">
                <div class="card-header text-danger">
                    <h3>Delete Confirmation</h3>
                </div>
                <div class="card-body">
                    <p>Are you sure you want to delete this sushi item?</p>
                    <form method="POST" action="delete_sushi_item.php">
                        <input type="hidden" name="itemID" value="<?php echo htmlspecialchars($itemID); ?>">
                        <button type="submit" class="btn btn-danger">Yes, Delete</button>
                        <a href="manage_sushi.php" class="btn btn-secondary">No, Go Back</a>
                    </form>
                </div>
            </div>
        </div>
        <!-- Bootstrap JS Bundle -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
    <?php
} else {
    echo "<div class='container mt-5'><div class='alert alert-warning'>Sushi item ID not specified.</div></div>";
}

ob_end_flush();
?>

