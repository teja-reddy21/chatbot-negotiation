<?php
session_start();
if (!isset($_SESSION['admin'])) header("Location: login.php");
include '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];
    $min = $_POST['min_price'];
    $imageName = null;

    if (!empty($_FILES['image']['name'])) {
    $imageName = time() . '_' . basename($_FILES['image']['name']);
    $target = "../images/" . $imageName;
    move_uploaded_file($_FILES['image']['tmp_name'], $target);
    }

    $stmt = $conn->prepare("INSERT INTO products (name, description, price, min_price,image) VALUES (?, ?, ?, ?,?)");
    $stmt->execute([$name, $desc, $price, $min,$imageName]);
    header("Location: products.php");
    exit();
}
?>

<h2>Add Product</h2>
<form method="POST"  enctype="multipart/form-data">
  <input type="text" name="name" placeholder="Product Name" required><br>
  <textarea name="description" placeholder="Description" required></textarea><br>
  <input type="number" name="price" step="0.01" placeholder="Price" required><br>
  <input type="number" name="min_price" step="0.01" placeholder="Minimum Price" required><br>
   <input type="file" name="image" accept="image/*"><br>
  <button type="submit">Add Product</button>
</form>
<p><a href="products.php">← Back to Product List</a></p>
