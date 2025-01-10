<?php
require_once '../../config/database.php';
require_once '../../includes/auth.php';

// Ensure the user is logged in and is a client
if ($_SESSION['role'] !== 'client') {
    header("Location: ../../php/auth/login.php");
    exit;
}

// Validate bill ID
if (!isset($_GET['bill_id']) || !is_numeric($_GET['bill_id'])) {
    die('Invalid Bill ID');
}

$billId = intval($_GET['bill_id']);
$clientId = $_SESSION['user_id'];

// Check if the bill belongs to the client
$stmt = $pdo->prepare("SELECT id FROM bills WHERE id = :bill_id AND client_id = :client_id");
$stmt->execute(['bill_id' => $billId, 'client_id' => $clientId]);
$bill = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$bill) {
    die('Bill not found or does not belong to you.');
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = trim($_POST['message']);

    // Insert request into database
    $stmt = $pdo->prepare("INSERT INTO requests (bill_id, client_id, message, status, created_at) 
                           VALUES (:bill_id, :client_id, :message, 'pending', NOW())");
    $stmt->execute([
        'bill_id' => $billId,
        'client_id' => $clientId,
        'message' => $message,
    ]);

    $success = "Your request has been submitted successfully.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Update</title>
    <link rel="stylesheet" href="../../assets/css/client.css">
</head>
<body>
    <div class="dashboard">
        <header class="dashboard-header">
            <h1>Request Update for Bill #<?= htmlspecialchars($billId) ?></h1>
            <a href="dashboard.php" class="back-btn">Back to Dashboard</a>
        </header>
        <main class="dashboard-content">
            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
            <form action="request_update.php?bill_id=<?= $billId ?>" method="POST">
                <label for="message">Message:</label>
                <textarea name="message" id="message" rows="5" required></textarea>
                <button type="submit">Submit Request</button>
            </form>
        </main>
    </div>
</body>
</html>
