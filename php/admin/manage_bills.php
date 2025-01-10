<?php
require_once '../../config/database.php';
require_once '../../includes/auth.php';

// Fetch all bills
$stmt = $pdo->query("SELECT b.id, u.name AS client_name, b.total, b.created_at FROM bills b INNER JOIN users u ON b.client_id = u.id");
$bills = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bills</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>
<body>
    <div class="dashboard">
        <header class="dashboard-header">
            <h1>Manage Bills</h1>
            <a href="dashboard.php" class="back-btn">Back to Dashboard</a>
        </header>

        <!-- Navigation -->
        <?php include 'sidebar.php'; ?>

        <main class="dashboard-content">
            <h2>Bill List</h2>
            <table class="bill-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Client Name</th>
                        <th>Total Amount</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bills as $bill): ?>
                        <tr>
                            <td><?= htmlspecialchars($bill['id']) ?></td>
                            <td><?= htmlspecialchars($bill['client_name']) ?></td>
                            <td>₹<?= htmlspecialchars($bill['total']) ?></td>
                            <td><?= htmlspecialchars($bill['created_at']) ?></td>
                            <td>
                                <a href="../../php/bills/print_a4.php?id=<?= $bill['id'] ?>">Print A4</a> |
                                <a href="../../php/bills/print_normal.php?id=<?= $bill['id'] ?>">Print Normal</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </main>
    </div>
</body>
</html>
