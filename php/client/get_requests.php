<?php
require_once '../../config/database.php';
require_once '../../includes/auth.php';

if ($_SESSION['role'] !== 'client') {
    header("Location: ../../templates/auth/login.html");
    exit;
}

$clientId = $_SESSION['user_id'];

// Fetch client requests
$stmt = $pdo->prepare(
    "SELECT id, bill_id, description, status 
     FROM requests 
     WHERE client_id = :client_id"
);
$stmt->execute(['client_id' => $clientId]);
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($requests);
?>
