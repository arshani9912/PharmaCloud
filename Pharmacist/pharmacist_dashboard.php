<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pharmacist Dashboard - PharmaCloud</title>

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
.main-content {
    flex: 1;
    margin-left: 240px;
    padding: 30px;
}

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
</style>
</head>
<body>

<!-- Sidebar -->
<nav class="sidebar">
  <div class="sidebar-profile">
    <h3><i class="bi bi-person-circle"></i> Pharmacist</h3>
  </div>
  <a href="pharmacist_dashboard.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
  <a href="manage_medicines.php"><i class="fas fa-pills"></i> Manage Medicines</a>
  <a href="pharmacist_sales.php"><i class="fas fa-cash-register"></i> Sales</a>
  <a href="purchases.php"><i class="fas fa-truck"></i> Purchases</a>
  <a href="suppliers.php"><i class="fas fa-users"></i> Suppliers</a>
  <a href="pharmacist_report.php"><i class="fas fa-chart-bar"></i> Reports</a>
  <a href="home.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</nav>

<!-- Main Content -->
<div class="main-content">
  <h2 class="fw-bold mb-4">Welcome, Arshani 👩‍⚕️</h2>

  <!-- Dashboard Cards -->
  <div class="row g-4">
    <div class="col-md-4">
      <div class="card">
        <h5><i class="fas fa-pills text-primary me-2"></i> Medicines</h5>
        <p class="text-muted">Track and manage stock levels.</p>
        <a href="manage_medicines.php" class="btn btn-sm btn-primary">View Medicines</a>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card">
        <h5><i class="fas fa-truck text-success me-2"></i> Purchases</h5>
        <p class="text-muted">Record supplier purchases.</p>
        <a href="purchases.php" class="btn btn-sm btn-success">Add Purchase</a>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card">
        <h5><i class="fas fa-cash-register text-warning me-2"></i> Sales</h5>
        <p class="text-muted">Process customer sales quickly.</p>
        <a href="pharmacist_sales.php" class="btn btn-sm btn-warning">New Sale</a>
      </div>
    </div>
  </div>

  <!-- Reports Section -->
  <div class="row g-4 mt-4">
    <div class="col-md-6">
      <div class="card">
        <h5><i class="fas fa-exclamation-triangle text-danger me-2"></i> Expiry Alerts</h5>
        <p class="text-muted">Medicines nearing expiry.</p>
        <a href="nearly_expire_drugs.php" class="btn btn-sm btn-danger">Check Expiry</a>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card">
        <h5><i class="fas fa-chart-bar text-info me-2"></i> Reports</h5>
        <p class="text-muted">View sales & purchase summaries.</p>
        <a href="pharmacist_report.php" class="btn btn-sm btn-info">View Reports</a>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
