<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $aksi = $_POST['aksi'];
    $nilai_usd = floatval($_POST['nilai']);

    if ($nilai_usd <= 0) {
        die("Jumlah USD tidak boleh nol atau negatif.");
    }

    // Ambil harga pasar dari API jika aset adalah kripto
    $dummy = [
        // Saham dan Indeks dummy (pastikan huruf besar sama dengan portofolio.php)
        "S&P 500" => 5567.19, "NASDAQ" => 18403.74, "Dow Jones" => 39375.87,
        "FTSE 100" => 8230.96, "Nikkei 225" => 40912.37, "Hang Seng" => 17892.89,
        "Euro Stoxx 50" => 5031.42, "ASX 200" => 7900.00, "DAX" => 18400.00,
        "CAC 40" => 7600.00, "AAPL" => 199.33, "MSFT" => 426.21, "GOOGL" => 182.78,
        "AMZN" => 190.44, "TSLA" => 263.25, "META" => 490.29, "NVDA" => 128.91,
        "BRK.A" => 625600.00, "V" => 270.12, "JPM" => 206.10
    ];

    $harga_satuan = 0;

    if (isset($dummy[$nama])) {
        $harga_satuan = $dummy[$nama];
    } else {
        // Asumsikan kripto: gunakan CoinGecko
        $id = strtolower($nama);
        $url = "https://api.coingecko.com/api/v3/coins/markets?vs_currency=usd&ids=$id";

        $data = @file_get_contents($url);
        if ($data !== false) {
            $json = json_decode($data, true);
            if (isset($json[0]['current_price'])) {
                $harga_satuan = $json[0]['current_price'];
            }
        }
    }

    if ($harga_satuan <= 0) {
        die("Gagal mengambil harga pasar aset '$nama'.");
    }

    // Konversi USD ke unit aset
    $jumlah_unit = $nilai_usd / $harga_satuan;

    // Simpan ke log histori
    $stmt_log = $conn->prepare("INSERT INTO aset_portofolio_log (nama, jumlah_unit, aksi) VALUES (?, ?, ?)");
    $stmt_log->bind_param("sds", $nama, $jumlah_unit, $aksi);
    $stmt_log->execute();

    // Cek apakah aset sudah ada
    $cek = $conn->prepare("SELECT * FROM aset_portofolio WHERE nama = ?");
    $cek->bind_param("s", $nama);
    $cek->execute();
    $res = $cek->get_result();

    if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $jumlah_lama = $row['jumlah_unit'];
        $jumlah_baru = $aksi === 'tambah' ? $jumlah_lama + $jumlah_unit : $jumlah_lama - $jumlah_unit;

        if ($jumlah_baru <= 0) {
            $hapus = $conn->prepare("DELETE FROM aset_portofolio WHERE nama = ?");
            $hapus->bind_param("s", $nama);
            $hapus->execute();
        } else {
            $update = $conn->prepare("UPDATE aset_portofolio SET jumlah_unit = ? WHERE nama = ?");
            $update->bind_param("ds", $jumlah_baru, $nama);
            $update->execute();
        }
    } else {
        if ($aksi === 'tambah') {
            $insert = $conn->prepare("INSERT INTO aset_portofolio (nama, jumlah_unit) VALUES (?, ?)");
            $insert->bind_param("sd", $nama, $jumlah_unit);
            $insert->execute();
        }
    }

    header("Location: portofolio.php");
    exit;
}
?>
