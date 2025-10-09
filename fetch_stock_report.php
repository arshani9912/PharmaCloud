<?php
include 'connect.php';

// Fetch stock data
$stmt = $conn->query("SELECT * FROM medicines ORDER BY medicine_id ASC");
$medicines = $stmt->fetchAll(PDO::FETCH_ASSOC);

$html = '<table class="table table-bordered table-striped">
<thead>
<tr>
<th>Id</th>
<th>Medicine Name</th>
<th>Brand</th>
<th>Quantity</th>
<th>Status</th>
</tr>
</thead>
<tbody>';

$i = 1;
foreach($medicines as $med){
    // Determine stock status
    if($med['quantity'] == 0){
        $status = '<span class="badge bg-danger">Out of Stock</span>';
    } elseif($med['quantity'] < 50){
        $status = '<span class="badge bg-warning text-dark">Low Stock</span>';
    } else{
        $status = '<span class="badge bg-success">Available</span>';
    }

    $html .= "<tr>
    <td>{$i}</td>
    <td>{$med['name']}</td>
    <td>{$med['brand_name']}</td>
    <td>{$med['quantity']}</td>
    <td>{$status}</td>
    </tr>";
    $i++;
}

$html .= '</tbody></table>';

echo $html;
?>
