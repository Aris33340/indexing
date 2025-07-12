<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$asets = [];
$sql = "SELECT * FROM aset_portofolio WHERE user_id = $user_id";
$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
    $asets[] = $row;
}
// Pisahkan kripto dan non-kripto
$crypto_ids = [];
$non_crypto = [];

foreach ($asets as $aset) {
    $nama = strtolower($aset['nama']);
    if (in_array($nama, [
        's&p 500','nasdaq','dow jones','ftse 100','nikkei 225','hang seng','euro stoxx 50','asx 200','dax','cac 40',
        'aapl','msft','googl','amzn','tsla','meta','nvda','brk.a','v','jpm'
    ])) {
        $non_crypto[] = $aset;
    } else {
        $crypto_ids[] = $nama;
    }
}

// Ambil harga kripto dari CoinGecko
$crypto_data = [];
if (count($crypto_ids) > 0) {
    $ids_str = implode('%2C', $crypto_ids);
    $url = "https://api.coingecko.com/api/v3/coins/markets?vs_currency=usd&ids=$ids_str";
    $response = @file_get_contents($url);
    if ($response !== false) {
        $crypto_data = json_decode($response, true);
    }
}

// Dummy data untuk saham dan indeks
$dummy_data = [
    "S&P 500" => ["price" => 5567.19, "change" => 0.45],
    "NASDAQ" => ["price" => 18403.74, "change" => -0.23],
    "Dow Jones" => ["price" => 39375.87, "change" => 0.17],
    "FTSE 100" => ["price" => 8230.96, "change" => 0.10],
    "Nikkei 225" => ["price" => 40912.37, "change" => -0.20],
    "Hang Seng" => ["price" => 17892.89, "change" => 0.65],
    "Euro Stoxx 50" => ["price" => 5031.42, "change" => 0.08],
    "ASX 200" => ["price" => 7900.00, "change" => 0.12],
    "DAX" => ["price" => 18400.00, "change" => -0.15],
    "CAC 40" => ["price" => 7600.00, "change" => 0.05],
    "AAPL" => ["price" => 199.33, "change" => 1.42],
    "MSFT" => ["price" => 426.21, "change" => -0.80],
    "GOOGL" => ["price" => 182.78, "change" => 0.65],
    "AMZN" => ["price" => 190.44, "change" => 0.35],
    "TSLA" => ["price" => 263.25, "change" => -0.33],
    "META" => ["price" => 490.29, "change" => 0.25],
    "NVDA" => ["price" => 128.91, "change" => 0.76],
    "BRK.A" => ["price" => 625600.00, "change" => -0.11],
    "V" => ["price" => 270.12, "change" => 0.52],
    "JPM" => ["price" => 206.10, "change" => -0.18],
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Portofolio</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #0d0d0d; color: #f5f9fa; margin: 0; padding: 20px; }
        header { background-color: #0c6c79; padding: 15px 30px; border-radius: 0 0 10px 10px; display: flex; justify-content: space-between; align-items: center; }
        header h1 { margin: 0; font-size: 1.8rem; }
        nav a { color: #f5f9fa; margin-left: 20px; text-decoration: none; font-weight: 500; }
        nav a:hover { text-decoration: underline; }
        h2 { color: #0c6c79; margin-top: 30px; }
        table { width: 100%; margin-top: 20px; border-collapse: collapse; background-color: rgba(255,255,255,0.02); border-radius: 10px; overflow: hidden; box-shadow: 0 0 15px rgba(12, 108, 121, 0.5); }
        th, td { padding: 15px 20px; text-align: center; border-bottom: 1px solid rgba(255, 255, 255, 0.1); }
        th { background-color: #0c6c79; color: #f5f9fa; text-transform: uppercase; font-weight: 400; font-size: 1rem; }
        tr:hover { background-color: rgba(12, 108, 121, 0.2); }
        .total-row { background-color: #0c6c79; font-weight: bold; }
        .action-btn { padding: 5px 10px; margin: 0 3px; font-weight: bold; border: none; cursor: pointer; background-color: #0c6c79; color: white; border-radius: 4px; }
        .action-btn:hover { background-color: #095f6c; }
        #assetModal {
            display: none; position: fixed; top: 30%; left: 50%; transform: translate(-50%, -30%);
            background-color: #0d0d0d; color: white; padding: 25px; border-radius: 12px; border: 1px solid #0c6c79;
            box-shadow: 0 0 10px rgba(12, 108, 121, 0.5); z-index: 1000; min-width: 300px;
        }
        #assetModal h3 { margin-top: 0; margin-bottom: 15px; }
        #hargaPasar { margin-bottom: 10px; font-weight: 500; color: #ccc; }
        #assetModal input[type="number"] { padding: 8px; width: 100%; margin-top: 5px; background: #1a1a1a; color: white; border: 1px solid #444; border-radius: 5px; }
        #assetModal button { padding: 8px 15px; margin-right: 10px; background-color: #0c6c79; color: white; border: none; border-radius: 4px; cursor: pointer; }
        #assetModal button:hover { background-color: #095f6c; }
    </style>
</head>
<body>
<header>
    <h1>Portofolio Anda</h1>
    <nav>
        <a href="dashboard.php">Dashboard</a>
        <a href="logout.php">Keluar</a>
    </nav>
</header>

<h2>Ringkasan Aset</h2>

<table>
    <tr>
        <th>Nama Aset</th>
        <th>Jumlah Unit</th>
        <th>Harga Pasar</th>
        <th>Perubahan 24jam</th>
        <th>Nilai (USD)</th>
        <th>Aksi</th>
    </tr>

<?php
$total_usd = 0;
foreach ($asets as $aset) {
    $nama = $aset['nama'];
    $jumlah = $aset['jumlah_unit'];
    $harga = 0;
    $change = 0;

    $found = false;
    foreach ($crypto_data as $crypto) {
        if (strtolower($crypto['id']) == strtolower($nama)) {
            $harga = $crypto['current_price'];
            $change = $crypto['price_change_percentage_24h'];
            $found = true;
            break;
        }
    }

    if (!$found && isset($dummy_data[$nama])) {
        $harga = $dummy_data[$nama]['price'];
        $change = $dummy_data[$nama]['change'];
    }

    $nilai_usd = $jumlah * $harga;
    $total_usd += $nilai_usd;
    $warna = $change >= 0 ? 'lime' : 'red';

    echo "<tr>
        <td>$nama</td>
        <td>" . number_format($jumlah, 4) . "</td>
        <td>$" . number_format($harga, 2) . "</td>
        <td style='color:$warna;'>" . number_format($change, 2) . "%</td>
        <td>$" . number_format($nilai_usd, 2) . "</td>
        <td>
            <button class='action-btn' onclick=\"editAsset('$nama', 'tambah', $harga)\">+</button>
            <button class='action-btn' onclick=\"editAsset('$nama', 'kurangi', $harga)\">−</button>
        </td>
    </tr>";
}
echo "<tr class='total-row'><td colspan='5'>Total Nilai Portofolio</td><td>$" . number_format($total_usd, 2) . "</td></tr>";
?>
</table>

<!-- Modal -->
<div id="assetModal">
    <h3 id="modalTitle"></h3>
    <form id="assetForm" method="POST" action="update_portofolio.php">
        <input type="hidden" name="nama" id="namaAset">
        <input type="hidden" name="aksi" id="aksiAset">
        <div id="hargaPasar"></div>
        <label for="nilai">Jumlah (USD):</label><br>
        <input type="number" name="nilai" id="nilai" required min="1"><br><br>
        <button type="submit">Simpan</button>
        <button type="button" onclick="closeModal()">Batal</button>
    </form>
</div>

<script>
    function editAsset(nama, aksi, harga) {
        document.getElementById('namaAset').value = nama;
        document.getElementById('aksiAset').value = aksi;
        document.getElementById('modalTitle').innerText = (aksi === 'tambah' ? 'Tambah' : 'Kurangi') + ' Aset: ' + nama;
        document.getElementById('hargaPasar').innerText = 'Harga pasar saat ini: $' + parseFloat(harga).toFixed(2);
        document.getElementById('nilai').value = '';
        document.getElementById('assetModal').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('assetModal').style.display = 'none';
    }
</script>
</body>
</html>
