<?php
include 'connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard | PharmaCloud</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Poppins', sans-serif; min-height: 100vh; display: flex; background-color: #f4f6fc; margin: 0; }
    .sidebar { width: 250px; background-color: #343a40; color: #fff; display: flex; flex-direction: column; padding-top: 20px; position: fixed; top:0; bottom:0; }
    .sidebar h3 { font-weight: 600; text-align: center; padding: 20px 0; border-bottom:1px solid #444; margin-bottom: 20px; }
    .sidebar a { color: #e2e8e8ff; text-decoration: none; display:block; padding:15px 25px; margin:4px 8px; border-radius:8px; transition: background 0.3s, color 0.3s; }
    .sidebar a:hover, .sidebar a.active { background-color:#495057; color:#fff; }
    .main-content { flex:1; margin-left:250px; padding:30px; }
    h2 { margin-bottom:25px; }
    .card { border-radius:12px; border:none; }
    .btn-primary { background-color:#343a40; border:none; color:#fff; }
    .btn-primary:hover { background-color:#495057; }
    .btn-success { background-color:#28a745; border:none; color:#fff; }
    .btn-success:hover { background-color:#1e7e34; }
    .btn-warning { background-color:#fd7e14; border:none; color:#fff; }
    .btn-warning:hover { background-color:#e8590c; }
    .btn-danger { background-color:#dc3545; border:none; color:#fff; }
    .btn-danger:hover { background-color:#a71d2a; }
    table th { background-color:#212529; color:#fff; }
    .report-section { display:none; margin-top:20px; }
    .totals { margin-top:15px; }
    .totals span { font-weight:600; margin-right:15px; }
  </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
  <h3><i class="bi bi-person-circle"></i> Admin</h3>
  <a href="Admin_dashboard.php"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
  <a href="users.php"><i class="bi bi-people me-2"></i> Users</a>
  <a href="Admin_sales.php"><i class="bi bi-receipt me-2"></i> Sales</a>
  <a href="Admin_purchases.php"><i class="bi bi-basket me-2"></i> Purchases</a>
  <a href="Admin_medicines.php"><i class="bi bi-box-seam me-2"></i> Medicines</a>
  <a href="Admin_reports.php" class="active"><i class="bi bi-bar-chart-line me-2"></i> Reports</a>
  <a href="settings.php"><i class="bi bi-gear me-2"></i> Settings</a>
  <a href="home.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
</div>

<!-- Main Content -->
<div class="main-content">
  <h2 class="fw-bold">Reports</h2>

  <div class="row g-4 mb-4">
    <!-- Stock Report -->
    <div class="col-md-3">
      <div class="card shadow-sm p-4 report-card">
        <h5><i class="bi bi-box-seam text-primary me-2"></i> Stock Report</h5>
        <p class="text-muted">Current stock levels and low stock alerts.</p>
        <button class="btn btn-primary btn-sm" id="btnStockReport">Generate Stock Report</button>
      </div>
    </div>
    <!-- Sales Report -->
    <div class="col-md-3">
      <div class="card shadow-sm p-4 report-card">
        <h5><i class="bi bi-cash-stack text-success me-2"></i> Sales Report</h5>
        <p class="text-muted">Daily, weekly, monthly sales and revenue.</p>
        <button class="btn btn-success btn-sm" id="btnSalesReport">Generate Sales Report</button>
      </div>
    </div>
    <!-- Purchase Report -->
    <div class="col-md-3">
      <div class="card shadow-sm p-4 report-card">
        <h5><i class="bi bi-truck text-warning me-2"></i> Purchase Report</h5>
        <p class="text-muted">Track purchases from suppliers.</p>
        <button class="btn btn-warning btn-sm" id="btnPurchaseReport">Generate Purchase Report</button>
      </div>
    </div>
    <!-- Expiry Report -->
    <div class="col-md-3">
      <div class="card shadow-sm p-4 report-card">
        <h5><i class="bi bi-exclamation-triangle text-danger me-2"></i> Expiry Report</h5>
        <p class="text-muted">Medicines nearing expiry or expired.</p>
        <button class="btn btn-danger btn-sm" id="btnExpiryReport">Generate Expiry Report</button>
      </div>
    </div>
  </div>

  <!-- Report Sections -->
  <div id="stockReport" class="report-section card shadow-sm p-4"></div>

  <div id="salesReport" class="report-section card shadow-sm p-4">
    <div class="row mb-2">
      <div class="col-md-3"><input type="date" id="startDate" class="form-control"></div>
      <div class="col-md-3"><input type="date" id="endDate" class="form-control"></div>
      <div class="col-md-3"><button id="fetchSales" class="btn btn-success w-100">Fetch Report</button></div>
    </div>
    <div id="salesReportContent"></div>
    <div class="totals mt-3">
      <span>Total Revenue: <strong id="totalRevenue">0.00</strong></span>
      <span>Daily Total: <strong id="dailyTotal">0.00</strong></span>
      <span>Weekly Total: <strong id="weeklyTotal">0.00</strong></span>
      <span>Monthly Total: <strong id="monthlyTotal">0.00</strong></span>
    </div>
  </div>

  <div id="purchaseReport" class="report-section card shadow-sm p-4">
    <div class="row mb-2">
      <div class="col-md-3"><input type="date" id="purchaseStartDate" class="form-control"></div>
      <div class="col-md-3"><input type="date" id="purchaseEndDate" class="form-control"></div>
      <div class="col-md-3"><button id="fetchPurchase" class="btn btn-warning w-100">Fetch Report</button></div>
    </div>
    <div id="purchaseReportContent"></div>
    <div class="totals mt-3">
      <span>Total Quantity: <strong id="totalPurchaseQty">0</strong></span>
      <span>Total Amount: <strong id="totalPurchaseAmount">0.00</strong></span>
    </div>
  </div>

  <div id="expiryReport" class="report-section card shadow-sm p-4"></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function hideAllReports(){ $('.report-section').hide(); }

// Stock Report
$('#btnStockReport').click(function(){
    hideAllReports();
    $('#stockReport').show().html('<p>Loading Stock Report...</p>');
    $.post('fetch_stock_report.php', {}, function(res){
        $('#stockReport').html(res);
    });
});

// Sales Report
$('#btnSalesReport').click(function(){
    hideAllReports();
    $('#salesReport').show();
    fetchSales();
});
function fetchSales(start='', end=''){
    $.ajax({
        url: 'fetch_sales_report.php',
        method: 'POST',
        data: { start_date: start, end_date: end },
        dataType: 'json',
        success: function(res){
            $('#salesReportContent').html(res.html);
            $('#totalRevenue').text(res.totalRevenue);
            $('#dailyTotal').text(res.dailyTotal);
            $('#weeklyTotal').text(res.weeklyTotal);
            $('#monthlyTotal').text(res.monthlyTotal);
        },
        error: function(xhr){ alert(xhr.responseText); }
    });
}
$('#fetchSales').click(function(){
    let start = $('#startDate').val();
    let end = $('#endDate').val();
    if(!start || !end){ alert('Select both dates'); return; }
    fetchSales(start, end);
});

// Purchase Report
$('#btnPurchaseReport').click(function(){
    hideAllReports();
    $('#purchaseReport').show();
    fetchPurchases();
});
function fetchPurchases(start='', end=''){
    $.ajax({
        url: 'fetch_purchase_report.php',
        method: 'POST',
        data: { start_date: start, end_date: end },
        dataType: 'json',
        success: function(res){
            $('#purchaseReportContent').html(res.html);
            $('#totalPurchaseQty').text(res.totalQty);
            $('#totalPurchaseAmount').text(res.totalAmount);
        },
        error: function(xhr){ alert(xhr.responseText); }
    });
}
$('#fetchPurchase').click(function(){
    let start = $('#purchaseStartDate').val();
    let end = $('#purchaseEndDate').val();
    if(!start || !end){ alert('Select both dates'); return; }
    fetchPurchases(start, end);
});

// Expiry Report
$('#btnExpiryReport').click(function(){
    hideAllReports();
    $('#expiryReport').show().html('<p>Loading Expiry Report...</p>');
    $.post('fetch_expiry_report.php', {}, function(res){
        $('#expiryReport').html(res);
    });
});
</script>
</body>
</html>
