<?php
session_start();
include('../koneksi.php'); // Koneksi ke database

// Pastikan customer sudah login
if (!isset($_SESSION['username'])) {
    echo "<script>alert('Anda harus login terlebih dahulu!');</script>";
    echo "<script>window.location.href='login.php';</script>";
    exit();
}

$username = $_SESSION['username'];

// Handle form submission for becoming a vendor
$messageStatus = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $namaToko = mysqli_real_escape_string($connect, $_POST['namaToko']);
    $deskripsiToko = mysqli_real_escape_string($connect, $_POST['deskripsiToko']);

    // Insert vendor request to database
    $query = "INSERT INTO vendor (username, namaToko, deskripsiToko) VALUES (?, ?, ?)";
    $stmt = $connect->prepare($query);
    $stmt->bind_param('sss', $username, $namaToko, $deskripsiToko);
    if ($stmt->execute()) {
        header("Location: vendor.php?success=true");
        exit();
    } else {
        $messageStatus = "Gagal mengirim permintaan. Silakan coba lagi.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="16x16" href="../../img/logo.png">
    <title>Daftar Vendor</title>
    <link href="../dist/css/style.min.css" rel="stylesheet">
    <style>
        main {
            background-color: #f6f9ff;
        }

        .jumbotron {
            background-color: #2596be !important;
            color: white;
            text-align: center;
            padding: 80px 0;
            margin-top: 100px;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .jumbotron::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            background: rgba(37, 150, 190, 0.7); /* Layered background effect */
            z-index: 1;
        }

        .jumbotron h1 {
            z-index: 2;
            position: relative;
        }

        .card {
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-group input,
        .form-group textarea {
            font-size: 16px;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .btn {
            background-color: #2596be;
            border: none;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #1e7a9e;
        }

        .row {
            display: flex;
            justify-content: center;
        }

        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
            padding: 10px;
            margin-top: 20px;
            border-radius: 5px;
            text-align: center;
        }

        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
            padding: 10px;
            margin-top: 20px;
            border-radius: 5px;
            text-align: center;
        }
    </style>
</head>

<body>

    <!-- Header include -->
    <?php include 'includes/header.php'; ?>

    <main>
        <div class="container-fluid p-0">
            <!-- Jumbotron untuk tampilan halaman vendor -->
            <div class="jumbotron jumbotron-fluid">
                <div class="container text-center">
                    <h1 class="display-4">Daftar sebagai Vendor</h1>
                    <h5 style="color: white;">Mulai bisnis Anda bersama kami dan nikmati kemudahan berjualan online.</h5>

                </div>
            </div>

            <!-- Form untuk mendaftar sebagai vendor -->
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <!-- Tampilkan pesan sukses atau gagal -->
                        <?php if (isset($_GET['success']) && $_GET['success'] == 'true'): ?>
                            <div class="alert alert-success" id="statusMessage">
                                Permintaan menjadi vendor berhasil terkirim!
                            </div>
                        <?php elseif (!empty($messageStatus)): ?>
                            <div class="alert alert-danger" id="statusMessage">
                                <?php echo $messageStatus; ?>
                            </div>
                        <?php endif; ?>

                        <div class="card shadow-sm p-4">
                            <h2 class="text-center mb-4">Daftar Sekarang!</h2>
                            <form action="" method="POST">
                                <!-- Input Nama Toko -->
                                <div class="form-group">
                                    <label for="namaToko">Nama Toko</label>
                                    <input type="text" class="form-control" id="namaToko" name="namaToko" required placeholder="Masukkan nama toko Anda">
                                </div>
                                <!-- Input Deskripsi Toko -->
                                <div class="form-group">
                                    <label for="deskripsiToko">Deskripsi Toko</label>
                                    <textarea class="form-control" id="deskripsiToko" name="deskripsiToko" rows="5" required placeholder="Masukkan deskripsi toko Anda"></textarea>
                                </div>
                                <!-- Submit Button -->
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">Daftar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Jika ada pesan sukses, sembunyikan setelah 3 detik
            window.addEventListener('DOMContentLoaded', (event) => {
                const statusMessage = document.getElementById('statusMessage');
                if (statusMessage) {
                    setTimeout(() => {
                        statusMessage.style.display = 'none';
                        window.location.href = 'vendor.php';
                    }, 3000);
                }
            });
        </script>
    </main>


</body>

</html>
