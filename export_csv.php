<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=Laporan_Inventaris_Bakso_Berkah_Mandiri.csv');

$output = fopen('php://output', 'w');
fputcsv($output, array('ID Item', 'Nama Menu / Bahan Baku', 'Stok Tersedia', 'Harga Satuan (Rp)', 'Total Nilai Aset (Rp)'));

$stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
$produk = $stmt->fetchAll();

foreach ($produk as $p) {
    $total_aset = $p['stok'] * $p['harga'];
    fputcsv($output, array(
        $p['id'], 
        $p['nama_barang'], 
        $p['stok'], 
        $p['harga'], 
        $total_aset
    ));
}

fclose($output);
exit;
?>