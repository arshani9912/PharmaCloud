<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Customer Profile - PharmaCloud</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
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

    /* Main Content */
    .content {
      margin-left: 240px;
      flex: 1;
      padding: 30px;
    }

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

    h2.section-title {
      font-weight: 600;
      margin-bottom: 20px;
      color: #333;
    }

    label {
      font-weight: 500;
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
    <h2 class="section-title">Profile</h2>

    <div class="card p-4">
      <h5>Personal Information</h5>
      <hr>
      <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" id="name" class="form-control" value="Arshani Muthumali">
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" id="email" class="form-control" value="arshani@example.com" readonly>
      </div>
      <div class="mb-3">
        <label for="phone" class="form-label">Phone</label>
        <input type="text" id="phone" class="form-control" value="071-XXXXXXX">
      </div>

      <button class="btn btn-success">Update Profile</button>
    </div>

  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
