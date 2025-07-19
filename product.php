<?php
include 'includes/db.php';

session_start();

if (!isset($_SESSION['user'])) {
    header("Location: pages/login.php");
    exit();
}
$userEmail = $_SESSION['user'];



?>


<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Store</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- ✅ NAVBAR -->
<nav class="navbar">
  <div class="logo">🛍️ My Store</div>
  <ul class="nav-links">
    <?php if (isset($_SESSION['user'])): ?>
      <li><span class="welcome">Hi, <?= htmlspecialchars($_SESSION['user']) ?></span></li>
      <li><a href="pages/cart.php">Cart 🛒</a></li>
      <li><a href="pages/logout.php">Logout 🔒</a></li>
    <?php else: ?>
      <li><a href="pages/login.php">Login 🔐</a></li>
      <li><a href="pages/register.php">Register 📝</a></li>
    <?php endif; ?>
  </ul>
</nav>

<!-- ✅ PRODUCT SECTION -->
<main class="products">
  <h2>Products</h2>
 

  <?php


$stmt = $conn->query("SELECT * FROM products");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<div class="product-list">
  <?php foreach ($products as $product) : ?>
    <div class="product-card">
      <h3><?= htmlspecialchars($product['name']) ?></h3>
      <p><?= htmlspecialchars($product['description']) ?></p>
      <p>Price: ₹<?= number_format($product['price'], 2) ?></p>
     
      <form method="POST" action="pages/cart.php">
        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
        <button type="submit" name="add_to_cart">Add to Cart</button>
      </form>
    </div>
  <?php endforeach; ?>

  



</div>


</main>

</body>
</html>
