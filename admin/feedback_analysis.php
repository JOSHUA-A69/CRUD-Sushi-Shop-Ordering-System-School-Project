<?php
require_once '../config/database.php';

header('Content-Type: application/json');

// Connect to the database
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($mysqli->connect_error) {
    die(json_encode(['error' => 'Database connection failed: ' . $mysqli->connect_error]));
}

// SQL query for feedback analysis
$sql = "
    SELECT
        SUM(CASE
            WHEN Feedback LIKE '%good%' OR Feedback LIKE '%great%' OR Feedback LIKE '%delicious%' OR Feedback LIKE '%satisfied%' OR Feedback LIKE '%excellent%' THEN 1
            ELSE 0
        END) AS PositiveFeedback,
        SUM(CASE
            WHEN Feedback LIKE '%bad%' OR Feedback LIKE '%poor%' OR Feedback LIKE '%slow%' OR Feedback LIKE '%terrible%' OR Feedback LIKE '%unsatisfied%' THEN 1
            ELSE 0
        END) AS NegativeFeedback
    FROM Orders
    WHERE Feedback IS NOT NULL;
";

// Execute the query
$result = $mysqli->query($sql);

// Check if query executed successfully
if (!$result) {
    echo json_encode(['error' => 'SQL Error: ' . $mysqli->error]);
    exit;
}

// Fetch the data
$data = $result->fetch_assoc();

// Return the data as JSON
echo json_encode($data);

// Close the connection
$mysqli->close();
?>
