<?php
session_start();
include "connect.php";

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Allow only Admins
if ($_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit();
}

// Get admin name from session
$admin_name = $_SESSION['full_name'];

// Placeholder variables for summary cards (Replace with actual database queries)
try {
    // Total Users
    $totalUsers = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch(PDO::FETCH_ASSOC)['total'];
    // Total Medicines
    $totalMedicines = $conn->query("SELECT COUNT(*) AS total FROM medicines")->fetch(PDO::FETCH_ASSOC)['total'];
    // Low Stock (Quantity < 50 and > 0)
    $lowStock = $conn->query("SELECT COUNT(*) AS total FROM medicines WHERE quantity < 50 AND quantity > 0")->fetch(PDO::FETCH_ASSOC)['total'];
    // Nearly Expired (Expiry date within the next 30 days)
    $nearlyExpired = $conn->query("SELECT COUNT(*) AS total FROM medicines WHERE expiry_date <= DATE_ADD(CURDATE(), INTERVAL 30 DAY)")->fetch(PDO::FETCH_ASSOC)['total'];
    
    // Notifications (Fetch the latest 10)
    $notifStmt = $conn->query("SELECT message, created_at FROM admin_notifications ORDER BY created_at DESC LIMIT 10");
    $notifications = $notifStmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Basic error handling for database connection/query issues
    $totalUsers = $totalMedicines = $lowStock = $nearlyExpired = 'N/A';
    $notifications = [];
    // You might want to log the error here
    // error_log("Database Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard - PharmaCloud</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

<style>
html, body {
    width: 100vw;
    height: 100vh;
    margin: 0;
    overflow: hidden;
    font-family: 'Poppins', sans-serif;
    background-color: #f4f6fc;
    display: flex;
}
.sidebar {
    width: 250px;
    background-color: #343a40;
    color: #fff;
    display: flex;
    flex-direction: column;
    padding-top: 20px;
    height: 100vh;
    flex-shrink: 0;
}
.sidebar h3 {
    text-align: center;
    font-weight: 600;
    padding: 20px 0;
    border-bottom: 1px solid #444;
}
.sidebar a {
    color: #e2e8e8ff;
    text-decoration: none;
    display: block;
    padding: 15px 25px;
    font-size: 1rem;
    margin: 4px 8px;
    border-radius: 8px;
    transition: background 0.3s, color 0.3s;
}
.sidebar a:hover, .sidebar a.active {
    background-color: #495057;
    color: #fff;
}
.content {
    flex: 1;
    padding: 20px;
    height: 100vh;
    display: flex;
    flex-direction: column;
    overflow: hidden;
}
.topbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}
.topbar h2 {
    margin: 0;
    font-weight: 600;
    font-size: 1.3rem;
}
.row-cards, .row-charts {
    display: flex;
    gap: 20px;
    margin-bottom: 15px;
    flex-wrap: nowrap;
    overflow: hidden;
}

/* Cards */
.card-box {
    border-radius: 12px;
    padding: 15px;
    text-align: center;
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    height: 110px;
    min-width: 150px;
}
.card-box h5 {
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 5px;
}
.card-box h2 {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 5px;
}
.card-box i {
    font-size: 2rem;
    margin-top: 3px;
}

