<?php
session_start();
include 'connect.php'; // Database connection

$pharmacist_name = "Pharmacist";

// ----------- CRUD Operations ------------

// Add Medicine
if(isset($_POST['add_medicine'])){
    $stmt = $conn->prepare("INSERT INTO medicines (name, brand_name, description, unit_price, quantity, expiry_date) VALUES (:name, :brand, :desc, :price, :qty, :expiry)");
    $stmt->execute([
        ':name' => $_POST['name'],
        ':brand' => $_POST['brand_name'],
        ':desc' => $_POST['description'],
        ':price' => $_POST['unit_price'],
        ':qty' => $_POST['quantity'],
        ':expiry' => $_POST['expiry_date']
    ]);
    header("Location: manage_medicines.php");
    exit;
}

// Update Medicine
if(isset($_POST['update_medicine'])){
    $stmt = $conn->prepare("UPDATE medicines SET name=:name, brand_name=:brand, description=:desc, unit_price=:price, quantity=:qty, expiry_date=:expiry WHERE medicine_id=:id");
    $stmt->execute([
        ':name' => $_POST['name'],
        ':brand' => $_POST['brand_name'],
        ':desc' => $_POST['description'],
        ':price' => $_POST['unit_price'],
        ':qty' => $_POST['quantity'],
        ':expiry' => $_POST['expiry_date'],
        ':id' => $_POST['id']
    ]);
    header("Location: manage_medicines.php");
    exit;
}

// Delete Medicine
if(isset($_GET['delete'])){
    $stmt = $conn->prepare("DELETE FROM medicines WHERE medicine_id=:id");
    $stmt->execute([':id' => $_GET['delete']]);
    header("Location: manage_medicines.php");
    exit;
}

// Fetch Medicines
$medicines = $conn->query("SELECT * FROM medicines ORDER BY medicine_id ASC")->fetchAll();

