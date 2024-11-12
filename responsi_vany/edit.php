<?php
include "config2.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nim = $_POST["nim"];
    $nama = $_POST["nama"];
    $kelas = $_POST["kelas"];
    $matakuliah = $_POST["matakuliah"];

    $stmt = $sambung->prepare("UPDATE user SET nama = ?, kelas = ?, matakuliah = ? WHERE nim = ?");
    $stmt->bind_param("ssss", $nama, $kelas, $matakuliah, $nim);

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil diperbarui.'); window.location.href = 'admin.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data.'); window.location.href = 'admin.php';</script>";
    }

    $stmt->close();
    mysqli_close($sambung);
}
