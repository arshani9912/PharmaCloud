<?php
session_start();
include 'connect.php';

if(!isset($_SESSION['user_id'])) { 
    header("Location: login.php"); 
    exit; 
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    if($subject === '' || $message === '') {
        // Redirect back with error if fields are empty
        header("Location: support.php?success=0");
        exit;
    }
    
    // Insert into support_messages table
    $stmt = $conn->prepare("
        INSERT INTO support_messages (user_id, subject, message, created_at) 
        VALUES (:user_id, :subject, :message, NOW())
    ");
    $stmt->execute([
        ':user_id' => $user_id,
        ':subject' => $subject,
        ':message' => $message
    ]);

    // Also add notification for Admin
    $stmt2 = $conn->prepare("
        INSERT INTO admin_notifications (message, created_at) 
        VALUES (:message, NOW())
    ");
    $admin_message = "New support message from user #$user_id: $subject";
    $stmt2->execute([':message' => $admin_message]);

    // Redirect back with success
    header("Location: support.php?success=1");
    exit;
}
?>
