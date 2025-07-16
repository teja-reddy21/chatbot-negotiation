<?php
session_start();
include '../includes/db.php'; // Adjust path if your db.php is elsewhere

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['cart_id'])) {
    $cartId = $_POST['cart_id'];
    $userEmail = $_SESSION['user'];

    // Only delete if the item belongs to the logged-in user
    $stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_email = ?");
    $stmt->execute([$cartId, $userEmail]);

    // Optional: success message
    $_SESSION['msg'] = "Item removed from cart.";

    header("Location: cart.php");
    exit();
} else {
    echo "Invalid request.";
}
