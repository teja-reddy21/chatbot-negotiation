<?php
session_start();
include '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user['email'];
        header("Location: ../product.php");
        exit();
    } else {
        $error = "Invalid login credentials.";
    }
}
?>

<!-- Simple Login Form -->

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
  <div class="auth-container">
    <div class="auth-card">
      <h2>Login</h2>
      <?php if (isset($error)) echo "<p class='error-msg'>$error</p>"; ?>
      <form method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
      </form>
      <p>Don't have an account? <a href="register.php">Register</a></p>
    </div>
  </div>
</body>
</html>