// Stats
$available_meds = $conn->query("SELECT COUNT(*) AS total FROM medicines WHERE quantity>0")->fetch()['total'];
$out_of_stock = $conn->query("SELECT COUNT(*) AS total FROM medicines WHERE quantity=0")->fetch()['total'];
$nearly_expired = $conn->query("SELECT COUNT(*) AS total FROM medicines WHERE expiry_date<=DATE_ADD(CURDATE(), INTERVAL 30 DAY)")->fetch()['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Medicines - Pharmacist Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<style>
body { font-family: 'Poppins', sans-serif; min-height: 100vh; display: flex; background-color: #f5f7fa; }

/* Sidebar */
.sidebar { width: 240px; min-height: 100vh; background: #0d6efd; color: #fff; display: flex; flex-direction: column; padding-top: 30px; position: fixed; }
.sidebar-profile { text-align: center; margin-bottom: 30px; }
.sidebar-profile h3 { color: #fff; margin: 0; padding: 20px 0; font-weight: 600; }
.sidebar a { color: #fff; text-decoration: none; display: flex; align-items: center; padding: 12px 20px; border-radius: 8px; margin-bottom: 5px; transition: 0.3s; }
.sidebar a i { margin-right: 10px; min-width: 20px; text-align: center; }
.sidebar a:hover, .sidebar a.active { background: rgba(255,255,255,0.2); }

/* Content */
.content { margin-left: 240px; flex: 1; padding: 30px; }

/* Cards */
.card { border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); transition: transform 0.3s, box-shadow 0.3s; }
.card:hover { transform: translateY(-5px); box-shadow: 0 8px 25px rgba(0,0,0,0.2); }
.card-title { font-weight: bold; font-size: 1rem; }
.stat-card { cursor: default; color: white; }
.badge-low { background-color: #ffc107; color:#212529; }
.badge-out { background-color: #dc3545; }
.badge-available { background-color: #28a745; }

/* Scrollable Table */
.table-container { overflow-y: auto; overflow-x: auto; border-radius: 10px; box-shadow: inset 0 0 6px rgba(0,0,0,0.1); }
.table-container thead th { position: sticky; top: 0; background-color: #212529; color: white; z-index: 10; }
</style>
</head>
<body>

<!-- Sidebar -->
<nav class="sidebar">
  <div class="sidebar-profile"> 
    <h3><i class="bi bi-person-circle"></i> <?= htmlspecialchars($pharmacist_name) ?></h3>
  </div>
  <a href="pharmacist_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
  <a href="manage_medicines.php" class="active"><i class="fas fa-pills"></i> Manage Medicines</a>
  <a href="pharmacist_sales.php"><i class="fas fa-cash-register"></i> Sales</a>
  <a href="purchases.php"><i class="fas fa-truck"></i> Purchases</a>
  <a href="suppliers.php"><i class="fas fa-users"></i> Suppliers</a>
  <a href="pharmacist_report.php"><i class="fas fa-chart-bar"></i> Reports</a>
  <a href="home.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</nav>

<!-- Content -->
<div class="content">
<h2 class="mb-4">Welcome back, <strong><?= $pharmacist_name ?></strong>!</h2>

<!-- Stats -->
<div class="row g-3 mb-4">
  <div class="col-md-4"><div class="card stat-card bg-success text-center p-3"><h6 class="card-title">Available Medicines</h6><p class="fs-5 mb-0"><?= $available_meds ?></p></div></div>
  <div class="col-md-4"><div class="card stat-card bg-danger text-center p-3"><h6 class="card-title">Out-of-Stock Medicines</h6><p class="fs-5 mb-0"><?= $out_of_stock ?></p></div></div>
  <div class="col-md-4"><div class="card stat-card bg-warning text-center p-3"><h6 class="card-title">Nearly Expired (30 days)</h6><p class="fs-5 mb-0"><?= $nearly_expired ?></p></div></div>
</div>

<!-- Medicines Table -->
<div class="mb-4">
<h3>Manage Medicines</h3>
<div class="d-flex justify-content-between mb-3 flex-wrap">
    <button class="btn btn-success mb-2" data-bs-toggle="modal" data-bs-target="#addMedicineModal"><i class="bi bi-plus-circle"></i> Add Medicine</button>
    <input type="text" id="medicineSearch" class="form-control mb-2" style="max-width: 300px;" placeholder="Search by Medicine or Brand...">
</div>

<div class="table-container table-responsive">
<table class="table table-hover align-middle bg-white mb-0" id="medicinesTable">
<thead class="table-dark"><tr><th>ID</th><th>Name</th><th>Brand</th><th>Status</th><th>Qty</th><th>Unit Price</th><th>Actions</th></tr></thead>
<tbody>
<?php foreach($medicines as $med): 
    if($med['quantity']==0) $status='<span class="badge badge-out">Out of stock</span>';
    elseif($med['quantity']<50) $status='<span class="badge badge-low">Low stock</span>';
    else $status='<span class="badge badge-available">Available</span>';
?>
<tr>
<td><?= $med['medicine_id'] ?></td>
<td><?= htmlspecialchars($med['name']) ?></td>
<td><?= htmlspecialchars($med['brand_name']) ?></td>
<td><?= $status ?></td>
<td><?= $med['quantity'] ?></td>
<td><?= number_format($med['unit_price'],2) ?></td>
<td>
<button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $med['medicine_id'] ?>">Edit</button>
<a href="?delete=<?= $med['medicine_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this medicine?')">Delete</a>
</td>
</tr>

<!-- Edit Modal -->
<div class="modal fade" id="editModal<?= $med['medicine_id'] ?>" tabindex="-1">
<div class="modal-dialog"><form method="POST"><input type="hidden" name="id" value="<?= $med['medicine_id'] ?>">
<div class="modal-content"><div class="modal-header"><h5 class="modal-title">Edit Medicine</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
<div class="modal-body">
<input type="text" name="name" class="form-control mb-2" value="<?= htmlspecialchars($med['name']) ?>" required>
<input type="text" name="brand_name" class="form-control mb-2" value="<?= htmlspecialchars($med['brand_name']) ?>">
<textarea name="description" class="form-control mb-2"><?= htmlspecialchars($med['description']) ?></textarea>
<input type="number" step="0.01" name="unit_price" class="form-control mb-2" value="<?= $med['unit_price'] ?>" required>
<input type="number" name="quantity" class="form-control mb-2" value="<?= $med['quantity'] ?>" required>
<input type="date" name="expiry_date" class="form-control mb-2" value="<?= $med['expiry_date'] ?>">
</div><div class="modal-footer">
<button type="submit" name="update_medicine" class="btn btn-primary">Save</button>
</div></div></form></div></div>
<?php endforeach; ?>
</tbody></table>
</div></div>

<!-- Add Medicine Modal -->
<div class="modal fade" id="addMedicineModal" tabindex="-1">
<div class="modal-dialog"><form method="POST">
<div class="modal-content"><div class="modal-header"><h5 class="modal-title">Add Medicine</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
<div class="modal-body">
<input type="text" name="name" class="form-control mb-2" placeholder="Medicine Name" required>
<input type="text" name="brand_name" class="form-control mb-2" placeholder="Brand Name">
<textarea name="description" class="form-control mb-2" placeholder="Description"></textarea>
<input type="number" step="0.01" name="unit_price" class="form-control mb-2" placeholder="Unit Price" required>
<input type="number" name="quantity" class="form-control mb-2" placeholder="Quantity" required>
<input type="date" name="expiry_date" class="form-control mb-2">
</div><div class="modal-footer">
<button type="submit" name="add_medicine" class="btn btn-success">Add</button>
</div></div></form></div></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Search Filter -->
<script>
const searchInput = document.getElementById('medicineSearch');
searchInput.addEventListener('input', function() {
    const filter = this.value.toLowerCase();
    document.querySelectorAll('#medicinesTable tbody tr').forEach(row => {
        const name = row.cells[1].textContent.toLowerCase();
        const brand = row.cells[2].textContent.toLowerCase();
        row.style.display = (name.includes(filter) || brand.includes(filter)) ? '' : 'none';
    });
});
</script>

<!-- Dynamic Table Height (~8 rows) -->
<script>
function adjustTableHeight() {
    const tableContainer = document.querySelector('.table-container');
    const table = document.getElementById('medicinesTable');
    if (!table) return;
    const rows = table.querySelectorAll('tbody tr');
    if (rows.length === 0) return;

    const rowHeight = rows[0].getBoundingClientRect().height;
    const headerHeight = table.querySelector('thead').getBoundingClientRect().height;
    const desiredRows = 8; // ~8 rows visible
    tableContainer.style.maxHeight = (headerHeight + rowHeight * desiredRows) + 'px';
}
window.addEventListener('load', adjustTableHeight);
window.addEventListener('resize', adjustTableHeight);
</script>

</body>
</html>
