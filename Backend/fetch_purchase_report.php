<?php
include 'connect.php';

// Get start and end date if set
$start = isset($_POST['start_date']) && $_POST['start_date'] !== '' ? $_POST['start_date'] : null;
$end = isset($_POST['end_date']) && $_POST['end_date'] !== '' ? $_POST['end_date'] : null;

$sql = "SELECT * FROM purchases";
$params = [];

if ($start && $end) {
    $sql .= " WHERE purchase_date BETWEEN :start AND :end";
    $params = [':start'=>$start, ':end'=>$end];
}

$sql .= " ORDER BY purchase_date ASC";

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$purchases = $stmt->fetchAll(PDO::FETCH_ASSOC);

$html = '<table class="table table-bordered table-hover">';
$html .= '<thead><tr>
<th>ID</th>
<th>Supplier</th>
<th>Brand</th>
<th>Qty</th>
<th>Price</th>
<th>Total Price</th>
<th>Date</th>
</tr></thead><tbody>';

$totalQty = 0;
$totalAmount = 0;

foreach($purchases as $p){
    $html .= '<tr>';
    $html .= '<td>'.$p['id'].'</td>';
    $html .= '<td>'.htmlspecialchars($p['supplier']).'</td>';
    $html .= '<td>'.htmlspecialchars($p['brand_name']).'</td>';
    $html .= '<td>'.$p['quantity'].'</td>';
    $html .= '<td>Rs '.number_format($p['price'],2).'</td>';
    $html .= '<td>Rs '.number_format($p['total_price'],2).'</td>';
    $html .= '<td>'.$p['purchase_date'].'</td>';
    $html .= '</tr>';

    $totalQty += $p['quantity'];
    $totalAmount += $p['total_price'];
}

$html .= '</tbody></table>';

// Return JSON
header('Content-Type: application/json');
echo json_encode([
    'html' => $html,
    'totalQty' => $totalQty,
    'totalAmount' => number_format($totalAmount,2)
]);
