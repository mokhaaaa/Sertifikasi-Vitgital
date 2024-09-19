<?php

$title = 'Registrasi';

require 'koneksi.php';

if (isset($_POST["submit"])) {
    if (registrasi($_POST) > 0) {
        echo "<script>
                alert('User baru berhasil ditambahkan');
                window.location.href = 'custlogin.php';
              </script>";
    } else {
        echo "<script>
                alert('User baru gagal ditambahkan');
              </script>";
    }
}

// Function untuk registrasi
function registrasi($data) {
    global $connect;

    $username = htmlspecialchars(strtolower(stripslashes($data["username"])));
    $password = mysqli_real_escape_string($connect, $data["password"]);
    $password2 = mysqli_real_escape_string($connect, $data["password2"]);

    // Cek apakah username sudah ada di database
    $result = mysqli_query($connect, "SELECT username FROM customer WHERE username = '$username'");
    if (mysqli_fetch_assoc($result)) {
        echo "<script>
                alert('Username sudah terdaftar!');
              </script>";
        return false;
    }

    // Cek konfirmasi password
    if ($password !== $password2) {
        echo "<script>
                alert('Konfirmasi password tidak sesuai!');
              </script>";
        return false;
    }

    $namaLengkap = htmlspecialchars($data["namaLengkap"]);
    $email = htmlspecialchars(strtolower(stripslashes($data["email"])));
    $dob = htmlspecialchars($data["dob"]);
    $gender = $data["gender"];
    $alamat = htmlspecialchars($data["alamat"]);
    $kota = htmlspecialchars($data["kota"]);
    $contact = htmlspecialchars($data["contact"]);
    $paypalID = htmlspecialchars($data["paypalID"]);

    // Enkripsi password
    $password = password_hash($password, PASSWORD_DEFAULT);

    // Query untuk memasukkan data user baru ke database
    $query = "INSERT INTO customer (username, password, namaLengkap, email, dob, gender, alamat, kota, contact, paypalID) 
              VALUES('$username', '$password', '$namaLengkap', '$email', '$dob', '$gender', '$alamat', '$kota', '$contact', '$paypalID')";
    
    mysqli_query($connect, $query);

    return mysqli_affected_rows($connect);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title><?= $title; ?></title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="img/logo.png" rel="icon">
  <link href="img/logo.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

  <!-- Custom CSS -->
  <style>
    body, html {
      height: 100%;
      margin: 0;
      overflow: hidden; /* Prevent overall page scrolling */
    }

    main {
      background-image: url('img/bg.jpg');
      background-size: cover;
      background-position: center;
      background-attachment: fixed; /* Fix the background */
      height: 100%;
    }

    .scrollable-form-container {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      padding: 20px;
    }

    .scrollable-form {
      overflow-y: auto; 
      max-height: 90vh; 
      width: 90%; 
      max-width: 600px; 
      padding: 20px;
      background: white;
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .scrollable-form::-webkit-scrollbar {
      width: 8px; /* Customize scrollbar width */
    }

    .scrollable-form::-webkit-scrollbar-thumb {
      background-color: #2596be; /* Customize scrollbar thumb color */
      border-radius: 10px;
    }

    .card-title {
      color: #2596be;
    }

    .btn-primary {
      background-color: #2596be;
      border-color: #2596be;
    }

    .btn-primary:hover {
      background-color: #1f7aad;
      border-color: #1f7aad;
    }
  </style>
</head>

<body>

  <main>
    <div class="scrollable-form-container">
      <div class="scrollable-form col-lg-4 col-md-6">

        <div class="card ms-3">

          <div class="card-body">

            <!-- Logo inside the card -->
            <div class="d-flex justify-content-center py-2">
              <a href="index.html" class="logo d-flex align-items-center w-auto">
                <img src="img/logo.png" alt="" style="width: 80px; height: auto;">
              </a>
            </div>

            <div class="pt-2 pb-2 text-center">
              <h4 class="card-title fs-3 text-black">Welcome</h4>
              <p class="small text-black" style="font-size: 1.1rem;">Create an Account for <span style="color: #2596be;">Vitgital</span></p>
            </div>

            <form class="row g-3 needs-validation" method="POST">
              <div class="col-12">
                <label for="yourUsername" class="form-label">Username</label>
                <div class="input-group has-validation">
                  <input type="text" name="username" class="form-control" id="yourUsername" required>
                  <div class="invalid-feedback">Please choose a username.</div>
                </div>
              </div>

              <div class="col-12">
                <label for="yourPassword" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" id="yourPassword" required>
                <div class="invalid-feedback">Please enter your password!</div>
              </div>

              <div class="col-12">
                <label for="yourPassword2" class="form-label">Retype Password</label>
                <input type="password" name="password2" class="form-control" id="yourPassword2" required>
                <div class="invalid-feedback">Please retype your password!</div>
              </div>

              <div class="col-12">
                <label for="yourName" class="form-label">Name</label>
                <input type="text" name="namaLengkap" class="form-control" id="yourName" required>
                <div class="invalid-feedback">Please enter your full name!</div>
              </div>

              <div class="col-12">
                <label for="yourEmail" class="form-label">E-mail</label>
                <input type="email" name="email" class="form-control" id="yourEmail" required>
                <div class="invalid-feedback">Please enter a valid email address!</div>
              </div>

              <div class="col-12">
                <label for="dob" class="form-label">Date of Birth</label>
                <input type="date" name="dob" class="form-control" id="dob" required>
                <div class="invalid-feedback">Please enter a valid date of birth!</div>
              </div>

              <div class="col-12">
                <label for="gender" class="form-check-label">Gender</label><br>
                <input class="form-check-input" type="radio" name="gender" id="genderMale" value="male" required> Male
                <input class="form-check-input" type="radio" name="gender" id="genderFemale" value="female" required> Female
                <div class="invalid-feedback">Please select your gender!</div>
              </div>

              <div class="col-12">
                <label for="alamat" class="form-label">Address</label>
                <input type="text" name="alamat" class="form-control" id="alamat" required>
                <div class="invalid-feedback">Please enter your address!</div>
              </div>

              <div class="col-12">
                <label for="kota" class="form-label">City</label>
                <input type="text" name="kota" class="form-control" id="kota" required>
                <div class="invalid-feedback">Please enter your city!</div>
              </div>

              <div class="col-12">
                <label for="contact" class="form-label">Contact</label>
                <input type="number" name="contact" class="form-control" id="contact" required>
                <div class="invalid-feedback">Please enter your contact number!</div>
              </div>

              <div class="col-12">
                <label for="paypalID" class="form-label">Paypal ID</label>
                <input type="text" name="paypalID" class="form-control" id="paypalID" required>
                <div class="invalid-feedback">Please enter your Paypal ID!</div>
              </div>

              <div class="text-center">
                <button type="submit" class="btn btn-primary w-100" style="margin-left: 0;" name="submit">Submit</button>
                <button type="reset" class="btn btn-secondary mt-2 w-100" style="margin-left: 0;" >Reset</button>
              </div>

              <div class="col-12 text-center mt-3">
                <p class="small mb-0">Already have an account? <a href="custlogin.php">Log in</a></p>
              </div>

            </form>

          </div>
        </div>

      </div>
    </div>
  </main><!-- End #main -->

  <!-- Vendor JS Files -->
  <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/chart.js/chart.umd.js"></script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>
  <script src="assets/vendor/quill/quill.min.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>
