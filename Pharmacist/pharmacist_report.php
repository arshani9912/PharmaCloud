<?php 
include 'connect.php';
session_start();

// Example: pharmacist name from session
$pharmacist_name = isset($_SESSION['pharmacist_name']) ? $_SESSION['pharmacist_name'] : 'Pharmacist';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pharmacist Reports | PharmaCloud</title>

<!-- Bootstrap & Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

<style>
body { font-family: 'Poppins', sans-serif; display: flex; min-height: 100vh; margin: 0; background-color: #f4f6f8; }

/* Sidebar */
.sidebar {
    width: 240px;
    background-color: #0d6efd;
    color: #fff;
    display: flex;
    flex-direction: column;
    padding-top: 30px;
    position: fixed;
    height: 100vh;
}
.sidebar-profile { text-align: center; margin-bottom: 30px; }
.sidebar-profile h3 { color: #fff; margin: 0; padding: 20px 0; font-weight: 600; }
.sidebar a {
    color: #fff;
    text-decoration: none;
    display: flex;
    align-items: center;
    padding: 12px 20px;
    border-radius: 8px;
    margin-bottom: 5px;
    transition: 0.3s;
}
.sidebar a i { margin-right: 10px; min-width: 20px; text-align: center; }
.sidebar a:hover, .sidebar a.active { background: rgba(255,255,255,0.2); }

/* Main Content */
.main-content { flex: 1; margin-left: 240px; padding: 30px; }

/* Cards */
.card { border-radius: 15px; margin-bottom: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); padding: 20px; transition: 0.3s; }
.card:hover { transform: translateY(-5px); box-shadow: 0 6px 20px rgba(0,0,0,0.1); }

/* Buttons */
.btn-primary { background-color: #0d6efd; border: none; color: #fff; }
.btn-primary:hover { background-color: #0b5ed7; }
.btn-success { background-color: #198754; border: none; color: #fff; }
.btn-success:hover { background-color: #157347; }
.btn-warning { background-color: #ffc107; border: none; color: #000; }
.btn-warning:hover { background-color: #e0a800; }
.btn-danger { background-color: #dc3545; border: none; color: #fff; }
.btn-danger:hover { background-color: #b02a37; }
.btn-info { background-color: #0dcaf0; border: none; color: #000; }
.btn-info:hover { background-color: #31d2f2; }

table th { background-color:#212529; color:#fff; }
.report-section { display:none; margin-top:20px; }
.totals { margin-top:15px; }
.totals span { font-weight:600; margin-right:15px; }

@media (max-width:768px){
    .main-content { margin-left:0; padding:15px; }
    .sidebar { width:100%; height:auto; position:relative; }
}
</style>
</head>
<body>

<!-- Sidebar -->
<nav class="sidebar">
  <div class="sidebar-profile">
    <h3><i class="bi bi-person-circle"></i> <?= htmlspecialchars($pharmacist_name) ?></h3>
  </div>
  <a href="pharmacist_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
  <a href="manage_medicines.php"><i class="fas fa-pills"></i> Manage Medicines</a>
  <a href="pharmacist_sales.php"><i class="fas fa-cash-register"></i> Sales</a>
  <a href="purchases.php"><i class="fas fa-truck"></i> Purchases</a>
  <a href="suppliers.php"><i class="fas fa-users"></i> Suppliers</a>
  <a href="pharmacist_report.php" class="active"><i class="fas fa-chart-bar"></i> Reports</a>
  <a href="home.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</nav>

<!-- Main Content -->
<div class="main-content">
  <h2 class="fw-bold mb-4">Reports</h2>

  <div class="row g-4 mb-4">
    <!-- Stock Report -->
    <div class="col-md-3">
      <div class="card text-center">
        <h5><i class="bi bi-box-seam text-primary me-2"></i> Stock Report</h5>
        <p class="text-muted">Current stock levels & low stock alerts.</p>
        <button class="btn btn-primary btn-sm w-100" id="btnStockReport">Generate Stock Report</button>
      </div>
    </div>
    <!-- Sales Report -->
    <div class="col-md-3">
      <div class="card text-center">
        <h5><i class="bi bi-cash-stack text-success me-2"></i> Sales Report</h5>
        <p class="text-muted">Daily, weekly, monthly sales & revenue.</p>
        <button class="btn btn-success btn-sm w-100" id="btnSalesReport">Generate Sales Report</button>
      </div>
    </div>
    <!-- Purchase Report -->
    <div class="col-md-3">
      <div class="card text-center">
        <h5><i class="bi bi-truck text-warning me-2"></i> Purchase Report</h5>
        <p class="text-muted">Track purchases from suppliers.</p>
        <button class="btn btn-warning btn-sm w-100" id="btnPurchaseReport">Generate Purchase Report</button>
      </div>
    </div>
    <!-- Expiry Report -->
    <div class="col-md-3">
      <div class="card text-center">
        <h5><i class="bi bi-exclamation-triangle text-danger me-2"></i> Expiry Report</h5>
        <p class="text-muted">Medicines nearing expiry or expired.</p>
        <button class="btn btn-danger btn-sm w-100" id="btnExpiryReport">Generate Expiry Report</button>
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
    $.post('fetch_stock_report.php', {}, function(res){ $('#stockReport').html(res); });
});

// Sales Report
$('#btnSalesReport').click(function(){ hideAllReports(); $('#salesReport').show(); fetchSales(); });
function fetchSales(start='', end=''){
    $.ajax({
        url:'fetch_sales_report.php',
        method:'POST',
        data:{ start_date:start, end_date:end },
        dataType:'json',
        success:function(res){
            $('#salesReportContent').html(res.html);
            $('#totalRevenue').text(res.totalRevenue);
            $('#dailyTotal').text(res.dailyTotal);
            $('#weeklyTotal').text(res.weeklyTotal);
            $('#monthlyTotal').text(res.monthlyTotal);
        },
        error:function(xhr){ alert(xhr.responseText); }
    });
}
$('#fetchSales').click(function(){
    let start=$('#startDate').val(), end=$('#endDate').val();
    if(!start || !end){ alert('Select both dates'); return; }
    fetchSales(start,end);
});

// Purchase Report
$('#btnPurchaseReport').click(function(){ hideAllReports(); $('#purchaseReport').show(); fetchPurchases(); });
function fetchPurchases(start='', end=''){
    $.ajax({
        url:'fetch_purchase_report.php',
        method:'POST',
        data:{ start_date:start, end_date:end },
        dataType:'json',
        success:function(res){
            $('#purchaseReportContent').html(res.html);
            $('#totalPurchaseQty').text(res.totalQty);
            $('#totalPurchaseAmount').text(res.totalAmount);
        },
        error:function(xhr){ alert(xhr.responseText); }
    });
}
$('#fetchPurchase').click(function(){
    let start=$('#purchaseStartDate').val(), end=$('#purchaseEndDate').val();
    if(!start || !end){ alert('Select both dates'); return; }
    fetchPurchases(start,end);
});

// Expiry Report
$('#btnExpiryReport').click(function(){ hideAllReports(); $('#expiryReport').show().html('<p>Loading Expiry Report...</p>'); $.post('fetch_expiry_report.php', {}, function(res){ $('#expiryReport').html(res); }); });
</script>
</body>
</html>
