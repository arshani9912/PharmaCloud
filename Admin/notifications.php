<?php
session_start();
include 'connect.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit();
}

$admin_name = $_SESSION['full_name'];

// Fetch all support messages
$notifStmt = $conn->query("
    SELECT u.full_name, s.subject, s.message, s.created_at 
    FROM support_messages s 
    JOIN users u ON s.user_id = u.user_id 
    ORDER BY s.created_at DESC
");
$notifications = $notifStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Notifications - PharmaCloud</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<style>
body { font-family: Arial, sans-serif; background-color: #f4f6fc; }
.container { max-width: 900px; margin: 40px auto; }
.card { margin-bottom: 15px; border-radius: 10px; }
</style>
</head>
<body>

<div class="container">
<h2 class="mb-4"><i class="bi bi-bell me-2"></i> Customer Support Messages</h2>

<?php if($notifications): ?>
    <?php foreach($notifications as $notif): ?>
    <div class="card shadow-sm">
        <div class="card-body">
            <h6><?php echo htmlspecialchars($notif['subject']); ?> <small class="text-muted">by <?php echo htmlspecialchars($notif['full_name']); ?></small></h6>
            <p><?php echo htmlspecialchars($notif['message']); ?></p>
            <small class="text-muted"><?php echo date("d M Y H:i", strtotime($notif['created_at'])); ?></small>
        </div>
    </div>
    <?php endforeach; ?>
<?php else: ?>
    <p class="text-center text-muted">No notifications yet.</p>
<?php endif; ?>

<a href="Admin_dashboard.php" class="btn btn-primary mt-3"><i class="bi bi-arrow-left-circle me-2"></i> Back to Dashboard</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
