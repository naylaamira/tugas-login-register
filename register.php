<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* Additional styles for error messages */
        .error-message {
            color: #721c24;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 4px;
            padding: 10px;
            margin-bottom: 15px;
        }
        
        .password-warning {
            color: #721c24;
            font-size: 0.85em;
            margin-top: 5px;
            display: none;
        }
        
        .password-warning.visible {
            display: block;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2>Registrasi</h2>
            
            <?php
            // Display error message from URL parameter
            if (isset($_GET['error'])) {
                echo '<div class="error-message">' . htmlspecialchars($_GET['error']) . '</div>';
            }
            ?>
            
            <form action="includes/register_process.php" method="POST" id="registerForm">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                    <div id="passwordWarning" class="password-warning">Password minimal 8 karakter!</div>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Konfirmasi Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                    <div id="confirmWarning" class="password-warning">Konfirmasi password tidak cocok!</div>
                </div>
                <button type="submit" class="btn">Daftar</button>
            </form>
            <p class="login-link">Sudah punya akun? <a href="login.php">Login di sini</a></p>
        </div>
    </div>

    <script>
        // Get form elements
        const form = document.getElementById('registerForm');
        const passwordInput = document.getElementById('password');
        const confirmInput = document.getElementById('confirm_password');
        const passwordWarning = document.getElementById('passwordWarning');
        const confirmWarning = document.getElementById('confirmWarning');
        
        // Password validation
        
        
        // Confirm password validation
        function validateConfirmPassword() {
            if (confirmInput.value !== passwordInput.value) {
                confirmWarning.classList.add('visible');
                confirmInput.setCustomValidity('Konfirmasi password tidak cocok!');
            } else {
                confirmWarning.classList.remove('visible');
                confirmInput.setCustomValidity('');
            }
        }
        
        confirmInput.addEventListener('input', validateConfirmPassword);
        
        // Form submission validation
        form.addEventListener('submit', function(event) {
            // Revalidate password length
            if (passwordInput.value.length < 8) {
                passwordWarning.classList.add('visible');
                event.preventDefault();
            }
            
            // Revalidate password match
            if (confirmInput.value !== passwordInput.value) {
                confirmWarning.classList.add('visible');
                event.preventDefault();
            }
        });
    </script>
</body>
</html>
