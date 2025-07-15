<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
include '../includes/db.php';

$cartItems = $conn->query("SELECT * FROM cart ORDER BY added_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>All Cart Entries</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
  <div class="admin-container">
    <h2>🛒 All Cart Entries</h2>
    
    <?php if (empty($cartItems)) : ?>
      <p>No cart entries found.</p>
    <?php else : ?>
      <table class="admin-table">
        <thead>
          <tr>
            <th>User</th>
            <th>Product</th>
            <th>Price</th>
            <th>Time</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($cartItems as $item): ?>
            <tr>
              <td><?= htmlspecialchars($item['user_email']) ?></td>
              <td><?= htmlspecialchars($item['product_name']) ?></td>
              <td>₹<?= number_format($item['price'], 2) ?></td>
              <td><?= $item['added_at'] ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>

    <p><a class="back-link" href="dashboard.php">← Back to Dashboard</a></p>
  </div>
</body>
</html>
