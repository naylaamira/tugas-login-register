<?php
session_start();
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Cek apakah username ada di database
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Jika username ditemukan
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Cek apakah password cocok
        if (password_verify($password, $user['password'])) {
            // Login berhasil
            $_SESSION['username'] = $user['username'];
            header("Location: dashboard.php");
            exit;
        } else {
            // Password salah
            $_SESSION['error'] = "Password salah!";
            header("Location: login.php");
            exit;
        }
    } else {
        // Username tidak ditemukan
        $_SESSION['error'] = "Username tidak ditemukan!";
        header("Location: login.php");
        exit;
    }
} else {
    // Jika bukan POST request
    $_SESSION['error'] = "Akses tidak valid.";
    header("Location: login.php");
    exit;
}
?>