/* Card gradient backgrounds */
.bg-blue { background: linear-gradient(45deg,#4a6cf7,#203e9d); color: #fff; }
.bg-green { background: linear-gradient(45deg,#28a745,#1e7e34); color: #fff; }
.bg-orange { background: linear-gradient(45deg,#fd7e14,#e8590c); color: #fff; }
.bg-red { background: linear-gradient(45deg,#dc3545,#a71d2a); color: #fff; }

/* Chart & Calendar cards */
.card {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    background-color: #fff;
    overflow: hidden;
}
#calendar {
    background-color: #fff;
    border-radius: 10px;
    padding: 10px;
    flex: 1;
    overflow: hidden;
}
.chart-container {
    flex: 1;
    display: flex;
    flex-direction: column;
}

/* NEW CSS: Highlight today's date in yellow */
.fc-day-today {
    background-color: #ffc107 !important; /* Yellow color */
    opacity: 0.6; /* Slight transparency for a lighter look */
}

/* Responsive */
@media (max-width: 1200px) {
    .row-cards, .row-charts { flex-wrap: wrap; overflow-y: auto; }
    .card-box, .card { height: auto; flex: 1 1 48%; margin-bottom: 15px; }
}
@media (max-width: 768px) {
    .sidebar { width: 70px; padding-top: 10px; }
    .sidebar h3 { font-size: 0; padding: 10px 0; border: none; }
    .sidebar a { padding: 10px 12px; font-size: 0.9rem; text-align: center; }
    .sidebar a i { font-size: 1.2rem; }
    .topbar h2 { font-size: 1rem; }
    .row-cards, .row-charts { flex-direction: column; overflow-y: auto; }
    .card-box, .card { width: 100%; flex: 1 1 auto; height: auto; }
    .card-box h2 { font-size: 1.3rem; }
    .card-box i { font-size: 1.6rem; }
}
</style>
</head>
<body>

<div class="sidebar">
    <h3><i class="bi bi-person-circle"></i> Admin</h3>
    <a href="Admin_dashboard.php" class="active"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
    <a href="users.php"><i class="bi bi-people me-2"></i> Users</a>
    <a href="Admin_sales.php"><i class="bi bi-receipt me-2"></i> Sales</a>
    <a href="Admin_purchases.php"><i class="bi bi-basket me-2"></i> Purchases</a>
    <a href="Admin_medicines.php"><i class="bi bi-box-seam me-2"></i> Medicines</a>
    <a href="admin_medicine_instructions.php"><i class="bi bi-capsule me-2"></i> Medicine Instructions</a>
    <a href="Admin_reports.php"><i class="bi bi-bar-chart-line me-2"></i> Reports</a>
    <a href="settings.php"><i class="bi bi-gear me-2"></i> Settings</a>
    <a href="logout.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
</div>

<div class="content">
    <div class="topbar">
        <h2><i class="bi bi-speedometer2 me-2"></i> Welcome, <?php echo htmlspecialchars($admin_name); ?>!</h2>
        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle" type="button" id="notificationsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-bell"></i> Notifications
            </button>
            <ul class="dropdown-menu dropdown-menu-end p-2" id="notifList" style="min-width:300px; max-height:400px; overflow-y:auto;">
                <?php
                if($notifications) {
                    foreach($notifications as $notif) {
                        echo '<li class="list-group-item">';
                        echo htmlspecialchars($notif['message']) . '<br>';
                        echo '<small class="text-muted">' . date("d M Y H:i", strtotime($notif['created_at'])) . '</small>';
                        echo '</li>';
                    }
                } else {
                    echo '<li class="text-center">No new notifications</li>';
                }
                ?>
            </ul>
        </div>
    </div>

    <div class="row-cards">
        <div class="card-box bg-blue shadow-sm">
            <h5>Total Users</h5>
            <h2><?php echo $totalUsers; ?></h2>
            <i class="bi bi-people"></i>
        </div>
        <div class="card-box bg-green shadow-sm">
            <h5>Total Medicines</h5>
            <h2><?php echo $totalMedicines; ?></h2>
            <i class="bi bi-box-seam"></i>
        </div>
        <div class="card-box bg-orange shadow-sm">
            <h5>Low Stock</h5>
            <h2><?php echo $lowStock; ?></h2>
            <i class="bi bi-exclamation-triangle"></i>
        </div>
        <div class="card-box bg-red shadow-sm">
            <h5>Nearly Expired</h5>
            <h2><?php echo $nearlyExpired; ?></h2>
            <i class="bi bi-exclamation-octagon"></i>
        </div>
    </div>

    <div class="row-charts" style="flex:1; gap:20px; overflow:hidden;">
        <div class="card chart-container">
            <h5>Top Selling Medicines</h5>
            <canvas id="topMedicinesChart" class="mt-3" style="flex:1;"></canvas>
        </div>
        <div class="card chart-container">
            <h5 class="text-center mb-3">Current Time</h5>
            <h2 id="currentTime" class="text-center mb-4"></h2>
            <h5 class="text-center">Calendar</h5>
            <div id="calendar"></div>
        </div>
    </div>

</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Time updater
    function updateTime() {
        document.getElementById("currentTime").innerHTML = new Date().toLocaleString();
    }
    setInterval(updateTime, 1000);
    updateTime();

    // Chart
    const ctx = document.getElementById("topMedicinesChart").getContext("2d");
    new Chart(ctx, {
        type: "doughnut",
        data: {
            // NOTE: Replace these placeholder labels and data with dynamic PHP values from your database
            labels: ["Paracetamol", "Amoxicillin", "Ibuprofen", "Vitamin C", "Cetirizine"],
            datasets: [{ data: [120,95,80,75,60], backgroundColor: ["#4a6cf7","#28a745","#fd7e14","#ffc107","#dc3545"] }]
        },
        options: { responsive:true, maintainAspectRatio:false, plugins:{ legend:{ position:"bottom" } } }
    });

    // FullCalendar
    const calendarEl = document.getElementById("calendar");
    if (calendarEl) {
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: "dayGridMonth",
            selectable: true,
            editable: true,
            height: "100%",
            // FullCalendar automatically applies the .fc-day-today class to today's cell.
            // The custom CSS added above ('.fc-day-today { background-color: #ffc107 !important; }') handles the highlighting.
            
            dateClick: function(info) {
                const title = prompt("Enter Event Title:");
                if (title) { calendar.addEvent({ title: title, start: info.dateStr, allDay:true }); alert("Event added successfully!"); }
            },
            eventClick: function(info) { if (confirm("Delete this event?")) { info.event.remove(); } }
        });
        // Render the calendar after a short delay to ensure the container is sized correctly
        setTimeout(()=>{calendar.render();}, 100); 
    }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
