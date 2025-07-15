<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
include '../includes/db.php';

$history = $conn->query("SELECT * FROM negotiation_history ORDER BY accepted_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Negotiation History</h2>
<table border="1" cellpadding="8">
  <tr><th>User</th><th>Product ID</th><th>Original Price</th><th>Final Offer</th><th>Time</th></tr>
  <?php foreach ($history as $row): ?>
    <tr>
      <td><?= htmlspecialchars($row['user_email']) ?></td>
      <td><?= $row['product_id'] ?></td>
      <td>₹<?= $row['original_price'] ?></td>
      <td>₹<?= $row['final_offer'] ?></td>
      <td><?= $row['accepted_at'] ?></td>
    </tr>
  <?php endforeach; ?>
</table>
<p><a href="dashboard.php">← Back to Dashboard</a></p>
