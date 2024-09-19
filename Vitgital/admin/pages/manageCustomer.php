<?php
session_start();
include('../../koneksi.php'); // Pastikan path ini benar

// Menghapus pelanggan
if (isset($_GET['del'])) {
    $username = $_GET['del'];
    $query = "DELETE FROM customer WHERE username=?";
    $stmt = $connect->prepare($query);
    $stmt->bind_param('s', $username); // 's' karena username berupa varchar
    $stmt->execute();
    $stmt->close();
    echo "<script>alert('Data Berhasil Dihapus');</script>";
    echo "<script>window.location.href='manageCustomer.php';</script>";
}
?>

<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" sizes="16x16" href="../../img/logo.png">
    <title>Daftar Pelanggan</title>
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
                        <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Daftar Pelanggan</h4>
                    </div>
                </div>
            </div>

            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-subtitle">Menampilkan semua pelanggan yang terdaftar.</h6>
                                <hr>
                                <div class="table-responsive">
                                    <table id="zero_config" class="table table-striped table-hover table-bordered no-wrap">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>No</th>
                                                <th>Username</th>
                                                <th>Nama Lengkap</th>
                                                <th>Email</th>
                                                <th>Tanggal Lahir</th>
                                                <th>Gender</th>
                                                <th>Alamat</th>
                                                <th>Kota</th>
                                                <th>Kontak</th>
                                                <th>Paypal ID</th> <!-- Tambahkan kolom Paypal ID -->
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $query = "SELECT * FROM customer";
                                            $stmt = $connect->prepare($query);
                                            $stmt->execute();
                                            $res = $stmt->get_result();
                                            $cnt = 1;

                                            while ($row = $res->fetch_object()) {
                                                echo "<tr>";
                                                echo "<td>" . $cnt . "</td>";
                                                echo "<td>" . $row->username . "</td>";
                                                echo "<td>" . $row->namaLengkap . "</td>";
                                                echo "<td>" . $row->email . "</td>";
                                                echo "<td>" . $row->dob . "</td>";
                                                echo "<td>" . $row->gender . "</td>";
                                                echo "<td>" . $row->alamat . "</td>";
                                                echo "<td>" . $row->kota . "</td>";
                                                echo "<td>" . $row->contact . "</td>";
                                                echo "<td>" . $row->paypalID . "</td>"; // Menampilkan Paypal ID
                                                echo "<td>
                                                        <a href='editCustomer.php?username=" . $row->username . "' title='Edit'><i class='icon-note'></i></a>&nbsp;&nbsp;
                                                        <a href='manageCustomer.php?del=" . $row->username . "' title='Delete Record' onclick=\"return confirm('Yakin ingin menghapus pelanggan ini?');\"><i class='icon-close' style='color:red;'></i></a>
                                                      </td>";
                                                echo "</tr>";
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
