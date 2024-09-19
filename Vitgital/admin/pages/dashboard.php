<?php
// Memulai session
session_start();

// Menghubungkan ke database
include('../../koneksi.php');

// Error reporting untuk menampilkan kesalahan PHP dan SQL
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Error handling untuk koneksi MySQL
if (!$connect) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Fungsi untuk menjalankan query dan menangkap kesalahan jika ada
function executeQuery($connect, $query) {
    $result = mysqli_query($connect, $query);
    if (!$result) {
        die("Query gagal: " . mysqli_error($connect));
    }
    return $result;
}

// 1. Mengambil Total Produk
$queryTotalProduk = "SELECT COUNT(idProduk) AS total_produk FROM produk";
$resultTotalProduk = executeQuery($connect, $queryTotalProduk);
$totalProduk = mysqli_fetch_assoc($resultTotalProduk)['total_produk'];

// 2. Mengambil Total Customer
$queryTotalCustomer = "SELECT COUNT(username) AS total_customer FROM customer";
$resultTotalCustomer = executeQuery($connect, $queryTotalCustomer);
$totalCustomer = mysqli_fetch_assoc($resultTotalCustomer)['total_customer'];

// 3. Mengambil Total Kategori Produk
$queryTotalKategori = "SELECT COUNT(DISTINCT kategoriProduk) AS total_kategori FROM produk";
$resultTotalKategori = executeQuery($connect, $queryTotalKategori);
$totalKategori = mysqli_fetch_assoc($resultTotalKategori)['total_kategori'];

// 4. Mengambil Produk Terlaris (berdasarkan penjualan di keranjang)
$queryProdukTerlaris = "
    SELECT p.namaProduk, SUM(k.jumlah) AS total_terjual 
    FROM keranjang k 
    JOIN produk p ON k.idProduk = p.idProduk 
    WHERE k.status = 'Dibayar'
    GROUP BY k.idProduk
    ORDER BY total_terjual DESC 
    LIMIT 1";
$resultProdukTerlaris = executeQuery($connect, $queryProdukTerlaris);
$produkTerlaris = mysqli_fetch_assoc($resultProdukTerlaris);

// 5. Data untuk Doughnut Chart (Perbandingan Kategori Produk Terjual)
$queryKategoriTerjual = "
    SELECT p.kategoriProduk, SUM(k.jumlah) AS total_terjual 
    FROM keranjang k
    JOIN produk p ON k.idProduk = p.idProduk
    WHERE k.status = 'Dibayar'
    GROUP BY p.kategoriProduk";
$resultKategoriTerjual = executeQuery($connect, $queryKategoriTerjual);

$kategoriProduk = [];
$kuantitasTerjual = [];
while ($row = mysqli_fetch_assoc($resultKategoriTerjual)) {
    $kategoriProduk[] = $row['kategoriProduk'];
    $kuantitasTerjual[] = $row['total_terjual'];
}
?>

<!DOCTYPE html>
<html dir="ltr" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Dashboard page">
    <meta name="author" content="Your Name">
    <link rel="icon" type="image/png" sizes="16x16" href="../../img/logo.png">
    <title>Dashboard</title>

    <link href="../assets/libs/chartist/dist/chartist.min.css" rel="stylesheet">
    <link href="../assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet">
    <link href="../dist/css/style.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <!-- Preloader -->
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
    
    <div id="main-wrapper" data-theme="light" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed" data-boxed-layout="full">
        
        <!-- Topbar header -->
        <header class="topbar" data-navbarbg="skin6">
            <?php include 'includes/navigation.php'; ?>
        </header>

        <!-- Sidebar -->
        <aside class="left-sidebar" data-sidebarbg="skin6">
            <div class="scroll-sidebar" data-sidebarbg="skin6">
                <?php include 'includes/sidebar.php'; ?>
            </div>
        </aside>

        <!-- Page content -->
        <div class="page-wrapper">
            <div class="container-fluid">
                <!-- Row for Cards -->
                <div class="row">
                    <!-- Card 1: Total Produk -->
                    <div class="col-lg-3 col-md-6">
                        <div class="card border-right">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <h2 class="text-dark mb-1 font-weight-medium"><?= $totalProduk ?></h2>
                                        <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Total Produk</h6>
                                    </div>
                                    <div class="ml-auto">
                                        <span class="opacity-7 text-muted"><i data-feather="shopping-bag"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Card 2: Total Customer -->
                    <div class="col-lg-3 col-md-6">
                        <div class="card border-right">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <h2 class="text-dark mb-1 font-weight-medium"><?= $totalCustomer ?></h2>
                                        <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Total Customer</h6>
                                    </div>
                                    <div class="ml-auto">
                                        <span class="opacity-7 text-muted"><i data-feather="users"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Card 3: Total Kategori -->
                    <div class="col-lg-3 col-md-6">
                        <div class="card border-right">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <h2 class="text-dark mb-1 font-weight-medium"><?= $totalKategori ?></h2>
                                        <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Total Kategori Produk</h6>
                                    </div>
                                    <div class="ml-auto">
                                        <span class="opacity-7 text-muted"><i data-feather="grid"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Card 4: Produk Terlaris -->
                    <div class="col-lg-3 col-md-6">
                        <div class="card border-right">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <h2 class="text-dark mb-1 font-weight-medium"><?= $produkTerlaris['namaProduk'] ?? 'N/A' ?></h2>
                                        <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Produk Terlaris</h6>
                                    </div>
                                    <div class="ml-auto">
                                        <span class="opacity-7 text-muted"><i data-feather="star"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Doughnut Chart for Kategori Produk Terjual -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-header pb-0 pt-3 bg-transparent">
                            <h6 class="text-center">Perbandingan Kategori Produk Terjual</h6>
                        </div>
                        <div class="card-body p-3">
                            <div class="chart" style="max-width: 750px; margin: 0 auto;">
                                <canvas id="chart-doughnut"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Section -->
    <script src="../assets/libs/chartist/dist/chartist.min.js"></script>
    <script src="../assets/libs/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js"></script>
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
    <!-- Feather Icons Initialization -->
    <script>
        feather.replace();
    </script>

    <!-- Chart.js Doughnut Chart -->
    <script>
        try {
            var ctx = document.getElementById("chart-doughnut").getContext('2d');
            var doughnutChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: <?= json_encode($kategoriProduk) ?>,
                    datasets: [{
                        label: 'Kuantitas Terjual',
                        data: <?= json_encode($kuantitasTerjual) ?>,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.6)',
                            'rgba(54, 162, 235, 0.6)',
                            'rgba(255, 206, 86, 0.6)',
                            'rgba(75, 192, 192, 0.6)',
                            'rgba(153, 102, 255, 0.6)',
                            'rgba(255, 159, 64, 0.6)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom'
                        }
                    }
                }
            });
        } catch (error) {
            console.error('Error initializing the chart:', error);
        }
    </script>
</body>
</html>

