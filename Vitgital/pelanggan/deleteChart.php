<?php
session_start();
require '../koneksi.php'; // Koneksi ke database

// Pastikan pengguna sudah login
if (!isset($_SESSION["username"])) {
    header("Location: ../custlogin.php");
    exit;
}

$username = $_SESSION["username"];

// Mendapatkan semua produk dari keranjang pengguna yang belum dibayar
$allKeranjang = query("SELECT * FROM keranjang WHERE username = '$username' AND status = 'Belum Dibayar'");

if (!empty($allKeranjang)) {
    // Kembalikan stok produk
    foreach ($allKeranjang as $keranjang) {
        $idProduk = $keranjang["idProduk"];
        $jumlah = $keranjang["jumlah"];
        mysqli_query($connect, "UPDATE produk SET stokProduk = stokProduk + '$jumlah' WHERE idProduk = '$idProduk'");
    }

    // Hapus semua produk di keranjang yang statusnya 'Belum Dibayar'
    $query = "DELETE FROM keranjang WHERE username = '$username' AND status = 'Belum Dibayar'";
    $result = mysqli_query($connect, $query);

    // Cek apakah penghapusan berhasil
    if ($result) {
        echo "
            <script>
                alert('Keranjang berhasil dihapus!');
                document.location.href = 'cartCustomer.php'; // Kembali ke halaman keranjang
            </script>
        ";
    } else {
        echo "
            <script>
                alert('Gagal menghapus keranjang!');
                document.location.href = 'cartCustomer.php'; // Kembali ke halaman keranjang jika gagal
            </script>
        ";
    }
} else {
    echo "
        <script>
            alert('Keranjang kosong, tidak ada yang dihapus!');
            document.location.href = 'cartCustomer.php'; // Jika keranjang kosong
        </script>
    ";
}
?>
