<?php
include 'connect.php';

if(!isset($_GET['sale_id'])) die('Sale ID required');
$sale_id = (int)$_GET['sale_id'];

// Fetch sale info
$saleStmt = $conn->prepare("SELECT * FROM sales WHERE sale_id = :id");
$saleStmt->execute([':id' => $sale_id]);
$sale = $saleStmt->fetch(PDO::FETCH_ASSOC);
if(!$sale) die('Sale not found');

// Fetch sale items
$itemsStmt = $conn->prepare("SELECT si.*, m.name, m.brand_name 
                             FROM sale_items si 
                             JOIN medicines m ON si.medicine_id = m.medicine_id 
                             WHERE si.sale_id = :id");
$itemsStmt->execute([':id' => $sale_id]);
$items = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Invoice #<?= $sale_id ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { padding: 30px; font-family: Arial, sans-serif; }
        .invoice-header { text-align: center; margin-bottom: 20px; }
        .invoice-header h2 { margin-bottom: 0; }
        .invoice-header p { margin: 0; }
        .table th, .table td { vertical-align: middle; }
        .total-row th, .total-row td { font-weight: bold; }
        .thank-you { text-align: center; margin-top: 30px; font-size: 1.1rem; }
    </style>
</head>
<body onload="window.print()">

<div class="invoice-header">
    <h2>My Pharmacy Name</h2>
    <p>123 Main Street, City, Country</p>
    <p>Phone: 0123456789 | Email: info@mypharmacy.com</p>
    <hr>
</div>

<div class="row mb-3">
    <div class="col-md-6"><strong>Invoice #: </strong><?= $sale_id ?></div>
    <div class="col-md-6 text-end"><strong>Date: </strong><?= date('d-m-Y H:i', strtotime($sale['sale_date'])) ?></div>
</div>
<div class="row mb-3">
    <div class="col-md-12"><strong>Patient Name: </strong><?= htmlspecialchars($sale['patient_name']) ?></div>
</div>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Brand</th>
            <th>Medicine</th>
            <th>Qty</th>
            <th>Unit Price</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($items as $item) { ?>
        <tr>
            <td><?= $item['brand_name'] ?></td>
            <td><?= $item['name'] ?></td>
            <td><?= $item['qty'] ?></td>
            <td><?= number_format($item['unit_price'],2) ?></td>
            <td><?= number_format($item['total_price'],2) ?></td>
        </tr>
        <?php } ?>
    </tbody>
    <tfoot>
        <tr class="total-row">
            <th colspan="4" class="text-end">Total Amount</th>
            <th><?= number_format($sale['total_amount'],2) ?></th>
        </tr>
    </tfoot>
</table>

<div class="thank-you">
    <p>Thank you for your purchase!</p>
    <p>Get well soon!</p>
</div>

</body>
</html>
