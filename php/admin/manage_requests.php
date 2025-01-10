<?php
require_once '../../config/database.php';
require_once '../../includes/auth.php';

// Ensure the user is logged in and is an admin
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../../php/auth/login.php");
    exit;
}

// Fetch all client requests
$stmt = $pdo->query("
    SELECT r.id, r.bill_id, r.message, r.status, r.created_at, u.name AS client_name 
    FROM requests r 
    INNER JOIN users u ON r.client_id = u.id 
    ORDER BY r.created_at DESC
");
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Requests</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>
<body>
    <div class="dashboard">
        <header class="dashboard-header">
            <h1>Manage Requests</h1>
            <a href="dashboard.php" class="back-btn">Back to Dashboard</a>
        </header>

        <!-- Navigation -->
        <?php include 'sidebar.php'; ?>

        <main class="dashboard-content">
            <h2>Client Requests</h2>
            <table class="requests-table">
                <thead>
                    <tr>
                        <th>Request ID</th>
                        <th>Client Name</th>
                        <th>Bill ID</th>
                        <th>Message</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($requests as $request): ?>
                        <tr>
                            <td><?= htmlspecialchars($request['id']) ?></td>
                            <td><?= htmlspecialchars($request['client_name']) ?></td>
                            <td><?= htmlspecialchars($request['bill_id']) ?></td>
                            <td><?= htmlspecialchars($request['message']) ?></td>
                            <td><?= htmlspecialchars($request['status']) ?></td>
                            <td><?= htmlspecialchars($request['created_at']) ?></td>
                            <td>
                                <?php if ($request['status'] === 'pending'): ?>
                                    <a href="update_request.php?id=<?= $request['id'] ?>&action=approve" class="btn btn-approve">Approve</a>
                                    <a href="update_request.php?id=<?= $request['id'] ?>&action=reject" class="btn btn-reject">Reject</a>
                                <?php else: ?>
                                    <?= htmlspecialchars(ucfirst($request['status'])) ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </main>
    </div>
</body>
</html>
