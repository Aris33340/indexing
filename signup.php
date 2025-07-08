<?php
$host = "localhost";
$user = "root";
$password = "";
$db = "pbwakhir"; // Ganti sesuai database kamu

$conn = mysqli_connect($host, $user, $password, $db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $telepon = mysqli_real_escape_string($conn, $_POST['telepon']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    if ($password != $confirm) {
        $error = "Konfirmasi password tidak cocok.";
    } else {
        // Cek apakah email sudah terdaftar
        $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
        if (mysqli_num_rows($check) > 0) {
            $error = "Email sudah terdaftar.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO users (nama, email, telepon, password) VALUES ('$nama', '$email', '$telepon', '$hashed_password')";
            if (mysqli_query($conn, $query)) {
                $success = "Pendaftaran berhasil, silakan login.";
            } else {
                $error = "Terjadi kesalahan saat menyimpan data.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Akun - TradewithSuli</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #000;
            color: #fff;
            display: flex;
            height: 100vh;
        }
        .left {
            flex: 1;
            background: url('img/bullbear.jpg') no-repeat center center;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        .right {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 20px;
        }
        .form-container {
            width: 100%;
            max-width: 400px;
        }
        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
            background: #1a1a1a;
            color: #fff;
        }
        button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 5px;
            background: linear-gradient(90deg, #4AE7F3 0%, #DA6CFF 100%);
            color: #fff;
            font-weight: bold;
            cursor: pointer;
            margin-top: 10px;
            transition: transform 0.3s ease, box-shadow 0.3s ease, background 0.3s ease;
        }
        button:hover {
        transform: scale(1.05);
        box-shadow: 0 0 20px rgba(39, 100, 255, 0.6), 0 0 40px rgba(138, 43, 226, 0.4);
        background: linear-gradient(90deg, #8a2be2, #2764ff);
        }
        a {
            color: linear-gradient(90deg, #4AE7F3 0%, #DA6CFF 100%);
            text-decoration: none;
            font-size: 14px;
        }
        .error { color: red; font-size: 14px; margin-bottom: 10px; }
        .success { color: green; font-size: 14px; margin-bottom: 10px; }
    </style>
</head>
<body>

<div class="left">
    <!-- Disini bisa kamu pasang gambar seperti di desain -->
</div>

<div class="right">
    <div class="form-container">
        <h2>Daftar Akun</h2>
        <?php 
        if ($error != "") echo "<div class='error'>$error</div>"; 
        if ($success != "") echo "<div class='success'>$success</div>"; 
        ?>
        <form method="POST">
            <label>Nama Lengkap</label>
            <input type="text" name="nama" placeholder="Masukkan nama lengkap kamu" required>

            <label>Email</label>
            <input type="email" name="email" placeholder="email@gmail.com" required>

            <label>No Telpon (Whatsapp)</label>
            <input type="text" name="telepon" placeholder="+62 Masukkan nomor whatsapp kamu" required>

            <label>Password</label>
            <input type="password" name="password" placeholder="Buat password akun kamu" required>

            <label>Confirm Password</label>
            <input type="password" name="confirm" placeholder="Konfirmasi password kamu" required>

            <div style="margin: 10px 0;">
                <input type="checkbox" required> Saya setuju dengan Syarat & Kebijakan Privasi
            </div>

            <button type="submit">Daftar</button>
        </form>
        <div style="margin-top:10px;">
            Sudah punya akun? <a href="login.php">Masuk disini</a>
        </div>
    </div>
</div>

</body>
</html>
