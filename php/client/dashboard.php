<?php
require_once '../../config/database.php';
require_once '../../includes/auth.php';

// Ensure the user is logged in and is a client
if ($_SESSION['role'] !== 'client') {
    header("Location: ../../php/auth/login.php");
    exit;
}

$clientId = $_SESSION['user_id'];

// Fetch client bills
$stmt = $pdo->prepare("SELECT id, total, created_at FROM bills WHERE client_id = :client_id ORDER BY created_at DESC");
$stmt->execute(['client_id' => $clientId]);
$bills = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch client requests
$requestStmt = $pdo->prepare("
    SELECT r.id, r.bill_id, r.message, r.status, r.created_at 
    FROM requests r 
    WHERE r.client_id = :client_id 
    ORDER BY r.created_at DESC
");
$requestStmt->execute(['client_id' => $clientId]);
$requests = $requestStmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Dashboard</title>
    <link rel="stylesheet" href="../../assets/css/client.css">
</head>
<body>
    <div class="dashboard">
        <header class="dashboard-header">
            <h1>Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?></h1>
            <a href="../auth/logout.php" class="logout-btn">Logout</a>
        </header>
        <main class="dashboard-content">
            <section class="bills-section">
                <h2>Your Bills</h2>
                <table class="bills-table">
                    <thead>
                        <tr>
                            <th>Bill ID</th>
                            <th>Total Amount</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bills as $bill): ?>
                            <tr>
                                <td><?= htmlspecialchars($bill['id']) ?></td>
                                <td>₹<?= htmlspecialchars($bill['total']) ?></td>
                                <td><?= htmlspecialchars($bill['created_at']) ?></td>
                                <td>
                                    <a href="../../php/bills/print_a4.php?id=<?= $bill['id'] ?>" target="_blank">Print A4</a> |
                                    <a href="../../php/bills/print_normal.php?id=<?= $bill['id'] ?>" target="_blank">Print Normal</a> |
                                    <a href="request_update.php?bill_id=<?= $bill['id'] ?>">Request Update</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>

            <section class="requests-section">
    <h2>Your Requests</h2>
    <table class="requests-table">
        <thead>
            <tr>
                <th>Request ID</th>
                <th>Bill ID</th>
                <th>Message</th>
                <th>Status</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($requests as $request): ?>
                <tr>
                    <td><?= htmlspecialchars($request['id']) ?></td>
                    <td><?= htmlspecialchars($request['bill_id']) ?></td>
                    <td><?= htmlspecialchars($request['message']) ?></td>
                    <td><?= htmlspecialchars($request['status']) ?></td>
                    <td><?= htmlspecialchars($request['created_at']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>

        </main>
    </div>
</body>
</html>
