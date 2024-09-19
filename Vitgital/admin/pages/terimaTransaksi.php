<?php
session_start();
include('../../koneksi.php'); // Koneksi ke database

// Pastikan admin sudah login
if (!isset($_SESSION['admin_login'])) {
    header('Location: ../adminLogin.php');
    exit;
}

// Fungsi untuk menerima transaksi
function acceptTransaksi($idTransaksi) {
    global $connect;

    // Periksa apakah transaksi valid dan status belum diterima
    $stmt = $connect->prepare("SELECT * FROM transaksi WHERE idTransaksi = ? AND statusTransaksi != 'Accepted'");
    $stmt->bind_param('s', $idTransaksi);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        // Jika transaksi sudah diterima atau tidak ditemukan
        return false;
    }

    // Update status transaksi menjadi Accepted dan pengiriman menjadi Dalam Perjalanan
    $stmt = $connect->prepare("UPDATE transaksi SET statusTransaksi = 'Accepted', statusPengiriman = 'Dalam Perjalanan' WHERE idTransaksi = ?");
    $stmt->bind_param('s', $idTransaksi);
    $stmt->execute();
    $stmt->close();

    return true;
}

// Mengambil idTransaksi dari URL
if (isset($_GET['idTransaksi'])) {
    $idTransaksi = $_GET['idTransaksi'];

    // Proses menerima transaksi
    if (acceptTransaksi($idTransaksi)) {
        echo "<script>alert('Transaksi telah diterima!'); window.location.href='manageTransaksi.php';</script>";
    } else {
        echo "<script>alert('Transaksi tidak valid atau sudah diterima.'); window.location.href='manageTransaksi.php';</script>";
    }
}
?>
