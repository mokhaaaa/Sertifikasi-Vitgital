<?php
session_start();
include('../../koneksi.php'); // Koneksi ke database

// Pastikan admin sudah login
if (!isset($_SESSION['admin_login'])) {
    header('Location: ../adminLogin.php');
    exit;
}

// Fungsi untuk menolak transaksi
function rejectTransaksi($idTransaksi) {
    global $connect;

    // Periksa apakah transaksi valid dan status belum ditolak
    $stmt = $connect->prepare("SELECT * FROM transaksi WHERE idTransaksi = ? AND statusTransaksi != 'Rejected'");
    $stmt->bind_param('s', $idTransaksi);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        // Jika transaksi sudah ditolak atau tidak ditemukan
        return false;
    }

    // Update status transaksi menjadi Rejected dan pengiriman menjadi Dibatalkan
    $stmt = $connect->prepare("UPDATE transaksi SET statusTransaksi = 'Rejected', statusPengiriman = 'Dibatalkan' WHERE idTransaksi = ?");
    $stmt->bind_param('s', $idTransaksi);
    $stmt->execute();
    $stmt->close();

    // Mengembalikan stok produk yang terkait dengan transaksi ini
    $stmt = $connect->prepare("SELECT idProduk, jumlah FROM keranjang WHERE idTransaksi = ?");
    $stmt->bind_param('s', $idTransaksi);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($keranjang = $result->fetch_assoc()) {
        $idProduk = $keranjang['idProduk'];
        $jumlah = $keranjang['jumlah'];

        // Kembalikan stok produk
        $updateStokQuery = "UPDATE produk SET stokProduk = stokProduk + ? WHERE idProduk = ?";
        $stmt2 = $connect->prepare($updateStokQuery);
        $stmt2->bind_param('is', $jumlah, $idProduk);
        $stmt2->execute();
        $stmt2->close();
    }

    return true;
}

// Mengambil idTransaksi dari URL
if (isset($_GET['idTransaksi'])) {
    $idTransaksi = $_GET['idTransaksi'];

    // Proses menolak transaksi
    if (rejectTransaksi($idTransaksi)) {
        echo "<script>alert('Transaksi telah ditolak dan stok produk dikembalikan.'); window.location.href='manageTransaksi.php';</script>";
    } else {
        echo "<script>alert('Transaksi tidak valid atau sudah ditolak.'); window.location.href='manageTransaksi.php';</script>";
    }
}
?>
