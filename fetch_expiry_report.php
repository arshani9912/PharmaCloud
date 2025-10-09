<?php
include 'connect.php';

// Fetch medicines expiring in next 30 days
$stmt = $conn->query("SELECT * FROM medicines WHERE expiry_date <= DATE_ADD(CURDATE(), INTERVAL 30 DAY) ORDER BY expiry_date ASC");
$medicines = $stmt->fetchAll(PDO::FETCH_ASSOC);

$html = '<table class="table table-bordered table-striped">
<thead>
<tr>
<th>Id</th>
<th>Medicine Name</th>
<th>Brand</th>
<th>Quantity</th>
<th>Expiry Date</th>
<th>Status</th>
</tr>
</thead>
<tbody>';

$i = 1;
foreach($medicines as $med){
    $expiry = $med['expiry_date'];
    $daysLeft = (strtotime($expiry) - strtotime(date('Y-m-d'))) / (60*60*24);

    // Determine expiry status
    if($daysLeft < 0){
        $status = '<span class="badge bg-danger">Expired</span>';
    } else {
        $status = '<span class="badge bg-warning text-dark">Nearly Expired</span>';
    }

    $html .= "<tr>
    <td>{$i}</td>
    <td>{$med['name']}</td>
    <td>{$med['brand_name']}</td>
    <td>{$med['quantity']}</td>
    <td>{$expiry}</td>
    <td>{$status}</td>
    </tr>";
    $i++;
}

$html .= '</tbody></table>';

echo $html;
?>
