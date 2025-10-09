<?php
session_start();
include 'connect.php';

// ------------------------------
// Example: pharmacist login info
// Make sure pharmacist login sets $_SESSION['pharmacist_name']
// For demo, if not set, default name
$pharmacist_name = isset($_SESSION['pharmacist_name']) ? $_SESSION['pharmacist_name'] : 'Pharmacist';

// ------------------------------
// Fetch all medicines for dropdown
$medicines = $conn->query("SELECT * FROM medicines ORDER BY brand_name ASC")->fetchAll(PDO::FETCH_ASSOC);

// Fetch all purchases
$purchases = $conn->query("SELECT * FROM purchases ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);

// ------------------------------
// Handle Add / Update
if (isset($_POST['add_purchase']) || isset($_POST['update_purchase'])) {
    $supplier = $_POST['supplier'];
    $brand_name = $_POST['brand_name'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $total_price = $_POST['total_price'];
    $purchase_date = $_POST['purchase_date'];

    if (isset($_POST['add_purchase'])) {
        $stmt = $conn->prepare("INSERT INTO purchases (supplier, brand_name, quantity, price, total_price, purchase_date) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$supplier, $brand_name, $quantity, $price, $total_price, $purchase_date]);
    } else {
        $id = $_POST['id'];
        $stmt = $conn->prepare("UPDATE purchases SET supplier=?, brand_name=?, quantity=?, price=?, total_price=?, purchase_date=? WHERE id=?");
        $stmt->execute([$supplier, $brand_name, $quantity, $price, $total_price, $purchase_date, $id]);
    }
    header("Location: purchases.php");
    exit;
}

// ------------------------------
// Handle Delete
if (isset($_GET['delete'])) {
    $stmt = $conn->prepare("DELETE FROM purchases WHERE id=?");
    $stmt->execute([$_GET['delete']]);
    header("Location: purchases.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Purchases - PharmaCloud</title>

<!-- Bootstrap & Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

<style>
body { font-family: 'Poppins', sans-serif; display: flex; min-height: 100vh; background-color: #f4f6f8; margin:0; }

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
.sidebar a i { margin-right: 10px; min-width: 20px; text-align: center; font-size: 1.1rem; }
.sidebar a:hover, .sidebar a.active { background: rgba(255,255,255,0.2); }

/* Main Content */
.main-content { flex:1; margin-left:240px; padding:30px; }
.card { border-radius:12px; margin-bottom:20px; box-shadow:0 4px 12px rgba(0,0,0,0.1); padding:20px; }
.table th { background-color:#0d6efd; color:#fff; }
.table-hover tbody tr:hover { background-color:#e9ecef; }

/* Buttons */
.btn-primary { background-color:#0d6efd; border:none; color:#fff; }
.btn-primary:hover { background-color:#0b5ed7; }
</style>
</head>
<body>

<!-- Sidebar -->
<nav class="sidebar">
  <div class="sidebar-profile">
    <h3><i class="bi bi-person-circle"></i> <?= htmlspecialchars($pharmacist_name) ?></h3>
  </div>
  <a href="pharmacist_dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
  <a href="manage_medicines.php"><i class="bi bi-capsule"></i> Manage Medicines</a>
  <a href="pharmacist_sales.php"><i class="bi bi-cash-stack"></i> Sales</a>
  <a href="purchases.php" class="active"><i class="bi bi-truck"></i> Purchases</a>
  <a href="suppliers.php"><i class="bi bi-people"></i> Suppliers</a>
  <a href="pharmacist_report.php"><i class="bi bi-bar-chart"></i> Reports</a>
  <a href="home.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
</nav>

<div class="main-content">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-truck me-2"></i>Purchases</h2>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#purchaseModal" onclick="resetForm()"><i class="bi bi-plus-circle me-2"></i>New Purchase</button>
  </div>

  <div class="card table-responsive">
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
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
      <?php if($purchases): foreach($purchases as $purchase): ?>
        <tr>
          <td><?= $purchase['id'] ?></td>
          <td><?= htmlspecialchars($purchase['supplier']) ?></td>
          <td><?= htmlspecialchars($purchase['brand_name']) ?></td>
          <td><?= $purchase['quantity'] ?></td>
          <td>Rs <?= number_format($purchase['price'],2) ?></td>
          <td>Rs <?= number_format($purchase['total_price'],2) ?></td>
          <td><?= $purchase['purchase_date'] ?></td>
          <td>
            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#purchaseModal" onclick='editPurchase(<?= json_encode($purchase) ?>)'><i class="bi bi-pencil-square"></i></button>
            <a href="?delete=<?= $purchase['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')"><i class="bi bi-trash"></i></a>
          </td>
        </tr>
      <?php endforeach; else: ?>
        <tr><td colspan="8" class="text-center text-muted">No purchases found.</td></tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Add/Edit Purchase Modal -->
<div class="modal fade" id="purchaseModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" id="purchaseForm">
        <div class="modal-header bg-primary text-white">
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

function updatePriceAndTotal(){
  const brand=document.getElementById('brand_name').value;
  const qty=parseInt(document.getElementById('quantity').value)||0;
  const medicine=medicinesData.find(m=>m.brand_name===brand);
  const price=medicine? (parseFloat(medicine.unit_price)*0.8).toFixed(2):0;
  const total=(price*qty).toFixed(2);

  document.getElementById('price').value=price;
  document.getElementById('total_price').value=total;

  document.getElementById('priceTotalDisplay').innerHTML=`<strong>Price:</strong> Rs ${price} &nbsp; | &nbsp; <strong>Total:</strong> Rs ${total}`;
}

document.getElementById('brand_name').addEventListener('change',updatePriceAndTotal);
document.getElementById('quantity').addEventListener('input',updatePriceAndTotal);
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
