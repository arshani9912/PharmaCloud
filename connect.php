<?php
// connect.php using PDO

$host = 'localhost';
$dbname = 'pharmacloud_new';
$username = 'root';
$password = '';

try {
    // DSN (Data Source Name)
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";
    
    // Create PDO instance
    $conn = new PDO($dsn, $username, $password);
    
    // Set error mode to exceptions
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Optional: fetch associative arrays by default
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
