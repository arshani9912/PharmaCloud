<?php
include "connect.php";

// Fetch supplier
if (!isset($_GET['id'])) {
  header("Location: suppliers.php");
  exit;
}
$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM suppliers WHERE supplier_id = ?");
$stmt->execute([$id]);
$supplier = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$supplier) {
  die("Supplier not found!");
}

// Update supplier
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $supplier_name = $_POST['supplier_name'];
  $contact_person = $_POST['contact_person'];
  $email = $_POST['email'];
  $phone = $_POST['phone'];
  $address = $_POST['address'];

  $stmt = $conn->prepare("UPDATE suppliers SET supplier_name=?, contact_person=?, email=?, phone=?, address=? WHERE supplier_id=?");
  $stmt->execute([$supplier_name, $contact_person, $email, $phone, $address, $id]);

  header("Location: suppliers.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Supplier - PharmaCloud</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="card p-4 shadow-lg border-0">
    <h2 class="fw-bold mb-4 text-primary">Edit Supplier</h2>
    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Supplier Name</label>
        <input type="text" name="supplier_name" class="form-control" value="<?= htmlspecialchars($supplier['supplier_name']) ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Contact Person</label>
        <input type="text" name="contact_person" class="form-control" value="<?= htmlspecialchars($supplier['contact_person']) ?>">
      </div>
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($supplier['email']) ?>">
      </div>
      <div class="mb-3">
        <label class="form-label">Phone</label>
        <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($supplier['phone']) ?>">
      </div>
      <div class="mb-3">
        <label class="form-label">Address</label>
        <textarea name="address" class="form-control" rows="3"><?= htmlspecialchars($supplier['address']) ?></textarea>
      </div>
      <button type="submit" class="btn btn-primary">Update Supplier</button>
      <a href="suppliers.php" class="btn btn-secondary">Cancel</a>
    </form>
  </div>
</div>

</body>
</html>
