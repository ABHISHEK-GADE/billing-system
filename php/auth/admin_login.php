<?php
require_once '../../config/database.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validate admin credentials
    $stmt = $pdo->prepare("SELECT id, name, password FROM users WHERE email = :email AND role = 'admin'");
    $stmt->execute(['email' => $email]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin && password_verify($password, $admin['password'])) {
        // Start session and redirect to admin dashboard
        $_SESSION['user_id'] = $admin['id'];
        $_SESSION['user_name'] = $admin['name'];
        $_SESSION['role'] = 'admin';
        header("Location: ../admin/dashboard.php");
        exit;
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="../../assets/css/auth.css">
</head>
<body>
    <div class="auth-container">
        <h1>Admin Login</h1>
        <?php if (isset($error)): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form action="admin_login.php" method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
