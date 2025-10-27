<?php
session_start();
include 'connect.php';

// Redirect to login if user is not logged in
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmacist Dashboard - PharmaCloud</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            background-color: #f5f7fa;
            margin: 0;
        }
        /* Sidebar */
        .sidebar {
            width: 240px;
            min-height: 100vh;
            background: #0d6efd;
            color: #fff;
            display: flex;
            flex-direction: column;
            padding-top: 30px;
            position: fixed;
        }
        .sidebar-profile {
            text-align: center;
            margin-bottom: 30px;
        }
        .sidebar-profile h3 {
            color: #fff;
            margin: 0;
            padding: 20px 0;
            font-weight: 600;
        }
        .sidebar a {
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 12px 20px;
            border-radius: 8px;
            margin-bottom: 5px;
            transition: 0.3s;
        }
        .sidebar a i {
            margin-right: 10px;
            min-width: 20px;
            text-align: center;
        }
        .sidebar a:hover,
        .sidebar a.active {
            background: rgba(255,255,255,0.2);
        }
        /* Content */
        .content {
            margin-left: 240px;
            flex: 1;
            padding: 30px;
        }
        /* Cards */
        .card {
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        }
        .stat-card {
            cursor: default;
            color: white;
        }
        /* Calendar & Time */
        #calendar {
            width: 100%;
            max-width: 400px;
            margin-top: 20px;
        }
        #currentTime {
            font-size: 1.2rem;
            font-weight: 600;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="sidebar-profile">
            <h3><i class="bi bi-person-circle"></i> Pharmacist</h3>
            
        </div>
        <a href="pharmacist_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="manage_medicines.php"><i class="fas fa-pills"></i> Manage Medicines</a>
        <a href="pharmacist_sales.php"><i class="fas fa-cash-register"></i> Sales</a>
        <a href="purchases.php"><i class="fas fa-truck"></i> Purchases</a>
        <a href="suppliers.php"><i class="fas fa-users"></i> Suppliers</a>
        <a href="pharmacist_medicine_instructions.php"><i class="bi bi-capsule me-2"></i> Medicine Instructions</a>
        <a href="pharmacist_report.php"><i class="fas fa-chart-bar"></i> Reports</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </nav>

    <!-- Content -->
    <div class="content">
        <h2 class="mb-4">Welcome back, <?php echo htmlspecialchars($pharmacist_name); ?> 👩‍⚕️</h2>

        <!-- Dashboard Cards -->
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card stat-card bg-primary text-center p-3">
                    <h6>Medicines</h6>
                    <p class="fs-5 mb-0">Track stock levels</p>
                    <a href="manage_medicines.php" class="btn btn-light btn-sm mt-2">View Medicines</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-card bg-success text-center p-3">
                    <h6>Purchases</h6>
                    <p class="fs-5 mb-0">Record supplier purchases</p>
                    <a href="purchases.php" class="btn btn-light btn-sm mt-2">Add Purchase</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-card bg-warning text-center p-3">
                    <h6>Sales</h6>
                    <p class="fs-5 mb-0">Process customer sales</p>
                    <a href="pharmacist_sales.php" class="btn btn-light btn-sm mt-2">New Sale</a>
                </div>
            </div>
        </div>

        <!-- Calendar & Time -->
        <div class="row g-4 mt-4">
            <div class="col-md-6">
                <div class="card p-3 text-center">
                    <h5>Current Date & Time</h5>
                    <div id="currentTime"></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card p-3 text-center">
                    <h5>Calendar</h5>
                    <input type="date" id="calendar" class="form-control">
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Current Time Function
        function updateTime() {
            const now = new Date();
            const options = {
                weekday:'long', year:'numeric', month:'long', day:'numeric',
                hour:'2-digit', minute:'2-digit', second:'2-digit'
            };
            document.getElementById('currentTime').textContent = now.toLocaleDateString('en-US', options);
        }
        setInterval(updateTime, 1000);
        updateTime();
    </script>
</body>
</html>
