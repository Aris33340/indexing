<?php
// index.php

// Di masa depan, bagian ini bisa kamu isi dengan koneksi database atau session login.
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Indexing</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
  <header class="header">
    <a href="/" class="logo-link"> 
      <div class="logo">
        <img src="img/logo.png" alt="Indexing Logo" class="logo-img">
        <span>indexing</span>
      </div>
    </a>
    <nav class="nav">
      <a href="index.php" class="execpt-signup">Home</a>
      <a href="#instrumen" class="execpt-signup">Instrumen</a> <!-- Scroll langsung ke section instrumen -->
      <a href="login.php" class="execpt-signup">Login</a>
      <a href="signup.php" class="signup-btn">Sign Up</a>
    </nav>
  </header>

  <section class="hero">
  <div class="particles"></div>
  <div class="hero-content">
    <h1>Mengenal <br>Index <br>Instrumen <br>Keuangan</h1>
    <p>Platform terpercaya untuk membantu memahami dalam memilih instrumen keuangan </p>
    <a href="signup.php" class="cta-btn">Mulai Sekarang</a>
  </div>
</section>


  <h2 class="fitur-title">Fitur Utama</h2>
  <section class="fitur">
    <div class="fitur-box">
      <h3>Diversifikasi</h3>
      <p>Memungkinkan penyebaran risiko melalui berbagai jenis instrumen keuangan seperti saham, obligasi, dan crypto.</p>
    </div>
    <div class="fitur-box">
      <h3>Keamanan & Regulasi</h3>
      <p>Perusahaan diawasi oleh otoritas regulator untuk memastikan keamanan data dan kepatuhan hukum.</p>
    </div>
    <div class="fitur-box">
      <h3>Kecepatan Informasi</h3>
      <p>Akses mudah dan cepat ke instrumen yang Anda inginkan.</p>
    </div>
  </section>

  <h2 class="instrumen-title" id="instrumen">Instrumen</h2>
  <section class="instrumen">
  <a href="login.php" class="instrumen-item">
    <h3 class="judul">Crypto</h3>
    <img src="img/btc-min.png" alt="Crypto" />
    <span class="detail-btn">Lihat Detail</span>
  </a>

  <a href="login.php" class="instrumen-item">
    <h3 class="judul">Indeks Pasar</h3>
    <img src="img/obligasi-min.png" alt="Indeks Pasar" />
    <span class="detail-btn">Lihat Detail</span>
  </a>

  <a href="login.php" class="instrumen-item">
    <h3 class="judul">Saham</h3>
    <img src="img/saham-min.png" alt="Saham" />
    <span class="detail-btn">Lihat Detail</span>
  </a>
  </section>

  <section class="tentang-kami">
  <h2 class="judul-tentang">Tentang Kami</h2>
  <p class="deskripsi-tentang">
    Indexing berkomitmen menyediakan informasi yang akurat dan terpercaya tentang produk keuangan, membantu Anda membuat keputusan investasi yang cerdas dan aman.
  </p>

  <div class="kontak">
    <div class="kontak-item">
      <img src="img/logo.png" alt="Indexing Logo" class="logo-img">
      <span>indexing</span>
    </div>
    <div class="kontak-item">
      <i class="fab fa-instagram"></i>
      <span>indexing</span>
    </div>
    <div class="kontak-item">
      <i class="fab fa-whatsapp"></i>
      <span>+629999999</span>
    </div>
    <div class="kontak-item">
      <i class="fas fa-envelope"></i>
      <span>222312997@stis.ac.id</span>
    </div>
  </div>
</section>
<footer class="footer">
  <p>ARIS KRISTIAWAN &copy; 2025</p>
</footer>

  <script>
document.addEventListener("DOMContentLoaded", () => {
  const canvas = document.getElementById("aura-canvas");
  const ctx = canvas.getContext("2d");

  function resizeCanvas() {
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
  }
  resizeCanvas();
  window.addEventListener("resize", resizeCanvas);

  let particles = [];

  for (let i = 0; i < 100; i++) {
    particles.push({
      x: canvas.width * 0.75 + (Math.random() - 0.5) * 200,
      y: canvas.height * 0.5 + (Math.random() - 0.5) * 200,
      radius: Math.random() * 50 + 50,
      color: `hsla(${200 + Math.random() * 60}, 100%, 70%, 0.08)`
    });
  }

  function animate() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    for (let p of particles) {
      ctx.beginPath();
      ctx.arc(p.x, p.y, p.radius, 0, 2 * Math.PI);
      ctx.fillStyle = p.color;
      ctx.fill();
    }
    requestAnimationFrame(animate);
  }

  animate();
});
</script>
</body>
</html>
