<?php
require 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($action === 'signup') {
        $username = $_POST['username'];
        $hashed_pw = password_hash($password, PASSWORD_BCRYPT);

        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$username, $email, $hashed_pw]);
            echo "<script>alert('Account Created! Please login.'); window.location.href='login.php';</script>";
        } catch (Exception $e) {
            echo "<script>alert('Email already exists!'); window.history.back();</script>";
        }
    } else {
        // Login Logic
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            // Redirect using JS
            echo "<script>window.location.href='chat.php';</script>";
        } else {
            echo "<script>alert('Invalid Credentials'); window.history.back();</script>";
        }
    }
}
?>
