<?php
require_once '../../config/database.php';
require_once '../../includes/auth.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../../templates/auth/admin_login.html");
    exit;
}

// Fetch requests with client details
$stmt = $pdo->query(
    "SELECT requests.id, requests.bill_id, users.name AS client_name, 
            requests.request_type, requests.description, requests.status 
     FROM requests 
     INNER JOIN users ON requests.client_id = users.id"
);
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($requests);
?>
