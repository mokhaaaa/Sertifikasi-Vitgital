<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Homepage'; ?></title>
    <!-- Link ke file CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
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
            <li><a href="index.php">Produk</a></li>
            <li><a href="custlogin.php">Transaksi</a></li>
            <li><a href="guestbook.php">Kontak</a></li>
        </ul>
    </nav>

    <div class="header-actions d-flex align-items-center">
        <a href="registrasi.php">Daftar</a>
        <a href="custlogin.php">Log In</a>
        <a href="custlogin.php"><img src="img/keranjang.png" alt="Cart" class="cart-icon" /></a>
    </div>
</header>


<!-- Main content -->
<main style="padding-top: 80px;"> <!-- Tambahkan padding-top untuk menghindari konten tertutup header -->
    <!-- Konten halaman Anda di sini -->
</main>

<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>
</html>
