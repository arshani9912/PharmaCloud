<?php
session_start();
include 'connect.php';
if(!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

// Fetch logged-in user info
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT full_name FROM users WHERE user_id = :id");
$stmt->execute([':id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$full_name = $user ? $user['full_name'] : 'Customer';

// Handle success message
$success = isset($_GET['success']) && $_GET['success'] == 1;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Support - PharmaCloud</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<style>
body { font-family: Arial, sans-serif; background-color: #f8f9fa; min-height: 100vh; display: flex; }
.sidebar { width: 240px; background-color: #198754; color: #fff; height: 100vh; position: fixed; top: 0; left: 0; padding-top: 20px; }
.sidebar h3 { font-weight: 600; text-align: center; margin-bottom: 1rem; }
.sidebar a { color: #fff; text-decoration: none; display: block; padding: 15px 25px; font-size: 1rem; transition: background 0.3s, padding-left 0.3s; }
.sidebar a:hover, .sidebar a.active { background-color: #157347; padding-left: 30px; }
.content { margin-left: 240px; flex: 1; padding: 30px; }
h2.section-title { font-weight: 600; margin-bottom: 20px; color: #333; }
.card { border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); padding: 20px; }
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
<a href="support.php" class="active"><i class="bi bi-headset me-2"></i> Support</a>
<a href="logout.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
</div>

<!-- Main Content -->
<main class="content">
<h2 class="section-title">Support</h2>
<p class="text-muted">Contact our support team for any assistance or queries.</p>

<?php if($success): ?>
<div class="alert alert-success">Your message has been sent successfully!</div>
<?php endif; ?>

<div class="card col-md-6">
<form action="support_submit.php" method="POST">
  <div class="mb-3">
    <label for="subject" class="form-label">Subject</label>
    <input type="text" class="form-control" name="subject" id="subject" required>
  </div>
  <div class="mb-3">
    <label for="message" class="form-label">Message</label>
    <textarea class="form-control" name="message" id="message" rows="5" required></textarea>
  </div>
  <button type="submit" class="btn btn-success"><i class="bi bi-send me-2"></i>Send Message</button>
</form>
</div>

<a href="customer_dashboard.php" class="btn btn-primary mt-4"><i class="bi bi-arrow-left-circle me-2"></i> Back to Dashboard</a>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
