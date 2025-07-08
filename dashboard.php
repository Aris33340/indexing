<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Indexing - Kripto, Indeks Pasar, Saham</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body { font-family: 'Inter', sans-serif; background-color: #0d0d0d; color: #f5f9fa; margin: 0; padding: 20px; }
    header { display: flex; justify-content: space-between; align-items: center; background-color: #0c6c79; padding: 15px 30px; border-radius: 0 0 10px 10px; }
    header h1 { margin: 0; font-size: 1.8rem; }
    nav a { color: #f5f9fa; margin-left: 20px; text-decoration: none; font-weight: 500; }
    nav a:hover { text-decoration: underline; }
    .tabs { margin-top: 30px; }
    .tab-button { background-color: #0c6c79; border: none; color: white; padding: 10px 20px; margin-right: 10px; cursor: pointer; border-radius: 5px; }
    .tab-button.active { background-color: #095f6c; }
    table { width: 100%; margin-top: 20px; border-collapse: collapse; background-color: rgba(255, 255, 255, 0.02); border-radius: 10px; overflow: hidden; box-shadow: 0 0 15px rgba(12, 108, 121, 0.5); }
    th, td { padding: 15px 20px; text-align: center; border-bottom: 1px solid rgba(255, 255, 255, 0.1); }
    th { background-color: #0c6c79; color: #f5f9fa; text-transform: uppercase; font-weight: 400; font-size: 1rem; }
    tr:hover { background-color: rgba(12, 108, 121, 0.2); }
    img { width: 40px; height: 40px; border-radius: 50%; }
    .error { color: red; text-align: center; margin-top: 20px; }
    .action-btn { padding: 5px 10px; margin: 0 3px; font-weight: bold; border: none; cursor: pointer; background-color: #0c6c79; color: white; border-radius: 4px; }
    .action-btn:hover { background-color: #095f6c; }
    .index-box {
      background-color: #111;
      border: 1px solid #0c6c79;
      padding: 20px;
      border-radius: 10px;
      min-width: 200px;
      flex: 1;
      text-align: center;
    }
    .index-box h3 { margin-top: 0; color: #0c6c79; }
    .index-box p { font-size: 1.5rem; margin: 5px 0; }
    #assetModal {
      display: none;
      position: fixed;
      top: 30%;
      left: 50%;
      transform: translate(-50%, -30%);
      background-color: #0d0d0d;
      color: white;
      padding: 25px;
      border-radius: 12px;
      border: 1px solid #0c6c79;
      box-shadow: 0 0 10px rgba(12, 108, 121, 0.5);
      z-index: 1000;
      min-width: 300px;
    }
    #assetModal h3 { margin-top: 0; margin-bottom: 15px; }
    #hargaPasar { margin-bottom: 10px; font-weight: 500; color: #ccc; }
    #assetModal input[type="number"] {
      padding: 8px;
      width: 100%;
      margin-top: 5px;
      background: #1a1a1a;
      color: white;
      border: 1px solid #444;
      border-radius: 5px;
    }
    #assetModal button {
      padding: 8px 15px;
      margin-right: 10px;
      background-color: #0c6c79;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
    #assetModal button:hover { background-color: #095f6c; }
  </style>
</head>
<body>
  <header>
    <h1>Indexing Instrumen Keuangan</h1>
    <nav>
      <a href="portofolio.php">Portofolio</a>
      <a href="#" onclick="logout()">Keluar</a>
    </nav>
  </header>

  <section style="margin-top: 30px;">
    <h2 style="color:#0c6c79; margin-bottom: 10px;">Indeks Komposit Instrumen</h2>
    <div id="indexSummary" style="display: flex; gap: 20px; flex-wrap: wrap;">
      <div id="cryptoIndexBox" class="index-box"></div>
      <div id="marketIndexBox" class="index-box"></div>
      <div id="stockIndexBox" class="index-box"></div>
    </div>
  </section>

  <div class="tabs">
    <button class="tab-button active" onclick="loadData('kripto', event)">Kripto</button>
    <button class="tab-button" onclick="loadData('indeks', event)">Indeks Pasar</button>
    <button class="tab-button" onclick="loadData('saham', event)">Saham</button>
  </div>

  <div class="content">
    <div id="errorMessage" class="error"></div>
    <table id="dataTable">
      <thead>
        <tr id="tableHeader"></tr>
      </thead>
      <tbody id="tableBody"></tbody>
    </table>
  </div>

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
    async function loadIndexes() {
      try {
        const res = await fetch('https://api.coingecko.com/api/v3/coins/markets?vs_currency=usd&per_page=10');
        const data = await res.json();
        const cryptoAvg = data.reduce((acc, item) => acc + item.current_price, 0) / data.length;
        document.getElementById('cryptoIndexBox').innerHTML = `<h3>Kripto Index</h3><p>$${cryptoAvg.toFixed(2)}</p>`;
      } catch {
        document.getElementById('cryptoIndexBox').innerHTML = `<h3>Kripto Index</h3><p style="color:red;">Gagal</p>`;
      }

      const market = [5567.19,18403.74,39375.87,8230.96,40912.37,17892.89,5031.42,7900,18400,7600];
      const marketAvg = market.reduce((a,b)=>a+b,0)/market.length;
      document.getElementById('marketIndexBox').innerHTML = `<h3>Indeks Pasar</h3><p>$${marketAvg.toFixed(2)}</p>`;

      const stocks = [199.33,426.21,182.78,190.44,263.25,490.29,128.91,625600.00,270.12,206.10];
      const stockAvg = stocks.reduce((a,b)=>a+b,0)/stocks.length;
      document.getElementById('stockIndexBox').innerHTML = `<h3>Saham Index</h3><p>$${stockAvg.toFixed(2)}</p>`;
    }

    async function loadData(type, event) {
      document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
      if (event) event.target.classList.add('active');
      document.getElementById('errorMessage').innerHTML = '';

      const headerRow = document.getElementById('tableHeader');
      const body = document.getElementById('tableBody');
      headerRow.innerHTML = '';
      body.innerHTML = '';

      if (type === 'kripto') {
        try {
          const url = 'https://api.coingecko.com/api/v3/coins/markets?vs_currency=usd&per_page=10';
          const res = await fetch(url);
          const data = await res.json();
          headerRow.innerHTML = '<th>No</th><th>Logo</th><th>Nama</th><th>Symbol</th><th>Harga (USD)</th><th>Perubahan (%)</th><th>Aksi</th>';
          data.forEach((item, i) => {
            body.innerHTML += `<tr>
              <td>${i + 1}</td>
              <td><img src="${item.image}" alt="${item.name}"></td>
              <td>${item.name}</td>
              <td>${item.symbol.toUpperCase()}</td>
              <td>$${item.current_price.toFixed(2)}</td>
              <td style="color:${item.price_change_percentage_24h >= 0 ? 'lime' : 'red'}">${item.price_change_percentage_24h.toFixed(2)}%</td>
              <td>
                <button class='action-btn' onclick="editAsset('${item.id}', 'tambah', ${item.current_price})">+</button>
                <button class='action-btn' onclick="editAsset('${item.id}', 'kurangi', ${item.current_price})">&minus;</button>
              </td>
            </tr>`;
          });
        } catch {
          document.getElementById('errorMessage').innerText = 'Gagal memuat data kripto. Pastikan koneksi internet.';
        }
      } else if (type === 'indeks') {
        const dummyIndices = [
          { name: "S&P 500", price: 5567.19, change: 0.45 },
          { name: "NASDAQ", price: 18403.74, change: -0.23 },
          { name: "Dow Jones", price: 39375.87, change: 0.17 },
          { name: "FTSE 100", price: 8230.96, change: 0.10 },
          { name: "Nikkei 225", price: 40912.37, change: -0.20 },
          { name: "Hang Seng", price: 17892.89, change: 0.65 },
          { name: "Euro Stoxx 50", price: 5031.42, change: 0.08 },
          { name: "ASX 200", price: 7900.00, change: 0.12 },
          { name: "DAX", price: 18400.00, change: -0.15 },
          { name: "CAC 40", price: 7600.00, change: 0.05 }
        ];
        headerRow.innerHTML = '<th>No</th><th>Nama Indeks</th><th>Harga (USD)</th><th>Perubahan (%)</th><th>Aksi</th>';
        dummyIndices.forEach((item, i) => {
          body.innerHTML += `<tr>
            <td>${i + 1}</td>
            <td>${item.name}</td>
            <td>$${item.price.toFixed(2)}</td>
            <td style="color:${item.change >= 0 ? 'lime' : 'red'}">${item.change.toFixed(2)}%</td>
            <td>
              <button class='action-btn' onclick="editAsset('${item.name}', 'tambah', ${item.price})">+</button>
              <button class='action-btn' onclick="editAsset('${item.name}', 'kurangi', ${item.price})">&minus;</button>
            </td>
          </tr>`;
        });
      } else if (type === 'saham') {
        const symbols = ['AAPL','MSFT','GOOGL','AMZN','TSLA','META','NVDA','BRK.A','V','JPM'];
        headerRow.innerHTML = '<th>No</th><th>Symbol</th><th>Harga (USD)</th><th>Perubahan (%)</th><th>Aksi</th>';
        symbols.forEach((symbol, i) => {
          const price = [199.33, 426.21, 182.78, 190.44, 263.25, 490.29, 128.91, 625600.00, 270.12, 206.10][i];
          const change = [1.42, -0.80, 0.65, 0.35, -0.33, 0.25, 0.76, -0.11, 0.52, -0.18][i];
          body.innerHTML += `<tr>
            <td>${i + 1}</td>
            <td>${symbol}</td>
            <td>$${price.toFixed(2)}</td>
            <td style="color:${change >= 0 ? 'lime' : 'red'}">${change.toFixed(2)}%</td>
            <td>
              <button class='action-btn' onclick="editAsset('${symbol}', 'tambah', ${price})">+</button>
              <button class='action-btn' onclick="editAsset('${symbol}', 'kurangi', ${price})">&minus;</button>
            </td>
          </tr>`;
        });
      }
    }

    function editAsset(nama, aksi, harga = null) {
      document.getElementById('namaAset').value = nama;
      document.getElementById('aksiAset').value = aksi;
      document.getElementById('modalTitle').innerText = (aksi === 'tambah' ? 'Tambah' : 'Kurangi') + ' Aset: ' + nama;
      if (harga !== null) {
        document.getElementById('hargaPasar').innerText = 'Harga pasar saat ini: $' + parseFloat(harga).toFixed(2);
      } else {
        document.getElementById('hargaPasar').innerText = '';
      }
      document.getElementById('nilai').value = '';
      document.getElementById('assetModal').style.display = 'block';
    }

    function closeModal() {
      document.getElementById('assetModal').style.display = 'none';
    }

    function logout() {
      window.location.href = 'logout.php';
    }

    loadIndexes();
    loadData('kripto');
  </script>
</body>
</html>
