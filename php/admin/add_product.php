<?php
require_once '../../config/database.php';
require_once '../../includes/auth.php';

// Initialize variables
$error = $success = "";

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $image = null;

    $uploadDir = '../../assets/img/uploads/';

    // Ensure the uploads directory exists
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Handle file upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $fileName = preg_replace('/[^A-Za-z0-9\-\_\.]/', '_', $_FILES['image']['name']);
        $filePath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $filePath)) {
            $image = $fileName;
        } else {
            $error = "Failed to upload the image.";
        }
    }

    // Insert into database
    if (!$error) {
        try {
            $stmt = $pdo->prepare("INSERT INTO products (name, description, price, image) VALUES (:name, :description, :price, :image)");
            $stmt->execute([
                'name' => $name,
                'description' => $description,
                'price' => $price,
                'image' => $image
            ]);
            $success = "Product added successfully.";
        } catch (PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>
<body>
    <div class="dashboard">
        <header class="dashboard-header">
            <h1>Add Product</h1>
            <a href="manage_products.php" class="back-btn">Back to Products</a>
        </header>

        <!-- Navigation -->
        <?php include 'sidebar.php'; ?>

        <div class="dashboard-content">
            <h2>Add Product</h2>

            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form action="add_product.php" method="POST" enctype="multipart/form-data">
                <table class="user-table">
                    <thead>
                        <tr>
                            <th>Field</th>
                            <th>Input</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><label for="name">Product Name</label></td>
                            <td><input type="text" id="name" name="name" placeholder="Enter product name" required></td>
                        </tr>
                        <tr>
                            <td><label for="description">Product Description</label></td>
                            <td><textarea id="description" name="description" placeholder="Enter product description"></textarea></td>
                        </tr>
                        <tr>
                            <td><label for="price">Price</label></td>
                            <td><input type="number" id="price" name="price" placeholder="Enter price" step="0.01" required></td>
                        </tr>
                        <tr>
                            <td><label for="image">Product Image</label></td>
                            <td><input type="file" id="image" name="image"></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align: center;">
                                <button type="submit" class="btn btn-primary">Add Product</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
</body>
</html>
