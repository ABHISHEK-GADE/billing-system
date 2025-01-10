<?php
require_once '../../config/database.php';
require_once '../../includes/auth.php';

// Restrict access to admin role
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../../templates/auth/admin_login.html");
    exit;
}

// Fetch admin stats
$totalUsers = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'client'")->fetchColumn();
$totalProducts = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$totalBills = $pdo->query("SELECT COUNT(*) FROM bills")->fetchColumn();
$pendingRequests = $pdo->query("SELECT COUNT(*) FROM requests WHERE status = 'pending'")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>
<body>
    <div class="dashboard">
        <!-- Header -->
        <header class="dashboard-header">
        <h1>Welcome, <?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin') ?></h1>
            <a href="../auth/logout.php" class="logout-btn">Logout</a>
        </header>

        <!-- Navigation -->
        <?php include 'sidebar.php'; ?>

        <!-- Main Content -->
        <main class="dashboard-content">
            <h2>Admin Overview</h2>
            <div class="dashboard-overview">
    <div class="dashboard-card">
        <h3>Total Clients</h3>
        <a href="manage_users.php">1</a>
    </div>
    <div class="dashboard-card">
        <h3>Total Products</h3>
        <a href="manage_products.php">1</a>
    </div>
    <div class="dashboard-card">
        <h3>Total Bills</h3>
        <a href="manage_bills.php">2</a>
    </div>
    <div class="dashboard-card">
        <h3>Pending Requests</h3>
        <a href="manage_requests.php">0</a>
    </div>
</div>

        </main>
    </div>
</body>
</html>
