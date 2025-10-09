<?php
session_start();
include 'connect.php';

// Assume logged-in user
$_SESSION['user_id'] = 1;
$user_id = $_SESSION['user_id'];

// Fetch user info
$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id=?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone = $_POST['phone'];

    // Update password if provided
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET phone=?, password=? WHERE user_id=?");
        $stmt->execute([$phone, $password, $user_id]);
    } else {
        $stmt = $pdo->prepare("UPDATE users SET phone=? WHERE user_id=?");
        $stmt->execute([$phone, $user_id]);
    }

    // Handle profile picture upload
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
        $ext = pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION);
        $newFileName = 'uploads/profile_' . $user_id . '.' . $ext;
        move_uploaded_file($_FILES['profile_pic']['tmp_name'], $newFileName);

        $stmt = $pdo->prepare("UPDATE users SET profile_pic=? WHERE user_id=?");
        $stmt->execute([$newFileName, $user_id]);
    }

    header("Location: profile.php?success=1");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile - Customer Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body { display: flex; min-height: 100vh; font-family: Arial, sans-serif; background: #f8f9fa; }
    .sidebar { width: 240px; background-color: #198754; color: #fff; min-height: 100vh; position: fixed; padding-top: 20px; }
    .sidebar a { color: #fff; display: block; padding: 15px 25px; text-decoration: none; border-radius: 5px; margin: 4px 8px; }
    .sidebar a:hover, .sidebar a.active { background-color: #157347; }
    .content { margin-left: 240px; flex: 1; padding: 25px; }
    .card { border-radius: 10px; }
    .profile-pic { width: 120px; height: 120px; border-radius: 50%; object-fit: cover; }
  </style>
</head>
<body>
  <div class="sidebar">
    <h3 class="text-center py-4"><i class="bi bi-person-circle"></i> Customer</h3>
    <a href="dashboard.php"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
    <a href="profile.php"><i class="bi bi-person me-2"></i> Profile</a>
    <a href="orders.php"><i class="bi bi-basket me-2"></i> My Orders</a>
    <a href="medicines.php"><i class="bi bi-box-seam me-2"></i> Available Medicines</a>
    <a href="medicine_info.php"><i class="bi bi-info-circle me-2"></i> Medicine Info</a>
    <a href="home.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
  </div>

  <div class="content">
    <h2 class="mb-4">Profile</h2>

    <?php if(isset($_GET['success'])): ?>
      <div class="alert alert-success">Profile updated successfully!</div>
    <?php endif; ?>

    <div class="card p-4 shadow-sm">
      <form method="POST" enctype="multipart/form-data">
        <div class="row g-3">
          <div class="col-md-12 text-center mb-3">
            <img src="<?= $user['profile_pic'] ? $user['profile_pic'] : 'https://via.placeholder.com/120' ?>" class="profile-pic mb-2" alt="Profile Picture">
            <input type="file" name="profile_pic" class="form-control mt-2">
          </div>

          <div class="col-md-6">
            <label class="form-label">Username</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" readonly>
          </div>

          <div class="col-md-6">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" readonly>
          </div>

          <div class="col-md-6">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone']) ?>">
          </div>

          <div class="col-md-6">
            <label class="form-label">New Password</label>
            <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current password">
          </div>

          <div class="col-md-12 d-flex align-items-end">
            <button type="submit" class="btn btn-success w-100">Update Profile</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
