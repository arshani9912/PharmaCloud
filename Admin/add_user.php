<?php
include "connect.php";

if (isset($_POST['submit'])) {
    // Use user_name column (not username)
    $stmt = $conn->prepare("INSERT INTO users (user_name, password, full_name, email, role, is_verified) 
                            VALUES (:user_name, :password, :full_name, :email, :role, :verified)");
    
    $stmt->execute([
        ':user_name'  => $_POST['user_name'],
        ':password'   => password_hash($_POST['password'], PASSWORD_BCRYPT),
        ':full_name'  => $_POST['full_name'],
        ':email'      => $_POST['email'],
        ':role'       => $_POST['role'],
        ':verified'   => isset($_POST['is_verified']) ? 1 : 0
    ]);

    header("Location: users.php");
    exit;
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add User</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="card shadow p-4">
    <h3 class="mb-3">Add New User</h3>
    <form method="post">
      <input type="text" name="user_name" class="form-control mb-2" placeholder="Username" required>
      <input type="password" name="password" class="form-control mb-2" placeholder="Password" required>
      <input type="text" name="full_name" class="form-control mb-2" placeholder="Full Name" required>
      <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
      <select name="role" class="form-control mb-2" required>
        <option value="Admin">Admin</option>
        <option value="Pharmacist">Pharmacist</option>
        <option value="Customer">Customer</option>
      </select>
      <div class="form-check mb-3">
        <input type="checkbox" name="is_verified" class="form-check-input" id="verified">
        <label for="verified" class="form-check-label">Verified</label>
      </div>
      <button type="submit" name="submit" class="btn btn-primary">Save</button>
      <a href="users.php" class="btn btn-secondary">Cancel</a>
    </form>
  </div>
</div>

</body>
</html>
