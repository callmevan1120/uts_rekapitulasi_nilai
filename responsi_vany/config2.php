<?php

$server = "localhost";
$user = "root";
$password = ""; // Pastikan password sesuai jika ada
$nama_database = "responsi_2";

$sambung = mysqli_connect($server, $user, $password, $nama_database); // Pastikan ada parameter password
if (!$sambung) {
    die("Ada masalah koneksi database: " . mysqli_connect_error());
}
