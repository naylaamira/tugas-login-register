<?php
require_once 'config.php'; // koneksi database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email    = $_POST["email"];
    $password = $_POST["password"];
    $confirm  = $_POST["confirm_password"];

    // 1. Cek apakah username sudah ada
    $cek_user = $conn->prepare("SELECT id FROM Users WHERE username = ?");
    $cek_user->bind_param("s", $username);
    $cek_user->execute();
    $cek_user->store_result();

    if ($cek_user->num_rows > 0) {
        echo "❌ Username sudah dipakai. Silakan pilih yang lain.";
        exit;
    }

    // 2. Cek apakah password cocok
    if ($password !== $confirm) {
        echo "❌ Password dan konfirmasi tidak cocok.";
        exit;
    }

    // 3. Simpan user baru
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO Users (username, email, password, role, created_at) VALUES (?, ?, ?, 'user', NOW())");
    $stmt->bind_param("sss", $username, $email, $hash);

    if ($stmt->execute()) {
        echo "✅ Register berhasil! Silakan login.";
    } else {
        echo "❌ Terjadi kesalahan saat menyimpan data.";
    }

    $stmt->close();
    $cek_user->close();
    $conn->close();
}
?>
