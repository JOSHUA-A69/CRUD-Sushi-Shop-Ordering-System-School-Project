<?php
require_once '../config/database.php';

// Connect to the database
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($mysqli->connect_error) {
    die("Database connection failed: " . $mysqli->connect_error);
}

// Query to fetch top-selling sushi items
$query = "
    SELECT s.ItemName, SUM(o.Quantity) AS TotalSold
    FROM orders AS o
    JOIN sushi_item AS s ON o.ItemID = s.ItemID
    GROUP BY o.ItemID
    ORDER BY TotalSold DESC
    LIMIT 5
";
$result = $mysqli->query($query);

// Prepare data for JSON response
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = [
        'name' => $row['ItemName'],
        'totalSold' => $row['TotalSold']
    ];
}

// Send data as JSON
header('Content-Type: application/json');
echo json_encode($data);

// Close the database connection
$mysqli->close();
?>
