<?php
require_once '../../config/database.php';
require_once '../../includes/auth.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../../templates/auth/admin_login.html");
    exit;
}

$stmt = $pdo->query("SELECT * FROM branding LIMIT 1");
$branding = $stmt->fetch(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($branding);
?>
