<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    if (empty($username) || empty($password)) {
        $error = "Username dan password wajib diisi.";
    } elseif (strlen($password) < 8) {
        $error = "Password terlalu lemah. Minimal harus terdiri dari 8 karakter.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, password_hash, role) VALUES (:username, :password_hash, :role)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password_hash', $hashed_password);
            $stmt->bindParam(':role', $role);
            
            if ($stmt->execute()) {
                $success = "Registrasi akun berhasil. Silakan menuju halaman login.";
            }
        } catch (\PDOException $e) {
            if ($e->getCode() == 23000) { 
                $error = "Username sudah terdaftar, silakan gunakan username lain.";
            } else {
                $error = "Terjadi kesalahan sistem saat memproses pendaftaran.";
            }
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
    <title>Registrasi Pegawai - Bakso Berkah Mandiri</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">Registrasi Akun - Bakso Berkah Mandiri</h5>
                    </div>
                    <div class="card-body">
                        <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
                        <?php if(isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>
                        
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label class="form-label">Username / Email</label>
                                <input type="text" name="username" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Password (Minimal 8 Karakter)</label>
                                <div class="input-group">
                                    <input type="password" name="password" id="regPassword" class="form-control" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('regPassword', this)">Lihat</button>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Hak Akses (Role)</label>
                                <select name="role" class="form-select">
                                    <option value="kasir">Kasir</option>
                                    <option value="admin">Administrator</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Daftar Akun</button>
                        </form>
                        <div class="mt-3 text-center">
                            <a href="login.php" class="text-decoration-none">Sudah memiliki akun? Masuk di sini</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>