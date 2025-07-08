<?php
function getHargaPasar($nama) {
    // Dummy data untuk saham & indeks dari dashboard Anda
    $dummy_data = [
        // Saham
        'AAPL' => 200.50, 'MSFT' => 280.10, 'GOOGL' => 130.45, 'AMZN' => 145.30, 'TSLA' => 710.00,
        'META' => 310.22, 'NVDA' => 860.10, 'BRK.A' => 540000.00, 'V' => 255.30, 'JPM' => 190.80,
        // Indeks
        'S&P 500' => 5567.19, 'NASDAQ' => 18403.74, 'Dow Jones' => 39375.87, 'FTSE 100' => 8230.96,
        'Nikkei 225' => 40912.37, 'Hang Seng' => 17892.89, 'Euro Stoxx 50' => 5031.42,
        'ASX 200' => 7900.00, 'DAX' => 18400.00, 'CAC 40' => 7600.00
    ];

    // Jika nama ditemukan dalam dummy
    if (array_key_exists($nama, $dummy_data)) {
        return $dummy_data[$nama];
    }

    // Untuk kripto, gunakan CoinGecko
    $symbol_map = [
        'Bitcoin' => 'bitcoin', 'Ethereum' => 'ethereum', 'Tether' => 'tether',
        'BNB' => 'binancecoin', 'XRP' => 'ripple', 'Cardano' => 'cardano',
        'Solana' => 'solana', 'Dogecoin' => 'dogecoin', 'Polkadot' => 'polkadot',
        'Litecoin' => 'litecoin'
    ];

    if (array_key_exists($nama, $symbol_map)) {
        $symbol = $symbol_map[$nama];
        $url = "https://api.coingecko.com/api/v3/simple/price?ids={$symbol}&vs_currencies=usd";

        $json = @file_get_contents($url); // Gunakan @ untuk hindari warning
        if ($json === false) return null;

        $data = json_decode($json, true);
        if (isset($data[$symbol]['usd'])) {
            return $data[$symbol]['usd'];
        }
    }

    return null;
}
?>
