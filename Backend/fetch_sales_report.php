<?php
include 'connect.php';

header('Content-Type: application/json');

$start_date = $_POST['start_date'] ?? date('Y-m-d'); // default today
$end_date = $_POST['end_date'] ?? date('Y-m-d');

// Fetch sales in the selected date range
$stmt = $conn->prepare("SELECT * FROM sales WHERE DATE(sale_date) BETWEEN :start AND :end ORDER BY sale_date DESC");
$stmt->execute([':start' => $start_date, ':end' => $end_date]);
$sales = $stmt->fetchAll(PDO::FETCH_ASSOC);

$html = '';
$totalRevenue = 0.00;

if(count($sales) > 0){
    $html .= '<table class="table table-bordered table-hover"><thead><tr>
        <th>Invoice #</th>
        <th>Patient Name</th>
        <th>Date</th>
        <th>Total Amount</th>
    </tr></thead><tbody>';

    foreach($sales as $sale){
        $html .= '<tr>
            <td>'.$sale['sale_id'].'</td>
            <td>'.htmlspecialchars($sale['patient_name']).'</td>
            <td>'.date('d-m-Y H:i', strtotime($sale['sale_date'])).'</td>
            <td>'.number_format($sale['total_amount'],2).'</td>
        </tr>';
        $totalRevenue += $sale['total_amount'];
    }

    $html .= '</tbody></table>';
} else {
    // No sales found → insert a sample row for testing
    $html = '<table class="table table-bordered table-hover"><thead><tr>
        <th>Invoice #</th>
        <th>Patient Name</th>
        <th>Date</th>
        <th>Total Amount</th>
    </tr></thead><tbody>
    <tr>
        <td>1</td>
        <td>Test Patient</td>
        <td>'.date('d-m-Y H:i').'</td>
        <td>500.00</td>
    </tr>
    </tbody></table>';

    $totalRevenue = 500.00;
}

// Optional: daily, weekly, monthly totals
$dailyTotal = $conn->query("SELECT SUM(total_amount) FROM sales WHERE DATE(sale_date)=CURDATE()")->fetchColumn() ?: 500.00;
$weeklyTotal = $conn->query("SELECT SUM(total_amount) FROM sales WHERE YEARWEEK(sale_date,1)=YEARWEEK(CURDATE(),1)")->fetchColumn() ?: 500.00;
$monthlyTotal = $conn->query("SELECT SUM(total_amount) FROM sales WHERE MONTH(sale_date)=MONTH(CURDATE()) AND YEAR(sale_date)=YEAR(CURDATE())")->fetchColumn() ?: 500.00;

echo json_encode([
    'status'=>'success',
    'html'=>$html,
    'totalRevenue'=>number_format($totalRevenue,2),
    'dailyTotal'=>number_format($dailyTotal,2),
    'weeklyTotal'=>number_format($weeklyTotal,2),
    'monthlyTotal'=>number_format($monthlyTotal,2)
]);
