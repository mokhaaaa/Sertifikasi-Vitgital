<?php 
session_start(); // Memulai session
$title = 'Login Customer';
require 'koneksi.php';

if(isset($_COOKIE['username']) && isset($_COOKIE['key'])){
    $username = $_COOKIE['username'];
    $key = $_COOKIE['key'];

    // Verifikasi cookie
    $result = mysqli_query($connect, "SELECT username FROM customer WHERE username = '$username'");
    $row = mysqli_fetch_assoc($result);

    if($key === hash('sha256', $row['username'])){
        $_SESSION['login'] = true;
        $_SESSION['username'] = $row['username']; // Menyimpan username ke session
    }
}

// SET SESSION DAN REDIRECT LOGIN
if(isset($_SESSION["login"])){
    header("Location: pelanggan/homepageCustomer.php");
    exit;
}

if(isset($_POST["login"])){
    $username = mysqli_real_escape_string($connect, $_POST["username"]); // Menghindari SQL injection
    $password = $_POST["password"];

    $result = mysqli_query($connect, "SELECT * FROM customer WHERE username = '$username'");
    
    if(mysqli_num_rows($result) === 1){
        $row = mysqli_fetch_assoc($result);
        if(password_verify($password, $row["password"])){ // Verifikasi password yang di-hash
            // Set session
            $_SESSION['username'] = $row["username"];
            $_SESSION["login"] = true;

            // Jika remember me diaktifkan
            if(isset($_POST['remember'])){
                setcookie('username', $row['username'], time() + 86400, '/'); // Cookie bertahan 1 hari
                setcookie('key', hash('sha256', $row['username']), time() + 86400, '/');
            }

            header("Location: pelanggan/homepageCustomer.php");
            exit;
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title><?= $title; ?></title>

    <!-- CSS and other resources -->
    <link href="img/logo.png" rel="icon">
  <link href="img/logo.png" rel="apple-touch-icon">
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
    main {
      background-image: url('img/bg.jpg');
      background-size: cover;
    }
  </style>
</head>
<body>
  <main>
    
    <div class="container">
      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
              <div class="card mb-3">
                <div class="card-body">
                <div class="d-flex justify-content-center py-2">
                    <a href="index.html" class="logo d-flex align-items-center w-auto">
                      <img src="img/logo.png" alt="" class="me-2" style="width: 80px; height: auto;">
                    </a>
                  </div>

                  <div class="pt-2 pb-2 text-center">
                    <h4 class="card-title fs-3 text-black">Welcome</h4>
                    <p class="small text-black" style="font-size: 1.1rem;">Login to <span style="color: #2596be;">Vitgital</span></p>
                  </div>

                  <form class="row g-3 needs-validation" method="post" action="">
                    <div class="col-12">
                      <label for="yourUsername" class="form-label">Username</label>
                      <div class="input-group has-validation">
                        <input type="text" name="username" class="form-control" id="yourUsername" required>
                      </div>
                    </div>

                    <div class="col-12">
                      <label for="yourPassword" class="form-label">Password</label>
                      <input type="password" name="password" class="form-control" id="yourPassword" required>
                    </div>

                    <?php if(isset($error)) : ?>
                      <p style="color: red; font-style: italic;"><?= $error; ?></p>
                    <?php endif; ?>

                    <div class="col-12">
                      <button class="btn w-100" style="background-color: #2596be; color: white; margin-left: 0" type="submit" name="login">Login</button>
                    </div>

                    <div class="col-12 text-center">
                      <p class="small mb-0">Need an account? <a href="registrasi.php">Sign up now</a></p>
                      <p class="small mb-0">Admin access? <a href="adminlogin.php">Login as Administrator</a></p>
                    </div>
                  </form>

                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </main>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
