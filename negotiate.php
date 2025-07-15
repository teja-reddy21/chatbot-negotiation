<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['cart_id'])) {
    die("Cart item not specified.");
}

$cartId = $_GET['cart_id'];

$stmt = $conn->prepare("SELECT c.*, p.min_price FROM cart c JOIN products p ON c.product_id = p.id WHERE c.id = ?");
$stmt->execute([$cartId]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$item) {
    die("Invalid cart item.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Negotiate Price</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
  <div class="auth-container">
    <div class="auth-card">
      <h2>Negotiate for <?= htmlspecialchars($item['product_name']) ?></h2>
      <p>Original Price: ₹<?= number_format($item['price'], 2) ?></p>
      <p>Minimum Acceptable Price: ₹<?= number_format($item['min_price'], 2) ?></p>

      <form method="POST" action="process_negotiation.php">
        <input type="hidden" name="cart_id" value="<?= $cartId ?>">
        <input type="number" name="offer_price" placeholder="Your Offer Price ₹" required>
        <button type="submit" name="negotiate_btn">Submit Offer</button>
      </form>
      <p><a href="cart.php" class="back-link">← Back to Cart</a></p>
    </div>
  </div>
</body>
</html>
