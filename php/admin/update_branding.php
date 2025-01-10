<?php
require_once '../../config/database.php';
require_once '../../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $address = trim($_POST['address']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $logoPath = null;

    if (!empty($_FILES['logo']['name'])) {
        $logoPath = '../../assets/img/branding/' . basename($_FILES['logo']['name']);
        move_uploaded_file($_FILES['logo']['tmp_name'], $logoPath);
        $logoPath = substr($logoPath, 6); // Remove "../../" for database storage
    }

    // Check if branding exists
    $stmt = $pdo->query("SELECT COUNT(*) FROM branding");
    $exists = $stmt->fetchColumn();

    if ($exists) {
        // Update branding
        $stmt = $pdo->prepare(
            "UPDATE branding SET name = :name, address = :address, phone = :phone, email = :email, logo = COALESCE(:logo, logo) WHERE id = 1"
        );
        $stmt->execute([
            'name' => $name,
            'address' => $address,
            'phone' => $phone,
            'email' => $email,
            'logo' => $logoPath
        ]);
    } else {
        // Insert branding
        $stmt = $pdo->prepare(
            "INSERT INTO branding (name, address, phone, email, logo) VALUES (:name, :address, :phone, :email, :logo)"
        );
        $stmt->execute([
            'name' => $name,
            'address' => $address,
            'phone' => $phone,
            'email' => $email,
            'logo' => $logoPath
        ]);
    }

    $_SESSION['success'] = "Branding updated successfully.";
    header("Location: ../../templates/admin/branding.html");
    exit;
}
?>
