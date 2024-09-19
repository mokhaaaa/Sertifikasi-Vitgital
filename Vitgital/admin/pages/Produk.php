<?php
session_start();
include('../../koneksi.php'); // Koneksi ke database

// Fetch categories from the database (tabel produk dan kategori)
$queryKategoriProduk = "SELECT DISTINCT kategoriProduk FROM produk";
$queryKategori = "SELECT DISTINCT namaKategori FROM kategori";
$resultKategoriProduk = $connect->query($queryKategoriProduk);
$resultKategori = $connect->query($queryKategori);

// Gabungkan kategori dari tabel produk dan kategori ke dalam satu array untuk ditampilkan di datalist
$kategoriList = [];
while ($row = $resultKategoriProduk->fetch_assoc()) {
    $kategoriList[] = $row['kategoriProduk'];
}
while ($row = $resultKategori->fetch_assoc()) {
    if (!in_array($row['namaKategori'], $kategoriList)) {
        $kategoriList[] = $row['namaKategori'];
    }
}

// Function to generate new product ID
function generateNewProductId($connect) {
    $queryLastId = "SELECT idProduk FROM produk ORDER BY idProduk DESC LIMIT 1";
    $resultLastId = $connect->query($queryLastId);
    $prefix = "PRO2024";
    $newIdNumber = "01";

    if ($resultLastId->num_rows > 0) {
        $rowLastId = $resultLastId->fetch_assoc();
        $lastId = $rowLastId['idProduk'];
        $lastIdNumber = (int)substr($lastId, 7);
        $newIdNumber = str_pad($lastIdNumber + 1, 2, '0', STR_PAD_LEFT);
    }
    return $prefix . $newIdNumber;
}

// Handle form submission for adding a new product
if (isset($_POST['submit'])) {
    $namaProduk = $_POST['namaProduk'];
    $kategoriProduk = $_POST['kategoriProduk'];
    $hargaProduk = $_POST['hargaProduk'];
    $stokProduk = $_POST['stokProduk'];

    // Handle image upload
    $gambarProduk = $_FILES['gambarProduk']['name'];
    $gambarProdukTmp = $_FILES['gambarProduk']['tmp_name'];
    $uploadDir = '../../img/';
    $uploadFile = $uploadDir . basename($gambarProduk);

    // Generate new product ID
    $newIdProduk = generateNewProductId($connect);

    // Check if the category is new and insert it into the 'kategori' table
    $queryCheckKategori = "SELECT * FROM kategori WHERE namaKategori = ?";
    $stmtCheckKategori = $connect->prepare($queryCheckKategori);
    $stmtCheckKategori->bind_param('s', $kategoriProduk);
    $stmtCheckKategori->execute();
    $resultCheckKategori = $stmtCheckKategori->get_result();

    if ($resultCheckKategori->num_rows == 0) {
        // If category does not exist, insert it into 'kategori' table
        $queryInsertKategori = "INSERT INTO kategori (namaKategori) VALUES (?)";
        $stmtInsertKategori = $connect->prepare($queryInsertKategori);
        $stmtInsertKategori->bind_param('s', $kategoriProduk);
        $stmtInsertKategori->execute();
        $stmtInsertKategori->close();
    }

    // Move the uploaded image to the destination folder
    if (move_uploaded_file($gambarProdukTmp, $uploadFile)) {
        // Insert product details into the database
        $query = "INSERT INTO produk (idProduk, namaProduk, kategoriProduk, hargaProduk, stokProduk, gambarProduk) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $connect->prepare($query);
        $stmt->bind_param('sssdis', $newIdProduk, $namaProduk, $kategoriProduk, $hargaProduk, $stokProduk, $gambarProduk);
        $stmt->execute();
        $stmt->close();

        echo "<script>alert('Produk berhasil ditambahkan!');</script>";
        echo "<script>window.location.href='manageProduk.php';</script>";
    } else {
        echo "<script>alert('Gagal mengupload gambar.');</script>";
    }
}
?>

<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" sizes="16x16" href="../../img/logo.png">
    <title>Tambah Produk</title>
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
                                <h5 class="card-title">Tambah Produk</h5>

                                <!-- Form to add a new product -->
                                <form method="POST" enctype="multipart/form-data">
                                    <div class="row">
                                        <!-- Nama Produk -->
                                        <div class="col-sm-12 col-md-6 col-lg-4">
                                            <div class="form-group">
                                                <label for="namaProduk">Nama Produk</label>
                                                <input type="text" name="namaProduk" id="namaProduk" class="form-control" placeholder="Masukkan nama produk" required>
                                            </div>
                                        </div>

                                        <!-- Kategori Produk -->
                                        <div class="col-sm-12 col-md-6 col-lg-4">
                                            <div class="form-group">
                                                <label for="kategoriProduk">Kategori Produk</label>
                                                <input list="kategoriProdukList" name="kategoriProduk" id="kategoriProduk" class="form-control" placeholder="-- Pilih atau isi Kategori --" required>
                                                <datalist id="kategoriProdukList">
                                                    <?php foreach ($kategoriList as $kategori): ?>
                                                        <option value="<?= $kategori; ?>"></option>
                                                    <?php endforeach; ?>
                                                </datalist>
                                            </div>
                                        </div>

                                        <!-- Harga Produk -->
                                        <div class="col-sm-12 col-md-6 col-lg-4">
                                            <div class="form-group">
                                                <label for="hargaProduk">Harga Produk</label>
                                                <input type="number" name="hargaProduk" id="hargaProduk" class="form-control" placeholder="Masukkan harga produk" required>
                                            </div>
                                        </div>

                                        <!-- Stok Produk -->
                                        <div class="col-sm-12 col-md-6 col-lg-4">
                                            <div class="form-group">
                                                <label for="stokProduk">Stok Produk</label>
                                                <input type="number" name="stokProduk" id="stokProduk" class="form-control" placeholder="Masukkan jumlah stok" required>
                                            </div>
                                        </div>

                                        <!-- Gambar Produk -->
                                        <div class="col-sm-12 col-md-6 col-lg-4">
                                            <div class="form-group">
                                                <label for="gambarProduk">Gambar Produk</label>
                                                <input type="file" name="gambarProduk" id="gambarProduk" class="form-control" required>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Submit and Reset buttons -->
                                    <div class="text-center">
                                        <button type="submit" name="submit" class="btn btn-success">Submit</button>
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
