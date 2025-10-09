<?php
include 'connect.php';
header('Content-Type: application/json');

// Get top 5 selling medicines
$stmt = $conn->query("
    SELECT medicine_name, SUM(quantity) as total_sold 
    FROM sales 
    GROUP BY medicine_name 
    ORDER BY total_sold DESC 
    LIMIT 5
");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$labels = [];
$data = [];
foreach($rows as $row){
    $labels[] = $row['medicine_name'];
    $data[] = (int)$row['total_sold'];
}

echo json_encode(['labels'=>$labels, 'data'=>$data]);
?>
