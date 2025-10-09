<?php
include "connect.php"; // Ensure your DB connection is correct
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard - PharmaCloud</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
body { font-family: 'Poppins', sans-serif; background-color: #f4f6fc; display: flex; min-height: 100vh; margin: 0; }
/* Sidebar */
.sidebar { width: 250px; background-color: #343a40; color: #fff; display: flex; flex-direction: column; position: fixed; top:0; bottom:0; padding-top: 20px; }
.sidebar h3 { text-align: center; font-weight: 600; padding: 20px 0; border-bottom: 1px solid #444; }
.sidebar a { color: #e2e8e8ff; text-decoration: none; display:block; padding:15px 25px; font-size:1rem; margin:4px 8px; border-radius:8px; transition: background 0.3s, color 0.3s; }
.sidebar a:hover, .sidebar a.active { background-color:#495057; color:#fff; }

/* Main Content */
.content { flex:1; margin-left:250px; padding:30px; }

/* Topbar */
.topbar { display:flex; justify-content:space-between; align-items:center; margin-bottom:25px; }
.topbar h2 { margin:0; font-weight:600; }

/* Cards */
.card { border-radius:12px; border:none; padding:20px; }
.card-box { border-radius:12px; padding:20px; color:#fff; text-align:center; }
.card-box h5 { margin-bottom:10px; }
.bg-blue { background: linear-gradient(45deg,#4a6cf7,#203e9d); }
.bg-green { background: linear-gradient(45deg,#28a745,#1e7e34); }
.bg-orange { background: linear-gradient(45deg,#fd7e14,#e8590c); }
.bg-red { background: linear-gradient(45deg,#dc3545,#a71d2a); }

/* Tables */
.table-hover tbody tr:hover { background-color: #f1f1f1; }
.table th { background-color:#343a40; color:#fff; }
.table-responsive { max-height:400px; overflow-y:auto; }
</style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
  <h3><i class="bi bi-person-circle"></i> Admin</h3>
  <a href="Admin_dashboard.php" class="active"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
  <a href="users.php"><i class="bi bi-people me-2"></i> Users</a>
  <a href="Admin_sales.php"><i class="bi bi-receipt me-2"></i> Sales</a>
  <a href="Admin_purchases.php"><i class="bi bi-basket me-2"></i> Purchases</a>
  <a href="Admin_medicines.php"><i class="bi bi-box-seam me-2"></i> Medicines</a>
  <a href="Admin_reports.php"><i class="bi bi-bar-chart-line me-2"></i> Reports</a>
  <a href="settings.php"><i class="bi bi-gear me-2"></i> Settings</a>
  <a href="home.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
</div>

<!-- Main Content -->
<div class="content">

  <!-- Topbar -->
  <div class="topbar">
    <h2><i class="bi bi-speedometer2 me-2"></i> Dashboard</h2>
    <button class="btn btn-primary"><i class="bi bi-bell"></i> Notifications</button>
  </div>

  <!-- Summary Cards -->
  <div class="row g-4 mb-4">
    <div class="col-md-3">
      <div class="card-box bg-blue shadow-sm">
        <h5>Total Users</h5>
        <h2>
          <?php 
          $stmt = $conn->query("SELECT COUNT(*) as count FROM users");
          $row = $stmt->fetch(PDO::FETCH_ASSOC);
          echo $row['count']; 
          ?>
        </h2>
        <i class="bi bi-people fs-1"></i>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card-box bg-green shadow-sm">
        <h5>Total Medicines</h5>
        <h2>
          <?php 
          $stmt = $conn->query("SELECT COUNT(*) as count FROM medicines");
          $row = $stmt->fetch(PDO::FETCH_ASSOC);
          echo $row['count']; 
          ?>
        </h2>
        <i class="bi bi-box-seam fs-1"></i>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card-box bg-orange shadow-sm">
        <h5>Low Stock</h5>
        <h2>
          <?php 
          $stmt = $conn->query("SELECT COUNT(*) as count FROM medicines WHERE quantity < 50 AND quantity > 0");
          $row = $stmt->fetch(PDO::FETCH_ASSOC);
          echo $row['count']; 
          ?>
        </h2>
        <i class="bi bi-exclamation-triangle fs-1"></i>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card-box bg-red shadow-sm">
        <h5>Nearly Expired</h5>
        <h2>
          <?php 
          $stmt = $conn->query("SELECT COUNT(*) as count FROM medicines WHERE expiry_date <= DATE_ADD(CURDATE(), INTERVAL 30 DAY)");
          $row = $stmt->fetch(PDO::FETCH_ASSOC);
          echo $row['count']; 
          ?>
        </h2>
        <i class="bi bi-exclamation-octagon fs-1"></i>
      </div>
    </div>
  </div>

  <!-- Charts & Recent Purchases -->
  <div class="row g-4">
    <!-- Top Selling Medicines Chart -->
    <div class="col-lg-6">
      <div class="card shadow-sm p-4">
        <h5>Top Selling Medicines</h5>
        <canvas id="topMedicinesChart" class="mt-3"></canvas>
      </div>
    </div>

    <!-- Recent Purchases Table -->
    <div class="col-lg-6">
      <div class="card shadow-sm p-4">
        <h5>Recent Purchases</h5>
        <div class="table-responsive">
          <table class="table table-hover mt-3">
            <thead>
              <tr>
                <th>ID</th>
                <th>Supplier</th>
                <th>Brand</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Total</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody id="recentPurchasesBody">
              <!-- AJAX loaded -->
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function loadRecentPurchases() {
    $.ajax({
        url: 'fetch_recent_purchases.php',
        method: 'GET',
        success: function(data) {
            $('#recentPurchasesBody').html(data.html);
        },
        error: function(xhr) {
            $('#recentPurchasesBody').html('<tr><td colspan="7" class="text-danger text-center">Failed to load data</td></tr>');
        }
    });
}
loadRecentPurchases();
setInterval(loadRecentPurchases, 15000); // refresh every 15s
</script>

<script>
const ctx = document.getElementById('topMedicinesChart').getContext('2d');
new Chart(ctx, {
  type: 'doughnut',
  data: {
    labels: ['Paracetamol','Amoxicillin','Ibuprofen','Vitamin C','Cetirizine'],
    datasets: [{
      data: [120, 95, 80, 75, 60],
      backgroundColor: ['#4a6cf7','#28a745','#fd7e14','#ffc107','#dc3545']
    }]
  },
  options: { responsive:true, plugins:{ legend:{ position:'bottom' } } }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
