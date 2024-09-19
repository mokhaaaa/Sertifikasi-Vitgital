<?php
session_start();
include('../../koneksi.php'); // Pastikan path ini benar

// Handle form submission for editing the customer
if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'] ? password_hash($_POST['password'], PASSWORD_BCRYPT) : $_POST['passwordOLD']; // Jika password baru tidak dimasukkan, gunakan yang lama
    $namaLengkap = $_POST['namaLengkap'];
    $email = $_POST['email'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $alamat = $_POST['alamat'];
    $kota = $_POST['kota'];
    $contact = $_POST['contact'];
    $paypalID = $_POST['paypalID'];

    // Update customer details in the database
    $query = "UPDATE customer SET password=?, namaLengkap=?, email=?, dob=?, gender=?, alamat=?, kota=?, contact=?, paypalID=? WHERE username=?";
    $stmt = $connect->prepare($query);
    $stmt->bind_param('ssssssssss', $password, $namaLengkap, $email, $dob, $gender, $alamat, $kota, $contact, $paypalID, $username);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Customer berhasil diupdate!');</script>";
    echo "<script>window.location.href='manageCustomer.php';</script>";
}

// Get customer details for editing
$username = $_GET['username'];
$query = "SELECT * FROM customer WHERE username=?";
$stmt = $connect->prepare($query);
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();
$customer = $result->fetch_assoc();
$stmt->close();

?>

<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" sizes="16x16" href="../../img/logo.png">
    <title>Edit Customer</title>
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
                                <h5 class="card-title">Edit Customer <?= $customer['username']; ?></h5>

                                <!-- Form to edit a customer -->
                                <form method="POST">
                                    <div class="row">
                                        <!-- Username (Readonly) -->
                                        <div class="col-12 mt-2">
                                            <label for="username" class="form-label">Username</label>
                                            <input class="form-control" id="username" name="username" required readonly value="<?= $customer['username']; ?>">
                                        </div>

                                        <!-- Password (Optional) -->
                                        <div class="col-12 mt-2">
                                            <label for="password" class="form-label">Password (kosongkan jika tidak ingin mengubah)</label>
                                            <input type="password" class="form-control" id="password" name="password">
                                            <input type="hidden" name="passwordOLD" value="<?= $customer['password']; ?>">
                                        </div>

                                        <!-- Nama Lengkap -->
                                        <div class="col-12 mt-2">
                                            <label for="namaLengkap" class="form-label">Nama Lengkap</label>
                                            <input class="form-control" id="namaLengkap" name="namaLengkap" required value="<?= $customer['namaLengkap']; ?>">
                                        </div>

                                        <!-- E-Mail -->
                                        <div class="col-12 mt-2">
                                            <label for="email" class="form-label">E-Mail</label>
                                            <input type="email" class="form-control" id="email" name="email" required value="<?= $customer['email']; ?>">
                                        </div>

                                        <!-- Tanggal Lahir -->
                                        <div class="col-12 mt-2">
                                            <label for="dob" class="form-label">Tanggal Lahir</label>
                                            <input type="date" class="form-control" id="dob" name="dob" required value="<?= $customer['dob']; ?>">
                                        </div>

                                        <!-- Gender -->
                                        <div class="col-12 mt-2">
                                            <label for="gender" class="form-label">Jenis Kelamin</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" id="male" name="gender" value="male" <?= ($customer['gender'] == 'male') ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="male">Male</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" id="female" name="gender" value="female" <?= ($customer['gender'] == 'female') ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="female">Female</label>
                                            </div>
                                        </div>

                                        <!-- Alamat -->
                                        <div class="col-12 mt-2">
                                            <label for="alamat" class="form-label">Alamat</label>
                                            <input type="text" class="form-control" id="alamat" name="alamat" required value="<?= $customer['alamat']; ?>">
                                        </div>

                                        <!-- Kota -->
                                        <div class="col-12 mt-2">
                                            <label for="kota" class="form-label">Kota</label>
                                            <input type="text" class="form-control" id="kota" name="kota" required value="<?= $customer['kota']; ?>">
                                        </div>

                                        <!-- Contact -->
                                        <div class="col-12 mt-2">
                                            <label for="contact" class="form-label">Contact</label>
                                            <input type="text" class="form-control" id="contact" name="contact" required pattern="[0-9]*" value="<?= $customer['contact']; ?>">
                                        </div>

                                        <!-- Paypal ID -->
                                        <div class="col-12 mt-2">
                                            <label for="paypalID" class="form-label">Paypal ID</label>
                                            <input type="text" class="form-control" id="paypalID" name="paypalID" required value="<?= $customer['paypalID']; ?>">
                                        </div>
                                    </div>

                                    <!-- Submit and Reset buttons -->
                                    <div class="text-center mt-4">
                                        <button type="submit" name="submit" class="btn btn-success">Update</button>
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
