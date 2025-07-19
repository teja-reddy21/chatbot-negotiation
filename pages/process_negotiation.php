<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cart_id'], $_POST['offer_price'])) {
    $cartId = $_POST['cart_id'];
    $userMessage = $_POST['offer_price']; // This could be text or number
    $userEmail = $_SESSION['user'];

    // Extract the first number from user input
    preg_match('/\d+/', $userMessage, $matches);
    if (!$matches) {
        $_SESSION['msg'] = "❌ Could not understand your offer. Please enter a valid number.";
        header("Location: cart.php");
        exit();
    }

    $userOffer = floatval($matches[0]); // Get the first number mentioned in message

    // Get product info through the cart
    $stmt = $conn->prepare("SELECT c.*, p.min_price FROM cart c JOIN products p ON c.product_id = p.id WHERE c.id = ? AND c.user_email = ?");
    $stmt->execute([$cartId, $userEmail]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$item) {
        $_SESSION['msg'] = "Invalid cart item.";
        header("Location: cart.php");
        exit();
    }

    $accepted = ($userOffer >= $item['min_price']) ? 1 : 0;

    // Log negotiation
    $log = $conn->prepare("INSERT INTO negotiation_history (user_email, product_id, offered_price, accepted) VALUES (?, ?, ?, ?)");
    $log->execute([$userEmail, $item['product_id'], $userOffer, $accepted]);

    if ($accepted) {
        // Update cart price
        $update = $conn->prepare("UPDATE cart SET price = ? WHERE id = ?");
        $update->execute([$userOffer, $cartId]);
        $_SESSION['msg'] = "✅ Deal accepted for ₹$userOffer!";
    } else {
        $_SESSION['msg'] = "❌ Offer ₹$userOffer too low. Try again!";
    }

    header("Location: cart.php");
    exit();
} else {
    $_SESSION['msg'] = "Invalid request.";
    header("Location: cart.php");
    exit();
}
