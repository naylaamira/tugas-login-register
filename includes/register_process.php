<?php
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validasi data tidak boleh kosong
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "Semua field wajib diisi!";
        header("Location: ../register.php?error=" . urlencode($error));
        exit;
    }

    // Validasi email dengan filter_var
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid!";
        header("Location: ../register.php?error=" . urlencode($error));
        exit;
    }

    // Validasi password minimal 8 karakter
    if (strlen($password) < 8) {
        $error = "Password minimal 8 karakter!";
        header("Location: ../register.php?error=" . urlencode($error));
        exit;
    }

    // Cek apakah password dan konfirmasi sama
    if ($password !== $confirm_password) {
        $error = "Konfirmasi password tidak cocok!";
        header("Location: ../register.php?error=" . urlencode($error));
        exit;
    }

    // Cek apakah username sudah ada
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $error = "Username sudah digunakan, coba yang lain.";
        header("Location: ../register.php?error=" . urlencode($error));
        exit;
    }
    
    // Cek apakah email sudah ada
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $error = "Email sudah terdaftar.";
        header("Location: ../register.php?error=" . urlencode($error));
        exit;
    }

    // Hash password sebelum disimpan
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user baru
    $insert = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'user')");
    $insert->bind_param("sss", $username, $email, $hashed_password);

    if ($insert->execute()) {
        // Register berhasil
        header("Location: ../login.php?success=" . urlencode("Registrasi berhasil! Silakan login."));
        exit;
    } else {
        // Gagal insert
        $error = "Gagal melakukan registrasi: " . $conn->error;
        header("Location: ../register.php?error=" . urlencode($error));
        exit;
    }
}
?>