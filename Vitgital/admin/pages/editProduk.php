<?php
session_start();
include('../../koneksi.php'); // Pastikan path ini benar

// Fetch categories from the database
$queryKategori = "SELECT DISTINCT kategoriProduk FROM produk";
$resultKategori = $connect->query($queryKategori);

// Handle form submission for editing the product
if (isset($_POST['submit'])) {
    $idProduk = $_GET['id'];
    $namaProduk = $_POST['namaProduk'];
    $kategoriProduk = $_POST['kategoriProduk'];
    $hargaProduk = $_POST['hargaProduk'];
    $stokProduk = $_POST['stokProduk'];

    // Handle image upload if a new image is provided
    $gambarProduk = $_FILES['gambarProduk']['name'];
    $gambarProdukTmp = $_FILES['gambarProduk']['tmp_name'];
    $uploadDir = '../../img/'; // Path untuk upload gambar
    $uploadFile = $uploadDir . basename($gambarProduk);

    // Cek apakah ada gambar baru yang diupload
    if ($gambarProduk) {
        move_uploaded_file($gambarProdukTmp, $uploadFile);
        $query = "UPDATE produk SET namaProduk=?, kategoriProduk=?, hargaProduk=?, stokProduk=?, gambarProduk=? WHERE idProduk=?";
        $stmt = $connect->prepare($query);
        $stmt->bind_param('ssdisi', $namaProduk, $kategoriProduk, $hargaProduk, $stokProduk, $gambarProduk, $idProduk);
    } else {
        $query = "UPDATE produk SET namaProduk=?, kategoriProduk=?, hargaProduk=?, stokProduk=? WHERE idProduk=?";
        $stmt = $connect->prepare($query);
        $stmt->bind_param('ssdis', $namaProduk, $kategoriProduk, $hargaProduk, $stokProduk, $idProduk);
    }
    
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Produk berhasil diupdate!');</script>";
    echo "<script>window.location.href='manageProduk.php';</script>";
}

// Get product details for editing
$id = $_GET['id'];
$query = "SELECT * FROM produk WHERE idProduk=?";
$stmt = $connect->prepare($query);
$stmt->bind_param('s', $id);
$stmt->execute();
$result = $stmt->get_result();
$produk = $result->fetch_assoc();
$stmt->close();

?>

<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" sizes="16x16" href="../../img/logo.png">
    <title>Edit Produk</title>
    <link href="../assets/extra-libs/c3/c3.min.css" rel="stylesheet">
    <link href="../assets/libs/chartist/dist/chartist.min.css" rel="stylesheet">
    <link href="../dist/css/style.min.css" rel="stylesheet">
</head>

<body>
    <div id="main-wrapper" data-theme="light" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed" data-boxed-layout="full">
        <header class="topbar" data-navbarbg="skin6">
            <?php include 'includes/navigation.php'; ?>
        </header>
        <aside class="left-sidebar" data-sidebarbg="skin6">
            <div class="scroll-sidebar" data-sidebarbg="skin6">
                <?php include 'includes/sidebar.php'; ?>
            </div>
        </aside>

        <div class="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Edit Produk</h5>

                                <!-- Form to edit a product -->
                                <form method="POST" enctype="multipart/form-data">
                                    <div class="row">
                                        <!-- Nama Produk -->
                                        <div class="col-sm-12 col-md-6 col-lg-4">
                                            <div class="form-group">
                                                <label for="namaProduk">Nama Produk</label>
                                                <input type="text" name="namaProduk" id="namaProduk" value="<?= $produk['namaProduk']; ?>" class="form-control" required>
                                            </div>
                                        </div>

                                        <!-- Kategori Produk -->
                                        <div class="col-sm-12 col-md-6 col-lg-4">
    <div class="form-group">
        <label for="kategoriProduk">Kategori Produk</label>
        <!-- Menggunakan input dengan datalist -->
        <input list="kategoriProdukList" name="kategoriProduk" id="kategoriProduk" class="form-control" placeholder="-- Pilih atau isi Kategori --" value="<?= $produk['kategoriProduk']; ?>" required>
        <datalist id="kategoriProdukList">
            <?php while ($rowKategori = $resultKategori->fetch_assoc()) : ?>
                <option value="<?= $rowKategori['kategoriProduk']; ?>"></option>
            <?php endwhile; ?>
        </datalist>
    </div>
</div>


                                        <!-- Harga Produk -->
                                        <div class="col-sm-12 col-md-6 col-lg-4">
                                            <div class="form-group">
                                                <label for="hargaProduk">Harga Produk</label>
                                                <input type="number" name="hargaProduk" id="hargaProduk" value="<?= $produk['hargaProduk']; ?>" class="form-control" required>
                                            </div>
                                        </div>

                                        <!-- Stok Produk -->
                                        <div class="col-sm-12 col-md-6 col-lg-4">
                                            <div class="form-group">
                                                <label for="stokProduk">Stok Produk</label>
                                                <input type="number" name="stokProduk" id="stokProduk" value="<?= $produk['stokProduk']; ?>" class="form-control" required>
                                            </div>
                                        </div>

                                        <!-- Gambar Produk -->
                                        <div class="col-sm-12 col-md-6 col-lg-4">
                                        <div class="form-group">
    <!-- Label untuk Gambar Produk -->
    <label for="gambarProduk">Gambar Produk</label>

    <!-- Tampilkan gambar saat ini jika ada, langsung di bawah label tanpa jarak -->
    <?php if (!empty($produk['gambarProduk'])) : ?>
        <img src="../../img/<?= $produk['gambarProduk']; ?>" alt="<?= $produk['namaProduk']; ?>" style="width: 150px; display: block; margin-top: -10  px;">
    <?php endif; ?>

    <!-- Input untuk memilih gambar -->
    <input type="file" name="gambarProduk" id="gambarProduk" class="form-control" style="margin-top: 10px;">
</div>

</div>

                                    </div>

                                    <!-- Submit and Reset buttons -->
                                    <div class="text-center">
                                        <button type="submit" name="submit" class="btn btn-success">Update</button>
                                        <button type="reset" class="btn btn-dark">Reset</button>
                                    </div>
                                </form>
                                <!-- End Form -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>

    <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="../assets/libs/popper.js/dist/umd/popper.min.js"></script>
    <script src="../assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="../dist/js/app-style-switcher.js"></script>
    <script src="../dist/js/feather.min.js"></script>
    <script src="../assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
    <script src="../dist/js/sidebarmenu.js"></script>
    <script src="../dist/js/custom.min.js"></script>
</body>

</html>
