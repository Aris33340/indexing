<?php
include 'koneksi.php';
header('Content-Type: application/json');

$result = mysqli_query($conn, "SELECT * FROM aset_portofolio");
$portofolio = [];

while ($row = mysqli_fetch_assoc($result)) {
    $nama = $row['nama'];
    $jumlah = $row['jumlah'];
    $harga = 0;
    $perubahan = 0;

    // Ambil harga pasar terkini (dummy/hardcoded, bisa ganti API)
    $dummyHarga = [
        'Bitcoin' => [ 'harga' => 67000, 'perubahan' => 1.5 ],
        'Ethereum' => [ 'harga' => 3500, 'perubahan' => 2.1 ],
        'S&P 500' => [ 'harga' => 5567, 'perubahan' => 0.45 ],
        'NASDAQ' => [ 'harga' => 18400, 'perubahan' => -0.23 ],
        'AAPL' => [ 'harga' => 195, 'perubahan' => 0.75 ],
        'MSFT' => [ 'harga' => 335, 'perubahan' => -0.42 ],
        'GOOGL' => [ 'harga' => 128, 'perubahan' => 1.15 ],
        'TSLA' => [ 'harga' => 220, 'perubahan' => -1.30 ]
    ];

    if (isset($dummyHarga[$nama])) {
        $harga = $dummyHarga[$nama]['harga'];
        $perubahan = $dummyHarga[$nama]['perubahan'];
    }

    $total = $jumlah;
    $portofolio[] = [
        'nama' => $nama,
        'jumlah' => $jumlah,
        'harga' => $harga,
        'total' => $jumlah,
        'perubahan' => $perubahan
    ];
}

echo json_encode($portofolio);
?>
