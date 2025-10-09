<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Customer Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      min-height: 100vh;
      display: flex;
      font-family: Arial, sans-serif;
      background-color: #f8f9fa;
    }
    /* ✅ Green Sidebar Theme */
    .sidebar {
      width: 240px;
      background-color: #28a745;
      color: #fff;
      height: 100vh;
      position: fixed;
      top: 0;
      left: 0;
      padding-top: 20px;
    }
    .sidebar h3 {
      font-weight: 600;
      margin-bottom: 1rem;
      text-align: center;
    }
    .sidebar a {
      color: #fff;
      text-decoration: none;
      display: block;
      padding: 15px 25px;
      font-size: 1rem;
      border-radius: 5px;
      margin: 4px 8px;
    }
    .sidebar a:hover {
      background-color: #218838;
    }
    .content {
      margin-left: 240px;
      flex: 1;
      padding: 25px;
    }
    .card {
      border-radius: 10px;
    }

    /* ✅ Unified Green Dashboard Cards */
    .bg-green {
      background: linear-gradient(135deg, #28a745, #218838);
      color: #fff;
    }
    .bg-light-green {
      background: linear-gradient(135deg, #6ccf79, #28a745);
      color: #fff;
    }

    table th, table td {
      vertical-align: middle;
    }
    .badge-delivered {
      background-color: #28a745;
    }
    .badge-pending {
      background-color: #ffc107;
      color: #212529;
    }
    .badge-cancelled {
      background-color: #dc3545;
    }

    /* Medicine Cards */
    .medicine-card .card {
      margin-bottom: 20px;
      color: #fff;
    }
    .card-anticoagulant { background: linear-gradient(135deg, #6f42c1, #5a32a3); }
    .card-vitamin { background: linear-gradient(135deg, #fd7e14, #e8590c); }
    .card-cholesterol { background: linear-gradient(135deg, #20c997, #198754); }
    .card-pressure { background: linear-gradient(135deg, #0dcaf0, #0b8ea8); }
    .card-antacid { background: linear-gradient(135deg, #20c997, #198754); }
    .card-antidiabetic { background: linear-gradient(135deg, #fd7e14, #e8590c); }
    .card-antimigraine { background: linear-gradient(135deg, #ffc107, #e0a800); }
    .card-antiplatelet { background: linear-gradient(135deg, #6610f2, #520dc2); }
    .card-antipsychotic { background: linear-gradient(135deg, #dc3545, #a71d2a); }
    .usage-icon { font-size: 0.9rem; display: flex; align-items: center; gap: 6px; }
    .search-bar { max-width: 500px; margin: 20px auto; }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar d-flex flex-column">
    <h3 class="py-4"><i class="bi bi-person-circle"></i> Customer</h3>
    <a href="#dashboard"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
    <a href="#profile"><i class="bi bi-person me-2"></i> Profile</a>
    <a href="#orders"><i class="bi bi-basket me-2"></i> My Orders</a>
    <a href="#medicines"><i class="bi bi-box-seam me-2"></i> Available Medicines</a>
    <a href="#medicineInfo"><i class="bi bi-info-circle me-2"></i> Medicine Info</a>
    <a href="home.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
  </div>

  <!-- Main Content -->
  <div class="content">

    <!-- Dashboard -->
    <section id="dashboard">
      <h2 class="mb-4">Welcome, <strong>Arshani Muthumali</strong>!</h2>
      <div class="row g-3">
        <div class="col-md-4">
          <div class="card bg-green">
            <div class="card-body">
              <h5 class="card-title"><i class="bi bi-basket me-2"></i>Total Orders</h5>
              <p class="card-text fs-4">5</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card bg-light-green">
            <div class="card-body">
              <h5 class="card-title"><i class="bi bi-clock-history me-2"></i>Pending Orders</h5>
              <p class="card-text fs-4">2</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card bg-success text-white">
            <div class="card-body">
              <h5 class="card-title"><i class="bi bi-check2-circle me-2"></i>Delivered</h5>
              <p class="card-text fs-4">3</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <hr>

    <!-- Profile -->
    <section id="profile">
      <h3 class="mb-3"><i class="bi bi-person-lines-fill me-2"></i>Profile</h3>
      <form>
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Username</label>
            <input type="text" class="form-control" value="Arshani Muthumali" readonly>
          </div>
          <div class="col-md-6">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" value="arshani@example.com" readonly>
          </div>
          <div class="col-md-6">
            <label class="form-label">Phone</label>
            <input type="text" class="form-control" value="+94 712345678">
          </div>
          <div class="col-md-6 d-flex align-items-end">
            <button type="submit" class="btn btn-success w-100">Update Profile</button>
          </div>
        </div>
      </form>
    </section>

    <hr>

    <!-- Orders -->
    <section id="orders">
      <h3 class="mb-3"><i class="bi bi-basket-fill me-2"></i>My Orders</h3>
      <table class="table table-hover align-middle">
        <thead class="table-dark">
          <tr>
            <th>Order ID</th>
            <th>Medicine</th>
            <th>Quantity</th>
            <th>Status</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>101</td>
            <td>Paracetamol</td>
            <td>2</td>
            <td><span class="badge badge-delivered">Delivered</span></td>
            <td>2025-09-15</td>
          </tr>
          <tr>
            <td>102</td>
            <td>Amoxicillin</td>
            <td>1</td>
            <td><span class="badge badge-pending">Pending</span></td>
            <td>2025-09-16</td>
          </tr>
          <tr>
            <td>103</td>
            <td>Ibuprofen</td>
            <td>3</td>
            <td><span class="badge badge-cancelled">Cancelled</span></td>
            <td>2025-09-12</td>
          </tr>
        </tbody>
      </table>
    </section>

    <hr>

    <!-- Available Medicines -->
    <section id="medicines">
      <h3 class="mb-3"><i class="bi bi-box-seam me-2"></i>Available Medicines</h3>
      <table class="table table-striped align-middle">
        <thead class="table-dark">
          <tr>
            <th>Medicine ID</th>
            <th>Name</th>
            <th>Category</th>
            <th>Price (LKR)</th>
            <th>Stock</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>201</td>
            <td>Amoxicillin</td>
            <td>Antibiotic</td>
            <td>250</td>
            <td>50</td>
          </tr>
          <tr>
            <td>202</td>
            <td>Paracetamol</td>
            <td>Painkiller</td>
            <td>150</td>
            <td>100</td>
          </tr>
          <tr>
            <td>203</td>
            <td>Ibuprofen</td>
            <td>Painkiller</td>
            <td>200</td>
            <td>30</td>
          </tr>
        </tbody>
      </table>
    </section>

    <hr>

    <!-- Medicine Info -->
    <section id="medicineInfo">
      <h3 class="mb-3"><i class="bi bi-info-circle me-2"></i>Medicine Information</h3>
      <div class="search-bar input-group mb-3">
        <input type="text" class="form-control" placeholder="Search medicine..." id="medicineSearch">
        <button class="btn btn-success"><i class="bi bi-search"></i></button>
      </div>
      <div class="row" id="medicineContainer">
        <!-- Example medicine card -->
        <div class="col-md-6" data-name="warfarin">
          <div class="card card-anticoagulant p-3">
            <h5>Warfarin</h5>
            <p class="usage-icon"><i class="bi bi-clock"></i> Take as prescribed, usually once daily</p>
            <p class="usage-icon"><i class="bi bi-cup-straw"></i> Usually after meals</p>
          </div>
        </div>
        <div class="col-md-6" data-name="rivoxaban">
          <div class="card card-anticoagulant p-3">
            <h5>Rivoxaban</h5>
            <p class="usage-icon"><i class="bi bi-clock"></i> Once daily</p>
            <p class="usage-icon"><i class="bi bi-cup-straw"></i> With or after meal</p>
          </div>
        </div>
        <!-- Add more medicine cards like vitamins, cholesterol, pressure drugs, antacid, anti-diabetic, anti-migraine, anti-platelet, anti-psychotic -->
      </div>
    </section>

  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Filter medicine cards
    document.getElementById('medicineSearch').addEventListener('keyup', function() {
      let filter = this.value.toLowerCase();
      const cards = document.querySelectorAll('#medicineContainer .col-md-6');
      cards.forEach(card => {
        const name = card.dataset.name.toLowerCase();
        card.style.display = name.includes(filter) ? '' : 'none';
      });
    });
  </script>
</body>
</html>
