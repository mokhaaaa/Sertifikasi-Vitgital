<?php
session_start();
$title = 'Detail Transaksi';

require '../koneksi.php'; // Pastikan koneksi ke database
require 'includes/header.php'; // Header untuk customer

// Pastikan customer sudah login
if (!isset($_SESSION["username"])) {
    header("Location: ../custlogin.php");
    exit;
}

$idTransaksi = $_GET["idTransaksi"];
$username = $_SESSION["username"];

// Ambil detail transaksi dan produk terkait
$detailTransaksi = query("SELECT * FROM transaksi
                          JOIN customer ON transaksi.username = customer.username
                          WHERE transaksi.idTransaksi = '$idTransaksi' AND transaksi.username = '$username'")[0];

$keranjangUser = query("SELECT * FROM keranjang
                        JOIN produk ON keranjang.idProduk = produk.idProduk
                        WHERE keranjang.username = '$username' AND keranjang.idTransaksi = '$idTransaksi'");

$tanggalTransaksi = strtotime($detailTransaksi["tanggalTransaksi"]);
$tanggalFormatted = date("j F Y", $tanggalTransaksi);

// Proses feedback
if (isset($_POST["submit"])) {
    if (feedback($_POST) > 0) {
        echo "<script>
                alert('Feedback berhasil dikirim!');
                document.location.href = 'transaksiCustomer.php';
              </script>";
    } else {
        echo "<script>
                alert('Feedback gagal dikirim!');
                document.location.href = 'transaksiCustomer.php';
              </script>";
    }
}

// Fungsi feedback
function feedback($data) {
    global $connect;

    $idTransaksi = $data["idTransaksi"];
    $feedBack = htmlspecialchars($data["feedBack"]);

    $query = "UPDATE transaksi SET feedBack = '$feedBack' WHERE idTransaksi = '$idTransaksi'";
    mysqli_query($connect, $query);
    return mysqli_affected_rows($connect);
}
?>

<main id="main" class="main">
    <!-- Header Section untuk Logo, Nama Toko, dan Laporan Belanja -->
    <div class="pagetitle mb-4 text-center" style="padding-top: 150px;">
        <img src="../img/logo.png" alt="Logo" style="width: 100px; display: block; margin: 0 auto 20px;"> <!-- Tambahkan logo di atas tulisan -->
        <h2 style="color: #2596be;">Vitgital</h4>
        <h4>Laporan Belanja <?= $detailTransaksi["username"]; ?></h4>
    </div>

    <section class="section">
        <div class="container-fluid">

            <!-- Detail Informasi Transaksi -->
            <div class="row mb-4 justify-content-center">
                <div class="col-md-8">
                    
                    <ul class="list-group mt-3">
                        <li class="list-group-item"><strong>Username:</strong> <?= $detailTransaksi["username"]; ?></li>
                        <li class="list-group-item"><strong>Nama Lengkap:</strong> <?= $detailTransaksi["namaLengkap"]; ?></li>
                        <li class="list-group-item"><strong>Alamat:</strong> <?= $detailTransaksi["alamat"]; ?></li>
                        <li class="list-group-item"><strong>No. Telp:</strong> <?= $detailTransaksi["contact"]; ?></li>
                        <li class="list-group-item"><strong>Tanggal Transaksi:</strong> <?= $tanggalFormatted; ?></li>
                        <li class="list-group-item"><strong>ID Paypal:</strong> <?= $detailTransaksi["paypalID"]; ?></li>
                        <li class="list-group-item"><strong>Nama Bank:</strong> <?= $detailTransaksi["bank"]; ?></li>
                        <li class="list-group-item"><strong>Cara Bayar:</strong> <?= $detailTransaksi["caraBayar"]; ?></li>
                        <li class="list-group-item"><strong>Status Transaksi:</strong> <?= $detailTransaksi["statusTransaksi"]; ?></li>
                        <li class="list-group-item"><strong>Status Pengiriman:</strong> <?= $detailTransaksi["statusPengiriman"]; ?></li>
                    </ul>
                </div>
            </div>

            <!-- Detail Produk Transaksi -->
            <div class="row mb-4 justify-content-center">
                <div class="col-md-8">
                    <h5 class="text mt-3">Produk dalam Transaksi</h5>
                    <table class="table table-bordered mt-3">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>ID Produk</th>
                                <th>Nama Produk</th>
                                <th>Jumlah</th>
                                <th>Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            <?php foreach($keranjangUser as $keranjang): ?>
                            <tr>
                                <td><?= $i; ?></td>
                                <td><?= $keranjang["idProduk"]; ?></td>
                                <td><?= $keranjang["namaProduk"]; ?></td>
                                <td><?= $keranjang["jumlah"]; ?></td>
                                <td>Rp<?= number_format($keranjang["harga"], 0, ',', '.'); ?></td>
                            </tr>
                            <?php $i++; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="fw-bold mb-3" style="font-size: 24px;">Total Harga: Rp<?= number_format($detailTransaksi["totalHarga"], 0, ',', '.'); ?></div>
                </div>
            </div>

            <!-- Feedback dan Pemilik Toko Section -->
            <div class="col-12 d-flex justify-content-between mt-5">
                <!-- Feedback Section -->
                <div class="feedback-section">
                    <div class="fw-bold">Feedback:</div>
                    <p><?= $detailTransaksi["feedBack"] ? $detailTransaksi["feedBack"] : "Belum ada feedback."; ?></p>

                    <!-- Form untuk memberikan feedback -->
                    <?php if ($detailTransaksi["feedBack"] == NULL && $detailTransaksi["statusPengiriman"] == "Terkirim" && $detailTransaksi["statusTransaksi"] != 'Cancelled'): ?>
                    <form action="" method="post" class="mt-3">
                        <input type="hidden" name="idTransaksi" value="<?= $detailTransaksi["idTransaksi"]; ?>">
                        <div class="mb-3">
                            <label for="feedbackInput" class="form-label">Berikan Feedback:</label>
                            <input type="text" class="form-control" id="feedbackInput" name="feedBack" required>
                        </div>
                        <button type="submit" name="submit" class="btn btn-danger">Kirim Feedback</button>
                    </form>
                    <?php endif; ?>
                </div>
                
                <!-- TTD dan Pemilik -->
                <div class="signature-section">
                    <h5>Pemilik Toko</h5>
                    <img src="../img/ttd.png" alt="Tanda Tangan" >
                    <p class="fw-bold">Vitgital</p>
                </div>
            </div>

            <!-- Tombol Cetak dan Kembali -->
            <div class="text-center mt-4 d-print-none">
                <button id="printButton" class="btn btn-secondary mx-2">Cetak</button>
                <a href="transaksiCustomer.php" id="backButton" class="btn btn-warning mx-2">Kembali</a>
            </div>

        </div>
    </section><!-- End Section -->
</main>

<script>
    // Logika Cetak Halaman
    document.getElementById("printButton").addEventListener("click", function() {
        document.querySelector('header').style.display = 'none';
        document.getElementById('backButton').style.display = 'none'; // Sembunyikan tombol Kembali saat mencetak
        window.print();
    });

    // Mengatasi masalah layout rusak setelah membatalkan cetak
    window.onafterprint = function() {
        location.reload(); // Refresh halaman setelah mencetak atau membatalkan pencetakan
    };
</script>

<!-- Tambahkan CSS -->
<style>
    .feedback-section {
        display: block;
        margin-top: 20px;
        margin-left: 270px;
    }

    .signature-section {
        display: flex;
        flex-direction: column; /* Mengatur elemen secara vertikal */
        align-items: center; /* Mengatur elemen di tengah-tengah secara horizontal */
        margin-right: 230px; /* Mengatur agar seluruh div berada di sebelah kanan */
        text-align: center; /* Mengatur teks di tengah */
    }

    .signature-section h5 {
        margin-bottom: 20px; /* Memberi jarak antara "Pemilik Toko" dan gambar */
    }

    .signature-section img {
        width: 200px; /* Sesuaikan ukuran gambar */
        margin-bottom: 20px; /* Memberi jarak antara gambar dan "Vitgital" */
    }

    .signature-section p {
        margin-top: 0;
        font-weight: bold;
    }

    /* Tambahan gaya untuk mode cetak */
    @media print {
        body {
            -webkit-print-color-adjust: exact; /* Memastikan warna tercetak sesuai */
        }
        
        .feedback-section {
            margin-left: -10px;
        }
        .signature-section {
            margin-right: -40px;
        }
    }
</style>
