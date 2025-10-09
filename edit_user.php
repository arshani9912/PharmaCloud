<?php
include "connect.php";

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: users.php");
    exit;
}

// Fetch user
$stmt = $conn->prepare("SELECT * FROM users WHERE user_id = :id");
$stmt->execute([':id' => $id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    die("User not found!");
}

// Update
if (isset($_POST['update'])) {
    $query = "UPDATE users SET username=:username, full_name=:full_name, email=:email, role=:role, is_verified=:verified";
    $params = [
        ':username'   => $_POST['user_name'],
        ':full_name'  => $_POST['full_name'],
        ':email'      => $_POST['email'],
        ':role'       => $_POST['role'],
        ':verified'   => isset($_POST['is_verified']) ? 1 : 0,
        ':id'         => $id
    ];

    // If password entered → update
    if (!empty($_POST['password'])) {
        $query .= ", password=:password";
        $params[':password'] = password_hash($_POST['password'], PASSWORD_BCRYPT);
    }

    $query .= " WHERE user_id=:id";
    $stmt = $conn->prepare($query);
    $stmt->execute($params);

    header("Location: users.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit User</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="card shadow p-4">
    <h3 class="mb-3">Edit User</h3>
    <form method="post">
      <input type="text" name="username" class="form-control mb-2" value="<?= htmlspecialchars($row['user_name']) ?>" required>
      <input type="password" name="password" class="form-control mb-2" placeholder="New Password (leave blank to keep same)">
      <input type="text" name="full_name" class="form-control mb-2" value="<?= htmlspecialchars($row['full_name']) ?>" required>
      <input type="email" name="email" class="form-control mb-2" value="<?= htmlspecialchars($row['email']) ?>" required>
      <select name="role" class="form-control mb-2" required>
        <option value="Admin" <?= $row['role']=="Admin" ? "selected" : "" ?>>Admin</option>
        <option value="Pharmacist" <?= $row['role']=="Pharmacist" ? "selected" : "" ?>>Pharmacist</option>
        <option value="Customer" <?= $row['role']=="Customer" ? "selected" : "" ?>>Customer</option>
      </select>
      <div class="form-check mb-3">
        <input type="checkbox" name="is_verified" class="form-check-input" id="verified" <?= $row['is_verified'] ? "checked" : "" ?>>
        <label for="verified" class="form-check-label">Verified</label>
      </div>
      <button type="submit" name="update" class="btn btn-primary">Update</button>
      <a href="users.php" class="btn btn-secondary">Cancel</a>
    </form>
  </div>
</div>

</body>
</html>
