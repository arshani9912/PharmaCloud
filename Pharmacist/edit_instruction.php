<?php
session_start();
include 'connect.php';

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Allow only pharmacists to access this page
if ($_SESSION['role'] !== 'Pharmacist') {
    header("Location: login.php");
    exit();
}

// Get pharmacist details from session
$pharmacist_name = $_SESSION['full_name'];

// Check for instruction ID
if (!isset($_GET['id'])) {
    header("Location: pharmacist_dashboard.php#instructions");
    exit();
}

$id = intval($_GET['id']);

// Fetch existing instruction
$stmt = $conn->prepare("SELECT * FROM medicine_instructions WHERE id = ?");
$stmt->execute([$id]);
$instruction = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$instruction) {
    header("Location: pharmacist_dashboard.php#instructions");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $medicine_name = trim($_POST['medicine_name']);
    $instruction_text = trim($_POST['instruction']);

    $stmt = $conn->prepare("UPDATE medicine_instructions SET medicine_name = ?, instruction = ? WHERE id = ?");
    $stmt->execute([$medicine_name, $instruction_text, $id]);
    $success = "Instruction updated successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Medicine Instruction</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<style>
body { font-family:'Poppins', sans-serif; background:#f5f7fa; padding:50px; }
.card { max-width:600px; margin:auto; padding:30px; border-radius:15px; box-shadow:0 4px 12px rgba(0,0,0,0.1); }
</style>
</head>
<body>

<div class="card">
    <h3 class="mb-4"><i class="bi bi-pencil-square"></i> Edit Medicine Instruction</h3>

    <?php if(isset($success)) { echo '<div class="alert alert-success">'.$success.'</div>'; } ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Medicine Name</label>
            <input type="text" name="medicine_name" class="form-control" required value="<?php echo htmlspecialchars($instruction['medicine_name']); ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Instruction</label>
            <input type="text" name="instruction" class="form-control" required value="<?php echo htmlspecialchars($instruction['instruction']); ?>">
        </div>
        <button type="submit" class="btn btn-success">Update</button>
        <a href="pharmacist_dashboard.php#instructions" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
