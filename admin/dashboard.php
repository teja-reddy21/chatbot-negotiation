<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
  <div class="admin-container">
    <h2>🛠️ Admin Dashboard</h2>
    <ul class="admin-menu">
      <li><a href="users.php">👥 View Users</a></li>
      <li><a href="cart.php">🛒 View All Carts</a></li>
      <li><a href="negotiation_history.php">💬 View Negotiation History</a></li>
      <li><a href="products.php">🛍️ Manage Products</a></li>
      <li><a href="logout.php">🔓 Logout</a></li>
    </ul>
  </div>
</body>
</html>
