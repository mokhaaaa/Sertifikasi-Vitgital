<?php
session_start();
include('../../koneksi.php'); // Koneksi ke database

// Tambah Kategori
if (isset($_POST['tambahKategori'])) {
    $namaKategori = mysqli_real_escape_string($connect, $_POST['namaKategori']);
    
    // Cek apakah kategori sudah ada di tabel 'kategori'
    $cekKategori = "SELECT * FROM kategori WHERE namaKategori = '$namaKategori'";
    $result = mysqli_query($connect, $cekKategori);

    if (mysqli_num_rows($result) == 0) {
        $query = "INSERT INTO kategori (namaKategori) VALUES ('$namaKategori')";
        if (mysqli_query($connect, $query)) {
            echo "<script>alert('Kategori berhasil ditambahkan!');</script>";
        } else {
            echo "<script>alert('Gagal menambahkan kategori!');</script>";
        }
    } else {
        echo "<script>alert('Kategori sudah ada!');</script>";
    }
}

// Hapus Kategori
if (isset($_GET['del'])) {
    $idKategori = $_GET['del'];
    $query = "DELETE FROM kategori WHERE idKategori = ?";
    $stmt = $connect->prepare($query);
    $stmt->bind_param('i', $idKategori);
    if ($stmt->execute()) {
        echo "<script>alert('Kategori berhasil dihapus!');</script>";
    } else {
        echo "<script>alert('Gagal menghapus kategori!');</script>";
    }
    $stmt->close();
}

// Mendapatkan semua kategori dari tabel produk dan tabel kategori
$queryKategoriProduk = "SELECT DISTINCT kategoriProduk AS namaKategori FROM produk";
$resultKategoriProduk = mysqli_query($connect, $queryKategoriProduk);

$queryKategori = "SELECT * FROM kategori";
$resultKategori = mysqli_query($connect, $queryKategori);

// Gabungkan hasil kategori dari produk dan kategori
$allKategori = [];
while ($row = mysqli_fetch_assoc($resultKategoriProduk)) {
    $allKategori[] = $row['namaKategori'];
}
while ($row = mysqli_fetch_assoc($resultKategori)) {
    if (!in_array($row['namaKategori'], $allKategori)) {
        $allKategori[] = $row['namaKategori'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Kategori</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../../img/logo.png">
    <link href="../assets/libs/chartist/dist/chartist.min.css" rel="stylesheet">
    <link href="../assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet">
    <link href="../dist/css/style.min.css" rel="stylesheet">
    <link href="../assets/libs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Wrapper untuk navigasi dan sidebar -->
    <div id="main-wrapper" data-theme="light" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed" data-boxed-layout="full">
        
        <!-- Top Navbar -->
        <header class="topbar" data-navbarbg="skin6">
            <?php include 'includes/navigation.php'; ?>
        </header>
        
        <!-- Sidebar -->
        <aside class="left-sidebar" data-sidebarbg="skin6">
            <div class="scroll-sidebar">
                <?php include 'includes/sidebar.php'; ?>
            </div>
        </aside>

        <!-- Page Content -->
        <div class="page-wrapper">
            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col-7 align-self-center">
                        <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Manage Kategori</h4>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="container-fluid">
                <!-- Tambah Kategori Form -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-subtitle">Tambah kategori baru.</h6>
                                <form action="manageKategori.php" method="post" class="mb-4">
                                    <div class="input-group">
                                        <input type="text" name="namaKategori" class="form-control" placeholder="Masukkan nama kategori" required>
                                        <button type="submit" name="tambahKategori" class="btn btn-success">Tambah Kategori</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tampilkan Kategori dalam Bentuk Card -->
                <div class="row">
                    <?php foreach ($allKategori as $kategori): ?>
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <h5 class="card-title"><?= $kategori; ?></h5>
                                <!-- Tombol Hapus hanya muncul jika kategori berasal dari tabel kategori -->
                                <?php 
                                $queryCekKategori = "SELECT idKategori FROM kategori WHERE namaKategori = '$kategori'";
                                $cekResult = mysqli_query($connect, $queryCekKategori);
                                if (mysqli_num_rows($cekResult) > 0): 
                                    $row = mysqli_fetch_assoc($cekResult);
                                ?>
                                <!--
<a href="manageKategori.php?del= <//?= $row['idKategori']; ?> " class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">Hapus</a>
-->

                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>
    </div>

    <!-- JS Scripts -->
    <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../dist/js/app-style-switcher.js"></script>
    <script src="../dist/js/feather.min.js"></script>
    <script src="../assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
    <script src="../dist/js/sidebarmenu.js"></script>
    <script src="../dist/js/custom.min.js"></script>
</body>
</html>
