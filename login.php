<?php
session_start();
include 'connect.php';

$cookie_name = "pharmacloud_user";
$cookie_time = time() + (86400 * 30); // 30 days
$error = '';

// Handle POST login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $remember = isset($_POST['remember']);

    $sql = "SELECT * FROM users WHERE email = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if (isset($user['is_verified']) && $user['is_verified'] != 1) {
            $error = "Please verify your email before logging in.";
        } elseif (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['full_name'] = $user['full_name'];

            // Remember Me
            if ($remember) {
                $token = bin2hex(random_bytes(16));
                setcookie($cookie_name, $token, $cookie_time, "/", "", false, true);
                $stmt = $conn->prepare("UPDATE users SET remember_token = ? WHERE user_id = ?");
                $stmt->execute([$token, $user['user_id']]);
            }

            // Redirect by role
            if ($user['role'] === 'Admin') header("Location: admin_dashboard.php");
            elseif ($user['role'] === 'Pharmacist') header("Location: pharmacist_dashboard.php");
            else header("Location: customer_dashboard.php");
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "No user found with this email.";
    }
}

// Auto-login via cookie
if (!isset($_SESSION['user_id']) && isset($_COOKIE[$cookie_name])) {
    $token = $_COOKIE[$cookie_name];
    $stmt = $conn->prepare("SELECT * FROM users WHERE remember_token = ? LIMIT 1");
    $stmt->execute([$token]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['full_name'] = $user['full_name'];
        if ($user['role'] === 'Admin') header("Location: admin_dashboard.php");
        elseif ($user['role'] === 'Pharmacist') header("Location: pharmacist_dashboard.php");
        else header("Location: customer_dashboard.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - PharmaCloud</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    html, body { height: 100%; margin: 0; }
    body {
        background: url('photo/home.jpg') no-repeat center center fixed;
        background-size: cover;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .branding {
        color: #fff;
        text-shadow: 0 0 10px rgba(0,0,0,0.5);
        text-align: center;
        padding: 20px;
    }
    .glass-card {
        backdrop-filter: blur(10px) saturate(180%);
        background-color: rgba(255, 255, 255, 0.75);
        border-radius: 20px;
        padding: 40px 30px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.25);
        max-width: 400px;
        width: 100%;
        min-height: 400px;
        margin: auto;
        overflow-y: auto;
        transition: all 0.3s ease-in-out;
    }
    .glass-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.3);
    }
    .toggle-password {
        cursor: pointer;
        position: absolute;
        right: 15px;
        top: 12px;
        color: #888;
    }
</style>
</head>
<body>

<div class="container-fluid h-100">
    <div class="row h-100">
        <!-- Left branding -->
        <div class="col-md-6 d-none d-md-flex justify-content-center align-items-center">
            <div class="branding">
                <h1 class="display-4 fw-bold">Welcome to PharmaCloud</h1>
                <p class="lead">Smarter pharmacy management with stock control, sales tracking, and expiry alerts.</p>
            </div>
        </div>

        <!-- Right login card -->
        <div class="col-md-6 d-flex justify-content-center align-items-center">
            <div class="glass-card">
                <h2 class="text-center mb-4 fw-bold">Login</h2>

                <?php if(!empty($error)) echo "<div class='alert alert-danger text-center'>$error</div>"; ?>

                <form method="post" class="position-relative">
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                        <label for="email">Email address</label>
                    </div>

                    <div class="form-floating mb-3 position-relative">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                        <label for="password">Password</label>
                        <span class="toggle-password" onclick="togglePassword('password')">👁️</span>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="remember" name="remember">
                        <label class="form-check-label" for="remember">Remember Me</label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">Login</button>
                </form>

                <p class="text-center mt-3">Don’t have an account? <a href="register.php">Register here</a></p>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword(id) {
    const input = document.getElementById(id);
    input.type = input.type === 'password' ? 'text' : 'password';
}
</script>

</body>
</html>
