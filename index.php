<?php
session_start();

// Check if the user is already logged in and redirect accordingly
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: php/admin/dashboard.php");
        exit;
    } elseif ($_SESSION['role'] === 'client') {
        header("Location: php/client/dashboard.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billing System</title>
    <link rel="stylesheet" href="assets/css/index.css">
</head>
<body>
    <!-- Hero Section -->
    <div class="hero-section">
        <div class="content">
            <h1>Effortless Billing Management</h1>
            <p>Streamline your billing and client management with a modern, secure platform tailored to meet your needs.</p>
            <div class="cta-buttons">
                <a href="php/auth/signup.php" class="btn btn-signup">Sign Up</a>
                <a href="php/auth/login.php" class="btn btn-login">Login</a>
            </div>
        </div>
    </div>

    <!-- About Section -->
    <section class="about-section">
        <h2>Why Choose Us?</h2>
        <p>Our billing system is designed to simplify the process of managing bills and requests for businesses and their clients. With a user-friendly interface and secure login system, managing invoices has never been easier.</p>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <h2>Key Features</h2>
        <div class="features">
            <div class="feature">
                <h3>Quick Sign-Up</h3>
                <p>Get started in minutes with an easy and secure registration process.</p>
            </div>
            <div class="feature">
                <h3>Seamless Login</h3>
                <p>Access your account with secure login options for admins and clients.</p>
            </div>
            <div class="feature">
                <h3>Comprehensive Billing</h3>
                <p>Generate detailed invoices in multiple formats and manage them effortlessly.</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="main-footer">
        <p>&copy; <?= date('Y') ?> Billing System. Designed for simplicity and efficiency.</p>
    </footer>
</body>
</html>
