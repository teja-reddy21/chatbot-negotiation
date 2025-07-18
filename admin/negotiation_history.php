
<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$stmt = $conn->query("SELECT nh.*, u.name AS user_name, p.name AS product_name
                      FROM negotiation_history nh
                      JOIN users u ON nh.user_email = u.email
                      JOIN products p ON nh.product_id = p.id
                      ORDER BY nh.timestamp DESC");
$history = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Negotiation History (Admin)</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }
        th {
            background: #f4f4f4;
        }
        h2 {
            text-align: center;
            margin-top: 30px;
        }
    </style>
</head>
<body>

<h2>📜 Negotiation History (Admin View)</h2>

<?php if (empty($history)): ?>
    <p style="text-align: center;">No negotiation records found.</p>
<?php else: ?>
<table>
    <tr>
        <th>User</th>
        <th>Product</th>
        <th>Offered Price</th>
        <th>Status</th>
        <th>Date</th>
    </tr>
    <?php foreach ($history as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['user_name']) ?> (<?= $row['user_email'] ?>)</td>
            <td><?= htmlspecialchars($row['product_name']) ?></td>
            <td>₹<?= number_format($row['offered_price'], 2) ?></td>
            <td>
                <?= $row['accepted'] ? '✅ Accepted' : '❌ Rejected' ?>
            </td>
            <td><?= date("d M Y, h:i A", strtotime($row['timestamp'])) ?></td>
        </tr>
    <?php endforeach; ?>
     <p><a class="back-link" href="dashboard.php">← Back to Products</a></p>
</table>
<?php endif; ?>

</body>
</html>
