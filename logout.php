<?php
session_start();

// Remove session and cookie
session_unset();
session_destroy();

setcookie("pharmacloud_user", "", time() - 3600, "/");

// Redirect to login
header("Location: login.php");
exit();
?>
