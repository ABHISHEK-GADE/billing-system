<?php
require_once '../../config/database.php';
require_once '../../includes/auth.php';

// Fetch all products
try {
    $stmt = $pdo->query("SELECT id, name, description, price, image FROM products");
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>
<body>
    <div class="dashboard">
        <header class="dashboard-header">
            <h1>Manage Products</h1>
            <a href="dashboard.php" class="back-btn">Back to Dashboard</a>
        </header>

        <!-- Navigation -->
        <?php include 'sidebar.php'; ?>

        <main class="dashboard-content">
    <div class="product-header">
        <h2>Product List</h2>
        <a href="add_product.php" class="btn btn-primary">Add Product</a>
    </div>
    <table class="product-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?= htmlspecialchars($product['id']) ?></td>
                    <td><?= htmlspecialchars($product['name']) ?></td>
                    <td><?= htmlspecialchars($product['description']) ?></td>
                    <td><?= htmlspecialchars($product['price']) ?></td>
                    <td>
                        <?php if ($product['image']): ?>
                            <img src="../../assets/img/uploads/<?= htmlspecialchars($product['image']) ?>" alt="Product Image" width="50">
                        <?php else: ?>
                            No Image
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="edit_product.php?id=<?= $product['id'] ?>">Edit</a> |
                        <a href="delete_product.php?id=<?= $product['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>

    </div>
</body>
</html>
