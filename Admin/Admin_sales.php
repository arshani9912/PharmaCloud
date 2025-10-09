<?php
session_start();
include 'connect.php';

// Fetch all medicines
$medicines = $conn->query("SELECT medicine_id, name, brand_name, unit_price, quantity FROM medicines ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin - Sales | PharmaCloud</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<style>
body { font-family: 'Poppins', sans-serif; background-color: #f4f6fc; margin: 0; display: flex; min-height: 100vh; }

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
.sidebar h3 { font-weight: 600; margin-bottom: 20px; text-align: center; border-bottom: 1px solid #444; padding: 20px 0; }
.sidebar a { color: #e2e8e8ff; text-decoration: none; display: block; padding: 15px 25px; font-size: 1rem; margin: 4px 8px; border-radius: 8px; transition: background 0.3s, color 0.3s; }
.sidebar a:hover, .sidebar a.active { background-color: #495057; color: #fff; }

/* Main Content */
.main-content { flex: 1; margin-left: 250px; padding: 30px; }
.topbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
.card { border-radius: 12px; border: none; margin-bottom: 20px; background-color: #fff; }

/* Scrollable Tables */
.table-container { overflow-y: auto; overflow-x: auto; border-radius: 10px; margin-bottom: 20px; }
.table thead th { background-color: #343a40; color: #fff; position: sticky; top: 0; z-index: 10; }

/* Badges for stock */
.badge-low { background-color: #ffc107; color:#212529; }
.badge-out { background-color: #dc3545; }
.badge-available { background-color: #28a745; }

/* Buttons */
.btn-primary { background-color: #343a40; border: none; color: #fff; }
.btn-primary:hover { background-color: #495057; }
.btn-success { background-color: #28a745; border: none; color: #fff; }
.btn-success:hover { background-color: #1e7e34; }
.btn-outline-danger { border-color: #dc3545; color: #dc3545; }
.btn-outline-danger:hover { background-color: #dc3545; color: #fff; }

/* Inputs */
.invoice-input { width: 80px; border-radius: 6px; border: 1px solid #ccc; padding: 2px 5px; }
</style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
  <h3><i class="bi bi-person-circle"></i> Admin</h3>
  <a href="Admin_dashboard.php"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
  <a href="users.php"><i class="bi bi-people me-2"></i> Users</a>
  <a href="Admin_sales.php" class="active"><i class="bi bi-receipt me-2"></i> Sales</a>
  <a href="Admin_purchases.php"><i class="bi bi-basket me-2"></i> Purchases</a>
  <a href="Admin_medicines.php"><i class="bi bi-box-seam me-2"></i> Medicines</a>
  <a href="Admin_reports.php"><i class="bi bi-bar-chart-line me-2"></i> Reports</a>
  <a href="settings.php"><i class="bi bi-gear me-2"></i> Settings</a>
  <a href="home.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
</div>

<div class="main-content">
  <div class="topbar">
    <h2 class="fw-bold"><i class="bi bi-receipt me-2"></i>Manage Sales</h2>
  </div>

  <!-- Invoice Table -->
  <div class="card shadow-sm">
    <h5 class="mb-2">Invoice</h5>
    <div class="table-container" id="invoiceContainer">
      <table class="table table-bordered table-hover align-middle" id="salesTable">
        <thead>
          <tr>
            <th>Brand Name</th>
            <th>Medicine Name</th>
            <th>Qty</th>
            <th>Unit Price</th>
            <th>Total Price</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody></tbody>
        <tfoot>
          <tr>
            <th colspan="4" class="text-end">Total Amount:</th>
            <th id="totalAmount">0.00</th>
            <th></th>
          </tr>
        </tfoot>
      </table>
    </div>
    <button id="printInvoice" class="btn btn-primary mt-2">Print Invoice</button>
  </div>

  <!-- Medicines Table -->
  <div class="card shadow-sm mt-3">
    <h5 class="mb-2">Medicines</h5>
    <input type="text" id="medicineSearch" class="form-control mb-2" placeholder="Search by Medicine or Brand...">
    <div class="table-container" id="medicinesContainer">
      <table class="table table-hover align-middle" id="medicinesTable">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Brand</th>
            <th>Status</th>
            <th>Stock</th>
            <th>Unit Price</th>
            <th>Qty</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($medicines as $med):
            if($med['quantity']==0) $status='<span class="badge badge-out">Out of stock</span>';
            elseif($med['quantity']<50) $status='<span class="badge badge-low">Low stock</span>';
            else $status='<span class="badge badge-available">Available</span>';
          ?>
          <tr data-stock="<?= $med['quantity'] ?>" data-price="<?= $med['unit_price'] ?>" data-name="<?= htmlspecialchars($med['name']) ?>" data-brand="<?= htmlspecialchars($med['brand_name']) ?>">
            <td><?= $med['medicine_id'] ?></td>
            <td><?= htmlspecialchars($med['name']) ?></td>
            <td><?= htmlspecialchars($med['brand_name']) ?></td>
            <td><?= $status ?></td>
            <td><?= $med['quantity'] ?></td>
            <td><?= number_format($med['unit_price'],2) ?></td>
            <td><input type="number" class="form-control rowQty" min="1" value="1"></td>
            <td><button class="btn btn-success btn-sm addToInvoice">Add</button></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
let sales = [];

// Update price on selection
$('#medicineSelect').change(function(){
  let price = $(this).find(':selected').data('price') || 0;
  $('#price').val(price);
});

// Add to invoice
$(document).on('click', '.addToInvoice', function(){
  const row = $(this).closest('tr');
  const medId = row.find('td:first').text();
  const name = row.data('name');
  const brand = row.data('brand');
  const unitPrice = parseFloat(row.data('price'));
  const stock = parseInt(row.data('stock'));
  const qty = parseInt(row.find('.rowQty').val());

  if(qty<1 || qty>stock){ alert('Invalid quantity'); return; }
  sales.push({medId, brand, name, qty, unitPrice});
  renderTable();
});

// Render invoice table
function renderTable(){
  const tbody = $('#salesTable tbody');
  tbody.empty();
  let totalAmount = 0;
  sales.forEach((item, idx)=>{
    totalAmount += item.qty * item.unitPrice;
    tbody.append(`
      <tr>
        <td>${item.brand}</td>
        <td>${item.name}</td>
        <td><input type="number" class="invoice-input qtyInput" data-index="${idx}" value="${item.qty}" min="1"></td>
        <td><input type="number" class="invoice-input priceInput" data-index="${idx}" value="${item.unitPrice.toFixed(2)}" min="0" step="0.01"></td>
        <td class="totalPrice">${(item.qty*item.unitPrice).toFixed(2)}</td>
        <td><button class="btn btn-sm btn-outline-danger" onclick="removeItem(${idx})">Remove</button></td>
      </tr>
    `);
  });
  $('#totalAmount').text(totalAmount.toFixed(2));
}

// Remove item
function removeItem(idx){ sales.splice(idx,1); renderTable(); }

// Editable qty/price
$(document).on('input', '.qtyInput, .priceInput', function(){
  const idx = $(this).data('index');
  const row = $(this).closest('tr');
  const inputQty = parseInt(row.find('.qtyInput').val());
  const inputPrice = parseFloat(row.find('.priceInput').val());
  const medId = sales[idx].medId;
  const stockRow = $(`#medicinesTable tbody tr`).filter(function(){
    return $(this).find('td:first').text()==medId;
  });
  const availableStock = parseInt(stockRow.data('stock'));
  if(inputQty>availableStock){ alert(`Quantity cannot exceed stock (${availableStock})`); row.find('.qtyInput').val(availableStock); sales[idx].qty=availableStock; }
  else if(inputQty<1){ row.find('.qtyInput').val(1); sales[idx].qty=1; }
  else sales[idx].qty=inputQty;
  sales[idx].unitPrice = isNaN(inputPrice)||inputPrice<0 ? 0 : inputPrice;
  renderTable();
});

// Print invoice
$('#printInvoice').click(function(){
  if(sales.length==0){ alert('No products added'); return; }
  let printWindow = window.open('','', 'height=600,width=800');
  let html = `<h3>Invoice</h3><table border="1" width="100%" cellspacing="0" cellpadding="5">
    <tr><th>Brand</th><th>Medicine</th><th>Qty</th><th>Unit Price</th><th>Total</th></tr>`;
  let totalAmount = 0;
  sales.forEach(item=>{
    html += `<tr>
      <td>${item.brand}</td>
      <td>${item.name}</td>
      <td>${item.qty}</td>
      <td>${item.unitPrice.toFixed(2)}</td>
      <td>${(item.qty*item.unitPrice).toFixed(2)}</td>
    </tr>`;
    totalAmount += item.qty*item.unitPrice;
  });
  html += `<tr><th colspan="4">Total Amount</th><th>${totalAmount.toFixed(2)}</th></tr></table>`;
  printWindow.document.write(html);
  printWindow.document.close();
  printWindow.print();
});

// Search medicines
$('#medicineSearch').on('input', function(){
  const filter = $(this).val().toLowerCase();
  $('#medicinesTable tbody tr').each(function(){
    const name = $(this).find('td:nth-child(2)').text().toLowerCase();
    const brand = $(this).find('td:nth-child(3)').text().toLowerCase();
    $(this).toggle(name.includes(filter) || brand.includes(filter));
  });
});

// Table scroll heights
function adjustTableHeight(){
  const invoice = $('#invoiceContainer');
  const invoiceTable = invoice.find('table');
  const invoiceRows = invoiceTable.find('tbody tr');
  if(invoiceRows.length>0){
    const rowHeight = invoiceRows.first().outerHeight();
    const headerHeight = invoiceTable.find('thead').outerHeight();
    invoice.css('max-height', headerHeight + rowHeight*8 + 'px');
  }
  const meds = $('#medicinesContainer');
  const medsTable = meds.find('table');
  const medsRows = medsTable.find('tbody tr');
  if(medsRows.length>0){
    const rowHeight = medsRows.first().outerHeight();
    const headerHeight = medsTable.find('thead').outerHeight();
    meds.css('max-height', headerHeight + rowHeight*4 + 'px');
  }
}
$(window).on('load resize', adjustTableHeight);
</script>

</body>
</html>
