<?php
require_once '../../config/database.php';
require_once '../../includes/auth.php';

// Fetch product data for editing
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        header("Location: manage_products.php");
        exit;
    }
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $image = $_POST['existing_image'];

    // Handle new image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../../assets/img/uploads/';
        $fileName = basename($_FILES['image']['name']);
        $filePath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $filePath)) {
            $image = $fileName;
        }
    }

    // Update product in the database
    $stmt = $pdo->prepare("UPDATE products SET name = :name, description = :description, price = :price, image = :image WHERE id = :id");
    $stmt->execute([
        'name' => $name,
        'description' => $description,
        'price' => $price,
        'image' => $image,
        'id' => $id
    ]);

    $success = "Product updated successfully.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>
<body>
    <div class="dashboard">
        <header class="dashboard-header">
            <h1>Edit Product</h1>
            <a href="manage_products.php" class="back-btn">Back to Products</a>
        </header>

        <!-- Navigation -->
        <?php include 'sidebar.php'; ?>

        <main class="dashboard-content">
            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
            <form action="edit_product.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= htmlspecialchars($product['id']) ?>">
                <input type="hidden" name="existing_image" value="<?= htmlspecialchars($product['image']) ?>">
                <input type="text" name="name" placeholder="Product Name" value="<?= htmlspecialchars($product['name']) ?>" required>
                <textarea name="description" placeholder="Product Description"><?= htmlspecialchars($product['description']) ?></textarea>
                <input type="number" name="price" placeholder="Price" value="<?= htmlspecialchars($product['price']) ?>" required>
                <input type="file" name="image">
                <?php if ($product['image']): ?>
                    <p>Current Image: <img src="../../assets/img/uploads/<?= htmlspecialchars($product['image']) ?>" alt="Product Image" width="50"></p>
                <?php endif; ?>
                <button type="submit">Update Product</button>
            </form>
        </main>
    </div>
</body>
</html>
