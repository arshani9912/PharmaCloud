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
<title>Pharmacist - Sales | PharmaCloud</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<style>
body { font-family: 'Poppins', sans-serif; background-color: #f5f7fa; margin: 0; display: flex; min-height: 100vh; }

/* Sidebar (keep blue) */
.sidebar { width: 240px; background-color: #0d6efd; color: #fff; display: flex; flex-direction: column; padding-top: 30px; position: fixed; top: 0; bottom: 0; }
.sidebar-profile { text-align: center; margin-bottom: 30px; }
.sidebar-profile h3 { color: #fff; margin: 0; padding: 20px 0; font-weight: 600; }
.sidebar a { color: #fff; text-decoration: none; display: flex; align-items: center; padding: 12px 20px; border-radius: 8px; margin-bottom: 5px; transition: 0.3s; }
.sidebar a i { margin-right: 10px; min-width: 20px; text-align: center; font-size: 1.1rem; }
.sidebar a:hover, .sidebar a.active { background: rgba(255,255,255,0.2); }

/* Main Content */
.main-content { flex: 1; margin-left: 240px; padding: 30px; }

/* Cards */
.card { border-radius: 15px; border: none; margin-bottom: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); padding: 20px; background-color: #fff; }

/* Scrollable Tables */
.table-container { overflow-y: auto; overflow-x: auto; border-radius: 10px; margin-bottom: 20px; }
.table-container thead th { position: sticky; top: 0; background-color: #0d6efd; color: white; z-index: 10; }

/* Tables */
.table th, .table td { vertical-align: middle; }
.table-hover tbody tr:hover { background-color: #f1f1f1; }

/* Buttons */
.btn-primary { background-color: #28a745; border: none; color: #fff; }
.btn-primary:hover { background-color: #218838; }
.btn-success { background-color: #ffc107; border: none; color: #212529; }
.btn-success:hover { background-color: #e0a800; }
.btn-outline-danger { border-color: #dc3545; color: #dc3545; }
.btn-outline-danger:hover { background-color: #dc3545; color: #fff; }

/* Inputs */
.invoice-input { width: 80px; border-radius: 6px; border: 1px solid #ccc; padding: 2px 5px; }

/* Badges for stock */
.badge-low { background-color: #ffc107; color:#212529; }
.badge-out { background-color: #dc3545; }
.badge-available { background-color: #28a745; }
</style>
</head>
<body>

<!-- Sidebar -->
<nav class="sidebar">
  <div class="sidebar-profile"> 
    <h3><i class="bi bi-person-circle"></i> Pharmacist</h3>
  </div>
  <a href="pharmacist_dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
  <a href="manage_medicines.php"><i class="fas fa-pills"></i> Manage Medicines</a>
  <a href="pharmacist_sales.php" class="active"><i class="bi bi-wallet2"></i> Sales</a>
  <a href="purchases.php"><i class="bi bi-truck"></i> Purchases</a>
  <a href="suppliers.php"><i class="bi bi-people"></i> Suppliers</a>
  <a href="pharmacist_medicine_instructions.php"><i class="bi bi-capsule me-2"></i> Medicine Instructions</a>
  <a href="reports.php"><i class="bi bi-bar-chart-line"></i> Reports</a>
  <a href="home.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
</nav>

<div class="main-content">
<h2 class="mb-4"><i class="bi bi-receipt me-2"></i> Manage Sales</h2>

<!-- Invoice Table -->
<div class="card">
    <h5>Invoice</h5>
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
<div class="card">
    <h5>Medicines</h5>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
// Medicine search
$('#medicineSearch').on('input', function() {
    const filter = $(this).val().toLowerCase();
    $('#medicinesTable tbody tr').each(function() {
        const name = $(this).find('td:nth-child(2)').text().toLowerCase();
        const brand = $(this).find('td:nth-child(3)').text().toLowerCase();
        $(this).toggle(name.includes(filter) || brand.includes(filter));
    });
});

// Adjust table height
function adjustTableHeight() {
    // Invoice table ~8 rows
    const invoice = $('#invoiceContainer');
    const invoiceTable = invoice.find('table');
    const invoiceRows = invoiceTable.find('tbody tr');
    if(invoiceRows.length > 0){
        const rowHeight = invoiceRows.first().outerHeight();
        const headerHeight = invoiceTable.find('thead').outerHeight();
        invoice.css('max-height', headerHeight + rowHeight * 8 + 'px');
    }

    // Medicines table ~4 rows
    const meds = $('#medicinesContainer');
    const medsTable = meds.find('table');
    const medsRows = medsTable.find('tbody tr');
    if(medsRows.length > 0){
        const rowHeight = medsRows.first().outerHeight();
        const headerHeight = medsTable.find('thead').outerHeight();
        meds.css('max-height', headerHeight + rowHeight * 4 + 'px');
    }
}
$(window).on('load resize', adjustTableHeight);

// Sales functionality
let sales = [];
function renderSalesTable() {
    const tbody = $('#salesTable tbody');
    tbody.empty();
    let totalAmount = 0;
    sales.forEach((item, idx) => {
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
function removeItem(idx){ sales.splice(idx,1); renderSalesTable(); }

// Add medicine to invoice
$(document).on('click', '.addToInvoice', function(){
    const row = $(this).closest('tr');
    const medId = row.find('td:first').text();
    const name = row.data('name');
    const brand = row.data('brand');
    const unitPrice = parseFloat(row.data('price'));
    const stock = parseInt(row.data('stock'));
    const qty = parseInt(row.find('.rowQty').val());

    if(qty < 1 || qty > stock){ alert('Invalid quantity'); return; }

    sales.push({medId, brand, name, qty, unitPrice});
    renderSalesTable();
});

// Editable Qty & Price in invoice with stock validation
$(document).on('input', '.qtyInput, .priceInput', function(){
    const idx = $(this).data('index');
    const row = $(this).closest('tr');
    const inputQty = parseInt(row.find('.qtyInput').val());
    const inputPrice = parseFloat(row.find('.priceInput').val());
    const medId = sales[idx].medId;

    const stockRow = $(`#medicinesTable tbody tr`).filter(function() {
        return $(this).find('td:first').text() == medId;
    });
    const availableStock = parseInt(stockRow.data('stock'));

    if(inputQty > availableStock){
        alert(`Quantity cannot exceed available stock (${availableStock})`);
        row.find('.qtyInput').val(availableStock);
        sales[idx].qty = availableStock;
    } else if(inputQty < 1){
        row.find('.qtyInput').val(1);
        sales[idx].qty = 1;
    } else {
        sales[idx].qty = inputQty;
    }

    sales[idx].unitPrice = isNaN(inputPrice) || inputPrice < 0 ? 0 : inputPrice;
    renderSalesTable();
});

// Print invoice
$('#printInvoice').click(function(){
    if(sales.length == 0) { alert('No products added'); return; }
    let printWindow = window.open('', '', 'height=600,width=800');
    let html = `<h3>Invoice</h3><table border="1" width="100%" cellspacing="0" cellpadding="5">
        <tr><th>Brand</th><th>Medicine</th><th>Qty</th><th>Unit Price</th><th>Total</th></tr>`;
    let totalAmount = 0;
    sales.forEach(item => {
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
</script>
</body>
</html>
