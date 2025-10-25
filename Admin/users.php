<?php  
include "connect.php";

// Handle Delete
if (isset($_GET['delete'])) {
  $id = $_GET['delete'];
  $stmt = $conn->prepare("DELETE FROM users WHERE user_id = :id");
  $stmt->execute([':id' => $id]);
  header("Location: users.php");
  exit;
}

// Fetch All Users
$stmt = $conn->query("SELECT * FROM users ORDER BY user_id ASC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin - Users | PharmaCloud</title>
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
      position: fixed;
      top: 0;
      bottom: 0;
      padding-top: 20px;
    }

    .sidebar h3 {
      text-align: center;
      font-weight: 600;
      padding: 20px 0;
      border-bottom: 1px solid #444;
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

    /* Table */
    table th {
      background: #212529;
      color: #fff;
    }

    table tbody tr:hover {
      background-color: #f1f1f1;
    }

    /* Cards */
    .card {
      border-radius: 12px;
      border: none;
      padding: 20px;
    }

    /* Buttons */
    .btn-primary {
      background-color: #343a40;
      border: none;
      color: #fff;
    }

    .btn-primary:hover {
      background-color: #495057;
    }

    .btn-success {
      background-color: #28a745;
      border: none;
    }

    .btn-success:hover {
      background-color: #1e7e34;
    }

    .btn-danger {
      background-color: #dc3545;
      border: none;
    }

    .btn-danger:hover {
      background-color: #a71d2a;
    }
  </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
  <h3><i class="bi bi-person-circle"></i> Admin</h3>
  <a href="Admin_dashboard.php"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
  <a href="users.php" class="active"><i class="bi bi-people me-2"></i> Users</a>
  <a href="Admin_sales.php"><i class="bi bi-receipt me-2"></i> Sales</a>
  <a href="Admin_purchases.php"><i class="bi bi-basket me-2"></i> Purchases</a>
  <a href="Admin_medicines.php"><i class="bi bi-box-seam me-2"></i> Medicines</a>
  <a href="admin_medicine_instructions.php"><i class="bi bi-capsule me-2"></i> Medicine Instructions</a>
  <a href="Admin_reports.php"><i class="bi bi-bar-chart-line me-2"></i> Reports</a>
  <a href="settings.php"><i class="bi bi-gear me-2"></i> Settings</a>
  <a href="logout.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
</div>

<!-- Main Content -->
<div class="content">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold"><i class="bi bi-people me-2"></i>Manage Users</h2>
    <a href="add_user.php" class="btn btn-primary"><i class="bi bi-plus-lg me-2"></i> Add User</a>
  </div>

  <div class="card shadow-sm">
    <div class="table-responsive">
      <table class="table table-bordered table-hover text-center align-middle mb-0">
        <thead>
          <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Verified</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($users): ?>
            <?php foreach ($users as $row): ?>
              <tr>
                <td><?= $row['user_id'] ?></td>
                <td><?= htmlspecialchars($row['user_name']) ?></td>
                <td><?= htmlspecialchars($row['full_name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['role']) ?></td>
                <td><?= $row['is_verified'] ? "✔" : "❌" ?></td>
                <td>
                  <a href="edit_user.php?id=<?= $row['user_id'] ?>" class="btn btn-success btn-sm"><i class="bi bi-pencil"></i> Edit</a>
                  <a href="users.php?delete=<?= $row['user_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this user?');"><i class="bi bi-trash"></i> Delete</a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="7" class="text-center text-muted">No users found</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
