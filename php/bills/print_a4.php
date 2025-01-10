<?php
require_once '../../config/database.php';
require_once '../../includes/auth.php';

// Fetch branding details
$stmt = $pdo->query("SELECT * FROM branding LIMIT 1");
$branding = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch bill details
$billId = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT b.id, b.created_at, b.total, c.name AS client_name, c.email AS client_email FROM bills b JOIN users c ON b.client_id = c.id WHERE b.id = :id");
$stmt->execute(['id' => $billId]);
$bill = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch bill items
$stmt = $pdo->prepare("SELECT p.name, bi.price, bi.quantity FROM bill_items bi JOIN products p ON bi.product_id = p.id WHERE bi.bill_id = :bill_id");
$stmt->execute(['bill_id' => $billId]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #<?= htmlspecialchars($bill['id']) ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .invoice {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background: #fff;
        }
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #007bff;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .invoice-header .branding {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .invoice-header img {
            max-width: 100px;
        }
        .invoice-header .branding h1 {
            margin: 0;
            font-size: 24px;
        }
        .invoice-header .branding p {
            margin: 0;
            font-size: 14px;
            color: #555;
        }
        .invoice-title {
            text-align: right;
            font-size: 36px;
            color: #007bff;
            margin: 0;
        }
        .invoice-meta {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }
        .invoice-meta .meta-item {
            font-size: 14px;
            color: #555;
        }
        .invoice-details {
            margin-bottom: 20px;
        }
        .invoice-details h3 {
            margin: 0 0 10px;
        }
        .invoice-details p {
            margin: 5px 0;
        }
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .invoice-table th,
        .invoice-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .invoice-table th {
            background-color: #007bff;
            color: white;
        }
        .invoice-footer {
            text-align: right;
            margin-top: 20px;
        }
        .invoice-footer .total {
            font-size: 18px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="invoice">
        <!-- Invoice Header -->
        <div class="invoice-header">
            <div class="branding">
                <?php if (!empty($branding['logo'])): ?>
                    <img src="../../assets/img/branding/<?= htmlspecialchars($branding['logo']) ?>" alt="Company Logo">
                <?php endif; ?>
                <div>
                    <h1><?= htmlspecialchars($branding['name'] ?? 'Company Name') ?></h1>
                    <p><?= htmlspecialchars($branding['address'] ?? '') ?></p>
                    <p><?= htmlspecialchars($branding['phone'] ?? '') ?> | <?= htmlspecialchars($branding['email'] ?? '') ?></p>
                </div>
            </div>
            <h1 class="invoice-title">Invoice</h1>
        </div>

        <!-- Invoice Meta -->
        <div class="invoice-meta">
            <p class="meta-item"><strong>Invoice #: </strong><?= htmlspecialchars($bill['id']) ?></p>
            <p class="meta-item"><strong>Date: </strong><?= htmlspecialchars(date('d-m-Y', strtotime($bill['created_at']))) ?></p>
        </div>

        <!-- Client Details -->
        <div class="invoice-details">
            <h3>Invoice Details</h3>
            <p><strong>Client Name: </strong><?= htmlspecialchars($bill['client_name']) ?></p>
            <p><strong>Client Email: </strong><?= htmlspecialchars($bill['client_email']) ?></p>
        </div>

        <!-- Invoice Table -->
        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['name']) ?></td>
                        <td>₹<?= number_format($item['price'], 2) ?></td>
                        <td><?= htmlspecialchars($item['quantity']) ?></td>
                        <td>₹<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Invoice Footer -->
        <div class="invoice-footer">
            <p class="total">Total: ₹<?= number_format($bill['total'], 2) ?></p>
        </div>
    </div>
</body>
</html>
