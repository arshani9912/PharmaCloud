<?php
session_start();
include 'connect.php';
header('Content-Type: application/json');

$from = $_POST['from'] ?? '';
$to = $_POST['to'] ?? '';

if(!$from || !$to){
    echo json_encode(['status'=>'error','message'=>'Invalid dates']);
    exit;
}

try {
    // Fetch sales in date range
    $stmt = $conn->prepare("SELECT s.*, SUM(si.total_price) as total_sale 
                            FROM sales s 
                            JOIN sale_items si ON s.sale_id = si.sale_id 
                            WHERE s.sale_date BETWEEN :from AND :to
                            GROUP BY s.sale_id ORDER BY s.sale_date ASC");
    $stmt->execute([':from'=>$from.' 00:00:00', ':to'=>$to.' 23:59:59']);
    $sales = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $html = '<table class="table table-bordered table-hover">';
    $html .= '<thead><tr><th>Sale ID</th><th>Patient</th><th>Date</th><th>Total Amount</th></tr></thead><tbody>';

    $total = 0;
    foreach($sales as $s){
        $html .= "<tr>
                    <td>{$s['sale_id']}</td>
                    <td>{$s['patient_name']}</td>
                    <td>".date('d-m-Y H:i', strtotime($s['sale_date']))."</td>
                    <td>".number_format($s['total_sale'],2)."</td>
                  </tr>";
        $total += $s['total_sale'];
    }

    $html .= '</tbody></table>';

    // Compute summary
    $dailyStmt = $conn->prepare("SELECT SUM(total_amount) as daily_total FROM sales WHERE DATE(sale_date) = CURDATE()");
    $dailyStmt->execute(); $daily_total = (float)$dailyStmt->fetchColumn();

    $weeklyStmt = $conn->prepare("SELECT SUM(total_amount) as weekly_total FROM sales WHERE YEARWEEK(sale_date,1) = YEARWEEK(CURDATE(),1)");
    $weeklyStmt->execute(); $weekly_total = (float)$weeklyStmt->fetchColumn();

    $monthlyStmt = $conn->prepare("SELECT SUM(total_amount) as monthly_total FROM sales WHERE MONTH(sale_date) = MONTH(CURDATE()) AND YEAR(sale_date) = YEAR(CURDATE())");
    $monthlyStmt->execute(); $monthly_total = (float)$monthlyStmt->fetchColumn();

    echo json_encode([
        'status'=>'success',
        'html'=>$html,
        'total'=>$total,
        'daily_total'=>$daily_total,
        'weekly_total'=>$weekly_total,
        'monthly_total'=>$monthly_total
    ]);

} catch(Exception $e){
    echo json_encode(['status'=>'error','message'=>$e->getMessage()]);
}
