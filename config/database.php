<?php
$host = 'localhost';
$dbname = 'billing_system';
$username = 'root'; // Adjust as needed for your MySQL setup
$password = ''; // Leave blank if no password is set for root

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
