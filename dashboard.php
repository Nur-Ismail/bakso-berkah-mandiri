<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$username = htmlspecialchars($_SESSION['username']);
$role = htmlspecialchars($_SESSION['role']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Bakso Berkah Mandiri</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Bakso Berkah Mandiri</a>
            <div class="d-flex align-items-center">
                <span class="navbar-text me-3 text-white">
                    Pengguna: <strong><?= $username ?></strong> (<?= strtoupper($role) ?>)
                </span>
                <a href="logout.php" class="btn btn-outline-light btn-sm">Keluar</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row">
            <div class="col-12">
                <h3>Sistem Manajemen Inventaris & Bahan Baku</h3>
                <p class="text-muted">UMKM Bakso Berkah Mandiri</p>
                <hr>
                <?php if ($role === 'admin'): ?>
                    <div class="alert alert-success">
                        Anda masuk sebagai <strong>Administrator</strong>. Anda memiliki akses penuh untuk menambah, mengubah, menghapus data inventaris, serta mengunduh laporan rekapitulasi.
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        Anda masuk sebagai <strong>Kasir</strong>. Anda memiliki akses untuk memantau stok, menambah item baru, dan memperbarui jumlah stok.
                    </div>
                <?php endif; ?>

                <a href="produk.php" class="btn btn-primary mt-2">Kelola Data Inventaris & Menu</a>
            </div>
        </div>
    </div>
</body>
</html>