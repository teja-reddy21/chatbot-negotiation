<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];

// Fetch cart items
$stmt = $conn->prepare("SELECT * FROM cart WHERE user_email = ?");
$stmt->execute([$user]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Payment</title>
<link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="cart-container">
    <h2>Payment Summary 💳</h2>

    <?php if (empty($items)) : ?>
        <p class="empty-cart">Your cart is empty.</p>
    <?php else : ?>
        <table class="cart-table">
            <tr>
                <th>Product</th>
                <th>Price to Pay</th>
            </tr>
            <?php foreach ($items as $item):
                // Use final_price if set, otherwise fallback to original price
                $priceToPay = $item['final_price'] ?? $item['price'];
                $total += $priceToPay;
            ?>
            <tr>
                <td><?= htmlspecialchars($item['product_name']) ?></td>
                <td>₹<?= number_format($priceToPay, 2) ?></td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <td><strong>Total</strong></td>
                <td><strong>₹<?= number_format($total, 2) ?></strong></td>
            </tr>
        </table>

        <form action="process_payment.php" method="POST" style="margin-top: 20px;">
            <input type="hidden" name="total_amount" value="<?= $total ?>">
            <button type="submit" style="padding: 10px 20px; font-size: 16px;">Pay Now 💳</button>
        </form>
    <?php endif; ?>

    <p><a class="back-link" href="cart.php">← Back to Cart</a></p>
</div>
</body>
</html>
