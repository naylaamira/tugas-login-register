<?php
session_start();
require_once 'includes/config.php';

// Check if user is logged in
if (!isset($_SESSION["username"])) {
    header("Location: login.php"); 
    exit;
}

$user_id = $_SESSION["user_id"];
$username = $_SESSION["username"];
$role = $_SESSION["role"] ?? 'user';

// Fetch user data from database
$stmt = $conn->prepare("SELECT email, created_at FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user_data = $result->fetch_assoc();
    $email = $user_data['email'];
    $created_at = $user_data['created_at'];
} else {
    $error = "Data pengguna tidak ditemukan.";
}

// Handle profile update
$success_message = "";
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
    $new_email = $_POST['email'];
    
    // Validate email
    if (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Format email tidak valid!";
    } else {
        // Check if email already exists for other users
        $check_stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $check_stmt->bind_param("si", $new_email, $user_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            $error_message = "Email sudah digunakan oleh pengguna lain.";
        } else {
            // Update email
            $update_stmt = $conn->prepare("UPDATE users SET email = ? WHERE id = ?");
            $update_stmt->bind_param("si", $new_email, $user_id);
            
            if ($update_stmt->execute()) {
                $success_message = "Profil berhasil diperbarui!";
                $email = $new_email; // Update displayed email
            } else {
                $error_message = "Gagal memperbarui profil: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .profile-container {
            text-align: left;
            padding: 20px;
        }
        .profile-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .profile-info {
            margin-bottom: 30px;
        }
        .profile-info p {
            margin-bottom: 10px;
            padding: 8px;
            background-color: #f9f9f9;
            border-radius: 4px;
        }
        .profile-info strong {
            display: inline-block;
            width: 120px;
        }
        .back-btn {
            display: inline-block;
            padding: 8px 16px;
            background-color: #2196f3;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 20px;
        }
        .back-btn:hover {
            background-color: #1976d2;
            text-decoration: none;
        }
        .update-form {
            margin-top: 30px;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
        .update-form h3 {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container profile-container">
            <div class="profile-header">
                <h2>Profil Saya</h2>
                
                <?php if (!empty($error)): ?>
                    <div class="error-message"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                
                <?php if (!empty($success_message)): ?>
                    <div class="success-message"><?= htmlspecialchars($success_message) ?></div>
                <?php endif; ?>
                
                <?php if (!empty($error_message)): ?>
                    <div class="error-message"><?= htmlspecialchars($error_message) ?></div>
                <?php endif; ?>
            </div>
            
            <?php if (empty($error)): ?>
            <div class="profile-info">
                <p><strong>Username:</strong> <?= htmlspecialchars($username) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($email) ?></p>
                <p><strong>Role:</strong> <?= htmlspecialchars($role) ?></p>
                <p><strong>Terdaftar pada:</strong> <?= htmlspecialchars($created_at) ?></p>
            </div>
            
            <div class="update-form">
                <h3>Perbarui Profil</h3>
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="email">Email Baru:</label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
                    </div>
                    <button type="submit" name="update_profile" class="btn">Perbarui Profil</button>
                </form>
            </div>
            <?php endif; ?>
            
            <a href="dashboard.php" class="back-btn">Kembali ke Dashboard</a>
        </div>
    </div>
</body>
</html>
