<?php
include "connect.php";

if (isset($_POST['submit'])) {
    $stmt = $conn->prepare("INSERT INTO medicines (name, brand_name, description, unit_price, quantity, expiry_date) 
                            VALUES (:name, :brand, :desc, :price, :qty, :expiry)");
    $stmt->execute([
        ':name'   => $_POST['name'],
        ':brand'  => $_POST['brand'],
        ':desc'   => $_POST['description'],
        ':price'  => $_POST['unit_price'],
        ':qty'    => $_POST['quantity'],
        ':expiry' => $_POST['expiry_date']
    ]);
    header("Location: Admin_medicines.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Medicine</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="card shadow p-4">
    <h3 class="mb-3">Add New Medicine</h3>
    <form method="post">
      <input type="text" name="name" class="form-control mb-2" placeholder="Medicine Name" required>
      <input type="text" name="brand" class="form-control mb-2" placeholder="Brand Name">
      <textarea name="description" class="form-control mb-2" placeholder="Description"></textarea>
      <input type="number" step="0.01" name="unit_price" class="form-control mb-2" placeholder="Unit Price (LKR)" required>
      <input type="number" name="quantity" class="form-control mb-2" placeholder="Stock Quantity" required>
      <input type="date" name="expiry_date" class="form-control mb-3">
      <button type="submit" name="submit" class="btn btn-primary">Save</button>
      <a href="Admin_medicines.php" class="btn btn-secondary">Cancel</a>
    </form>
  </div>
</div>

</body>
</html>
