<?php
session_start();

// Hapus data sesi pengguna yang sedang login
unset($_SESSION['username']);

// Hancurkan sesi
session_destroy();

// Arahkan pengguna ke halaman login
header('Location: ../index.php');
exit;
?>
