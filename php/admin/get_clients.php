<?php
require_once '../../config/database.php';
require_once '../../includes/auth.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../../templates/auth/admin_login.html");
    exit;
}

// Fetch clients
$stmt = $pdo->query("SELECT id, name FROM users WHERE role = 'client'");
$clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($clients);
?>
