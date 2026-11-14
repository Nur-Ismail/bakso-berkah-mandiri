<?php
session_set_cookie_params([
    'httponly' => true,
    // 'secure' => true, // Aktifkan setelah deployment menggunakan HTTPS
]);
session_start();
require 'config.php';

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

// Inisialisasi penghitung percobaan login gagal (Rate Limiting sederhana)
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['last_attempt_time'] = time();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Cek jika gagal >= 5 kali dalam 1 menit
    if ($_SESSION['login_attempts'] >= 5 && (time() - $_SESSION['last_attempt_time']) < 60) {
        $sisa_waktu = 60 - (time() - $_SESSION['last_attempt_time']);
        $error = "Terlalu banyak percobaan login gagal. Silakan tunggu $sisa_waktu detik lagi.";
    } else {
        // Reset counter jika sudah lewat 60 detik
        if ((time() - $_SESSION['last_attempt_time']) >= 60) {
            $_SESSION['login_attempts'] = 0;
        }

        $username = trim($_POST['username']);
        $password = $_POST['password'];

        $stmt = $pdo->prepare("SELECT id, username, password_hash, role FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['login_attempts'] = 0; // Reset jika berhasil
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            
            session_regenerate_id(true);
            header("Location: dashboard.php");
            exit;
        } else {
            $_SESSION['login_attempts']++;
            $_SESSION['last_attempt_time'] = time();
            $error = "Username atau password tidak valid.";
        }
    }
}
?>
<script>
function togglePassword(inputId, btn) {
    const input = document.getElementById(inputId);
    if (input.type === "password") {
        input.type = "text";
        btn.textContent = "Sembunyikan";
    } else {
        input.type = "password";
        btn.textContent = "Lihat";
    }
}
</script>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Sistem - Bakso Berkah Mandiri</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Login - Bakso Berkah Mandiri</h5>
                    </div>
                    <div class="card-body">
                        <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
                        
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label class="form-label">Username / Email</label>
                                <input type="text" name="username" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <div class="input-group">
                                    <input type="password" name="password" id="loginPassword" class="form-control" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('loginPassword', this)">Lihat</button>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Masuk</button>
                        </form>
                        <div class="mt-3 text-center">
                            <a href="register.php" class="text-decoration-none">Belum memiliki akun? Registrasi</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>