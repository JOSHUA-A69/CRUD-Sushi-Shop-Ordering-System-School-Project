<?php
require_once '../config/database.php';

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

$query = "
    SELECT DATE(OrderDate) AS date, SUM(TotalPrice) AS totalRevenue
    FROM orders
    WHERE MONTH(OrderDate) = MONTH(CURRENT_DATE()) AND YEAR(OrderDate) = YEAR(CURRENT_DATE())
    GROUP BY DATE(OrderDate)
    ORDER BY DATE(OrderDate) ASC;
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
