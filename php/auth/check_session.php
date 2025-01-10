<?php
session_start();

// Redirect to login if the session is not valid
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header("Location: ../auth/login.php");
    exit;
}
