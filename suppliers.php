<?php
include "connect.php";

// Handle Delete
if (isset($_GET['delete'])) {
  $id = $_GET['delete'];
  $stmt = $conn->prepare("DELETE FROM suppliers WHERE supplier_id = :id");
  $stmt->execute([':id' => $id]);
  header("Location: suppliers.php");
  exit;
}

// Fetch all suppliers
$stmt = $conn->query("SELECT * FROM suppliers ORDER BY supplier_id ASC");
$suppliers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Supplier Management - PharmaCloud</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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

/* Card & Table */
.card { border-radius: 15px; margin-bottom: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); padding: 20px; }
.table th { background-color: #0d6efd; color: #fff; }
.table-hover tbody tr:hover { background-color: #e9ecef; }

/* Buttons */
.btn-primary { background-color: #0d6efd; border: none; color: #fff; }
.btn-primary:hover { background-color: #0b5ed7; }
.btn-success { background-color: #198754; border: none; color: #fff; }
.btn-success:hover { background-color: #157347; }
</style>
</head>
<body>

<!-- Sidebar -->
<nav class="sidebar">
  <div class="sidebar-profile">
    <h3><i class="bi bi-person-circle"></i> Pharmacist</h3>
  </div>
  <a href="pharmacist_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
  <a href="manage_medicines.php"><i class="fas fa-pills"></i> Manage Medicines</a>
  <a href="pharmacist_sales.php"><i class="fas fa-cash-register"></i> Sales</a>
  <a href="purchases.php"><i class="fas fa-truck"></i> Purchases</a>
  <a href="suppliers.php" class="active"><i class="fas fa-users"></i> Suppliers</a>
  <a href="pharmacist_report.php"><i class="fas fa-chart-bar"></i> Reports</a>
  <a href="home.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</nav>

<!-- Main Content -->
<div class="main-content">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold"><i class="fas fa-users me-2"></i> Supplier Management</h2>
    <a href="add_supplier.php" class="btn btn-success"><i class="fas fa-plus me-2"></i> Add Supplier</a>
  </div>

  <!-- Supplier Table -->
  <div class="card">
    <div class="table-responsive">
      <table class="table table-bordered table-hover text-center align-middle mb-0">
        <thead>
          <tr>
            <th>ID</th>
            <th>Supplier Name</th>
            <th>Contact Person</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Address</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php if (count($suppliers) > 0): ?>
          <?php foreach ($suppliers as $row): ?>
            <tr>
              <td><?= htmlspecialchars($row['supplier_id']) ?></td>
              <td><?= htmlspecialchars($row['supplier_name']) ?></td>
              <td><?= htmlspecialchars($row['contact_person']) ?></td>
              <td><?= htmlspecialchars($row['email']) ?></td>
              <td><?= htmlspecialchars($row['phone']) ?></td>
              <td><?= htmlspecialchars($row['address']) ?></td>
              <td>
                <a class='btn btn-success btn-sm' href='edit_supplier.php?id=<?= $row['supplier_id'] ?>'><i class='fas fa-edit'></i></a>
                <a class='btn btn-danger btn-sm' href='suppliers.php?delete=<?= $row['supplier_id'] ?>' onclick="return confirm('Are you sure you want to delete this supplier?');"><i class='fas fa-trash'></i></a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="7">No suppliers found.</td></tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
