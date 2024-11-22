<?php
require_once '../config/database.php';

header('Content-Type: application/json');

// Connect to the database using MySQLi
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check for connection errors
if ($mysqli->connect_error) {
    echo json_encode(['error' => 'Database connection failed: ' . $mysqli->connect_error]);
    exit;  // Stop execution if connection fails
}

// SQL query to get top-selling sushi items
$sql = "SELECT 
            Sushi_Item.ItemName, 
            Sushi_Item.Description, 
            COALESCE(Sushi_Item.Price, 0) AS Price, 
            SUM(Orders.Quantity) AS TotalSold
        FROM Sushi_Item
        JOIN Orders ON Sushi_Item.ItemID = Orders.ItemID
        GROUP BY Sushi_Item.ItemID
        ORDER BY TotalSold DESC
        LIMIT 5";

// Execute the query and check for errors
$result = $mysqli->query($sql);

// Error handling for the query execution
if (!$result) {
    echo json_encode(['error' => 'SQL Error: ' . $mysqli->error]);
    $mysqli->close();  // Close connection before exiting
    exit;
}

// Check if the query returned any rows
if ($result->num_rows > 0) {
    // Fetch all rows into an associative array
    $topSushiItems = $result->fetch_all(MYSQLI_ASSOC);
    
    // Return the result as JSON
    echo json_encode($topSushiItems);
} else {
    // No top-selling sushi items found
    echo json_encode(['message' => 'No top-selling sushi items found']);
}

// Close the database connection
$mysqli->close();
?>
