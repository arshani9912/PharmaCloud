<?php 
include 'connect.php';
session_start();

// Example: pharmacist name from session
$pharmacist_name = isset($_SESSION['pharmacist_name']) ? $_SESSION['pharmacist_name'] : 'Pharmacist';

// Handle search filter
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Fetch nearly expired drugs (within next 30 days but not yet expired)
$query = "
    SELECT name, brand_name, quantity, expiry_date 
    FROM medicines 
    WHERE expiry_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)
";
if ($search != '') {
    $query .= " AND (name LIKE :search OR brand_name LIKE :search)";
}
$query .= " ORDER BY expiry_date ASC";

$stmt = $conn->prepare($query);
if ($search != '') {
    $stmt->bindValue(':search', "%$search%");
}
$stmt->execute();
$drugs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Nearly Expired Medicines | PharmaCloud</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

<style>
body {
    font-family: 'Poppins', sans-serif;
    display: flex;
    min-height: 100vh;
    margin: 0;
    background-color: #f4f6f8;
}

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
    overflow: hidden;
}
.card {
    border-radius: 15px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
.table-container {
    max-height: 70vh; /* Fits within desktop screen */
    overflow-y: auto;
    scrollbar-width: thin;
}
.table-container::-webkit-scrollbar {
    width: 8px;
}
.table-container::-webkit-scrollbar-thumb {
    background-color: #0d6efd;
    border-radius: 10px;
}
.table-container::-webkit-scrollbar-track {
    background: #f1f1f1;
}
.table th {
    background-color: #0d6efd; /* Matching dashboard blue */
    color: #fff;
    position: sticky;
    top: 0;
    z-index: 2;
}
.alert-info {
    background-color: #e7f3fe;
    border-left: 5px solid #0d6efd;
}
.search-bar {
    display: flex;
    justify-content: flex-end;
    margin-bottom: 15px;
}
.search-bar input {
    width: 250px;
    border-radius: 10px;
}

@media (max-width:768px){
    .main-content { margin-left:0; padding:15px; }
    .sidebar { width:100%; height:auto; position:relative; }
    .search-bar { justify-content:center; }
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
  <a href="pharmacist_report.php"><i class="fas fa-chart-bar"></i> Reports</a>
  <a href="nearly_expire_drugs.php" class="active"><i class="bi bi-exclamation-circle"></i> Nearly Expired</a>
  <a href="home.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</nav>

<!-- Main Content -->
<div class="main-content">
  <h2 class="fw-bold mb-4">Nearly Expired Medicines (Next 30 Days)</h2>

  <form method="GET" class="search-bar">
    <input type="text" name="search" class="form-control" placeholder="Search by medicine or brand..." value="<?= htmlspecialchars($search) ?>">
    <button class="btn btn-primary ms-2"><i class="bi bi-search"></i></button>
  </form>

  <?php if (empty($drugs)) : ?>
      <div class="alert alert-info">No medicines are nearing expiry within the next 30 days.</div>
  <?php else: ?>
  <div class="card p-4">
    <div class="table-container">
      <table class="table table-striped align-middle text-center">
        <thead>
          <tr>
            <th>#</th>
            <th>Medicine Name</th>
            <th>Brand</th>
            <th>Quantity</th>
            <th>Expiry Date</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php 
          $i = 1;
          $today = new DateTime();
          foreach ($drugs as $drug) {
              $expiry = new DateTime($drug['expiry_date']);
              $diff = $today->diff($expiry)->days;
              $status = ($diff <= 10) ? "<span class='badge bg-danger'>Expiring Very Soon</span>" : "<span class='badge bg-warning text-dark'>Nearing Expiry</span>";
              echo "<tr>
                  <td>{$i}</td>
                  <td>{$drug['name']}</td>
                  <td>{$drug['brand_name']}</td>
                  <td>{$drug['quantity']}</td>
                  <td>{$drug['expiry_date']}</td>
                  <td>{$status}</td>
              </tr>";
              $i++;
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php endif; ?>
</div>

</body>
</html>
