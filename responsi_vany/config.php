<?php

$server = "localhost";
$user = "root";
$password = "";
$nama_database = "responsi_1";

$sambung = mysqli_connect($server, $user, $password, $nama_database);
if (!$sambung) {
    die("Ada masalah koneksi database: " . mysqli_connect_error());
}
