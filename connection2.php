<?php
// Database configuration
$host = 'localhost';
$dbname = 'mindb';
$username = 'root';
$password = ''; // Change this to your DB root password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    // Set PDO error mode to exception for better error handling
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Display error or handle connection failure
    die("Database connection failed: " . $e->getMessage());
}
?>
