<?php
session_start();
if (!isset($_SESSION['admin'])) header("Location: login.php");
include '../includes/db.php';

$id = $_GET['id'] ?? null;
if (!$id) header("Location: products.php");

$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) die("Product not found.");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];
    $min = $_POST['min_price'];
  


    
    $stmt = $conn->prepare("UPDATE products SET name=?, description=?, price=?, min_price=? WHERE id=?");
    $stmt->execute([$name, $desc, $price, $min, $id]);
    header("Location: products.php");
    exit();
}
?>

<h2>Edit Product</h2>
<form method="POST">
  <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required><br>
  <textarea name="description" required><?= htmlspecialchars($product['description']) ?></textarea><br>
  <input type="number" name="price" step="0.01" value="<?= $product['price'] ?>" required><br>
  <input type="number" name="min_price" step="0.01" value="<?= $product['min_price'] ?>" required><br>
 
  <button type="submit">Update Product</button>
</form>
<p><a href="products.php">← Back to Product List</a></p>
