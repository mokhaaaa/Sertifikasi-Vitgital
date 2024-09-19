<?php
$title = 'Profil Saya';
require '../koneksi.php'; // Koneksi ke database
require 'includes/header.php'; // Header untuk customer

// Pastikan pengguna sudah login
if (!isset($_SESSION["username"])) {
    header("Location: ../custlogin.php");
    exit;
}

$username = $_SESSION["username"];
$customer = query("SELECT * FROM customer WHERE username = '$username'")[0];

// Format tanggal lahir
$tanggalLahir = strtotime($customer["dob"]);
$tanggalFormatted = date("j F Y", $tanggalLahir);

?>

<style>
    .profile-container {
        margin-top: 50px;
    }
    .profile-header {
        font-size: 32px;
        color: #2596be;
        font-weight: bold;
        text-align: center;
        margin-bottom: 40px;
    }
    .profile-info {
        margin-bottom: 20px;
    }
    .profile-label {
        color: #2596be;
        font-weight: bold;
    }
    .profile-card {
        padding: 40px;
        background-color: #ffffff;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }
    .profile-card:hover {
        transform: scale(1.03);
    }
    .profile-value {
        font-weight: normal;
        color: #343a40;
    }
    .profile-section {
        margin-top: 60px;
    }
</style>

<main id="main" class="main">
    <div class="container profile-container">
        <!-- Title -->
        <div class="profile-header" style="padding-top: 100px;">
            PROFIL SAYA
        </div>

        <!-- Card Section -->
        <section class="profile-section">
            <div class="card profile-card shadow-lg rounded">
                <div class="card-body">
                    <div class="tab-pane fade show active profile-overview" id="profile-overview">

                        <!-- Detail Akun -->
                        <div class="row profile-info">
                            <div class="col-lg-3 col-md-4 profile-label">Username</div>
                            <div class="col-lg-9 col-md-8 profile-value">: <?= htmlspecialchars($customer["username"]); ?></div>
                        </div>

                        <div class="row profile-info">
                            <div class="col-lg-3 col-md-4 profile-label">Nama Lengkap</div>
                            <div class="col-lg-9 col-md-8 profile-value">: <?= htmlspecialchars($customer["namaLengkap"]); ?></div>
                        </div>

                        <div class="row profile-info">
                            <div class="col-lg-3 col-md-4 profile-label">Email</div>
                            <div class="col-lg-9 col-md-8 profile-value">: <?= htmlspecialchars($customer["email"]); ?></div>
                        </div>

                        <div class="row profile-info">
                            <div class="col-lg-3 col-md-4 profile-label">Tanggal Lahir</div>
                            <div class="col-lg-9 col-md-8 profile-value">: <?= htmlspecialchars($tanggalFormatted); ?></div>
                        </div>

                        <div class="row profile-info">
                            <div class="col-lg-3 col-md-4 profile-label">Gender</div>
                            <div class="col-lg-9 col-md-8 profile-value">: <?= htmlspecialchars($customer["gender"]); ?></div>
                        </div>

                        <div class="row profile-info">
                            <div class="col-lg-3 col-md-4 profile-label">Alamat</div>
                            <div class="col-lg-9 col-md-8 profile-value">: <?= htmlspecialchars($customer["alamat"]); ?></div>
                        </div>

                        <div class="row profile-info">
                            <div class="col-lg-3 col-md-4 profile-label">Kota</div>
                            <div class="col-lg-9 col-md-8 profile-value">: <?= htmlspecialchars($customer["kota"]); ?></div>
                        </div>

                        <div class="row profile-info">
                            <div class="col-lg-3 col-md-4 profile-label">Contact</div>
                            <div class="col-lg-9 col-md-8 profile-value">: <?= htmlspecialchars($customer["contact"]); ?></div>
                        </div>

                        <div class="row profile-info">
                            <div class="col-lg-3 col-md-4 profile-label">Paypal ID</div>
                            <div class="col-lg-9 col-md-8 profile-value">: <?= htmlspecialchars($customer["paypalID"]); ?></div>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </div>
</main>
