<?php
require_once '../../config/database.php';
require_once '../../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $status = trim($_POST['status']);

    // Update user details
    $stmt = $pdo->prepare("UPDATE users SET name = :name, email = :email, status = :status WHERE id = :id");
    $stmt->execute([
        'name' => $name,
        'email' => $email,
        'status' => $status,
        'id' => $id
    ]);

    $_SESSION['success'] = "User updated successfully.";
    header("Location: ../../templates/admin/manage_users.html");
    exit;
}
?>
