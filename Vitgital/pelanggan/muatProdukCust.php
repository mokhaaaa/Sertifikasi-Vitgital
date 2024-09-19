<?php
require '../koneksi.php';

// Ambil kategori dari parameter GET
$category = isset($_GET['category']) ? $_GET['category'] : '';

// Jika tidak ada kategori yang dipilih, tampilkan semua produk
if ($category == '') {
    $products = query("SELECT * FROM produk");
} else {
    $products = query("SELECT * FROM produk WHERE kategoriProduk = '$category'");
}

// Tampilkan produk
foreach ($products as $product): ?>
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm">
            <img src="../img/<?= $product['gambarProduk']; ?>" class="card-img-top" alt="<?= $product['namaProduk']; ?>" style="height: 200px; object-fit: cover;">
            <div class="card-body">
                <h5 class="card-title"><?= $product['namaProduk']; ?></h5>
                <p class="card-text">Rp<?= number_format($product['hargaProduk'], 0, ',', '.'); ?></p>
                <div class="d-flex justify-content-between">
                    <a href="detailProdukCust.php?id=<?= $product['idProduk']; ?>" class="btn btn-primary" style="width: 48%;">Detail</a>
                    <a href="" class="btn btn-success" style="width: 48%;">Beli</a>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>
