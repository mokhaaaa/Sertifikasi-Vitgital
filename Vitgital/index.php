<?php
$title = 'Homepage';
require 'koneksi.php';
require 'header.php';

// Mengambil semua kategori produk dari database
$categories = query("SELECT DISTINCT kategoriProduk FROM produk");
?>

<main>
    
    <link href="assets/css/style.css" rel="stylesheet">
    <div class="container-fluid p-0">
        <!-- Jumbotron Full Width -->
        
        <div class="jumbotron jumbotron-fluid text-white" style="background-image: url('img/tema.png'); background-size: cover; background-position: center; height: 100vh; display: flex;">
            <div class="container text-center">
                <h3 class="display-4" style="padding-top: 580px; padding-left:30px">Connecting You to <br> Better Health</h3>
            </div>
        </div>

        <!-- CTA dipindahkan tepat di bawah jumbotron -->
        <section id="cta" class="cta">
            <div class="container" data-aos="zoom-in">
                <div class="container text-center" data-aos="zoom-in">
                    <h3>Anda Tertarik Menjadi Bagian dari Vitgital?</h3>
                    <p>Bergabunglah dengan kami dan optimalkan pengalaman kesehatan digital Anda. Daftar sekarang untuk menjadi mitra Vitgital!</p>
                    <a class="cta-btn btn btn-primary" href="custlogin.php">Daftar Sekarang</a>
                </div>
            </div>
        </section>

        <!-- Teks "Produk & Kategori" di bawah CTA -->
        <div class="container text-center my-5">
            <h1 class="produk-kategori-title">Produk & Kategori</h1>
        </div>

        <div class="container-fluid mt-3">
            <div class="row">
                <!-- Menampilkan kategori produk sebagai card -->
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-header text-white text-center" style="background-color: #2596be;">
                            <h5>Kategori Produk</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item kategori-item" data-category="">Semua Kategori</li>
                                <?php foreach ($categories as $category): ?>
                                    <li class="list-group-item kategori-item" data-category="<?= $category['kategoriProduk']; ?>">
                                        <?= $category['kategoriProduk']; ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Menampilkan produk -->
                <div class="col-md-9">
                    <div class="row" id="product-list">
                        <?php $products = query("SELECT * FROM produk"); ?>
                        <?php foreach ($products as $product): ?>
                            <div class="col-md-4 mb-4">
                                <div class="card h-100 shadow-sm">
                                    <img src="img/<?= $product['gambarProduk']; ?>" class="card-img-top" alt="<?= $product['namaProduk']; ?>" style="height: 200px; object-fit: cover;">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= $product['namaProduk']; ?></h5>
                                        <p class="card-text">Rp<?= number_format($product['hargaProduk'], 0, ',', '.'); ?></p>
                                        <div class="d-flex justify-content-between">
                                            <a href="detailProduk.php?id=<?= $product['idProduk']; ?>" class="btn btn-primary" style="width: 48%;">Detail</a>
                                            <a href="custlogin.php" class="btn btn-success" style="width: 48%;">Beli</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Tambahkan script jQuery dan AJAX -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Klik kategori untuk memuat produk menggunakan AJAX
        $('.kategori-item').on('click', function() {
            var category = $(this).data('category');

            // AJAX untuk mengambil produk berdasarkan kategori
            $.ajax({
                url: 'muatProduk.php',
                type: 'GET',
                data: { category: category },
                success: function(data) {
                    $('#product-list').html(data);
                }
            });
        });
    });
</script>

</body>
</html>
