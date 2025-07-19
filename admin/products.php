<?php
session_start();
if (!isset($_SESSION['admin'])) header("Location: login.php");
include '../includes/db.php';

$products = $conn->query("SELECT * FROM products ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>🛍️ Product Management</h2>
<a href="add_product.php">+ Add New Product</a>
<table border="1" cellpadding="8">
  <tr><th>ID</th><th>Name</th><th>Price</th><th>Min Price</th><th>Actions</th></tr>
  <?php foreach ($products as $product): ?>
    <tr>
      <td><?= $product['id'] ?></td>
      <td><?= htmlspecialchars($product['name']) ?></td>
      <td>₹<?= $product['price'] ?></td>
      <td>₹<?= $product['min_price'] ?></td>
      <td>
        <a href="edit_product.php?id=<?= $product['id'] ?>">Edit</a> |
        <a href="delete_product.php?id=<?= $product['id'] ?>" onclick="return confirm('Are you sure?');">Delete</a>
      </td>
      <td>
  

</td>

    </tr>
  <?php endforeach; ?>
</table>
<p><a href="dashboard.php">← Back to Dashboard</a></p>
