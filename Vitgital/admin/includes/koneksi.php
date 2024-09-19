<?php
// koneksi.php
$servername = "localhost:3307"; // atau "127.0.0.1"
$username = "root"; // Ganti dengan username Anda
$password = ""; // Ganti dengan password Anda
$dbname = "vitgital"; // Ganti dengan nama database Anda

// Membuat koneksi
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
