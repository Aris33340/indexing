<?php
$host = "localhost";
$user = "projec15_root";
$password = "@kaesquare123";
$db = "projec15_pbwakhir";

$conn = mysqli_connect($host, $user, $password, $db);

// Tampilkan error jika koneksi gagal
if (!$conn) {
    echo "Koneksi gagal: " . mysqli_connect_error();
    exit();
}
?>

