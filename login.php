<?php
session_start();
include 'koneksi.php'; // Memanggil file koneksi

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = $_POST['password'];

    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($pass, $row['password'])) {
            $_SESSION['email'] = $email;
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Email tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Masuk Akun - indexing</title>
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
        input[type="email"], input[type="password"] {
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
        .error {
            color: red;
            margin-bottom: 10px;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="left">
    <!-- Tempat untuk gambar background -->
</div>

<div class="right">
    <div class="form-container">
        <h2>Masuk Akun</h2>
        <?php if ($error != "") echo "<div class='error'>$error</div>"; ?>
        <form method="POST">
            <label>Email</label>
            <input type="email" name="email" placeholder="email@gmail.com" required>

            <label>Password</label>
            <input type="password" name="password" placeholder="Masukkan password Anda" required>

            <div style="margin: 10px 0;">
                <input type="checkbox" name="remember"> Ingat saya
            </div>

            <button type="submit">Masuk</button>
        </form>
        <div style="margin-top:10px;">
            <a href="#">Lupa Password?</a> | <a href="signup.php">Daftar disini</a>
        </div>
    </div>
</div>

</body>
</html>
