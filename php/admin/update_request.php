<?php
require_once '../../config/database.php';
require_once '../../includes/auth.php';

// Ensure the user is logged in and is an admin
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../../php/auth/login.php");
    exit;
}

// Validate request parameters
if (!isset($_GET['id']) || !isset($_GET['action'])) {
    die('Invalid Request');
}

$requestId = intval($_GET['id']);
$action = $_GET['action'];

// Ensure the action is valid
if (!in_array($action, ['approve', 'reject'])) {
    die('Invalid Action');
}

// Update the request status
$status = $action === 'approve' ? 'approved' : 'rejected';
$stmt = $pdo->prepare("UPDATE requests SET status = :status WHERE id = :id");
$stmt->execute(['status' => $status, 'id' => $requestId]);

// Redirect back to manage requests page
header("Location: manage_requests.php");
exit;
