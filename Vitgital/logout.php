<?php
session_start();
session_destroy(); // Menghapus semua session

// Hapus cookie
setcookie('username', '', time() - 3600, '/');
setcookie('key', '', time() - 3600, '/');

header("Location: custlogin.php");
exit;
?>
