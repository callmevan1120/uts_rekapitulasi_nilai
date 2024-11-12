<?php
// Di controler1.php
session_start();
include("config.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Periksa kredensial di database (gunakan prepared statements!)
    $query = "SELECT * FROM user WHERE username = ? AND password = ?";
    $stmt = $sambung->prepare($query);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['akses'] = true;
        $_SESSION['role'] = $user['role'];

        // Redirect berdasarkan role
        switch ($_SESSION['role']) {
            case 'admin':
                header('Location: ./admin.php');
                break;
            case 'mahasiswa':
                header('Location: ./mahasiswa.php');
                break;
            case 'dosen':
                header('Location: ./dosen.php');
                break;
            default:
                header('Location: ./dashboard.php');
        }
        exit();
    } else {
        header('Location: login.php?pesan=gagal');
        exit();
    }
}
