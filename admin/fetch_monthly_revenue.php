<?php
require_once '../config/database.php';

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

$query = "
    SELECT DATE(OrderTime) AS date, SUM(TotalPrice) AS totalRevenue
    FROM Orders
    WHERE MONTH(OrderTime) = MONTH(CURRENT_DATE()) AND YEAR(OrderTime) = YEAR(CURRENT_DATE())
    GROUP BY DATE(OrderTime)
    ORDER BY DATE(OrderTime) ASC;
";


$result = $mysqli->query($query);

$revenueData = [];
while ($row = $result->fetch_assoc()) {
    $revenueData[] = [
        'date' => $row['date'],
        'totalRevenue' => $row['totalRevenue']
    ];
}

header('Content-Type: application/json');
echo json_encode($revenueData);
?>
