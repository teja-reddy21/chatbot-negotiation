<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $pass = $_POST['password'];

    if ($email === 'admin@store.com' && $pass === 'admin123') {
        $_SESSION['admin'] = true;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid admin credentials!";
    }
}
?>

<h2>Admin Login</h2>
<?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
<form method="POST">
  <input type="email" name="email" placeholder="Admin Email" required><br>
  <input type="password" name="password" placeholder="Password" required><br>
  <button type="submit">Login</button>
</form>
