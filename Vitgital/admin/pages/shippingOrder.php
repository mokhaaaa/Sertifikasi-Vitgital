<?php
session_start();
include('../../koneksi.php'); // Pastikan path sudah benar

// Periksa apakah idTransaksi dan username ada dalam URL
if (isset($_GET['idTransaksi']) && isset($_GET['username'])) {
    $idTransaksi = $_GET['idTransaksi'];
    $username = $_GET['username'];

    // Ambil detail transaksi
    $queryTransaksi = "SELECT * FROM transaksi JOIN customer ON transaksi.username = customer.username WHERE transaksi.idTransaksi = ? AND transaksi.username = ?";
    $stmt = $connect->prepare($queryTransaksi);
    $stmt->bind_param('ss', $idTransaksi, $username);
    $stmt->execute();
    $detailTransaksi = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    // Ambil data keranjang user
    $queryKeranjang = "SELECT * FROM keranjang JOIN produk ON keranjang.idProduk = produk.idProduk WHERE keranjang.username = ? AND keranjang.idTransaksi = ?";
    $stmt = $connect->prepare($queryKeranjang);
    $stmt->bind_param('ss', $username, $idTransaksi);
    $stmt->execute();
    $keranjangUser = $stmt->get_result();
    $stmt->close();

    $tanggalTransaksi = strtotime($detailTransaksi["tanggalTransaksi"]);
    $tanggalFormatted = date("j F Y", $tanggalTransaksi);
} else {
    echo "<script>alert('Transaksi atau pengguna tidak ditemukan!');</script>";
    echo "<script>window.location.href='managetransaksi.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pengiriman</title>
    <link href="../assets/libs/chartist/dist/chartist.min.css" rel="stylesheet">
    <link href="../assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet">
    <link href="../dist/css/style.min.css" rel="stylesheet">
    <style>
        @media print {
            #printButton, #toggleFeedback, .topbar, .left-sidebar {
                display: none !important;
            }
            .feedback-section, .signature-section {
                display: block;
                margin-top: 20px;
            }
            main.container {
                width: 100%;
                margin: 0 auto;
            }
        }

        /* Styling untuk font */
        body, h4, p, ul, li, table {
            color: black;
            font-size: 18px; /* Ukuran font diperbesar */
        }

        .text-danger {
            font-size: 24px; /* Ukuran khusus untuk "Vitgital" */
        }

        /* Styling untuk tabel */
        table {
            background-color: white;
            color: black;
        }

        th, td {
            border: 1px solid black !important;
        }

        th {
            background-color: #f2f2f2;
        }

        /* Styling untuk logo, toko, dan laporan */
        .header-section {
            text-align: center;
            margin-bottom: 30px;
        }

        .header-section img {
            width: 60px;
            margin-bottom: 10px;
        }
        .signature-section {
    display: flex;
    flex-direction: column; /* Mengatur elemen secara vertikal */
    align-items: center; /* Mengatur elemen di tengah-tengah secara horizontal */
    margin-left: 20px  /* Mengatur agar seluruh div berada di sebelah kanan */
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

    </style>
</head>

<body>

<div id="main-wrapper" data-theme="light" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed" data-boxed-layout="full">
        <!-- Topbar -->
        <header class="topbar" data-navbarbg="skin6">
            <?php include 'includes/navigation.php'; ?>
        </header>
        
        <!-- Sidebar -->
        <aside class="left-sidebar" data-sidebarbg="skin6">
            <div class="scroll-sidebar" data-sidebarbg="skin6">
                <?php include 'includes/sidebar.php'; ?>
            </div>
        </aside>
</div>

<main class="container mt-4">
    <!-- Header Section untuk Logo, Nama Toko, dan Laporan Belanja -->
    <div class="header-section">
        <img src="../img/logo.png" alt="Logo">
        <h2><strong class="text" style="color: #2596be; font-size: 36px;">Vitgital</strong></h2>
        <h4 class="mb-4">Laporan Belanja <?= $detailTransaksi["username"]; ?></h4>
    </div>

    <section class="section mt-3">
        <div class="row">
            <!-- Detail Transaksi -->
            <div class="col-12">
                <ul class="list-group">
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

            <!-- Produk dalam Transaksi -->
            <div class="col-12 mt-4">
                <h4 class="fw-bold">Produk dalam Transaksi</h4>
                <table class="table table-bordered mt-3">
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">ID Produk</th>
                            <th scope="col">Nama Produk</th>
                            <th scope="col">Jumlah</th>
                            <th scope="col">Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $cnt = 1; ?>
                        <?php while ($keranjang = $keranjangUser->fetch_assoc()) : ?>
                            <tr>
                                <td><?= $cnt; ?></td>
                                <td><?= $keranjang["idProduk"]; ?></td>
                                <td><?= $keranjang["namaProduk"]; ?></td>
                                <td><?= $keranjang["jumlah"]; ?></td>
                                <td>Rp<?= number_format($keranjang["harga"], 0, ',', '.'); ?></td>
                            </tr>
                            <?php $cnt++; ?>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <div class="fw-bold">Total Harga: Rp<?= number_format($detailTransaksi["totalHarga"], 0, ',', '.'); ?></div>
            </div>

            <!-- Feedback dan TTD Pemilik -->
            <div class="col-12 d-flex justify-content-between mt-5">
                <!-- Feedback Section -->
                <div class="feedback-section">
                    <div class="fw-bold">Feedback:</div>
                    <p><?= $detailTransaksi["feedBack"] ? $detailTransaksi["feedBack"] : "Belum ada feedback."; ?></p>
                </div>
                
                <!-- TTD dan Pemilik -->
                <div class="signature-section">
                    <h5>Pemilik Toko</h5>
                    <img src="../../img/ttd.png" alt="Tanda Tangan" >
                    <p class="fw-bold">Vitgital</p>
                </div>
            </div>

            <!-- Tombol Cetak dan Lihat Feedback -->
            <div class="col-12 text-center mt-3">
                <button id="printButton" class="btn btn-secondary mx-2">Cetak</button>
                <button id="toggleFeedback" class="btn btn-warning mx-2">Lihat Feedback</button>
            </div>
        </div>
    </section>
</main>

<script>
    // Fungsi cetak halaman
    document.getElementById("printButton").addEventListener("click", function() {
        document.getElementById("printButton").style.display = "none";
        document.getElementById("toggleFeedback").style.display = "none";
        window.print();
    });

    window.onafterprint = function() {
        document.getElementById("printButton").style.display = "inline-block";
        document.getElementById("toggleFeedback").style.display = "inline-block";
    };

    // Tampilkan atau sembunyikan feedback
    document.getElementById("toggleFeedback").addEventListener("click", function() {
        var feedbackSection = document.querySelector('.feedback-section');
        feedbackSection.style.display = feedbackSection.style.display === "none" ? "block" : "none";
    });
</script>

</body>
</html>
