<?php
session_start();
include('../../koneksi.php'); // Koneksi ke database

// Jika admin menekan tombol Accept atau Reject
if (isset($_GET['action']) && isset($_GET['idVendor'])) {
    $idVendor = $_GET['idVendor'];
    $action = $_GET['action'];

    // Tentukan status vendor berdasarkan aksi yang dipilih
    if ($action == 'accept') {
        $statusVendor = 'Accepted';
    } elseif ($action == 'reject') {
        $statusVendor = 'Rejected';
    }

    // Update status vendor dalam database
    $queryUpdate = "UPDATE vendor SET statusVendor = ? WHERE idVendor = ?";
    $stmt = $connect->prepare($queryUpdate);
    $stmt->bind_param('si', $statusVendor, $idVendor);
    if ($stmt->execute()) {
        echo "<script>alert('Status Vendor berhasil diubah menjadi $statusVendor!');</script>";
        echo "<script>window.location.href='manageVendor.php';</script>";
    } else {
        echo "<script>alert('Gagal mengubah status vendor!');</script>";
    }
    $stmt->close();
}

// Ambil semua vendor
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all'; // Filter status, default "all"
if ($filter == 'all') {
    $queryVendor = "SELECT v.*, c.namaLengkap FROM vendor v JOIN customer c ON v.username = c.username";
} else {
    $queryVendor = "SELECT v.*, c.namaLengkap FROM vendor v JOIN customer c ON v.username = c.username WHERE statusVendor = ?";
}

$stmt = $connect->prepare($queryVendor);
if ($filter != 'all') {
    $stmt->bind_param('s', $filter); // Bind filter status (Pending, Accepted, Rejected)
}
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
    <title>Manage Vendor</title>
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
                        <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Daftar Vendor</h4>
                    </div>
                </div>
            </div>

            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-subtitle">Menampilkan semua vendor yang terdaftar.</h6>
                                <div class="d-flex mb-3">
                                    <!-- Filter Dropdown -->
                                    <form method="GET" action="manageVendor.php">
                                        <select name="filter" onchange="this.form.submit()" class="form-control">
                                            <option value="all" <?= ($filter == 'all') ? 'selected' : '' ?>>Semua</option>
                                            <option value="Pending" <?= ($filter == 'Pending') ? 'selected' : '' ?>>Pending</option>
                                            <option value="Accepted" <?= ($filter == 'Accepted') ? 'selected' : '' ?>>Accepted</option>
                                            <option value="Rejected" <?= ($filter == 'Rejected') ? 'selected' : '' ?>>Rejected</option>
                                        </select>
                                    </form>
                                </div>
                                <hr>
                                <div class="table-responsive">
                                    <table id="zero_config" class="table table-striped table-hover table-bordered no-wrap">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>No</th>
                                               
                                                <th>Nama Toko</th>
                                                <th>Nama Pemilik</th>
                                                <th>Deskripsi Toko</th>
                                                <th>Status</th>
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
                                                  
                                                    <td><?php echo $row['namaToko']; ?></td>
                                                    <td><?php echo $row['namaLengkap']; ?></td>
                                                    <td><?php echo $row['deskripsiToko']; ?></td>
                                                    <td><?php echo $row['statusVendor']; ?></td>
                                                    <td>
                                                        <?php if ($row['statusVendor'] == 'Pending') : ?>
                                                            <a href="manageVendor.php?action=accept&idVendor=<?php echo $row['idVendor']; ?>" class="btn btn-success" onclick="return confirm('Yakin menerima vendor <?php echo $row['namaToko']; ?>?');">Accept</a>
                                                            <a href="manageVendor.php?action=reject&idVendor=<?php echo $row['idVendor']; ?>" class="btn btn-danger" onclick="return confirm('Yakin menolak vendor <?php echo $row['namaToko']; ?>?');">Reject</a>
                                                        <?php else : ?>
                                                            <span class="badge <?= $row['statusVendor'] == 'Accepted' ? 'badge-success' : 'badge-danger' ?>">
                                                                <?= $row['statusVendor']; ?>
                                                            </span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                                <?php
                                                $cnt++;
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                    <?php if ($result->num_rows == 0): ?>
                                        <p class="text-center">Tidak ada vendor yang sesuai dengan filter.</p>
                                    <?php endif; ?>
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
    <script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../dist/js/app-style-switcher.js"></script>
    <script src="../dist/js/feather.min.js"></script>
    <script src="../assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
    <script src="../dist/js/sidebarmenu.js"></script>
    <script src="../dist/js/custom.min.js"></script>
    <script src="../assets/extra-libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="../dist/js/pages/datatable/datatable-basic.init.js"></script>
</body>

</html>
