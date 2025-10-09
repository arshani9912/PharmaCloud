<?php
include 'connect.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $stmt = $conn->prepare("
        SELECT user_id FROM email_verifications WHERE token = :token
    ");
    $stmt->execute([':token' => $token]);

    if ($stmt->rowCount() == 1) {
        $row = $stmt->fetch();
        $user_id = $row['user_id'];

        // Verify the user
        $stmt = $conn->prepare("UPDATE users SET is_verified = 1 WHERE user_id = :user_id");
        $stmt->execute([':user_id' => $user_id]);

        // Delete token
        $stmt = $conn->prepare("DELETE FROM email_verifications WHERE user_id = :user_id");
        $stmt->execute([':user_id' => $user_id]);

        echo "<h3>Your email has been verified successfully!</h3>";
        echo "<p><a href='login.php'>Click here to login</a></p>";
    } else {
        echo "<h3>Invalid or expired verification link.</h3>";
    }
} else {
    echo "<h3>No token provided.</h3>";
}
?>
