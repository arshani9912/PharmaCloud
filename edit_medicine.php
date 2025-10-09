<?php
include "connect.php";

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: Admin_medicines.php");
    exit;
}

// Fetch current data
$stmt = $conn->prepare("SELECT * FROM medicines WHERE medicine_id = :id");
$stmt->execute([':id' => $id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    die("Medicine not found!");
}

// Update logic
if (isset($_POST['update'])) {
    $stmt = $conn->prepare("UPDATE medicines 
        SET name=:name, brand_name=:brand, description=:desc, unit_price=:price, quantity=:qty, expiry_date=:expiry 
        WHERE medicine_id=:id");
    $stmt->execute([
        ':name'   => $_POST['name'],
        ':brand'  => $_POST['brand'],
        ':desc'   => $_POST['description'],
        ':price'  => $_POST['unit_price'],
        ':qty'    => $_POST['quantity'],
        ':expiry' => $_POST['expiry_date'],
        ':id'     => $id
    ]);
    header("Location: Admin_medicines.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Medicine</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="card shadow p-4">
    <h3 class="mb-3">Edit Medicine</h3>
    <form method="post">
      <input type="text" name="name" class="form-control mb-2" value="<?= htmlspecialchars($row['name']) ?>" required>
      <input type="text" name="brand" class="form-control mb-2" value="<?= htmlspecialchars($row['brand_name']) ?>">
      <textarea name="description" class="form-control mb-2"><?= htmlspecialchars($row['description']) ?></textarea>
      <input type="number" step="0.01" name="unit_price" class="form-control mb-2" value="<?= $row['unit_price'] ?>" required>
      <input type="number" name="quantity" class="form-control mb-2" value="<?= $row['quantity'] ?>" required>
      <input type="date" name="expiry_date" class="form-control mb-3" value="<?= $row['expiry_date'] ?>">
      <button type="submit" name="update" class="btn btn-primary">Update</button>
      <a href="Admin_medicines.php" class="btn btn-secondary">Cancel</a>
    </form>
  </div>
</div>

</body>
</html>
