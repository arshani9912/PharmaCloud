<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PharmaCloud | Home</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: #ADD8E6;
    }
    .navbar {
      background: linear-gradient(90deg, #007bff, #00c6ff);
    }
    .navbar-brand, .nav-link {
      color: white !important;
      font-weight: 500;
    }
    .hero {
      background: url('photo/background.jpg') center/cover no-repeat;
      height: 90vh;
      display: flex;
      align-items: center;
      color: white;
      position: relative;
    }
    .hero::after {
      content: '';
      position: absolute;
      inset: 0;
      background: rgba(0,0,0,0.6);
    }
    .hero-content {
      position: relative;
      z-index: 1;
    }

    /* Features */
    .feature-box {
      background: white;
      border-radius: 12px;
      padding: 30px;
      box-shadow: 0 6px 20px rgba(0,0,0,0.1);
      transition: 0.3s;
    }
    .feature-box:hover {
      transform: translateY(-8px);
      box-shadow: 0 12px 25px rgba(0,0,0,0.2);
    }

    .glass-card {
      background: rgba(255, 255, 255, 0.9);
      border-radius: 15px;
      padding: 25px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }
    footer {
      background: #343a40;
      color: white;
      padding: 15px;
      text-align: center;
      margin-top: 50px;
    }
  </style>
</head>
<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg">
    <div class="container">
      <a class="navbar-brand fw-bold" href="home.php">
        <i class="fas fa-capsules me-2"></i>
                <span class="brand-name">PharmaCloud</span>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
          <li class="nav-item"><a class="nav-link" href="login.php">Sign In</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="hero text-center text-white">
    <div class="container hero-content">
      <h1 class="display-3 fw-bold">Smarter <span class="text-info">Pharmacy</span> Management</h1>
      <p class="lead mb-4">Manage medicines, stock, sales, and purchases with ease using PharmaCloud.</p>
      <a href="register.php" class="btn btn-lg btn-primary me-3">
        Get Started <i class="fas fa-arrow-right ms-1"></i>
      </a>
      <a href="#about" class="btn btn-lg btn-outline-light">Learn More</a>
    </div>
  </section>

  <!-- Features Section -->
  <section class="container py-5">
    <h2 class="text-center mb-5 fw-bold">Key Features</h2>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="feature-box text-center">
          <i class="fas fa-box fa-3x text-primary mb-3"></i>
          <h5>Stock Tracking</h5>
          <p>Monitor available medicines, batch numbers, and expiry dates with real-time updates.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="feature-box text-center">
          <i class="fas fa-file-invoice fa-3x text-success mb-3"></i>
          <h5>Sales & Purchases</h5>
          <p>Manage supplier purchases, customer sales, and generate invoices seamlessly.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="feature-box text-center">
          <i class="fas fa-user-shield fa-3x text-warning mb-3"></i>
          <h5>Secure Access</h5>
          <p>Role-based authentication for Admin, Pharmacists, and Customers ensures data safety.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- About Section -->
  <section id="about" class="container py-5">
    <div class="glass-card">
      <h2 class="mb-4 text-center"><i class="fas fa-info-circle me-2"></i> About PharmaCloud</h2>
      <div class="row">
        <div class="col-md-6">
          <h4 class="text-primary">Our Mission</h4>
          <p>PharmaCloud simplifies pharmacy operations — from tracking medicines and suppliers to managing sales and generating reports.</p>
          <img src="photo/homepage.png" class="img-fluid rounded mt-3">
        </div>
        <div class="col-md-6">
          <h4 class="text-primary">Why Choose PharmaCloud?</h4>
          <ul class="list-unstyled">
            <li><i class="fas fa-check-circle text-success me-2"></i> Accurate stock tracking</li>
            <li><i class="fas fa-check-circle text-success me-2"></i> Expiry alerts</li>
            <li><i class="fas fa-check-circle text-success me-2"></i> Easy sales & purchase management</li>
            <li><i class="fas fa-check-circle text-success me-2"></i> Secure user roles (Admin, Pharmacist, Customer)</li>
          </ul>
          <img src="photo/mission.jpg" class="img-fluid rounded mt-3">
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer>
    <p>&copy; <?= date("Y") ?> PharmaCloud. All Rights Reserved.</p>
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
