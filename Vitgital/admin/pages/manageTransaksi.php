<?php
session_start();
include('../../koneksi.php'); // Pastikan path ini benar

// Menghapus transaksi
if (isset($_GET['del'])) {
    $idTransaksi = $_GET['del'];
    $query = "DELETE FROM transaksi WHERE idTransaksi=?";
    $stmt = $connect->prepare($query);
    $stmt->bind_param('s', $idTransaksi); // 's' karena idTransaksi berupa varchar
    $stmt->execute();
    $stmt->close();
    echo "<script>alert('Transaksi Berhasil Dihapus');</script>";
    echo "<script>window.location.href='manageTransaksi.php';</script>";
}

// Fetch all transaksi entries from the database
$query = "SELECT * FROM transaksi ORDER BY idTransaksi DESC";
$stmt = $connect->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" sizes="16x16" href="../../img/logo.png">
    <title>Daftar Transaksi</title>
    <link href="../assets/libs/chartist/dist/chartist.min.css" rel="stylesheet">
    <link href="../assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet">
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
            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col-7 align-self-center">
                        <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Daftar Transaksi</h4>
                    </div>
                </div>
            </div>

            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-subtitle">Menampilkan semua transaksi yang terdaftar.</h6>
                                <hr>
                                <div class="table-responsive">
                                    <table id="zero_config" class="table table-striped table-hover table-bordered no-wrap">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>No</th>
                                                <th>ID Transaksi</th>
                                                <th>Username</th>
                                                <th>Tanggal Transaksi</th>
                                                <th>Cara Bayar</th>
                                                <th>Bank</th>
                                                <th>Status Transaksi</th>
                                                <th>Status Pengiriman</th>
                                                <th>Total Harga</th>
                                                <th>Status Permohonan</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $cnt = 1;
                                            while ($row = $result->fetch_assoc()) {
                                                ?>
                                                <tr>
                                                    <td><?php echo $cnt; ?></td>
                                                    <td><?php echo $row['idTransaksi']; ?></td>
                                                    <td><?php echo $row['username']; ?></td>
                                                    <td><?php echo $row['tanggalTransaksi']; ?></td>
                                                    <td><?php echo $row['caraBayar']; ?></td>
                                                    <td><?php echo $row['bank']; ?></td>
                                                    <td><?php echo $row['statusTransaksi']; ?></td>
                                                    <td><?php echo $row['statusPengiriman']; ?></td>
                                                    <td>Rp<?php echo number_format($row['totalHarga'], 0, ',', '.'); ?></td>
                                                    <td>
                                                        <?php if ($row['statusTransaksi'] == 'Accepted' || $row['statusTransaksi'] == 'Rejected' || $row['statusTransaksi'] == 'Cancelled') : ?>
                                                            Disabled!
                                                        <?php else : ?>
                                                            <a href="terimaTransaksi.php?idTransaksi=<?php echo $row['idTransaksi']; ?>" class="btn btn-success" onclick="return confirm('Yakin menerima pesanan dengan id <?php echo $row['idTransaksi']; ?>?');">Accept</a>
                                                            <a href="tolakTransaksi.php?idTransaksi=<?php echo $row['idTransaksi']; ?>" class="btn btn-danger" onclick="return confirm('Yakin menolak pesanan dengan id <?php echo $row['idTransaksi']; ?>?');">Reject</a>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
    <div style="display: flex; justify-content: center; align-items: center; height: 100%;">
        <a href="shippingOrder.php?idTransaksi=<?php echo $row['idTransaksi']; ?>&username=<?php echo $row['username']; ?>" title="View Full Details">
            <i class="icon-size-fullscreen"></i>
        </a>
    </div>
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
