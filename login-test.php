<?php
// Tampilkan error agar tidak silent
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Tes koneksi database
$conn = mysqli_connect("localhost", "projec15_root", "@kaesquare123", "pbwakhir");

if (!$conn) {
    echo "Gagal koneksi database: " . mysqli_connect_error();
    exit;
}
?>

<!DOCTYPE html>
<html>
<head><title>Login Tes</title></head>
<body>
<h1>Halaman Login Berhasil Dimuat</h1>
</body>
</html>
