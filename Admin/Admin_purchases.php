<?php
session_start();
include 'connect.php';

// Fetch all medicines for brand_name dropdown and price calculation
$medicines = $conn->query("SELECT * FROM medicines ORDER BY brand_name ASC")->fetchAll(PDO::FETCH_ASSOC);

// Pagination setup
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Search
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$searchQuery = $search ? "WHERE supplier LIKE :search OR brand_name LIKE :search" : "";

// Count total
$countStmt = $conn->prepare("SELECT COUNT(*) as total FROM purchases $searchQuery");
if ($search) $countStmt->bindValue(':search', "%$search%");
$countStmt->execute();
$total = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
$pages = ceil($total / $limit);

// Fetch purchases
$stmt = $conn->prepare("SELECT * FROM purchases $searchQuery ORDER BY id ASC LIMIT $start, $limit");
if ($search) $stmt->bindValue(':search', "%$search%");
$stmt->execute();
$purchases = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle Add
if (isset($_POST['add_purchase'])) {
    $supplier = $_POST['supplier'];
    $brand_name = $_POST['brand_name'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $total_price = $_POST['total_price'];
    $purchase_date = $_POST['purchase_date'];

    $stmt = $conn->prepare("INSERT INTO purchases (supplier, brand_name, quantity, price, total_price, purchase_date) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$supplier, $brand_name, $quantity, $price, $total_price, $purchase_date]);
    header("Location: Admin_purchases.php");
    exit;
}

// Handle Update
if (isset($_POST['update_purchase'])) {
    $id = $_POST['id'];
    $supplier = $_POST['supplier'];
    $brand_name = $_POST['brand_name'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $total_price = $_POST['total_price'];
    $purchase_date = $_POST['purchase_date'];

    $stmt = $conn->prepare("UPDATE purchases SET supplier=?, brand_name=?, quantity=?, price=?, total_price=?, purchase_date=? WHERE id=?");
    $stmt->execute([$supplier, $brand_name, $quantity, $price, $total_price, $purchase_date, $id]);
    header("Location: Admin_purchases.php");
    exit;
}

// Handle Delete
if (isset($_GET['delete'])) {
    $stmt = $conn->prepare("DELETE FROM purchases WHERE id=?");
    $stmt->execute([$_GET['delete']]);
    header("Location: Admin_purchases.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Purchases - PharmaCloud</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

<style>
body { font-family: 'Poppins', sans-serif; background-color: #f4f6fc; margin:0; display:flex; min-height:100vh;}
.sidebar { width: 250px; background:#343a40; color:#fff; display:flex; flex-direction:column; padding-top:20px; position:fixed; top:0; left:0; bottom:0;}
.sidebar h3 { font-weight:600; text-align:center; border-bottom:1px solid #444; padding:20px 0; margin-bottom:20px;}
.sidebar a { color:#e2e8e8; text-decoration:none; display:block; padding:15px 25px; font-size:1rem; margin:4px 8px; border-radius:8px; transition:0.3s;}
.sidebar a:hover, .sidebar a.active { background-color:#495057; color:#fff;}
.main-content { flex:1; margin-left:250px; padding:30px;}
.table th { background-color: #212529; color:#fff;}
.table-hover tbody tr:hover { background-color:#f1f1f1;}
.btn-primary { background-color:#343a40; border:none; color:#fff;}
.btn-primary:hover { background-color:#495057;}
</style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
  <h3><i class="bi bi-person-circle"></i> Admin</h3>
  <a href="Admin_dashboard.php"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
  <a href="users.php"><i class="bi bi-people me-2"></i> Users</a>
  <a href="Admin_sales.php"><i class="bi bi-receipt me-2"></i> Sales</a>
  <a href="Admin_purchases.php" class="active"><i class="bi bi-basket me-2"></i> Purchases</a>
  <a href="Admin_medicines.php"><i class="bi bi-box-seam me-2"></i> Medicines</a>
  <a href="Admin_reports.php"><i class="bi bi-bar-chart-line me-2"></i> Reports</a>
  <a href="settings.php"><i class="bi bi-gear me-2"></i> Settings</a>
  <a href="home.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
</div>

<div class="main-content">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-basket me-2"></i>Manage Purchases</h2>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#purchaseModal" onclick="resetForm()"><i class="bi bi-plus-circle me-2"></i>New Purchase</button>
  </div>

  <!-- Search -->
  <form class="input-group mb-4" method="GET">
    <input type="text" name="search" class="form-control" placeholder="Search supplier or brand..." value="<?= htmlspecialchars($search) ?>">
    <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
  </form>

  <!-- Purchases Table -->
  <div class="card shadow-sm">
    <div class="card-body table-responsive">
      <table class="table table-bordered table-hover align-middle">
        <thead>
          <tr>
            <th>ID</th>
            <th>Supplier</th>
            <th>Brand Name</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Total Price</th>
            <th>Date</th>
            <th width="120">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if($purchases): ?>
            <?php foreach($purchases as $purchase): ?>
            <tr>
              <td><?= $purchase['id'] ?></td>
              <td><?= htmlspecialchars($purchase['supplier']) ?></td>
              <td><?= htmlspecialchars($purchase['brand_name']) ?></td>
              <td><?= $purchase['quantity'] ?></td>
              <td>Rs <?= number_format($purchase['price'],2) ?></td>
              <td>Rs <?= number_format($purchase['total_price'],2) ?></td>
              <td><?= $purchase['purchase_date'] ?></td>
              <td>
                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#purchaseModal" onclick='editPurchase(<?= json_encode($purchase) ?>)'><i class="bi bi-pencil-square"></i></button>
                <a href="?delete=<?= $purchase['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')"><i class="bi bi-trash"></i></a>
              </td>
            </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="8" class="text-center text-muted">No purchases found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Pagination -->
  <div class="d-flex justify-content-between align-items-center mt-3">
    <p class="text-muted mb-0">Showing <?= count($purchases) ?> of <?= $total ?> purchases</p>
    <div>
      <?php if ($page>1): ?><a href="?page=<?= $page-1 ?>&search=<?= urlencode($search) ?>" class="btn btn-outline-dark btn-sm"><i class="bi bi-chevron-left"></i> Previous</a><?php endif; ?>
      <?php if ($page<$pages): ?><a href="?page=<?= $page+1 ?>&search=<?= urlencode($search) ?>" class="btn btn-outline-dark btn-sm">Next <i class="bi bi-chevron-right"></i></a><?php endif; ?>
    </div>
  </div>
</div>

<!-- Add/Edit Purchase Modal -->
<div class="modal fade" id="purchaseModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" id="purchaseForm">
        <div class="modal-header bg-dark text-white">
          <h5 class="modal-title">Add / Edit Purchase</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="purchase_id">
          <div class="mb-3">
            <label>Supplier</label>
            <input type="text" name="supplier" id="supplier" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Brand Name</label>
            <select name="brand_name" id="brand_name" class="form-control" required>
              <option value="">Select Brand</option>
              <?php foreach($medicines as $m): ?>
                <option value="<?= htmlspecialchars($m['brand_name']) ?>"><?= htmlspecialchars($m['brand_name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3">
            <label>Quantity</label>
            <input type="number" name="quantity" id="quantity" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Purchase Date</label>
            <input type="date" name="purchase_date" id="purchase_date" class="form-control" required>
          </div>
          <!-- Hidden inputs to store calculated price and total -->
          <input type="hidden" name="price" id="price">
          <input type="hidden" name="total_price" id="total_price">
          <div id="priceTotalDisplay" style="margin-top:10px;"></div>
        </div>
        <div class="modal-footer">
          <button type="submit" name="add_purchase" class="btn btn-primary">Save</button>
          <button type="submit" name="update_purchase" class="btn btn-primary">Update</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
const medicinesData = <?= json_encode($medicines) ?>;

function resetForm(){
  document.getElementById('purchaseForm').reset();
  document.getElementById('purchase_id').value='';
  updatePriceAndTotal();
}

function editPurchase(data){
  document.getElementById('purchase_id').value=data.id;
  document.getElementById('supplier').value=data.supplier;
  document.getElementById('brand_name').value=data.brand_name;
  document.getElementById('quantity').value=data.quantity;
  document.getElementById('purchase_date').value=data.purchase_date;
  updatePriceAndTotal();
}

// Calculate price and total
function updatePriceAndTotal(){
  const brand=document.getElementById('brand_name').value;
  const qty=parseInt(document.getElementById('quantity').value)||0;
  const medicine=medicinesData.find(m=>m.brand_name===brand);
  const price=medicine? (parseFloat(medicine.unit_price)*0.8).toFixed(2):0;
  const total=(price*qty).toFixed(2);

  document.getElementById('price').value=price;
  document.getElementById('total_price').value=total;

  const display=document.getElementById('priceTotalDisplay');
  display.innerHTML=`<strong>Price:</strong> Rs ${price} &nbsp; | &nbsp; <strong>Total:</strong> Rs ${total}`;
}

document.getElementById('brand_name').addEventListener('change',updatePriceAndTotal);
document.getElementById('quantity').addEventListener('input',updatePriceAndTotal);
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
