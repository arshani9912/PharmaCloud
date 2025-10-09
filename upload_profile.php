<?php
session_start();
include 'db_connection.php'; // Include your database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: home.php");
    exit();
}

$userId = $_SESSION['user_id'];

// Check if a file was uploaded
if(isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK){
    
    $fileTmpPath = $_FILES['profile_picture']['tmp_name'];
    $fileName = $_FILES['profile_picture']['name'];
    $fileSize = $_FILES['profile_picture']['size'];
    $fileType = $_FILES['profile_picture']['type'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));

    // Allowed file extensions
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

    if(in_array($fileExtension, $allowedExtensions)){

        // Sanitize file name and create unique name
        $newFileName = 'user_' . $userId . '_' . time() . '.' . $fileExtension;
        $uploadFileDir = './uploads/';
        $dest_path = $uploadFileDir . $newFileName;

        if(move_uploaded_file($fileTmpPath, $dest_path)){

            // Update user profile picture path in database
            $sql = "UPDATE customers SET profile_picture = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $newFileName, $userId);

            if($stmt->execute()){
                $_SESSION['profile_picture'] = $newFileName; // optional session update
                header("Location: customer_profile.php?success=Profile updated successfully");
                exit();
            } else {
                $error_message = "Database update failed.";
            }

        } else {
            $error_message = "Failed to move uploaded file.";
        }

    } else {
        $error_message = "Invalid file type. Only JPG, PNG, GIF allowed.";
    }

} else {
    $error_message = "No file uploaded or upload error.";
}

// Redirect back with error message
header("Location: customer_profile.php?error=" . urlencode($error_message));
exit();
?>
