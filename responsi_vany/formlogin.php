<?php
session_start();
$akses = @$_SESSION['akses'];

if (isset($_GET['pesan'])) {
    if ($_GET['pesan'] == "gagal") {
        echo '<div class="error-message">Login gagal! Username dan password salah!</div>';
    } else if ($_GET['pesan'] == "logout") {
        echo '<div class="error-message">Anda telah berhasil logout</div>';
    } else if ($_GET['pesan'] == "belum_login") {
        echo '<div class="error-message">Anda harus login untuk mengakses halaman admin</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Login Page</title>
    <style>
        body {
    font-family: 'Arial', sans-serif;
    background: linear-gradient(135deg, #d1c4e9, #b39ddb); /* Nuansa ungu lembut */
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    animation: fadeIn 1s;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

.wrapper {
    display: flex;
    align-items: center;
    background-color: #ffffff;
    padding: 20px;
    border-radius: 20px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
    max-width: 720px;
    width: 90%;
    transition: transform 0.2s ease-in-out;
}

.wrapper:hover {
    transform: scale(1.03);
}

.image {
    flex: 1;
    max-width: 50%;
    height: auto;
}

.form-container {
    flex: 1;
    max-width: 360px;
    padding: 20px;
    text-align: center;
}

.logo {
    font-size: 48px;
    color: #7e57c2; /* Ungu tua */
    margin-bottom: 25px;
}

.head {
    color: #4e3d83; /* Ungu lebih gelap */
    margin-bottom: 20px;
    font-size: 22px;
    font-weight: 600;
}

.input-box {
    margin-bottom: 20px;
    text-align: left;
}

.input-label {
    margin-bottom: 8px;
    font-weight: bold;
    color: #673ab7; /* Ungu utama */
}

input[type="text"], input[type="password"] {
    width: 100%;
    padding: 12px;
    border: 1px solid #b39ddb; /* Ungu lebih terang */
    border-radius: 12px;
    font-size: 14px;
    transition: border 0.3s, box-shadow 0.3s;
}

input[type="text"]:focus, input[type="password"]:focus {
    border: 1px solid #7e57c2; /* Warna ungu saat fokus */
    outline: none;
    box-shadow: 0 0 8px rgba(126, 87, 194, 0.3); /* Bayangan ungu */
}

.input-box input[type="text"],
.input-box input[type="password"],
.submit-btn {
    display: block;
    width: 100%; /* Ambil seluruh lebar container */
    padding: 12px; /* Konsisten dengan ukuran padding */
    font-size: 14px;
    border: 1px solid #b39ddb;
    border-radius: 12px;
    box-sizing: border-box; /* Pastikan padding dan border dihitung dalam lebar */
    margin: 0; /* Menghapus margin agar ukuran konsisten */
}

.submit-btn {
    background-color: #7e57c2; /* Warna ungu utama */
    color: #fff;
    cursor: pointer;
    margin-top: 20px;
    border: none;
    transition: background-color 0.3s, transform 0.3s;
}

.submit-btn:hover {
    background-color: #5e35b1; /* Warna ungu lebih gelap */
    transform: translateY(-2px);
}

form {
    margin: 0;
    padding: 0;
}


.info {
    margin-top: 20px;
    font-size: 14px;
    color: #4e3d83; /* Ungu gelap */
}

.info a {
    color: #7e57c2; /* Ungu utama */
    text-decoration: none;
    transition: color 0.3s;
}

.info a:hover {
    text-decoration: underline;
    color: #5e35b1; /* Ungu lebih gelap saat hover */
}

    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Left side image -->
        <img src="https://images.pexels.com/photos/3184287/pexels-photo-3184287.jpeg" alt="Sample Image" class="image">

        <!-- Right side form container -->
        <div class="form-container">
            <div class="logo">
                <i class="fa-brands fa-github"></i>
            </div>
            <h1 class="head">Masuk</h1>
            <div class="login-wrapper">
                <form method="POST" action="controler.php">
                    <div class="input-box">
                        <div class="input-label">Username</div>
                        <input type="text" name="username" placeholder="Masukkan Username" required />
                    </div>

                    <div class="input-box">
                        <div class="input-label">Kata Sandi</div>
                        <input type="password" name="password" placeholder="***" required />
                    </div>
                    <button class="submit-btn" type="submit">Login</button>
                </form>
            </div>

            <div class="info">
                <span><a href="formregister.php">Buat akun baru.</a></span>
            </div>
        </div>
    </div>
</body>
</html>
