<?php
// Aktifkan output buffering untuk menghindari masalah output sebelum header
ob_start();

// Cek apakah session sudah dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../koneksi.php'; // Pastikan koneksi ke database di-include

// Cek apakah pengguna sudah login
if (isset($_SESSION["username"])) {
    $userLogin = $_SESSION["username"];
    // Ambil username dari session
} else {
    // Jika tidak ada session login, arahkan ke halaman login
    header("Location: ../custlogin.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Homepage'; ?></title>
    <!-- Link ke file CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .user-icon {
            margin-right: 10px; /* Memberi jarak antara user icon dan teks */
        }
        .welcome-text {
            padding-left: 10px; /* Memberi jarak antara icon dan tulisan Welcome */
        }
    </style>
</head>
<body>

<!-- Header -->
<header class="header">
    <div class="header-logo d-flex align-items-center">
        <img src="img/logo.png" alt="Logo" class="main-logo" />
        <span class="brand-name">VITGITAL</span>
    </div>

    <nav class="header-nav">
        <ul class="d-flex">
            <li><a href="homepageCustomer.php">Produk</a></li>
            <li><a href="transaksiCustomer.php">Transaksi</a></li>
            <li><a href="guestbookCustomer.php">Kontak</a></li>
        </ul>
    </nav>

    <div class="header-actions d-flex align-items-center">
        <!-- Icon Cart -->
        <a href="cartCustomer.php"><img src="../img/keranjang.png" alt="Cart" class="cart-icon" /></a>

        <!-- Icon User dan Nama Pengguna dengan Dropdown -->
        <div class="dropdown user-info d-flex align-items-center">
            <a href="#" class="d-flex align-items-center" id="userDropdown" data-toggle="dropdown">
                <!-- User Icon dengan jarak -->
                <img src="../img/user.png" alt="User" class="cart-icon user-icon" />
                <span class="welcome-text">Welcome, <?= htmlspecialchars($userLogin); ?></span> <!-- Ganti jadi username -->
            </a>

            <!-- Dropdown Menu untuk Profil Saya dan Sign Out -->
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="akun.php">Profil Saya</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="../logout.php">Sign Out</a>
            </div>
        </div>
    </div>
</header>


<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>
</html>
