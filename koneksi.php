<?php
$host = "localhost";
$user = "projec15_root";
$password = "@kaesquare123";
$db = "projec15_pbwakhir";

$conn = mysqli_connect($host, $user, $password, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
