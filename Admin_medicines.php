<?php
include "connect.php";

// Handle Delete (AJAX)
if (isset($_POST['delete_id'])) {
  $id = $_POST['delete_id'];
  $stmt = $conn->prepare("DELETE FROM medicines WHERE medicine_id=?");
  $stmt->execute([$id]);
  echo "deleted";
  exit;
}

// Handle Add or Update (AJAX)
if (isset($_POST['action']) && $_POST['action'] === 'save') {
  $id = $_POST['id'] ?? '';
  $name = $_POST['name'];
  $brand = $_POST['brand_name'];
  $description = $_POST['description'];
  $unit_price = $_POST['unit_price'];
  $quantity = $_POST['quantity'];
  $expiry_date = $_POST['expiry_date'];

  if ($id) {
    $stmt = $conn->prepare("UPDATE medicines SET name=?, brand_name=?, description=?, unit_price=?, quantity=?, expiry_date=? WHERE medicine_id=?");
    $stmt->execute([$name, $brand, $description, $unit_price, $quantity, $expiry_date, $id]);
    echo "updated";
  } else {
    $stmt = $conn->prepare("INSERT INTO medicines (name, brand_name, description, unit_price, quantity, expiry_date) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $brand, $description, $unit_price, $quantity, $expiry_date]);
    echo "added";
  }
  exit;
}

// Fetch all medicines
$medicines = $conn->query("SELECT * FROM medicines ORDER BY medicine_id ASC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Medicines - PharmaCloud</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

<style>
body { font-family: 'Poppins', sans-serif; display: flex; min-height: 100vh; background-color: #f4f6fc; margin: 0; }
.sidebar { width: 250px; background-color: #343a40; color: #fff; display: flex; flex-direction: column; padding-top: 20px; position: fixed; top:0; bottom:0; }
.sidebar h3 { font-weight: 600; text-align: center; border-bottom: 1px solid #444; padding: 20px 0; margin-bottom: 20px; }
.sidebar a { color: #e2e8e8ff; text-decoration: none; display:block; padding:15px 25px; margin:4px 8px; border-radius:8px; transition: background 0.3s, color 0.3s; }
.sidebar a:hover, .sidebar a.active { background-color: #495057; color: #fff; }
.content { flex: 1; margin-left: 250px; padding: 30px; }
.table th { background-color: #212529; color: #fff; }
.table-hover tbody tr:hover { background-color: #f1f1f1; }
.card { border-radius: 12px; border: none; }
.btn-primary { background-color: #343a40; border: none; color: #fff; }
.btn-primary:hover { background-color: #495057; }
.btn-warning { background-color: #fd7e14; border: none; color: #fff; }
.btn-warning:hover { background-color: #e8590c; }
.btn-danger { background-color: #dc3545; border: none; color: #fff; }
.btn-danger:hover { background-color: #a71d2a; }
.form-control { border-radius: 6px; }
.badge-available { background-color: #28a745; }
.badge-low { background-color: #ffc107; color: #212529; }
.badge-out { background-color: #dc3545; }
.badge-expired { background-color: #fd7e14; color: #212529; }
</style>
</head>

<body>
<div class="sidebar">
  <h3><i class="bi bi-person-circle"></i> Admin</h3>
  <a href="Admin_dashboard.php"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
  <a href="users.php"><i class="bi bi-people me-2"></i> Users</a>
  <a href="sales.php"><i class="bi bi-receipt me-2"></i> Sales</a>
  <a href="Admin_purchases.php"><i class="bi bi-basket me-2"></i> Purchases</a>
  <a href="Admin_medicines.php" class="active"><i class="bi bi-box-seam me-2"></i> Medicines</a>
  <a href="Admin_reports.php"><i class="bi bi-bar-chart-line me-2"></i> Reports</a>
  <a href="settings.php"><i class="bi bi-gear me-2"></i> Settings</a>
  <a href="home.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
</div>

<div class="content">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Manage Medicines</h2>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#medicineModal" onclick="resetForm()">
      <i class="bi bi-plus-lg"></i> Add Medicine
    </button>
  </div>

  <div class="card shadow p-3">
    <div class="table-responsive">
      <table id="medicinesTable" class="table table-striped table-bordered align-middle">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Brand</th>
            <th>Price</th>
            <th>Qty</th>
            <th>Expiry</th>
            <th>Description</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($medicines as $m): 
            // Calculate status
            $status = '';
            $today = new DateTime();
            $expiry = new DateTime($m['expiry_date']);
            $diff = $today->diff($expiry)->days;
            if ($m['quantity'] == 0) $status = '<span class="badge badge-out text-white">Out of Stock</span>';
            else if ($diff <= 30) $status = '<span class="badge badge-expired">Nearly Expired</span>';
            else if ($m['quantity'] < 50) $status = '<span class="badge badge-low">Low Stock</span>';
            else $status = '<span class="badge badge-available text-white">Available</span>';
        ?>
          <tr>
            <td><?= $m['medicine_id'] ?></td>
            <td><?= htmlspecialchars($m['name']) ?></td>
            <td><?= htmlspecialchars($m['brand_name']) ?></td>
            <td><?= $m['unit_price'] ?></td>
            <td><?= $m['quantity'] ?></td>
            <td><?= $m['expiry_date'] ?></td>
            <td><?= htmlspecialchars($m['description']) ?></td>
            <td><?= $status ?></td>
            <td>
              <button class="btn btn-warning btn-sm" onclick='editMedicine(<?= json_encode($m) ?>)'><i class="bi bi-pencil"></i></button>
              <button class="btn btn-danger btn-sm" onclick="deleteMedicine(<?= $m['medicine_id'] ?>)"><i class="bi bi-trash"></i></button>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="medicineModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="medicineForm">
        <div class="modal-header bg-dark text-white">
          <h5 class="modal-title">Add / Edit Medicine</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="id">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Medicine Name</label>
              <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Brand Name</label>
              <input type="text" name="brand_name" id="brand_name" class="form-control" required>
            </div>
            <div class="col-md-4">
              <label class="form-label">Unit Price</label>
              <input type="number" step="0.01" name="unit_price" id="unit_price" class="form-control" required>
            </div>
            <div class="col-md-4">
              <label class="form-label">Quantity</label>
              <input type="number" name="quantity" id="quantity" class="form-control" required>
            </div>
            <div class="col-md-4">
              <label class="form-label">Expiry Date</label>
              <input type="date" name="expiry_date" id="expiry_date" class="form-control" required>
            </div>
            <div class="col-md-12">
              <label class="form-label">Description</label>
              <textarea name="description" id="description" class="form-control"></textarea>
            </div>
            <div class="col-md-12 mt-2">
              <label class="form-label">Status</label>
              <p id="statusText" class="fw-bold badge badge-available text-white">Available</p>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Save</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
  $('#medicinesTable').DataTable();
});

// Reset form
function resetForm() {
  $('#medicineForm')[0].reset();
  $('#id').val('');
  $('#statusText').text('Available').removeClass('badge-low badge-out badge-expired').addClass('badge-available text-white');
}

// Edit medicine
function editMedicine(data) {
  $('#medicineModal').modal('show');
  $('#id').val(data.medicine_id);
  $('#name').val(data.name);
  $('#brand_name').val(data.brand_name);
  $('#unit_price').val(data.unit_price);
  $('#quantity').val(data.quantity);
  $('#expiry_date').val(data.expiry_date);
  $('#description').val(data.description);
  calculateStatus();
}

// Delete medicine
function deleteMedicine(id) {
  if(confirm('Are you sure you want to delete this medicine?')) {
    $.post('', {delete_id:id}, function(res){
      if(res==='deleted'){ alert('Medicine deleted successfully!'); location.reload(); }
    });
  }
}

// Dynamic status in modal
function calculateStatus() {
  let qty = parseInt($('#quantity').val());
  let expiry = $('#expiry_date').val();
  let status = 'Available';
  let classes = 'badge-available text-white';

  if(qty === 0) { status='Out of Stock'; classes='badge-out text-white'; }
  else if(expiry) {
    let today = new Date();
    let expDate = new Date(expiry);
    let diff = Math.ceil((expDate - today)/(1000*60*60*24));
    if(diff <= 30) { status='Nearly Expired'; classes='badge-expired'; }
    else if(qty < 50) { status='Low Stock'; classes='badge-low'; }
  } else if(qty < 50) { status='Low Stock'; classes='badge-low'; }

  $('#statusText').text(status).removeClass('badge-available badge-low badge-out badge-expired text-white').addClass(classes);
}

$('#quantity, #expiry_date').on('input change', calculateStatus);

// Save medicine
$('#medicineForm').submit(function(e){
  e.preventDefault();
  $.post('', $(this).serialize()+'&action=save', function(res){
    alert('Medicine '+res+' successfully!');
    location.reload();
  });
});
</script>
</body>
</html>
