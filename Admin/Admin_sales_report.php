<?php
session_start();
include 'connect.php';

// Fetch all sales and related items
$sales = $conn->query("SELECT * FROM sales ORDER BY sale_date DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Generate Sales Report | PharmaCloud</title>
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
    font-weight: 600;
    text-align: center;
    padding: 20px 0;
    border-bottom: 1px solid #444;
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
.main-content {
    flex: 1;
    margin-left: 250px;
    padding: 30px;
}

h2 {
    margin-bottom: 25px;
}

.table th {
    background-color: #212529;
    color: #fff;
}

.btn-primary {
    background-color: #343a40;
    border: none;
    color: #fff;
}
.btn-primary:hover {
    background-color: #495057;
}
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
    <h2 class="fw-bold"><i class="bi bi-cash-stack me-2"></i> Sales Report</h2>

    <div class="card shadow-sm p-3">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Invoice #</th>
                        <th>Patient Name</th>
                        <th>Date</th>
                        <th>Total Amount</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($sales)): ?>
                        <?php foreach ($sales as $sale): ?>
                        <tr>
                            <td><?= $sale['sale_id'] ?></td>
                            <td><?= htmlspecialchars($sale['patient_name']) ?></td>
                            <td><?= date('d-m-Y H:i', strtotime($sale['sale_date'])) ?></td>
                            <td><?= number_format($sale['total_amount'], 2) ?></td>
                            <td>
                                <a href="print_invoice.php?sale_id=<?= $sale['sale_id'] ?>" target="_blank" class="btn btn-primary btn-sm">
                                    View / Print
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">No sales found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
