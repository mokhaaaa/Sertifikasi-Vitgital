<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$title = 'Guest Book';
require_once 'includes/header.php'; // Path ke header.php untuk customer
require_once '../koneksi.php'; // Koneksi database

$messageStatus = "";

// Proses jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $name = mysqli_real_escape_string($connect, $_POST['name']);
    $email = mysqli_real_escape_string($connect, $_POST['email']);
    $message = mysqli_real_escape_string($connect, $_POST['message']);

    // Panggil fungsi untuk menyimpan data guestbook
    if (submit_guestbook($name, $email, $message)) {
        // Redirect halaman ke guestbookCustomer.php dengan status sukses
        header("Location: guestbookCustomer.php?success=true");
        exit();
    } else {
        $messageStatus = "Gagal mengirim pesan. Silakan coba lagi.";
    }
}
?>


<main>
<style>
        /* Tambahkan margin-top ke konten agar tidak tertutup oleh header */
        main {
            background-color: #f6f9ff;
        }

        /* Jumbotron styling for "Kontak Kami" */
        .jumbotron {
            background-color: #2596be;
            color: white;
            text-align: center;
            padding: 50px 0;
            margin-top:100px;
        }

        /* Card styling for form */
        .card {
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Form input and textarea styling */
        .form-group input,
        .form-group textarea {
            font-size: 16px;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        /* Button styling */
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

        /* Centered form in the page */
        .row {
            display: flex;
            justify-content: center;
        }

        /* Success message styling */
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

        /* Tombol spacing */
        
    </style>

    <div class="container-fluid p-0">
        <!-- Container Panjang dengan Teks "Kontak Kami" -->
        <div class="jumbotron jumbotron-fluid text-white" style="background-color: #2596be; padding: 50px 0; margin-bottom: 30px;">
            <div class="container text-center">
                <h1 class="display-4">Kontak Kami</h1>
            </div>
        </div>

        <!-- Form Guest Book -->
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <!-- Tampilkan pesan sukses atau gagal -->
                    <?php if (isset($_GET['success']) && $_GET['success'] == 'true'): ?>
                        <div class="alert alert-success" id="statusMessage">
                            Pesan berhasil terkirim!
                        </div>
                    <?php elseif (!empty($messageStatus)): ?>
                        <div class="alert alert-danger" id="statusMessage">
                            <?php echo $messageStatus; ?>
                        </div>
                    <?php endif; ?>

                    <div class="card shadow-sm p-4">
                        <h2 class="text-center mb-4">Tinggalkan Pesan!</h2>
                        <form action="" method="POST">
                            <!-- Input Nama -->
                            <div class="form-group">
                                <label for="name">Nama</label>
                                <input type="text" class="form-control" id="name" name="name" required placeholder="Masukkan nama Anda">
                            </div>
                            <!-- Input Email -->
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required placeholder="Masukkan email Anda">
                            </div>
                            <!-- Input Pesan -->
                            <div class="form-group">
                                <label for="message">Pesan</label>
                                <textarea class="form-control" id="message" name="message" rows="5" required placeholder="Masukkan pesan Anda"></textarea>
                            </div>
                            <!-- Submit Button -->
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary" style="background-color: #2596be;">Kirim Pesan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Jika ada pesan sukses, sembunyikan setelah 3 detik dan refresh halaman
        window.addEventListener('DOMContentLoaded', (event) => {
            const statusMessage = document.getElementById('statusMessage');
            if (statusMessage) {
                setTimeout(() => {
                    statusMessage.style.display = 'none'; // Sembunyikan pesan
                    window.location.href = 'guestbookCustomer.php'; // Refresh halaman setelah pesan disembunyikan
                }, 3000); // Sembunyikan setelah 3 detik
            }
        });
    </script>
</main>

</body>
</html>
