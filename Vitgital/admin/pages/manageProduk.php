<?php
session_start();
include('../../koneksi.php'); // Make sure this path is correct

if (isset($_GET['del'])) {
    $id = $_GET['del']; // idProduk is a string, so no need for intval()
    $query = "DELETE FROM produk WHERE idProduk=?";
    $stmt = $connect->prepare($query); // Use $connect from koneksi.php
    $stmt->bind_param('s', $id); // 's' for string since idProduk is varchar
    if ($stmt->execute()) {
        echo "<script>alert('Data Berhasil Dihapus');</script>";
    } else {
        echo "<script>alert('Gagal menghapus data');</script>";
    }
    $stmt->close();
}

?>

<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" sizes="16x16" href="../../img/logo.png">
    <title>Produk</title>
    <link href="../assets/libs/chartist/dist/chartist.min.css" rel="stylesheet">
    <link href="../assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet">
    <link href="../dist/css/style.min.css" rel="stylesheet">
</head>

<body>
    <div id="main-wrapper" data-theme="light" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed" data-boxed-layout="full">
        <header class="topbar" data-navbarbg="skin6">
            <?php include 'includes/navigation.php' ?>
        </header>
        <aside class="left-sidebar" data-sidebarbg="skin6">
            <div class="scroll-sidebar" data-sidebarbg="skin6">
                <?php include 'includes/sidebar.php' ?>
            </div>
        </aside>
        <div class="page-wrapper">
            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col-7 align-self-center">
                        <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Daftar Produk</h4>
                    </div>
                </div>
            </div>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-subtitle">Menampilkan semua produk yang terdaftar.</h6>
                                <a href="Produk.php"><button type="button" class="btn btn-block btn-md btn-success">Tambah Produk</button></a>
                                <hr>
                                <div class="table-responsive">
                                    <table id="zero_config" class="table table-striped table-hover table-bordered no-wrap">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>No</th>
                                                <th>ID Produk</th>
                                                <th>Nama Produk</th>
                                                <th>Kategori</th>
                                                <th>Harga</th>
                                                <th>Stok</th>
                                                <th>Gambar</th> <!-- Add Gambar column -->
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $query = "SELECT * FROM produk";
                                            $stmt = $connect->prepare($query);
                                            $stmt->execute();
                                            $res = $stmt->get_result();
                                            $cnt = 1;
                                            while ($row = $res->fetch_object()) {
                                                ?>
                                                <tr>
                                                    <td><?php echo $cnt; ?></td>
                                                    <td><?php echo $row->idProduk; ?></td>
                                                    <td><?php echo $row->namaProduk; ?></td>
                                                    <td><?php echo $row->kategoriProduk; ?></td>
                                                    <td>Rp<?php echo number_format($row->hargaProduk, 0, ',', '.'); ?></td>
                                                    <td><?php echo $row->stokProduk; ?></td>
                                                    <td style="text-align: center;">
                                                        <img src="../../img/<?php echo $row->gambarProduk; ?>" alt="<?php echo $row->namaProduk; ?>" style="height: 75px; width: 75px;">
                                                    </td>
                                                    <td>
                                                        <a href="editProduk.php?id=<?php echo $row->idProduk; ?>" title="Edit"><i class="icon-note"></i></a>&nbsp;&nbsp;
                                                        <a href="manageProduk.php?del=<?php echo $row->idProduk; ?>" title="Delete Record" onclick="return confirm('Apakah kamu yakin ingin menghapus produk?');"><i class="icon-close" style="color:red;"></i></a>
                                                    </td>
                                                </tr>
                                                <?php
                                                $cnt++;
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
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
    <script src="../assets/extra-libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="../dist/js/pages/datatable/datatable-basic.init.js"></script>
</body>

</html>
