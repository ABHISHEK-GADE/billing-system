<?php
require_once '../../config/database.php';
require_once '../../includes/auth.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../../templates/auth/admin_login.html");
    exit;
}

// Fetch bills
$stmt = $pdo->query(
    "SELECT bills.id, users.name AS client_name, bills.total, bills.status 
     FROM bills 
     INNER JOIN users ON bills.client_id = users.id"
);
$bills = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($bills);
?>
