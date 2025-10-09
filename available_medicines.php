<?php
include 'connect.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if(isset($_POST['action'])){
    $action = $_POST['action'];

    if($action=='fetch'){
        $stmt=$conn->query("SELECT * FROM medicines ORDER BY medicine_id ASC");
        echo json_encode($stmt->fetchAll());
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Available Medicines - PharmaCloud</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
body {
    font-family: Arial, sans-serif;
    background-color: #f8f9fa;
    min-height: 100vh;
    display: flex;
    margin: 0;
}

/* Sidebar */
.sidebar {
    width: 240px;
    background-color: #198754;
    color: #fff;
    height: 100vh;
    position: fixed;
    top: 0;
    left: 0;
    padding-top: 20px;
}
.sidebar h3 {
    font-weight: 600;
    text-align: center;
    margin-bottom: 1rem;
}
.sidebar a {
    color: #fff;
    text-decoration: none;
    display: block;
    padding: 15px 25px;
    font-size: 1rem;
    transition: background 0.3s, padding-left 0.3s;
}
.sidebar a:hover,
.sidebar a.active {
    background-color: #157347;
    padding-left: 30px;
    border-radius: 8px;
}

/* Main content */
.content {
    margin-left: 240px;
    flex: 1;
    padding: 30px;
}

h2.section-title {
    font-weight: 600;
    margin-bottom: 20px;
    color: #333;
}

.card {
    border-radius: 10px;
    margin-bottom: 20px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.table th {
    background-color: #20c997;
    color: #fff;
}
.table td {
    vertical-align: middle;
}
.low-stock {
    background-color: #ffe5e5;
}
.input-group-text {
    background-color: #198754;
    color: #fff;
    border: none;
}
#medicineSearch {
    border-radius: 0.375rem;
}
</style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar d-flex flex-column">
<h3 class="py-4"><i class="bi bi-person-circle"></i> Customer</h3>
<a href="customer_dashboard.php"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
<a href="customer_profile.php"><i class="bi bi-person me-2"></i> Profile</a>
<a href="stock_status.php"><i class="bi bi-basket me-2"></i> Stock Status</a>
<a href="available_medicines.php"><i class="bi bi-box-seam me-2"></i> Available Medicines</a>
<a href="medicine_instructions.php"><i class="bi bi-capsule me-2"></i> Medicine Instructions</a>
<a href="home.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
</div>

<!-- Main Content -->
<main class="content">
<div class="d-flex justify-content-between align-items-center mb-4">
  <h2 class="section-title">Available Medicines</h2>
</div>

<div class="input-group mb-3" style="max-width: 500px;">
  <span class="input-group-text"><i class="fas fa-search"></i></span>
  <input type="text" id="medicineSearch" class="form-control" placeholder="Search medicines...">
</div>

<div class="card p-3">
<table class="table table-bordered table-hover align-middle" id="medicineTable">
<thead>
<tr>
  <th>ID</th>
  <th>Name</th>
  <th>Brand</th>
  <th>Description</th>
  <th>Price</th>
  <th>Qty</th>
  <th>Expiry</th>
</tr>
</thead>
<tbody></tbody>
</table>
</div>
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
    function fetchMedicines(){
        $.post('available_medicines.php',{action:'fetch'},function(data){
            let rows='';
            JSON.parse(data).forEach(m=>{
                let low=m.quantity<=10?'low-stock':'';
                rows+=`<tr class="${low}">
                    <td>${m.medicine_id}</td>
                    <td>${m.name}</td>
                    <td>${m.brand_name||''}</td>
                    <td>${m.description||''}</td>
                    <td>${m.unit_price}</td>
                    <td>${m.quantity}</td>
                    <td>${m.expiry_date||''}</td>
                </tr>`;
            });
            $('#medicineTable tbody').html(rows);
        });
    }

    fetchMedicines();

    $('#medicineSearch').on('keyup', function(){
        let val=$(this).val().toLowerCase();
        $('#medicineTable tbody tr').filter(function(){
            $(this).toggle($(this).text().toLowerCase().indexOf(val) > -1);
        });
    });
});
</script>
</body>
</html>
