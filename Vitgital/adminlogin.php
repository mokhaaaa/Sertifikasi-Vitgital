<?php 

$title = 'Login Admin';

session_set_cookie_params(3600); // 1 hour session
session_start();
require 'koneksi.php';

// Set Cookie "Remember Me"
if(isset($_COOKIE['username']) && isset($_COOKIE['key'])){
    $username = $_COOKIE['username'];
    $key = $_COOKIE['key'];

    $stmt = $connect->prepare("SELECT username FROM admin WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if($key === hash('sha256', $row['username'])){
        $_SESSION['admin_login'] = true;  
    }
}

// Redirect jika sudah login
if(isset($_SESSION["admin_login"])){
    header("Location: admin/pages/manageproduk.php");
    exit;
}

// Proses form login
if(isset($_POST["login"])){
    $username = htmlspecialchars($_POST["username"]);  // Sanitasi input
    $password = $_POST["password"];

    $stmt = $connect->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows === 1){
        $row = $result->fetch_assoc();
        if(password_verify($password, $row["password"])){
            $_SESSION['username'] = $row["username"];
            $_SESSION["admin_login"] = true;  // Set session admin

            if(isset($_POST['remember'])){
                setcookie('username', $row['username'], time() + (86400 * 30));  // Cookie 30 hari
                setcookie('key', hash('sha256', $row['username']), time() + (86400 * 30));
            }

            // Redirect to admin dashboard
            header("Location: admin/pages/dashboard.php");
            exit;
        }
    }

    $error = true;  // Tampilkan pesan error jika gagal login
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
                    <p class="small text-black" style="font-size: 1.1rem;">Login to <span style="color: #2596be;">Admin Vitgital</span></p>
                  </div>

                  <form class="row g-3 needs-validation" method="post">
                    <div class="col-12">
                      <label for="yourUsername" class="form-label">Username</label>
                      <div class="input-group has-validation">
                        <input type="text" name="username" class="form-control" id="yourUsername" required>
                        <div class="invalid-feedback">Please enter your username.</div>
                      </div>
                    </div>

                    <div class="col-12">
                      <label for="yourPassword" class="form-label">Password</label>
                      <input type="password" name="password" class="form-control" id="yourPassword" required>
                      <div class="invalid-feedback">Please enter your password!</div>
                    </div>

                    <?php if(isset($error)) : ?>
                        <p style="color: red; font-style: italic;">Username / Password salah!</p>
                    <?php endif; ?>

                    <div class="col-12">
                      <button class="btn w-100" style="background-color: #2596be; color: white; margin-left: 0;" type="submit" name="login">Login</button>
                    </div>

                    <div class="col-12 text-center">
                      <p class="small mb-0">Regular user? <a href="custlogin.php">Login here</a></p>
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
  <script src="assets/js/main.js"></script>

</body>

</html>
