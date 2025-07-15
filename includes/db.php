<?php
$host = 'localhost';
$dbname = 'ecommerce_bot';
$user = 'root';
$pass = ''; // XAMPP default has no password

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("DB Error: " . $e->getMessage());
}
?>
