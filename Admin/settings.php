<?php
// settings.php
session_start();
include "connect.php"; // Make sure your DB connection works

// Example: Fetch admin info from database
// $stmt = $conn->prepare("SELECT fullname, email, phone FROM admins WHERE id=?");
// $stmt->execute([$_SESSION['admin_id']]);
// $admin = $stmt->fetch(PDO::FETCH_ASSOC);
$admin = [
    'fullname' => 'Admin User',
    'email' => 'admin@pharmacloud.com',
    'phone' => '+94 71 123 4567',
    'pharmacy_name' => 'PharmaCloud Pharmacy',
    'currency' => 'LKR',
    'invoice_prefix' => 'PHARM-'
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Settings - PharmaCloud</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<style>
body {
  font-family: 'Poppins', sans-serif;
  min-height: 100vh;
  display: flex;
  background-color: #f4f6fc;
  margin: 0;
}

/* Sidebar */
.sidebar {
  width: 250px;
  background-color: #343a40;
  color: #fff;
  display: flex;
  flex-direction: column;
  padding-top: 20px;
  position: fixed;
  top: 0;
  left: 0;
  bottom: 0;
}

.sidebar h3 {
  text-align: center;
  padding: 20px 0;
  border-bottom: 1px solid #444;
  font-weight: 600;
  margin-bottom: 20px;
}

.sidebar a {
  color: #e2e8e8ff;
  text-decoration: none;
  display: block;
  padding: 15px 25px;
  font-size: 1rem;
  margin: 4px 8px;
  border-radius: 8px;
  transition: background 0.3s, color 0.3s;
}

.sidebar a:hover,
.sidebar a.active {
  background-color: #495057;
  color: #fff;
}

/* Main Content */
.content {
  flex: 1;
  margin-left: 250px;
  padding: 30px;
}

h2 {
  margin-bottom: 25px;
}

/* Tab Content */
.tab-content {
  background: #fff;
  padding: 25px;
  border-radius: 12px;
  box-shadow: 0 3px 12px rgba(0,0,0,0.1);
}

.nav-tabs .nav-link {
  border: none;
  border-bottom: 3px solid transparent;
  font-weight: 500;
  color: #495057;
}

.nav-tabs .nav-link.active {
  border-bottom: 3px solid #0d6efd;
  font-weight: 600;
  color: #0d6efd;
}

/* Buttons */
.btn-primary {
  background-color: #343a40;
  border: none;
  color: #fff;
}
.btn-primary:hover { background-color: #495057; }

.btn-success {
  background-color: #28a745;
  border: none;
  color: #fff;
}
.btn-success:hover { background-color: #1e7e34; }

.btn-danger {
  background-color: #dc3545;
  border: none;
  color: #fff;
}
.btn-danger:hover { background-color: #a71d2a; }

.form-control, .form-select {
  border-radius: 6px;
  box-shadow: none;
}

</style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
  <h3><i class="bi bi-person-circle"></i> Admin</h3>
  <a href="Admin_dashboard.php"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
  <a href="users.php"><i class="bi bi-people me-2"></i> Users</a>
  <a href="sales.php"><i class="bi bi-receipt me-2"></i> Sales</a>
  <a href="Admin_orders.php"><i class="bi bi-basket me-2"></i> Purchases</a>
  <a href="Admin_medicines.php"><i class="bi bi-box-seam me-2"></i> Medicines</a>
  <a href="Admin_reports.php"><i class="bi bi-bar-chart-line me-2"></i> Reports</a>
  <a href="settings.php" class="active"><i class="bi bi-gear me-2"></i> Settings</a>
  <a href="home.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
</div>

<!-- Main Content -->
<div class="content">
  <h2><i class="bi bi-gear me-2"></i> Settings</h2>

  <!-- Tabs -->
  <ul class="nav nav-tabs" id="settingsTab" role="tablist">
    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#profile">Profile</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#security">Security</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#notifications">Notifications</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#system">System</a></li>
  </ul>

  <!-- Tab Content -->
  <div class="tab-content mt-4">
    <!-- Profile -->
    <div class="tab-pane fade show active" id="profile">
      <h5>Profile Settings</h5>
      <form>
        <div class="mb-3">
          <label class="form-label">Full Name</label>
          <input type="text" class="form-control" value="<?= $admin['fullname'] ?>">
        </div>
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" class="form-control" value="<?= $admin['email'] ?>" readonly>
        </div>
        <div class="mb-3">
          <label class="form-label">Phone</label>
          <input type="text" class="form-control" value="<?= $admin['phone'] ?>">
        </div>
        <button type="submit" class="btn btn-primary">Save Changes</button>
      </form>
    </div>

    <!-- Security -->
    <div class="tab-pane fade" id="security">
      <h5>Security Settings</h5>
      <form>
        <div class="mb-3">
          <label class="form-label">Current Password</label>
          <input type="password" class="form-control">
        </div>
        <div class="mb-3">
          <label class="form-label">New Password</label>
          <input type="password" class="form-control">
        </div>
        <div class="mb-3">
          <label class="form-label">Confirm New Password</label>
          <input type="password" class="form-control">
        </div>
        <button type="submit" class="btn btn-danger">Update Password</button>
      </form>
    </div>

    <!-- Notifications -->
    <div class="tab-pane fade" id="notifications">
      <h5>Notification Settings</h5>
      <form>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" checked>
          <label class="form-check-label">Email alerts for new orders</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" checked>
          <label class="form-check-label">Low stock medicine alerts</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox">
          <label class="form-check-label">Weekly sales report email</label>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Save Preferences</button>
      </form>
    </div>

    <!-- System -->
    <div class="tab-pane fade" id="system">
      <h5>System Settings</h5>
      <form>
        <div class="mb-3">
          <label class="form-label">Pharmacy Name</label>
          <input type="text" class="form-control" value="<?= $admin['pharmacy_name'] ?>">
        </div>
        <div class="mb-3">
          <label class="form-label">Currency</label>
          <select class="form-select">
            <option <?= $admin['currency'] == 'LKR' ? 'selected' : '' ?>>LKR</option>
            <option <?= $admin['currency'] == 'USD' ? 'selected' : '' ?>>USD</option>
            <option <?= $admin['currency'] == 'INR' ? 'selected' : '' ?>>INR</option>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Invoice Prefix</label>
          <input type="text" class="form-control" value="<?= $admin['invoice_prefix'] ?>">
        </div>
        <button type="submit" class="btn btn-success">Update System</button>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
