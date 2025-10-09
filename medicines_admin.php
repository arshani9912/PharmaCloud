<?php
include 'connect.php';

$stmt = $conn->query("SELECT * FROM medicines");
$medicines = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="container mt-4">
  <h3>Manage Medicines</h3>
  <a href="add_medicine.php" class="btn btn-success mb-2">+ Add Medicine</a>
  <table class="table table-striped">
    <thead>
      <tr><th>ID</th><th>Name</th><th>Brand</th><th>Price</th><th>Qty</th><th>Expiry</th><th>Action</th></tr>
    </thead>
    <tbody>
      <?php foreach($medicines as $row){ ?>
      <tr>
        <td><?= $row['medicine_id'] ?></td>
        <td><?= $row['name'] ?></td>
        <td><?= $row['brand_name'] ?></td>
        <td><?= $row['unit_price'] ?></td>
        <td><?= $row['quantity'] ?></td>
        <td><?= $row['expiry_date'] ?></td>
        <td>
          <a href="edit_medicine.php?id=<?= $row['medicine_id'] ?>" class="btn btn-warning btn-sm">Edit</a>
          <a href="delete_medicine.php?id=<?= $row['medicine_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete?')">Delete</a>
        </td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
