<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: produk.php");
    exit;
}

$id = (int)$_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $nama = trim($_POST['nama_barang']);
    $stok = (int)$_POST['stok'];
    $harga = (float)$_POST['harga'];

    if (!empty($nama)) {
        $stmt = $pdo->prepare("UPDATE products SET nama_barang = :nama, stok = :stok, harga = :harga WHERE id = :id");
        $stmt->bindParam(':nama', $nama);
        $stmt->bindParam(':stok', $stok);
        $stmt->bindParam(':harga', $harga);
        $stmt->bindParam(':id', $id);
        
        if ($stmt->execute()) {
            header("Location: produk.php");
            exit;
        }
    }
}

$stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$p = $stmt->fetch();

if (!$p) {
    die("Data inventaris tidak ditemukan.");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Ubah Data - Bakso Berkah Mandiri</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-warning">
                        <h5 class="mb-0">Perbarui Data Inventaris</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label class="form-label">Nama Menu / Bahan Baku</label>
                                <input type="text" name="nama_barang" class="form-control" value="<?= htmlspecialchars($p['nama_barang']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Jumlah Stok</label>
                                <input type="number" name="stok" class="form-control" value="<?= $p['stok'] ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Harga Satuan (Rp)</label>
                                <input type="number" name="harga" class="form-control" value="<?= $p['harga'] ?>" required>
                            </div>
                            <button type="submit" name="update" class="btn btn-warning w-100">Simpan Perubahan</button>
                            <a href="produk.php" class="btn btn-secondary w-100 mt-2">Batal</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>