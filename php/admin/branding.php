<?php
require_once '../../config/database.php';
require_once '../../includes/auth.php';

// Fetch current branding
$stmt = $pdo->query("SELECT * FROM branding LIMIT 1");
$branding = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$branding) {
    // Initialize default values if no branding exists
    $branding = [
        'id' => null,
        'name' => '',
        'address' => '',
        'phone' => '',
        'email' => '',
        'logo' => null,
    ];
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $address = trim($_POST['address']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $logo = $branding['logo'];

    // Handle logo upload
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../../assets/img/branding/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $fileName = preg_replace('/[^A-Za-z0-9\.\-_]/', '_', $_FILES['logo']['name']);
        $filePath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['logo']['tmp_name'], $filePath)) {
            $logo = $fileName;
        }
    }

    if ($branding['id']) {
        // Update existing branding
        $stmt = $pdo->prepare("UPDATE branding SET name = :name, address = :address, phone = :phone, email = :email, logo = :logo WHERE id = :id");
        $stmt->execute([
            'name' => $name,
            'address' => $address,
            'phone' => $phone,
            'email' => $email,
            'logo' => $logo,
            'id' => $branding['id']
        ]);
    } else {
        // Insert new branding
        $stmt = $pdo->prepare("INSERT INTO branding (name, address, phone, email, logo) VALUES (:name, :address, :phone, :email, :logo)");
        $stmt->execute([
            'name' => $name,
            'address' => $address,
            'phone' => $phone,
            'email' => $email,
            'logo' => $logo
        ]);
    }

    $success = "Branding updated successfully.";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Branding</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>
<body>
    <div class="dashboard">
        <header class="dashboard-header">
            <h1>Edit Branding</h1>
            <a href="dashboard.php" class="btn btn-primary">Back to Dashboard</a>
        </header>

        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>

        <main class="dashboard-content">
            <h2>Update Branding</h2>
            <table class="user-table">
                <thead>
                    <tr>
                        <th>Field</th>
                        <th>Input</th>
                    </tr>
                </thead>
                <tbody>
                    <form action="branding.php" method="POST" enctype="multipart/form-data">
                        <tr>
                            <td><label for="name">Company Name</label></td>
                            <td><input type="text" id="name" name="name" placeholder="Enter company name" value="<?= htmlspecialchars($branding['name']) ?>" required></td>
                        </tr>
                        <tr>
                            <td><label for="address">Address</label></td>
                            <td><textarea id="address" name="address" placeholder="Enter address"><?= htmlspecialchars($branding['address']) ?></textarea></td>
                        </tr>
                        <tr>
                            <td><label for="phone">Phone</label></td>
                            <td><input type="text" id="phone" name="phone" placeholder="Enter phone number" value="<?= htmlspecialchars($branding['phone']) ?>" required></td>
                        </tr>
                        <tr>
                            <td><label for="email">Email</label></td>
                            <td><input type="email" id="email" name="email" placeholder="Enter email" value="<?= htmlspecialchars($branding['email']) ?>" required></td>
                        </tr>
                        <tr>
                            <td><label for="logo">Logo</label></td>
                            <td>
                                <input type="file" id="logo" name="logo">
                                <?php if ($branding['logo']): ?>
                                    <p>Current Logo: <img src="../../assets/img/branding/<?= htmlspecialchars($branding['logo']) ?>" alt="Brand Logo" width="50"></p>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align: center;">
                                <button type="submit" class="btn btn-primary">Update Branding</button>
                            </td>
                        </tr>
                    </form>
                </tbody>
            </table>

            <!-- Display Success or Error Messages -->
            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>
