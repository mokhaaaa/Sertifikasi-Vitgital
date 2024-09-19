<?php
session_start();
$title = 'Keranjang Belanja';
require '../koneksi.php'; // Koneksi ke database
require 'includes/header.php'; // Header untuk customer

// Pastikan pengguna sudah login
if (!isset($_SESSION["username"])) {
    header("Location: ../custlogin.php");
    exit;
}

$username = $_SESSION["username"];

// Mengambil data keranjang dari database (belum dibayar)
$allKeranjang = query("SELECT keranjang.idKeranjang, keranjang.jumlah, produk.idProduk, produk.namaProduk, produk.hargaProduk 
                       FROM keranjang 
                       JOIN produk ON keranjang.idProduk = produk.idProduk 
                       WHERE keranjang.username = '$username' AND keranjang.status = 'Belum Dibayar'");

// Menghitung total harga semua produk di keranjang
$totalHarga = query("SELECT SUM(produk.hargaProduk * keranjang.jumlah) AS totalHarga 
                     FROM keranjang 
                     JOIN produk ON keranjang.idProduk = produk.idProduk 
                     WHERE keranjang.username = '$username' AND keranjang.status = 'Belum Dibayar'")[0]["totalHarga"];

// Fungsi untuk checkout
function checkout($data) {
    global $connect;

    // Hitung total transaksi untuk menentukan ID Transaksi berikutnya
    $result = mysqli_query($connect, "SELECT COUNT(*) as total FROM transaksi");
    $row = mysqli_fetch_assoc($result);
    $nextId = $row['total'] + 1; // Tentukan ID berikutnya

    // Buat ID Transaksi dengan format TRA2024XX
    $idTransaksi = 'TRA2024' . str_pad($nextId, 2, '0', STR_PAD_LEFT);

    $username = $data["username"];
    $tanggalTransaksi = date("Y-m-d");
    $caraBayar = $data["caraBayar"];
    $bank = $data["bank"];
    $statusTransaksi = "Pending";
    $totalHarga = $data["totalHarga"];

    // Masukkan data transaksi ke dalam tabel transaksi
    $queryTransaksi = "INSERT INTO transaksi (idTransaksi, username, tanggalTransaksi, caraBayar, bank, statusTransaksi, totalHarga, statusPengiriman) 
                       VALUES('$idTransaksi', '$username', '$tanggalTransaksi', '$caraBayar', '$bank', '$statusTransaksi', '$totalHarga', 'Pending')";
    mysqli_query($connect, $queryTransaksi);

    // Update keranjang untuk memberikan status 'Dibayar' dan masukkan idTransaksi
    $queryKeranjang = "UPDATE keranjang SET status = 'Dibayar', idTransaksi='$idTransaksi' WHERE username = '$username' AND status = 'Belum Dibayar'";
    mysqli_query($connect, $queryKeranjang);

    return mysqli_affected_rows($connect);
}

// Proses checkout
if (isset($_POST["submit"])) {
    if (checkout($_POST) > 0) {
        echo "<script>alert('Checkout berhasil!'); document.location.href = 'transaksiCustomer.php';</script>";
    } else {
        echo "<script>alert('Checkout gagal!'); document.location.href = 'cartCustomer.php';</script>";
    }
}
?>

<main id="main" class="main">
    <div class="container text-center mb-5">
        <h1 class="text-danger my-4">Keranjang Belanja</h1>
    </div>

    <section class="section">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card" style="margin-top: 30px;">
                    <div class="card-body">
                        <h4 class="card-title text-center mb-4" style="font-size: 24px;">Barang di Keranjang</h4>

                        <!-- Tabel Barang di Keranjang -->
                        <table class="table table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">ID Produk</th>
                                    <th scope="col">Nama Produk</th>
                                    <th scope="col">Jumlah</th>
                                    <th scope="col">Harga Satuan</th>
                                    <th scope="col">Total Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                <?php foreach ($allKeranjang as $keranjang) : ?>
                                <tr>
                                    <td><?= $i; ?></td>
                                    <td><?= $keranjang["idProduk"]; ?></td>
                                    <td><?= $keranjang["namaProduk"]; ?></td>
                                    <td><?= $keranjang["jumlah"]; ?></td>
                                    <td>Rp<?= number_format($keranjang["hargaProduk"], 0, ',', '.'); ?></td>
                                    <td>Rp<?= number_format($keranjang["hargaProduk"] * $keranjang["jumlah"], 0, ',', '.'); ?></td>
                                </tr>
                                <?php $i++; ?>
                                <?php endforeach; ?>
                                <tr>
                                    <td colspan="5" class="text-right"><strong>Total Harga</strong></td>
                                    <td><strong>Rp<?= number_format($totalHarga, 0, ',', '.'); ?></strong></td>
                                </tr>
                            </tbody>
                        </table>
                        <!-- End Tabel Barang di Keranjang -->

                        <!-- Form Checkout -->
                        <form action="" method="post" id="checkoutForm">
                            <input type="hidden" name="username" value="<?= $username; ?>">
                            <input type="hidden" name="totalHarga" value="<?= $totalHarga; ?>">

                            <!-- Cara Pembayaran -->
                            <label for="caraBayar" class="form-label" style="font-size: 20px; font-weight: bold;">Cara Pembayaran</label>
                            <div class="d-flex flex-column mb-4">
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" id="prepaid" value="Prepaid" name="caraBayar" required> 
                                    <label for="prepaid" class="form-check-label" style="font-size: 18px;">Prepaid</label>
                                </div>
                                <div class="form-check mt-2">
                                    <input type="radio" class="form-check-input" id="postpaid" value="Postpaid" name="caraBayar" required> 
                                    <label for="postpaid" class="form-check-label" style="font-size: 18px;">Postpaid</label>
                                </div>
                            </div>

                            <!-- Pilihan Bank hanya muncul jika prepaid dipilih -->
                            <div id="bankOptions" style="display: none; margin-bottom: 20px;">
                                <label for="pembayaran" class="form-label" style="font-size: 20px; font-weight: bold;">Metode Pembayaran (Prepaid)</label>
                                <div>
                                    <select name="bank" class="form-select" style="font-size: 18px;">
                                        <option value="">-- Pilih Bank --</option>
                                        <option value="BCA">BCA</option>
                                        <option value="BNI">BNI</option>
                                        <option value="BRI">BRI</option>
                                        <option value="Mandiri">Mandiri</option>
                                        <option value="BTN">BTN</option>
                                        <option value="CIMB Niaga">CIMB Niaga</option>
                                        <option value="BSI">BSI</option>
                                        <option value="Bank 9 Jambi">Bank 9 Jambi</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Bayar di tempat hanya jika postpaid dipilih -->
                            <div id="postpaidOption" style="display: none; margin-bottom: 10px;">
                                <label class="form-label" style="font-size: 20px; font-weight: bold;">Metode Pembayaran (Postpaid)</label>
                                <div style="margin-left: 20px;">
                                    <input type="radio" class="form-check-input" value="Bayar Ditempat" name="bank" checked> 
                                    <label for="bayarDitempat" class="form-check-label" style="font-size: 18px;">Bayar Ditempat</label>
                                </div>
                            </div>

                            <!-- Button Checkout dan Hapus -->
                            <div class="d-flex justify-content-center" style="margin-top: 20px;">
                                <button type="submit" class="btn btn-danger" name="submit" style="width:150px; font-size: 16px; margin-right: 10px;">Checkout</button>
                                <button class="btn btn-warning" onclick="return confirm('Apakah Anda yakin ingin menghapus semua produk di keranjang?')" style="width: 150px; font-size: 16px;">
                                    <a href="deleteChart.php" style="color: white; text-decoration: none;">Hapus Keranjang</a>
                                </button>
                            </div>
                        </form>
                        <!-- End Form Checkout -->
                    </div>
                </div>
            </div><!-- End Col -->
        </div><!-- End Row -->
    </section><!-- End Section -->
</main>

<script>
    // Tampilkan opsi pembayaran sesuai pilihan
    document.getElementById('prepaid').addEventListener('click', function() {
        document.getElementById('bankOptions').style.display = 'block';
        document.getElementById('postpaidOption').style.display = 'none';
    });

    document.getElementById('postpaid').addEventListener('click', function() {
        document.getElementById('bankOptions').style.display = 'none';
        document.getElementById('postpaidOption').style.display = 'block';
    });
</script>
