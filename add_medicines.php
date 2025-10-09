<?php
// medicine_instructions.php
session_start();
include "connect.php"; // Your DB connection

// Handle Add
if(isset($_POST['add_instruction'])){
    $medicine_name = $_POST['medicine_name'];
    $instruction = $_POST['instruction'];
    $stmt = $conn->prepare("INSERT INTO medicine_instructions (medicine_name, instruction) VALUES (?, ?)");
    $stmt->execute([$medicine_name, $instruction]);
    header("Location: medicine_instructions.php");
}

// Handle Delete
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM medicine_instructions WHERE id=?");
    $stmt->execute([$id]);
    header("Location: medicine_instructions.php");
}

// Handle Edit
if(isset($_POST['edit_instruction'])){
    $id = $_POST['id'];
    $medicine_name = $_POST['medicine_name'];
    $instruction = $_POST['instruction'];
    $stmt = $conn->prepare("UPDATE medicine_instructions SET medicine_name=?, instruction=? WHERE id=?");
    $stmt->execute([$medicine_name, $instruction, $id]);
    header("Location: medicine_instructions.php");
}

// Fetch all instructions
$instructions = $conn->query("SELECT * FROM medicine_instructions ORDER BY medicine_name ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Medicine Instructions</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<style>
body { min-height: 100vh; display: flex; font-family: Arial, sans-serif; background-color: #f8f9fa; }
.sidebar { width: 240px; background-color: #198754; color: #fff; height: 100vh; position: fixed; padding-top: 20px; }
.sidebar a { color: #fff; text-decoration: none; display: block; padding: 15px 25px; font-size: 1rem; }
.sidebar a:hover { background-color: #157347; }
.content { margin-left: 240px; flex: 1; padding: 25px; }
.card { border-radius: 10px; margin-bottom: 15px; }
.important { border-left: 6px solid #dc3545; background: #fff; border-radius: 10px; }
.card-title { color: #28a745; font-weight: bold; }
</style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar d-flex flex-column">
<h3 class="text-center py-4"><i class="bi bi-person-circle"></i> Customer</h3>
<a href="customer_dashboard.php"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
<a href="customer_profile.php"><i class="bi bi-person me-2"></i> Profile</a>
<a href="orders.php"><i class="bi bi-basket me-2"></i> Stock Status</a>
<a href="available_medicines.php"><i class="bi bi-box-seam me-2"></i> Available Medicines</a>
<a href="medicine_instructions.php"><i class="bi bi-capsule me-2"></i> Medicine Instructions</a>
<a href="home.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
</div>

<!-- Main Content -->
<div class="content">
<h3 class="mb-4"><i class="bi bi-capsule me-2"></i>Medicine Instructions</h3>

<!-- Add Instruction Form -->
<div class="card p-3 mb-4">
    <h5 class="card-title">Add New Instruction</h5>
    <form method="POST">
        <div class="mb-2">
            <input type="text" name="medicine_name" class="form-control" placeholder="Medicine Name" required>
        </div>
        <div class="mb-2">
            <textarea name="instruction" class="form-control" placeholder="Instruction" required></textarea>
        </div>
        <button type="submit" name="add_instruction" class="btn btn-success"><i class="bi bi-plus-circle"></i> Add Instruction</button>
    </form>
</div>

<!-- List Instructions -->
<div class="row g-4">
<?php foreach($instructions as $inst): ?>
<div class="col-md-6">
    <div class="card p-3 important">
        <h5 class="card-title"><?= htmlspecialchars($inst['medicine_name']) ?></h5>
        <p><?= nl2br(htmlspecialchars($inst['instruction'])) ?></p>
        <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editModal<?= $inst['id'] ?>"><i class="bi bi-pencil"></i> Edit</a>
        <a href="?delete=<?= $inst['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this instruction?')"><i class="bi bi-trash"></i> Delete</a>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal<?= $inst['id'] ?>" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST">
        <div class="modal-header">
          <h5 class="modal-title">Edit Instruction</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" value="<?= $inst['id'] ?>">
          <div class="mb-2">
              <input type="text" name="medicine_name" class="form-control" value="<?= htmlspecialchars($inst['medicine_name']) ?>" required>
          </div>
          <div class="mb-2">
              <textarea name="instruction" class="form-control" required><?= htmlspecialchars($inst['instruction']) ?></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" name="edit_instruction" class="btn btn-success">Save Changes</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php endforeach; ?>
</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
