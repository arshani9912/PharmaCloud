<?php
session_start();
include 'connect.php'; // PDO connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    $role = "Customer";
    $is_verified = 0;
    $error = '';
    $success = '';

    if ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        try {
            // Check if email already exists
            $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = :email");
            $stmt->execute([':email' => $email]);

            if ($stmt->rowCount() > 0) {
                $error = "Email is already registered.";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Generate unique username
                $username_base = explode("@", $email)[0];
                $username = $username_base;
                $i = 1;
                while (true) {
                    $stmt = $conn->prepare("SELECT user_id FROM users WHERE username = :username");
                    $stmt->execute([':username' => $username]);
                    if ($stmt->rowCount() == 0) break;
                    $username = $username_base . $i;
                    $i++;
                }

                // Insert user
                $stmt = $conn->prepare("
                    INSERT INTO users (username, password, full_name, email, role, is_verified)
                    VALUES (:username, :password, :full_name, :email, :role, :is_verified)
                ");
                $stmt->execute([
                    ':username' => $username,
                    ':password' => $hashed_password,
                    ':full_name' => $name,
                    ':email' => $email,
                    ':role' => $role,
                    ':is_verified' => $is_verified
                ]);

                // Redirect to login with success message
                $_SESSION['register_success'] = "You have successfully registered! Please login.";
                header("Location: login.php");
                exit();
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register - PharmaCloud</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    html, body { height: 100%; margin: 0; }
    body {
        background: url('photo/register.png') no-repeat center center fixed;
        background-size: cover;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .glass-card {
        backdrop-filter: blur(10px) saturate(180%);
        background-color: rgba(255, 255, 255, 0.75);
        border-radius: 20px;
        padding: 40px 30px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.25);
        max-width: 450px;
        width: 100%;
        min-height: 480px;
        margin: auto;
        overflow-y: auto;
        transition: all 0.3s ease-in-out;
    }
    .glass-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.3);
    }
</style>
</head>
<body>

<div class="container h-100 d-flex justify-content-center align-items-center">
    <div class="glass-card">
        <h2 class="text-center mb-4 fw-bold">Register</h2>

        <?php if(!empty($error)) echo "<div class='alert alert-danger text-center'>$error</div>"; ?>

        <form method="post">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="full_name" name="full_name" placeholder="Full Name" required>
                <label for="full_name">Full Name</label>
            </div>

            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                <label for="email">Email address</label>
            </div>

            <div class="form-floating mb-3 position-relative">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                <label for="password">Password</label>
                <span class="toggle-password" onclick="togglePassword('password')" style="position:absolute; right:15px; top:12px; cursor:pointer;">👁️</span>
            </div>

            <div class="form-floating mb-3 position-relative">
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
                <label for="confirm_password">Confirm Password</label>
                <span class="toggle-password" onclick="togglePassword('confirm_password')" style="position:absolute; right:15px; top:12px; cursor:pointer;">👁️</span>
            </div>

            <button type="submit" class="btn btn-success w-100 py-2 fw-bold">Register</button>
        </form>

        <p class="text-center mt-3">Already have an account? <a href="login.php">Login here</a></p>
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
