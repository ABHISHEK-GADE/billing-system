<?php
require_once '../../config/database.php';
require_once '../../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Delete the product
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = :id");
    $stmt->execute(['id' => $id]);

    $_SESSION['success'] = "Product deleted successfully.";
    header("Location: manage_products.php");
    exit;
}
?>
