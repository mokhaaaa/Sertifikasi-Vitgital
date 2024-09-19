<?php
$connect = mysqli_connect("localhost:3307", "root", "", "vitgital");

// Cek apakah koneksi berhasil
if (!$connect) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Cek apakah fungsi query sudah ada
if (!function_exists('query')) {
    function query($query) {
        global $connect;
        $result = mysqli_query($connect, $query);
        $rows = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }
}

// Cek apakah fungsi submit_guestbook sudah ada
if (!function_exists('submit_guestbook')) {
    function submit_guestbook($name, $email, $message) {
        global $connect;
        
        // Hitung total entri saat ini di tabel guest
        $result = mysqli_query($connect, "SELECT COUNT(*) as total FROM guest");
        $row = mysqli_fetch_assoc($result);
        $nextId = $row['total'] + 1; // Menentukan ID berikutnya

        // Format ID Guest Book sebagai guest2024xx
        $guestId = 'guest2024' . str_pad($nextId, 2, '0', STR_PAD_LEFT);

        // Query untuk memasukkan data
        $query = "INSERT INTO guest (idGB, namaGB, emailGB, pesanGB) VALUES ('$guestId', '$name', '$email', '$message')";

        // Eksekusi query dan cek apakah berhasil
        if (mysqli_query($connect, $query)) {
            return true;
        } else {
            // Tampilkan pesan error jika gagal
            echo "Error: " . mysqli_error($connect);
            return false;
        }
    }
}
?>
