<?php
session_start();
include './includes/db.php';

// Redirect to login if not logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
$cartId = $_GET['cart_id'] ?? null;

if (!$cartId) {
    echo "No cart ID provided.";
    exit();
}

// Fetch product info from cart table
$stmt = $conn->prepare("SELECT * FROM cart WHERE id = ? AND user_email = ?");
$stmt->execute([$cartId, $user]);
$cartItem = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$cartItem) {
    echo "Invalid cart ID or no access.";
    exit();
}

$productName = $cartItem['product_name'];
$productPrice = (float) $cartItem['price'];
$minAcceptable = 850;
// Reset chat if product changed
if (!isset($_SESSION['current_cart_id']) || $_SESSION['current_cart_id'] != $cartId) {
      $_SESSION['chat'] = [];
    $_SESSION['current_cart_id'] = $cartId;
}

// Handle user message
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $userMessage = trim($_POST['message']);

    $_SESSION['chat'][] = ["user", htmlspecialchars($userInput)];

    // Extract number from message using regex
    preg_match('/\d+/', $userMessage, $matches);
    $offer = isset($matches[0]) ? (float)$matches[0] : null;

    if ($offer) {
         if ($offer >= $minAcceptable) {
            $_SESSION['chat'][] = ["bot", "✅ Deal accepted at ₹" . number_format($offer, 2) . ". Go to <a href='../cart.php'>Cart</a>"];
            $_SESSION['msg'] = "Deal for $productName accepted at ₹$offer.";
            header("Location: cart.php");
            exit();
        } else {
            $_SESSION['chat'][] = ["bot", "❌ Your offer ₹" . number_format($offer, 2) . " is too low. Try something better!"];
        }
    } else {
        $_SESSION['chat'][] = ["bot", "❓ I couldn't find a price in your message. Please mention your offer like 'I can pay 1000'."];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Price Negotiation Chatbot</title>
    <link rel="stylesheet" href="./css/style.css">
    <style>
        .chatbox {
            max-width: 600px;
            margin: 40px auto;
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 10px;
            background: #fdfdfd;
        }
        .chat-log {
            border: 1px solid #ddd;
            height: 300px;
            overflow-y: scroll;
            padding: 10px;
            background: #fff;
            margin-bottom: 15px;
        }
        .chat-log .user { color: #007bff; margin-bottom: 5px; }
        .chat-log .bot { color: #28a745; margin-bottom: 5px; }
        .chat-form input[type="text"] {
            width: 80%;
            padding: 10px;
        }
        .chat-form button {
            padding: 10px 15px;
        }
        .product-info {
            background: #f8f9fa;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="chatbox">
        <h2>🗣️ Price Negotiation Chatbot</h2>

        <div class="product-info">
            <strong>Product:</strong> <?= htmlspecialchars($productName) ?><br>
            <strong>Maximum Price:</strong> ₹<?= number_format($productPrice, 2) ?>
        </div>

        <div class="chat-log" id="chat-log">
            <?php if (!empty($_SESSION['chat'])): ?>
                <?php foreach ($_SESSION['chat_history'] as $msg): ?>
                    <div class="<?= $msg['from'] ?>">
                        <strong><?= ucfirst($msg['from']) ?>:</strong> <?= $msg['text'] ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="bot"><strong>Bot:</strong> Hi! Let's negotiate the price. Please enter your offer.</div>
            <?php endif; ?>
        </div>

        <form class="chat-form" method="POST">
            <input type="text" name="message" placeholder="Enter your offer..." required>
            <button type="submit">Send</button>
        </form>

        <p><a href="./cart.php">← Back to Cart</a></p>
    </div>

    <script>
        // Scroll chat to bottom on load
        const log = document.getElementById("chat-log");
        log.scrollTop = log.scrollHeight;
    </script>
</body>
</html>
