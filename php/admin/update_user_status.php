<?php
require_once '../../config/database.php';
require_once '../../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id']) && isset($_GET['status'])) {
    $id = intval($_GET['id']);
    $status = trim($_GET['status']);

    $stmt = $pdo->prepare("UPDATE users SET status = :status WHERE id = :id");
    $stmt->execute(['status' => $status, 'id' => $id]);

    $_SESSION['success'] = "User status updated successfully.";
    header("Location: ../../templates/admin/manage_users.html");
    exit;
}
?>
