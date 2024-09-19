<?php
session_start();
$title = 'Daftar Transaksi';

require '../koneksi.php'; // Pastikan koneksi ke database
require 'includes/header.php'; // Header untuk customer

// Pastikan customer sudah login
if (!isset($_SESSION["username"])) {
    header("Location: ../custlogin.php");
    exit;
}

$username = $_SESSION["username"];

// Ambil semua transaksi yang dilakukan oleh customer yang sedang login
$allTransaksi = query("SELECT * FROM transaksi WHERE username = '$username' ORDER BY idTransaksi DESC");

// Fungsi untuk membatalkan pesanan
function batalkanPesanan($idTransaksi) {
    global $connect;
    $query = "UPDATE transaksi SET statusTransaksi = 'Cancelled', statusPengiriman = 'Dibatalkan' WHERE idTransaksi = ?";
    $stmt = $connect->prepare($query);
    $stmt->bind_param('s', $idTransaksi);
    $stmt->execute();
    $stmt->close();
}

// Fungsi untuk menandai pesanan sebagai diterima oleh customer
function pesananDiterima($idTransaksi) {
    global $connect;
    $query = "UPDATE transaksi SET statusPengiriman = 'Terkirim' WHERE idTransaksi = ?";
    $stmt = $connect->prepare($query);
    $stmt->bind_param('s', $idTransaksi);
    $stmt->execute();
    $stmt->close();
}

// Jika ada permintaan untuk membatalkan pesanan
if (isset($_GET['cancel']) && $_GET['cancel'] != '') {
    $idTransaksi = $_GET['cancel'];
    batalkanPesanan($idTransaksi);
    echo "<script>alert('Pesanan berhasil dibatalkan.'); window.location.href='transaksiCustomer.php';</script>";
}

// Jika ada permintaan untuk menandai pesanan sebagai diterima
if (isset($_GET['accept']) && $_GET['accept'] != '') {
    $idTransaksi = $_GET['accept'];
    pesananDiterima($idTransaksi);
    echo "<script>alert('Pesanan telah diterima.'); window.location.href='transaksiCustomer.php';</script>";
}

?>

<main id="main" class="main">
    <!-- Tambah jarak dan styling untuk header -->
    <div class="pagetitle mb-4">
        <h1 class="text-danger text-center mb-4">Daftar Transaksi</h1>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <!-- Search Bar di sebelah kanan dan tidak terlalu panjang -->
                        

                        <h2 class="card-title text-center mt-4 mb-4" style="padding-top: 35px;">Daftar Transaksi</h2>


                        <div class="d-flex justify-content-end">
                            <div class="col-md-4">
                                <input type="text" placeholder="Cari transaksi..." class="form-control" id="searchingTable">
                            </div>
                        </div>

                        <!-- Tabel Transaksi -->
                        <table class="table table-striped table-hover table-bordered no-wrap">
                            <thead class="thead-dark">
                                <tr>
                                    <th>No</th>
                                    <th>ID Transaksi</th>
                                    <th>Tanggal Transaksi</th>
                                    <th>Cara Pembayaran</th>
                                    <th>Metode Pembayaran</th>
                                    <th>Status Transaksi</th>
                                    <th>Status Pengiriman</th>
                                    <th>Total Harga</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                <?php foreach($allTransaksi as $transaksi): ?>
                                <tr>
                                    <td><?= $i; ?></td>
                                    <td><?= $transaksi["idTransaksi"]; ?></td>
                                    <td><?= $transaksi["tanggalTransaksi"]; ?></td>
                                    <td><?= $transaksi["caraBayar"]; ?></td>
                                    <td><?= $transaksi["bank"]; ?></td>
                                    <td><?= $transaksi["statusTransaksi"]; ?></td>
                                    <td><?= $transaksi["statusPengiriman"]; ?></td>
                                    <td>Rp<?= number_format($transaksi["totalHarga"], 0, ',', '.'); ?></td>
                                    <td>
                                        <!-- Tombol Aksi -->
                                        <?php if ($transaksi["statusTransaksi"] == 'Accepted' && $transaksi["statusPengiriman"] == 'Dalam Perjalanan'): ?>
                                            <!-- Tombol Pesanan Diterima -->
                                            <a href="transaksiCustomer.php?accept=<?= $transaksi["idTransaksi"]; ?>" class="btn btn-success" onclick="return confirm('Anda yakin pesanan ini sudah diterima?');">Pesanan Diterima</a>
                                        <?php elseif ($transaksi["statusTransaksi"] == 'Pending'): ?>
                                            <!-- Tombol Batalkan Pesanan -->
                                            <a href="transaksiCustomer.php?cancel=<?= $transaksi["idTransaksi"]; ?>" class="btn btn-danger" onclick="return confirm('Anda yakin ingin membatalkan pesanan ini?');">Batalkan Pesanan</a>
                                        <?php endif; ?>
                                        <a href="transaksiDetail.php?idTransaksi=<?= $transaksi['idTransaksi']; ?>" class="btn btn-primary">Detail</a>
                                    </td>
                                </tr>
                                <?php $i++; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <!-- End of Table -->

                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
    // Logika pencarian di tabel
    document.getElementById('searchingTable').addEventListener('input', function() {
        var filter = this.value.toLowerCase();
        var rows = document.querySelectorAll('table tbody tr');

        rows.forEach(function(row) {
            var text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });
</script>


