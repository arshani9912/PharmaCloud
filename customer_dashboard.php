<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Customer Dashboard - PharmaCloud</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f8f9fa;
      min-height: 100vh;
      display: flex;
    }

    /* ✅ Sidebar */
    .sidebar {
      width: 240px;
      background-color: #198754; /* Green theme */
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
      background-color: #157347; /* Darker green */
      padding-left: 30px;
    }

    /* ✅ Main content */
    .content {
      margin-left: 240px;
      flex: 1;
      padding: 30px;
    }

    h2.section-title, h3.section-title {
      font-weight: 600;
      margin-bottom: 20px;
      color: #333;
    }

    /* ✅ Cards */
    .card {
      border-radius: 10px;
      margin-bottom: 20px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      transition: transform 0.3s, box-shadow 0.3s;
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 25px rgba(0,0,0,0.2);
    }

    .stat-card {
      color: #fff;
      text-align: center;
      padding: 20px;
      border-radius: 10px;
    }

    .bg-gradient-success {
      background: linear-gradient(135deg, #28a745, #198754);
    }

    .bg-gradient-warning {
      background: linear-gradient(135deg, #ffc107, #e0a800);
      color: #212529;
    }

    .bg-gradient-danger {
      background: linear-gradient(135deg, #dc3545, #c82333);
    }

    /* ✅ Feature cards */
    .feature-card {
      border: 1px solid #e0e0e0;
      border-radius: 10px;
      transition: transform 0.3s, box-shadow 0.3s;
      background: #fff;
      text-align: center;
      padding: 20px;
      cursor: pointer;
    }

    .feature-card:hover {
      transform: scale(1.05);
      box-shadow: 0 0 20px rgba(25,135,84,0.5);
    }

    /* ✅ Carousel */
    .carousel-item {
      border-radius: 10px;
    }

    .carousel-item h5 {
      font-weight: 600;
      color: #198754;
    }
  </style>
</head>

<body>

  <!-- ✅ Sidebar -->
  <div class="sidebar d-flex flex-column">
    <h3 class="py-4"><i class="bi bi-person-circle"></i> Customer</h3>
    <a href="customer_dashboard.php"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
    <a href="customer_profile.php"><i class="bi bi-person me-2"></i> Profile</a>
    <a href="stock_status.php"><i class="bi bi-basket me-2"></i> Stock Status</a>
    <a href="available_medicines.php"><i class="bi bi-box-seam me-2"></i> Available Medicines</a>
    <a href="medicine_instructions.php"><i class="bi bi-capsule me-2"></i> Medicine Instructions</a>
    <a href="home.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
  </div>

  <!-- ✅ Main Content -->
  <main class="content">
    <section class="mb-4">
      <h2 class="section-title">Welcome back, <strong>Arshani Muthumali</strong>!</h2>
      <p class="text-muted">Here’s a quick overview of the pharmacy stock and important medicine alerts.</p>
    </section>

    <!-- Stats Cards -->
    <section class="mb-4">
      <div class="row g-3">
        <div class="col-md-4">
          <div class="stat-card bg-gradient-success">
            <i class="bi bi-box-seam fs-2 mb-2"></i>
            <h6>Available Medicines</h6>
            <p class="fs-5 mb-0">120</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="stat-card bg-gradient-danger">
            <i class="bi bi-x-circle fs-2 mb-2"></i>
            <h6>Out-of-Stock</h6>
            <p class="fs-5 mb-0">8</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="stat-card bg-gradient-warning">
            <i class="bi bi-exclamation-circle fs-2 mb-2"></i>
            <h6>Nearly Expired (30 days)</h6>
            <p class="fs-5 mb-0">5</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Quick Actions -->
    <section class="mb-4">
      <h3 class="section-title">Quick Actions</h3>
      <div class="row g-3">
        <div class="col-md-3">
          <div class="feature-card">
            <i class="bi bi-bell fs-2 mb-2 text-warning"></i>
            <h6>Notifications</h6>
            <p class="small text-muted">View alerts</p>
          </div>
        </div>
        <div class="col-md-3">
          <div class="feature-card">
            <i class="bi bi-info-circle fs-2 mb-2 text-primary"></i>
            <h6>Medicine Info</h6>
            <p class="small text-muted">Check medicine details</p>
          </div>
        </div>
        <div class="col-md-3">
          <div class="feature-card">
            <i class="bi bi-exclamation-triangle fs-2 mb-2 text-danger"></i>
            <h6>Stock Alerts</h6>
            <p class="small text-muted">See low stock medicines</p>
          </div>
        </div>
        <div class="col-md-3">
          <div class="feature-card">
            <i class="bi bi-headset fs-2 mb-2 text-success"></i>
            <h6>Support</h6>
            <p class="small text-muted">Contact support</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Health Tips -->
    <section class="mt-5">
      <h3 class="section-title">Health Tips</h3>
      <div id="healthTipsCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
          <div class="carousel-item active text-center p-4 shadow-sm" style="background-color:#d0f0fd;">
            <h5>💧 Stay Hydrated</h5>
            <p>Drink at least 8 glasses of water daily.</p>
          </div>
          <div class="carousel-item text-center p-4 shadow-sm" style="background-color:#fff3cd;">
            <h5>💊 Take Medicines on Time</h5>
            <p>Follow your schedule carefully.</p>
          </div>
          <div class="carousel-item text-center p-4 shadow-sm" style="background-color:#d4edda;">
            <h5>🥦 Eat Healthy</h5>
            <p>Include fruits and vegetables in your meals.</p>
          </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#healthTipsCarousel" data-bs-slide="prev">
          <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#healthTipsCarousel" data-bs-slide="next">
          <span class="carousel-control-next-icon"></span>
        </button>
      </div>
    </section>
  </main>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
