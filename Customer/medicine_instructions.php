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
    /* ✅ Sidebar */
    .sidebar {
      width: 240px;
      background-color: #198754; /* Modern green */
      color: #fff;
      height: 100vh;
      position: fixed;
      top: 0;
      left: 0;
      padding-top: 20px;
    }
    .sidebar h3 {
      font-weight: 600;
    }
    .sidebar a {
      color: #fff;
      text-decoration: none;
      display: block;
      padding: 15px 25px;
      font-size: 1rem;
    }
    .sidebar a:hover {
      background-color: #157347; /* Darker green */
    }
    .content {
      margin-left: 240px;
      flex: 1;
      padding: 25px;
    }
    .card {
      border-radius: 10px;
      margin-bottom: 15px;
    }
    .important {
      border-left: 6px solid #dc3545;
      background: #fff;
      border-radius: 10px;
    }
    .card-title {
      color: #28a745;
      font-weight: bold;
    }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar d-flex flex-column">
    <h3 class="text-center py-4"><i class="bi bi-person-circle"></i> Customer</h3>
    <a href="customer_dashboard.php"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
    <a href="customer_profile.php"><i class="bi bi-person me-2"></i> Profile</a>
    <a href="stock_status.php"><i class="bi bi-basket me-2"></i> Stock Status</a>
    <a href="available_medicines.php"><i class="bi bi-box-seam me-2"></i> Available Medicines</a>
    <a href="medicine_instructions.php"><i class="bi bi-capsule me-2"></i> Medicine Instructions</a>
    <a href="home.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
  </div>

  <!-- Main Content -->
  <div class="content">

    <!-- ✅ Medicine Instructions -->
    <section id="instructions">
      <h3 class="mb-4"><i class="bi bi-capsule me-2"></i>Important Medicine Instructions</h3>
      <div class="row g-4">

        <!-- Warfarin -->
        <div class="col-md-6">
          <div class="card p-3 important">
            <h5 class="card-title">Warfarin</h5>
            <p>✔ Take at the same time daily.</p>
            <p>✔ Requires regular INR blood tests.</p>
            <p>❌ Avoid sudden diet changes (leafy greens, alcohol).</p>
          </div>
        </div>

        <!-- Rivaroxaban -->
        <div class="col-md-6">
          <div class="card p-3 important">
            <h5 class="card-title">Rivaroxaban</h5>
            <p>✔ Take with food to improve absorption.</p>
            <p>✔ Do not skip doses; risk of clotting.</p>
            <p>❌ Avoid double dosing if one is missed.</p>
          </div>
        </div>

        <!-- Thyroxine -->
        <div class="col-md-6">
          <div class="card p-3 important">
            <h5 class="card-title">Thyroxine</h5>
            <p>✔ Take in the morning on an empty stomach.</p>
            <p>❌ Avoid milk, tea, or coffee within 30 mins.</p>
          </div>
        </div>

        <!-- Methotrexate -->
        <div class="col-md-6">
          <div class="card p-3 important">
            <h5 class="card-title">Methotrexate</h5>
            <p>✔ Take only once weekly, never daily.</p>
            <p>✔ Folic acid is given to reduce side effects.</p>
            <p>❌ Strictly avoid alcohol (liver risk).</p>
          </div>
        </div>

        <!-- Folic Acid -->
        <div class="col-md-6">
          <div class="card p-3 important">
            <h5 class="card-title">Folic Acid</h5>
            <p>✔ Take daily, often alongside methotrexate therapy.</p>
            <p>✔ Helps prevent anemia and mouth ulcers.</p>
          </div>
        </div>

        <!-- Carbamazepine -->
        <div class="col-md-6">
          <div class="card p-3 important">
            <h5 class="card-title">Carbamazepine</h5>
            <p>✔ Take with food to reduce stomach upset.</p>
            <p>❌ May cause dizziness; avoid driving if affected.</p>
          </div>
        </div>

        <!-- Alendronic Acid -->
        <div class="col-md-6">
          <div class="card p-3 important">
            <h5 class="card-title">Alendronic Acid</h5>
            <p>✔ Take in the morning with full glass of water.</p>
            <p>✔ Stay upright for 30 mins after taking.</p>
            <p>❌ Avoid food or drink for at least 30 mins after.</p>
          </div>
        </div>

        <!-- Frusemide -->
        <div class="col-md-6">
          <div class="card p-3 important">
            <h5 class="card-title">Frusemide</h5>
            <p>✔ Take in the morning to avoid night-time urination.</p>
            <p>✔ Monitor potassium levels.</p>
          </div>
        </div>

        <!-- Hydrochlorothiazide -->
        <div class="col-md-6">
          <div class="card p-3 important">
            <h5 class="card-title">Hydrochlorothiazide (HCT)</h5>
            <p>✔ Take in the morning with water.</p>
            <p>✔ Can increase urination and lower potassium.</p>
          </div>
        </div>

        <!-- Gabapentin -->
        <div class="col-md-6">
          <div class="card p-3 important">
            <h5 class="card-title">Gabapentin</h5>
            <p>✔ Can be taken with or without food.</p>
            <p>✔ May cause drowsiness or dizziness.</p>
          </div>
        </div>

        <!-- H. pylori Kit -->
        <div class="col-md-6">
          <div class="card p-3 important">
            <h5 class="card-title">H. pylori Kit</h5>
            <p>✔ Take all prescribed antibiotics as directed.</p>
            <p>✔ Complete the full course (usually 14 days).</p>
            <p>❌ Do not stop early even if you feel better.</p>
          </div>
        </div>

        <!-- Flunarizine -->
        <div class="col-md-6">
          <div class="card p-3 important">
            <h5 class="card-title">Flunarizine</h5>
            <p>✔ Take in the evening; may cause drowsiness.</p>
            <p>✔ Used for migraine prevention.</p>
          </div>
        </div>

      </div>
    </section>

  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

