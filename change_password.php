 <?php
session_start();
include 'includes/config.php'; 

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];
$msg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $old = $_POST['old_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    $query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    $user = mysqli_fetch_assoc($query);

    if (!password_verify($old, $user['password'])) {
        $msg = "Password lama salah!";
    } elseif ($new !== $confirm) {
        $msg = "Konfirmasi password tidak cocok!";
    } else {
        $hashed = password_hash($new, PASSWORD_DEFAULT);
        mysqli_query($conn, "UPDATE users SET password='$hashed' WHERE username='$username'");
        $msg = "Password berhasil diubah!";

        session_destroy();
        header("Location: login.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Ubah Password</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="container">
    <div class="form-container">
        <h2>Ubah Password</h2>
        <?php if ($msg): ?>
            <div class="<?= strpos($msg, 'berhasil') !== false ? 'success-message' : 'error-message' ?>">
                <?= $msg ?>
            </div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label>Password Lama:</label>
                <input type="password" name="old_password" required>
            </div>
            <div class="form-group">
                <label>Password Baru:</label>
                <input type="password" name="new_password" required>
            </div>
            <div class="form-group">
                <label>Konfirmasi Password:</label>
                <input type="password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn">Ubah Password</button>
        </form>
        <div class="register-link">
            <a href="dashboard.php">← Kembali ke Dashboard</a>
        </div>
    </div>
</div>
</body>
</html>
