<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
$user = $_SESSION['user'];

$stmt = $conn->prepare("SELECT * FROM cart WHERE user_email = ?");
$stmt->execute([$user]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your Cart</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
  <div class="cart-container">
    <h2>Your Cart 🛒</h2>

    <?php if (empty($items)) : ?>
      <p class="empty-cart">Your cart is empty.</p>
    <?php else : ?>
      <table class="cart-table">
        <tr>
          <th>Product</th>
          <th>Price</th>
          <th>Added On</th>
          <th>Action</th>
        </tr>
        <?php foreach ($items as $item) : ?>
        <tr>
          <td><?= htmlspecialchars($item['product_name']) ?></td>
          <td>₹<?= number_format($item['price'], 2) ?></td>
          <td><?= $item['added_at'] ?></td>
          <td>
            <form method="POST" action="remove_from_cart.php" onsubmit="return confirm('Remove this item?');">
              <input type="hidden" name="cart_id" value="<?= $item['id'] ?>">
              <button type="submit" class="remove-btn">Remove</button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </table>
    <?php endif; ?>

    <table class="cart-table">
  <tr><th>Product</th><th>Price</th><th>Negotiate</th><th>Action</th></tr>
  <?php foreach ($items as $item): ?>
    <tr>
      <td><?= htmlspecialchars($item['product_name']) ?></td>
      <td>₹<?= number_format($item['price'], 2) ?></td>
      
      <td>
        <form method="POST" action="negotiate_cart.php">
          <input type="hidden" name="cart_id" value="<?= $item['id'] ?>">
          <input type="number" name="offer_price" placeholder="Your offer ₹" required>
          <button type="submit" name="negotiate_btn">Negotiate</button>
        </form>
      </td>

      <td>
        <form method="POST" action="remove_from_cart.php">
          <input type="hidden" name="cart_id" value="<?= $item['id'] ?>">
          <button type="submit">Remove</button>
        </form>
      </td>
    </tr>
  <?php endforeach; ?>
</table>

    <p><a class="back-link" href="../product.php">← Back to Products</a></p>
  </div>
</body>
</html>
