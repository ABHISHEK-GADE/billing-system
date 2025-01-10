<?php
require_once '../../config/database.php';
require_once '../../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
    $stmt->execute(['id' => $id]);

    $_SESSION['success'] = "User deleted successfully.";
    header("Location: ../../templates/admin/manage_users.html");
    exit;
}
?>
