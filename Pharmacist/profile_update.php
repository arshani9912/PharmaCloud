<?php
session_start();
include 'connect.php';

// Check login and role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Pharmacist') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);

    // Update name
    $stmt = $conn->prepare("UPDATE users SET full_name = ? WHERE user_id = ?");
    $stmt->execute([$full_name, $user_id]);
    $_SESSION['full_name'] = $full_name;

    // Handle image upload
    if (!empty($_FILES['profile_image']['name'])) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $file_name = time() . "_" . basename($_FILES['profile_image']['name']);
        $target_file = $target_dir . $file_name;
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validate image
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($file_type, $allowed_types)) {
            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file)) {
                $stmt = $conn->prepare("UPDATE users SET profile_image = ? WHERE user_id = ?");
                $stmt->execute([$target_file, $user_id]);
                $success = "Profile updated successfully!";
            } else {
                $error = "Error uploading the image.";
            }
        } else {
            $error = "Only JPG, PNG, or GIF files are allowed.";
        }
    } else {
        $success = "Profile updated successfully!";
    }
}

// Fetch current user data
$stmt = $conn->prepare("SELECT full_name, email, profile_image FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Update Profile - PharmaCloud</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background-color: #f5f7fa; font-family: 'Poppins', sans-serif; }
.container { max-width: 600px; margin-top: 80px; }
.card { border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
.profile-img { width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 3px solid #0d6efd; }
</style>
</head>
<body>

<div class="container">
  <div class="card p-4">
    <h3 class="text-center mb-4">Update Profile</h3>

    <?php if(!empty($error)): ?>
      <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php elseif(!empty($success)): ?>
      <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <div class="text-center mb-3">
      <img src="<?php echo !empty($user['profile_image']) ? htmlspecialchars($user['profile_image']) : 'photo/default_profile.png'; ?>" class="profile-img" alt="Profile Picture">
    </div>

    <form method="post" enctype="multipart/form-data">
      <div class="mb-3">
        <label class="form-label">Full Name</label>
        <input type="text" name="full_name" class="form-control" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Email (read-only)</label>
        <input type="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
      </div>

      <div class="mb-3">
        <label class="form-label">Profile Picture</label>
        <input type="file" name="profile_image" class="form-control" accept="image/*">
      </div>

      <button type="submit" class="btn btn-primary w-100">Update Profile</button>
      <a href="pharmacist_dashboard.php" class="btn btn-secondary w-100 mt-2">Back to Dashboard</a>
    </form>
  </div>
</div>

</body>
</html>
