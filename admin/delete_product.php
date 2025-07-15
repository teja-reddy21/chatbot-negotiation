<?php
session_start();
if (!isset($_SESSION['admin'])) header("Location: login.php");
include '../includes/db.php';

$id = $_GET['id'] ?? null;
if ($id) {
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$id]);
}
header("Location: products.php");
exit();
?>
