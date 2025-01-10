<?php
require_once '../../config/database.php';
require_once '../../includes/auth.php';

// Fetch all users
$stmt = $pdo->query("SELECT id, name, email, status FROM users WHERE role = 'client'");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>
<body>
    <div class="dashboard">
        <header class="dashboard-header">
            <h1>Manage Users</h1>
            <a href="dashboard.php" class="back-btn">Back to Dashboard</a>
        </header>

        <!-- Navigation -->
        <?php include 'sidebar.php'; ?>

        <main class="dashboard-content">
            <h2>User List</h2>
            <table class="user-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['id']) ?></td>
                            <td><?= htmlspecialchars($user['name']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= htmlspecialchars($user['status']) ?></td>
                            <td>
                                <a href="edit_user.php?id=<?= $user['id'] ?>">Edit</a> |
                                <a href="delete_user.php?id=<?= $user['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a> |
                                <a href="update_user_status.php?id=<?= $user['id'] ?>&status=<?= $user['status'] === 'active' ? 'inactive' : 'active' ?>">
                                    <?= $user['status'] === 'active' ? 'Deactivate' : 'Activate' ?>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </main>
    </div>
</body>
</html>
