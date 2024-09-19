<?php

include('../../koneksi.php'); // Make sure this path is correct

// Pengecekan koneksi database
if ($connect->connect_error) {
    die("Koneksi database gagal: " . $connect->connect_error);
}
?>

<nav class="navbar top-navbar navbar-expand-md">
    <div class="navbar-header" data-logobg="skin6">
        <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"><i
                class="ti-menu ti-close"></i></a>
                <div class="header-logo d-flex align-items-center">
                <div class="header-logo d-flex align-items-center">
            <img src="../img/logo.png" alt="Logo" class="main-logo" style="height: 70px;" />
            <span class="brand-name" style="font-size: 36px; font-weight: bold; color: #049ecf; margin-left: 15px;">VITGITAL</span>
        </div>
        <a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)"
            data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <i class="ti-more"></i>
        </a>
    </div>
        <a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)"
            data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><i
                class="ti-more"></i></a>
    </div>
    <div class="navbar-collapse collapse" id="navbarSupportedContent">
        <ul class="navbar-nav float-left mr-auto ml-3 pl-1">
        </ul>
        <ul class="navbar-nav float-right">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="javascript:void(0)" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <img src="../assets/images/users/admin-icn.png" alt="user" class="rounded-circle" width="35">
                    <?php
                    if (isset($_SESSION['username'])) {
                        $username = $_SESSION['username'];

                        // Persiapkan pernyataan SQL untuk memperoleh data mitra berdasarkan username
                        $stmt = $connect->prepare("SELECT * FROM admin WHERE username = ?");
                        $stmt->bind_param('s', $username);
                        $stmt->execute();

                        // Dapatkan hasil dari pernyataan SQL
                        $res = $stmt->get_result();

                        // Pengecekan apakah sesi valid
                        if ($res->num_rows > 0) {
                            // Tampilkan hasil
                            $row = $res->fetch_object();
                            echo '<span class="ml-2 d-none d-lg-inline-block"><span>Welcome,</span> <span class="text-dark">' . htmlspecialchars($row->username) . '</span></span>';
                        } else {
                            echo 'Data mitra tidak ditemukan.';
                        }

                        // Tutup pernyataan
                        $stmt->close();
                    } else {
                        echo 'Sesi tidak diatur.';
                    }
                    ?>
                    <i data-feather="chevron-down" class="svg-icon"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right user-dd animated flipInY">              
                    <a class="dropdown-item" href="../../logout.php"><i data-feather="power" class="svg-icon mr-2 ml-1"></i> Sign Out</a>
                </div>
            </li>
        </ul>
    </div>
</nav>
