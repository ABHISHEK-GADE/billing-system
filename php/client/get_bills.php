<?php
require_once '../../config/database.php';
require_once '../../includes/auth.php';

if ($_SESSION['role'] !== 'client') {
    header("Location: ../../templates/auth/login.html");
    exit;
}

$clientId = $_SESSION['user_id'];

// Fetch bills for the logged-in client
$stmt = $pdo->prepare("SELECT id, total, status FROM bills WHERE client_id = :client_id");
$stmt->execute(['client_id' => $clientId]);
$bills = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($bills);
?>
