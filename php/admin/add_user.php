<?php
require_once '../../config/database.php';
require_once '../../includes/auth.php';

// Initialize variables
$error = $success = "";

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $status = trim($_POST['status']);
    $defaultPassword = 'defaultpassword'; // Default password
    $password = password_hash($defaultPassword, PASSWORD_DEFAULT);

    try {
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);

        if ($stmt->rowCount() > 0) {
            $error = "The email address is already in use.";
        } else {
            // Insert new user
            $stmt = $pdo->prepare(
                "INSERT INTO users (name, email, password, role, status) 
                 VALUES (:name, :email, :password, 'client', :status)"
            );
            $stmt->execute([
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'status' => $status
            ]);

            $success = "User has been added successfully with the default password: $defaultPassword.";
        }
    } catch (PDOException $e) {
        $error = "An error occurred while adding the user. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>
<body>
    <div class="dashboard">
        <!-- Header -->
        <header class="dashboard-header">
            <h1>Add New User</h1>
            <a href="manage_users.php" class="btn btn-primary">Back to User List</a>
        </header>

        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>

        <!-- Main Content -->
        <div class="dashboard-content">
            <h2>Add User</h2>

            <?php if (!empty($error)): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <!-- Form for Adding User -->
            <form method="POST" action="add_user.php">
                <table class="user-table">
                    <thead>
                        <tr>
                            <th>Field</th>
                            <th>Input</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><label for="name">Full Name</label></td>
                            <td><input type="text" id="name" name="name" placeholder="Enter full name" required></td>
                        </tr>
                        <tr>
                            <td><label for="email">Email Address</label></td>
                            <td><input type="email" id="email" name="email" placeholder="Enter email address" required></td>
                        </tr>
                        <tr>
                            <td><label for="status">Status</label></td>
                            <td>
                                <select id="status" name="status" required>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align: center;">
                                <button type="submit" class="btn btn-primary">Add User</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
</body>
</html>
