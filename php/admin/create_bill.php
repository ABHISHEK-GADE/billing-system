<?php
require_once '../../config/database.php';
require_once '../../includes/auth.php';

// Fetch products for selection
$stmt = $pdo->query("SELECT id, name, price FROM products");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clientId = intval($_POST['client_id']);
    $items = json_decode($_POST['items'], true);
    $total = 0;

    // Calculate total amount
    foreach ($items as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    // Insert bill into database
    $stmt = $pdo->prepare("INSERT INTO bills (client_id, total, created_at) VALUES (:client_id, :total, NOW())");
    $stmt->execute(['client_id' => $clientId, 'total' => $total]);
    $billId = $pdo->lastInsertId();

    // Insert bill items
    $stmt = $pdo->prepare("INSERT INTO bill_items (bill_id, product_id, quantity, price) VALUES (:bill_id, :product_id, :quantity, :price)");
    foreach ($items as $item) {
        $stmt->execute([
            'bill_id' => $billId,
            'product_id' => $item['product_id'],
            'quantity' => $item['quantity'],
            'price' => $item['price']
        ]);
    }

    $success = "Bill created successfully.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Bill</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>
<body>
    <div class="dashboard">
        <!-- Header -->
        <header class="dashboard-header">
            <h1>Create Bill</h1>
            <a href="manage_bills.php" class="btn btn-primary">Back to Bills</a>
        </header>

        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>

        <!-- Main Content -->
        <main class="dashboard-content">
            <h2>Create a New Bill</h2>

            <!-- Success Message -->
            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <!-- Bill Creation Form -->
            <form id="create-bill-form" method="POST">
                <table class="user-table">
                    <thead>
                        <tr>
                            <th>Field</th>
                            <th>Input</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><label for="client_id">Select Client</label></td>
                            <td>
                                <select name="client_id" id="client_id" required>
                                    <?php
                                    $clients = $pdo->query("SELECT id, name FROM users WHERE role = 'client'");
                                    foreach ($clients as $client): ?>
                                        <option value="<?= $client['id'] ?>"><?= htmlspecialchars($client['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <h3>Add Items</h3>
                                <table id="items-table" class="item-table">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                                <button type="button" class="btn btn-primary" onclick="addItemRow()">Add Item</button>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align: center;">
                                <input type="hidden" name="items" id="items-json">
                                <button type="submit" class="btn btn-primary" onclick="prepareItems()">Create Bill</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
        </main>
    </div>

    <script>
        const products = <?= json_encode($products) ?>;

        function addItemRow() {
            const table = document.querySelector('#items-table tbody');
            const row = document.createElement('tr');

            const productSelect = document.createElement('select');
            products.forEach(product => {
                const option = document.createElement('option');
                option.value = product.id;
                option.textContent = `${product.name} - ₹${product.price}`;
                option.dataset.price = product.price;
                productSelect.appendChild(option);
            });

            row.innerHTML = `
                <td></td>
                <td><span class="product-price">₹0</span></td>
                <td><input type="number" min="1" class="quantity" value="1"></td>
                <td><button type="button" class="btn btn-danger" onclick="removeRow(this)">Remove</button></td>
            `;

            row.cells[0].appendChild(productSelect);
            table.appendChild(row);

            const quantityInput = row.querySelector('.quantity');
            const priceSpan = row.querySelector('.product-price');

            const updatePrice = () => {
                const price = productSelect.selectedOptions[0].dataset.price;
                const quantity = quantityInput.value;
                priceSpan.textContent = `₹${(price * quantity).toFixed(2)}`;
            };

            productSelect.addEventListener('change', updatePrice);
            quantityInput.addEventListener('input', updatePrice);

            updatePrice();
        }

        function removeRow(button) {
            button.closest('tr').remove();
        }

        function prepareItems() {
            const rows = document.querySelectorAll('#items-table tbody tr');
            const items = Array.from(rows).map(row => {
                const productId = row.querySelector('select').value;
                const price = row.querySelector('select').selectedOptions[0].dataset.price;
                const quantity = row.querySelector('.quantity').value;

                return { product_id: productId, price, quantity };
            });

            document.querySelector('#items-json').value = JSON.stringify(items);
        }
    </script>
</body>
</html>
