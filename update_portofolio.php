<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $aksi = $_POST['aksi'];
    $nilai_usd = floatval($_POST['nilai']);

    // Ambil harga pasar
    $harga = 0;
    if (!in_array(strtolower($nama), ['s&p 500','nasdaq','dow jones','ftse 100','nikkei 225','hang seng','euro stoxx 50','asx 200','dax','cac 40',
        'aapl','msft','googl','amzn','tsla','meta','nvda','brk.a','v','jpm'])) {
        // Kripto: ambil dari CoinGecko
        $url = "https://api.coingecko.com/api/v3/simple/price?ids=$nama&vs_currencies=usd";
        $response = @file_get_contents($url);
        if ($response) {
            $data = json_decode($response, true);
            $harga = $data[$nama]['usd'] ?? 0;
        }
    } else {
        // Dummy harga untuk saham dan indeks
        $dummy = [
            "s&p 500"=>5567.19, "nasdaq"=>18403.74, "dow jones"=>39375.87, "ftse 100"=>8230.96,
            "nikkei 225"=>40912.37, "hang seng"=>17892.89, "euro stoxx 50"=>5031.42, "asx 200"=>7900,
            "dax"=>18400, "cac 40"=>7600, "aapl"=>199.33, "msft"=>426.21, "googl"=>182.78, "amzn"=>190.44,
            "tsla"=>263.25, "meta"=>490.29, "nvda"=>128.91, "brk.a"=>625600, "v"=>270.12, "jpm"=>206.10
        ];
        $harga = $dummy[strtolower($nama)] ?? 0;
    }

    if ($harga <= 0) {
        die("Gagal mengambil harga pasar aset '$nama'.");
    }

    $jumlah_unit = $nilai_usd / $harga;
    if ($aksi == "kurangi") $jumlah_unit = -$jumlah_unit;

    // Simpan ke log
    $stmt = mysqli_prepare($conn, "INSERT INTO aset_portofolio_log (nama, jumlah_unit, aksi, waktu, user_id) VALUES (?, ?, ?, NOW(), ?)");
    mysqli_stmt_bind_param($stmt, "sdsi", $nama, $jumlah_unit, $aksi, $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Perbarui portofolio
    $query = "SELECT * FROM aset_portofolio WHERE nama = '$nama' AND user_id = $user_id";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        $update = "UPDATE aset_portofolio SET jumlah_unit = jumlah_unit + $jumlah_unit WHERE nama = '$nama' AND user_id = $user_id";
        mysqli_query($conn, $update);
    } else {
        $insert = "INSERT INTO aset_portofolio (nama, jumlah_unit, user_id) VALUES ('$nama', $jumlah_unit, $user_id)";
        mysqli_query($conn, $insert);
    }

    header("Location: portofolio.php");
    exit();
}
?>
