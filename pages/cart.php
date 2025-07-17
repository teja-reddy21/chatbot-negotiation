<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$cartId = $_GET['cart_id'] ?? null;
$user = $_SESSION['user'];

$stmt = $conn->prepare("SELECT * FROM cart WHERE user_email = ?");
$stmt->execute([$user]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);


if (isset($_POST['add_to_cart'])) {
    $productId = $_POST['product_id'];
    $userEmail = $_SESSION['user'];

    // ✅ Fetch correct product from DB
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$productId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        // ✅ Insert into cart with actual name and price
        $insert = $conn->prepare("INSERT INTO cart (user_email, product_id, product_name, price) VALUES (?, ?, ?, ?)");
        $insert->execute([
            $userEmail,
            $productId,
            $product['name'],
            $product['price']
        ]);
    }

    header("Location: cart.php");
    exit();
}
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
    <?php if (isset($_SESSION['msg'])): ?>
  <div class="success-message">
    <?= $_SESSION['msg'] ?>
  </div>
  <?php unset($_SESSION['msg']); ?>
<?php endif; ?>

    <h2>Your Cart 🛒</h2>
<?php if (empty($items)) : ?>
  <p class="empty-cart">Your cart is empty.</p>
<?php else : ?>
  <table class="cart-table">
    <tr>
      <th>Product</th>
      <th>Price</th>
      <th>Added On</th>
      <th>Negotiate</th>
      <th>Action</th>
    </tr>
    <?php foreach ($items as $item): ?>
      <tr>
        <td><?= htmlspecialchars($item['product_name']) ?></td>
        <td>₹<?= number_format($item['price'], 2) ?></td>
        <td><?= $item['added_at'] ?></td>

        <!-- Negotiate Form -->
        <td>
          <form method="POST" action="negotiate_cart.php">
            <input type="hidden" name="cart_id" value="<?= $item['id'] ?>">
            
            <a href="../chatbot.php?cart_id=<?= $item['id'] ?>">Negotiate</a>


          </form>
        </td>

        <!-- Remove Button -->
        <td>
          <form method="POST" action="remove_from_cart.php" onsubmit="return confirm('Remove this item?');">
            <input type="hidden" name="cart_id" value="<?= $item['id'] ?>">
            <button type="submit">Remove</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
<?php endif; ?>


    <p><a class="back-link" href="../product.php">← Back to Products</a></p>
  </div>
</body>
</html>
