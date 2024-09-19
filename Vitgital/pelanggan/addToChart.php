<?php
session_start();
require_once '../koneksi.php'; // Koneksi ke database

// Pastikan pengguna sudah login
if (!isset($_SESSION["username"])) {
    echo json_encode(['status' => 'error', 'message' => 'Anda harus login terlebih dahulu']);
    exit;
}

// Validasi input
if (!isset($_POST['idProduk']) || !isset($_POST['jumlah'])) {
    echo json_encode(['status' => 'error', 'message' => 'Input tidak valid']);
    exit;
}

$username = $_SESSION["username"];
$idProduk = mysqli_real_escape_string($connect, $_POST['idProduk']);
$jumlah = (int)$_POST['jumlah'];

if ($jumlah <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Jumlah produk harus lebih dari nol']);
    exit;
}

// Ambil detail produk dari database
$produkQuery = "SELECT hargaProduk, stokProduk FROM produk WHERE idProduk = '$idProduk'";
$produkResult = mysqli_query($connect, $produkQuery);

if (!$produkResult || mysqli_num_rows($produkResult) == 0) {
    echo json_encode(['status' => 'error', 'message' => 'Produk tidak ditemukan']);
    exit;
}

$produk = mysqli_fetch_assoc($produkResult);

// Cek jika produk memiliki stok yang cukup
if ($produk['stokProduk'] < $jumlah) {
    echo json_encode(['status' => 'error', 'message' => 'Stok produk tidak mencukupi']);
    exit;
}

$hargaProduk = $produk['hargaProduk'];
$totalHarga = $jumlah * $hargaProduk;

// Cek apakah produk sudah ada di keranjang
$cekProdukQuery = "SELECT * FROM keranjang WHERE idProduk = '$idProduk' AND username = '$username' AND status = 'Belum Dibayar'";
$cekProdukResult = mysqli_query($connect, $cekProdukQuery);

if (mysqli_num_rows($cekProdukResult) > 0) {
    // Produk sudah ada di keranjang, update jumlah dan total harga
    $cekProduk = mysqli_fetch_assoc($cekProdukResult);
    $newJumlah = $cekProduk['jumlah'] + $jumlah;
    $newTotalHarga = $newJumlah * $hargaProduk;
    $updateQuery = "UPDATE keranjang SET jumlah = '$newJumlah', harga = '$newTotalHarga' WHERE idProduk = '$idProduk' AND username = '$username' AND status = 'Belum Dibayar'";
    $result = mysqli_query($connect, $updateQuery);
} else {
    // Produk belum ada di keranjang, tambahkan sebagai entri baru
    $insertQuery = "INSERT INTO keranjang (username, idProduk, jumlah, harga, status) VALUES ('$username', '$idProduk', '$jumlah', '$totalHarga', 'Belum Dibayar')";
    $result = mysqli_query($connect, $insertQuery);
}

// Kurangi stok produk
if ($result) {
    $updateStokQuery = "UPDATE produk SET stokProduk = stokProduk - '$jumlah' WHERE idProduk = '$idProduk'";
    mysqli_query($connect, $updateStokQuery);
    echo json_encode(['status' => 'success', 'message' => 'Produk berhasil ditambahkan ke keranjang']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Gagal menambahkan produk ke keranjang']);
}
?>
