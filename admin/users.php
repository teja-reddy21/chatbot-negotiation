<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
include '../includes/db.php';

$users = $conn->query("SELECT * FROM users")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Registered Users</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
  <div class="admin-container">
    <h2>👥 Registered Users</h2>
    <?php if (empty($users)) : ?>
      <p>No users found.</p>
    <?php else : ?>
      <table class="admin-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Registered</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($users as $user): ?>
            <tr>
              <td><?= $user['id'] ?></td>
              <td><?= htmlspecialchars($user['name']) ?></td>
              <td><?= htmlspecialchars($user['email']) ?></td>
              <td><?= $user['created_at'] ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
    <p><a class="back-link" href="dashboard.php">← Back to Dashboard</a></p>
  </div>
</body>
</html>
