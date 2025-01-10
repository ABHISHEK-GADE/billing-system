<?php
require_once '../../config/database.php';
require_once '../../includes/auth.php';

// Validate bill ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Invalid Bill ID');
}

$billId = intval($_GET['id']);

// Fetch bill details
$stmt = $pdo->prepare("SELECT b.id, u.name AS client_name, b.total, b.created_at 
                       FROM bills b 
                       INNER JOIN users u ON b.client_id = u.id 
                       WHERE b.id = :id");
$stmt->execute(['id' => $billId]);
$bill = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$bill) {
    die('Bill not found.');
}

// Fetch bill items
$stmt = $pdo->prepare("SELECT p.name, bi.quantity 
                       FROM bill_items bi 
                       INNER JOIN products p ON bi.product_id = p.id 
                       WHERE bi.bill_id = :bill_id");
$stmt->execute(['bill_id' => $billId]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch branding details
$stmt = $pdo->query("SELECT * FROM branding LIMIT 1");
$branding = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$branding) {
    // Initialize default branding if no data exists
    $branding = [
        'name' => 'Default Company',
        'logo' => null,
        'address' => 'Default Address',
        'phone' => '1234567890',
        'email' => 'default@example.com'
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Bill - Normal</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 10px; font-size: 12px; }
        h1, h2 { text-align: center; margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table th, table td { border: 1px solid #ccc; padding: 5px; text-align: left; }
        .compact { margin: 0; padding: 0; font-size: 10px; }
    </style>
</head>
<body>
    <div style="text-align: center;">
        <?php if ($branding['logo']): ?>
            <img src="../../assets/img/branding/<?= htmlspecialchars($branding['logo']) ?>" alt="Company Logo" width="50">
        <?php endif; ?>
        <h1><?= htmlspecialchars($branding['name']) ?></h1>
        <p><?= htmlspecialchars($branding['address']) ?></p>
        <p>Phone: <?= htmlspecialchars($branding['phone']) ?></p>
        <p>Email: <?= htmlspecialchars($branding['email']) ?></p>
    </div>

    <h2 style="text-align: center;">Invoice</h2>
    <p><strong>Client:</strong> <?= htmlspecialchars($bill['client_name']) ?></p>
    <p><strong>Date:</strong> <?= htmlspecialchars($bill['created_at']) ?></p>

    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td><?= htmlspecialchars($item['quantity']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th>Total:</th>
                <th>₹<?= htmlspecialchars($bill['total']) ?></th>
            </tr>
        </tfoot>
    </table>
</body>
</html>
