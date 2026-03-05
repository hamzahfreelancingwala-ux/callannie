<?php
$host = "localhost";
$dbname = "rsoa_rsoa112_60";
$username = "rsoa_rsoa112_60";
$password = "123456";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
