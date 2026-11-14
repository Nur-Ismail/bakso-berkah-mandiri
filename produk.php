<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tambah'])) {
    $nama = trim($_POST['nama_barang']);
    $stok = (int)$_POST['stok'];
    $harga = (float)$_POST['harga'];

    if (!empty($nama)) {
        $stmt = $pdo->prepare("INSERT INTO products (nama_barang, stok, harga) VALUES (:nama, :stok, :harga)");
        $stmt->bindParam(':nama', $nama);
        $stmt->bindParam(':stok', $stok);
        $stmt->bindParam(':harga', $harga);
        
        if ($stmt->execute()) {
            $msg = "Data inventaris berhasil ditambahkan.";
        }
    }
}

$stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
$produk = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Inventaris - Bakso Berkah Mandiri</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="mb-0">Data Inventaris & Menu Bakso</h3>
                <small class="text-muted">Bakso Berkah Mandiri</small>
            </div>
            <div>
                <a href="export_csv.php" class="btn btn-success me-2">Unduh Laporan (CSV)</a>
                <a href="dashboard.php" class="btn btn-secondary">Kembali ke Dashboard</a>
            </div>
        </div>
        
        <?php if(isset($msg)) echo "<div class='alert alert-success'>$msg</div>"; ?>

        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-dark text-white">Tambah Item Baru</div>
            <div class="card-body">
                <form method="POST" action="">
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <input type="text" name="nama_barang" class="form-control" placeholder="Nama Menu / Bahan Baku" required>
                        </div>
                        <div class="col-md-3 mb-2">
                            <input type="number" name="stok" class="form-control" placeholder="Jumlah Stok" required>
                        </div>
                        <div class="col-md-3 mb-2">
                            <input type="number" name="harga" class="form-control" placeholder="Harga Satuan (Rp)" required>
                        </div>
                        <div class="col-md-2 mb-2">
                            <button type="submit" name="tambah" class="btn btn-primary w-100">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nama Item</th>
                            <th>Stok Tersedia</th>
                            <th>Harga Satuan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($produk as $p): ?>
                        <tr>
                            <td><?= $p['id'] ?></td>
                            <td><?= htmlspecialchars($p['nama_barang']) ?></td>
                            <td><?= $p['stok'] ?></td>
                            <td>Rp <?= number_format($p['harga'], 0, ',', '.') ?></td>
                            <td>
                                <a href="edit_produk.php?id=<?= $p['id'] ?>" class="btn btn-warning btn-sm">Ubah</a>
                                <?php if ($_SESSION['role'] === 'admin'): ?>
                                    <a href="hapus_produk.php?id=<?= $p['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">Hapus</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if(count($produk) == 0) echo "<tr><td colspan='5' class='text-center text-muted'>Belum ada data inventaris yang tercatat.</td></tr>"; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>