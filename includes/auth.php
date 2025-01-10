<?php
session_start();

if (!isset($_SESSION['role'])) {
    header("Location: ../../index.php");
    exit;
}

if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'client') {
    header("Location: ../../index.php");
    exit;
}

?>
