<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$cartId = $_GET['cart_id'] ?? null;
if (!$cartId) {
    echo "Cart ID is missing.";
    exit();
}

// Fetch cart item
$stmt = $conn->prepare("SELECT * FROM cart WHERE id = ?");
$stmt->execute([$cartId]);
$cartItem = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$cartItem) {
    echo "Cart item not found.";
    exit();
}

$productName = $cartItem['product_name'];
$productPrice = $cartItem['price'];

// Minimum acceptable price (e.g., 85% of product price)
$minAcceptablePrice = round($productPrice * 0.85);

if (!isset($_SESSION['chat']) || !is_array($_SESSION['chat'])) {
    $_SESSION['chat'] = [];
}

if (!isset($_SESSION['deal_done']) || !is_array($_SESSION['deal_done'])) {
    $_SESSION['deal_done'] = [];
}


if (!isset($_SESSION['chat'][$cartId])) {
    $_SESSION['chat'][$cartId] = [];
    $_SESSION['deal_done'][$cartId] = false;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_message'])) {
    $userMessage = trim($_POST['user_message']);

    if ($userMessage !== '') {
        $_SESSION['chat'][$cartId][] = ['from' => 'user', 'text' => "User: $userMessage"];

        // Extract numeric value
        $offer = (int) filter_var($userMessage, FILTER_SANITIZE_NUMBER_INT);

        if ($offer >= $minAcceptablePrice) {

          $_SESSION['deal_done'][$cartId] = true;

        } else {
            $response = "❌ Your offer ₹" . number_format($offer, 2) . " is too low. Try something better!";
        }

        $_SESSION['chat'][$cartId][] = ['from' => 'bot', 'text' => "Bot: $response"];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Chatbot Negotiation</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 20px;
      background: #f4f4f4;
    }
    .chat-box {
      background: white;
      padding: 20px;
      border-radius: 10px;
      max-width: 600px;
      margin: auto;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .message {
      margin: 10px 0;
      padding: 10px;
      border-radius: 8px;
    }
    .user-message {
      background-color: #d0f0fd;
      text-align: right;
    }
    .bot-message {
      background-color: #e0ffe0;
      text-align: left;
    }
    .chat-input {
      display: flex;
      margin-top: 15px;
    }
    .chat-input input {
      flex: 1;
      padding: 10px;
      border-radius: 5px 0 0 5px;
      border: 1px solid #ccc;
    }
    .chat-input button {
      padding: 10px 15px;
      border: none;
      background-color: #28a745;
      color: white;
      border-radius: 0 5px 5px 0;
      cursor: pointer;
    }
    h2 {
      text-align: center;
      margin-bottom: 15px;
    }
    .chat-heading {
      font-size: 18px;
      margin-bottom: 10px;
      text-align: center;
    }
  </style>
</head>
<body>
  <div class="chat-box">
    <h2>🗣️ Price Negotiation Chatbot</h2>
    <div class="chat-heading">
      <strong>Product:</strong> <?= htmlspecialchars($productName) ?><br>
      <strong>Maximum Price:</strong> ₹<?= number_format($productPrice, 2) ?>
    </div>

    <?php if (!empty($_SESSION['chat'][$cartId])): ?>
      <?php foreach ($_SESSION['chat'][$cartId] as $message): ?>
        <?php if (is_array($message) && isset($message['from'], $message['text'])): ?>
          <div class="message <?= $message['from'] === 'user' ? 'user-message' : 'bot-message' ?>">
            <?= $message['text'] ?>
          </div>
        <?php endif; ?>
      <?php endforeach; ?>
    <?php endif; ?>

    <?php if (empty($_SESSION['deal_done'][$cartId])): ?>

      <form method="POST" class="chat-input">
        <input type="text" name="user_message" placeholder="Enter your offer (e.g., 800)" required>
        <button type="submit">Send</button>
      </form>
    <?php else: ?>
      <p style="text-align:center; color:green;">🎉 Deal finalized. Go to <a href="pages/cart.php">Cart</a> to proceed.</p>
    <?php endif; ?>
  </div>
</body>
</html>
