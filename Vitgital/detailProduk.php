<?php
$title = 'Detail Produk';
require 'koneksi.php';
require 'header.php';

// Ambil ID produk dari URL
$id = $_GET['id'];

// Ambil detail produk dari database berdasarkan ID
$produk = query("SELECT * FROM produk WHERE idProduk = '$id'")[0];
?>

<main class="container mt-5">
    <div class="text-center">
        <h1>Detail Produk: <?= $produk["namaProduk"]; ?></h1>
    </div>

    <section class="section">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <img src="img/<?= $produk["gambarProduk"]; ?>" class="card-img-top" alt="<?= $produk["namaProduk"]; ?>" style="height: 400px; object-fit: cover;">

                    <div class="card-body">
                        <!-- Informasi produk -->
                        <div class="mb-3">
                            <label for="namaProduk" class="form-label"><strong>Nama Produk:</strong></label>
                            <p class="form-control" readonly><?= $produk["namaProduk"]; ?></p>
                        </div>

                        <div class="mb-3">
                            <label for="kategoriProduk" class="form-label"><strong>Kategori Produk:</strong></label>
                            <p class="form-control" readonly><?= $produk["kategoriProduk"]; ?></p>
                        </div>

                        <div class="mb-3">
                            <label for="hargaProduk" class="form-label"><strong>Harga Produk:</strong></label>
                            <p class="form-control" readonly>Rp<?= number_format($produk["hargaProduk"], 0, ',', '.'); ?></p>
                        </div>

                        <div class="mb-3">
                            <label for="stokProduk" class="form-label"><strong>Stok Produk:</strong></label>
                            <p class="form-control" readonly><?= $produk["stokProduk"]; ?></p>
                        </div>

                        <!-- Tombol Kembali dan Beli -->
                        <div class="d-flex justify-content-center mt-4">
                            <a href="index.php" class="btn btn-warning" style="width: 48%; background-color: #2596be; color: white;">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

</body>
</html>
