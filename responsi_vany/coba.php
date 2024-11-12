<?php
include "config2.php";

// Memeriksa apakah tabel ada, jika tidak ada, buat tabel
$tableCheckQuery = "SHOW TABLES LIKE 'data_mahasiswa'";
$tableCheckResult = mysqli_query($sambung, $tableCheckQuery);

// Yang perlu ditambahkan ke dashboard admin
// Memeriksa apakah form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $nama = isset($_POST["nama"]) ? trim($_POST["nama"]) : '';
    $nim = isset($_POST["nim"]) ? trim($_POST["nim"]) : '';
    $nilai = isset($_POST["nilai"]) ? trim($_POST["nilai"]) : '';
    $kelas = isset($_POST["kelas"]) ? $_POST["kelas"] : '';
    $matakuliah = isset($_POST["matakuliah"]) ? $_POST["matakuliah"] : '';

    // Validasi NIM (hanya angka)
    if (!empty($nim) && !is_numeric($nim)) {
        header("location: gagalregister.php?error=nim_invalid");
        exit;
    }

    // Buat prepared statement untuk update nilai
    $stmt = $sambung->prepare("UPDATE user SET nilai = ?, kelas = ?, matakuliah = ? WHERE nim = ?");
    $stmt->bind_param("ssss", $nilai, $kelas, $matakuliah, $nim);

    // Eksekusi query
    if ($stmt->execute()) {
        header("location: dosen.php");
        exit;
    } else {
        header("location: gagalregister.php");
        exit;
    }

    $stmt->close(); // Tutup statement
}
// Yang perlu ditambahkan ke dashboard admin

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Style untuk Body */
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #e0f7fa, #b2ebf2);
            color: #1a202c;
            animation: fadeIn 1s;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Container Utama */
        .container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
            width: 90%;
            max-width: 600px;
        }

        h2 {
            color: #1a202c;
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: 600;
        }

        /* Style untuk Form */
        .form-label {
            color: #00796b;
            font-weight: bold;
        }

        .form-select, .form-control {
            border: 1px solid #b2dfdb;
            border-radius: 12px;
            font-size: 14px;
            padding: 12px;
            transition: border 0.3s, box-shadow 0.3s;
        }

        .form-select:focus, .form-control:focus {
            border: 1px solid #009688;
            outline: none;
            box-shadow: 0 0 8px rgba(0, 150, 136, 0.3);
        }

        .btn {
            color: #ffffff;
            border: none;
            padding: 12px;
            font-size: 15px;
            border-radius: 12px;
            transition: background-color 0.3s, transform 0.3s;
            cursor: pointer;
            width: 100%;
            max-width: 180px;
        }

        .btn:hover {
            background-color: #00796b;
            transform: translateY(-2px);
        }

        .btn-primary {
            background-color: #009688;

        }

        .table-container {
            width: 90%;
            max-width: 600px; /* Sama seperti container form */
            margin: 0 auto;
        }

        /* Style untuk Tabel */
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
        }

        .table thead th {
            background-color: #fffde7;
            color: #1a202c;
            font-weight: 600;
            padding: 12px;
            text-align: left;
        }

        .table td, .table th {
            padding: 10px;
            border: 1px solid #b2dfdb;
            text-align: left;
        }

        /* Style jika data kosong */
        .table tbody tr td[colspan] {
            text-align: center;
            font-style: italic;
            color: #757575;
        }
    </style>
</head>
<body>


<!-- Yang perlu ditambahkan ke dashboard admin -->
<div class="container">
    <h2>Ubah Nilai Mahasiswa</h2>
    <form method="POST" action="dosen.php">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="nama" class="form-label">Nama</label>
                <select class="form-select" id="nama" name="nama" required onchange="updateNim()">
                    <option value="">Pilih Nama</option>
                    <?php
$sql = "SELECT * FROM user";
$result = mysqli_query($sambung, $sql);
if (!$result) {
    die("Query Failed: " . mysqli_error($sambung));
}
while ($data = mysqli_fetch_assoc($result)) {
    echo "<option value=\"" . htmlspecialchars($data['nim']) . "\">" . htmlspecialchars($data['nama']) . "</option>";
}
?>
                </select>
                <input type="hidden" id="nim" name="nim" value="">
            </div>
            <div class="col-md-6 mb-3">
                <label for="nilai" class="form-label">Nilai</label>
                <input type="number" class="form-control" id="nilai" name="nilai" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="kelas" class="form-label">Kelas</label>
                <input type="text" class="form-control" id="kelas" name="kelas" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="matakuliah" class="form-label">Mata Kuliah</label>
                <input type="text" class="form-control" id="matakuliah" name="matakuliah" required>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Ubah Nilai</button>
    </form>
</div>
<!-- Yang perlu ditambahkan ke dashboard admin -->

<div class="table-container">
    <h2 class="mt-5">Data Mahasiswa</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nomor</th>
                <th>Nama</th>
                <th>NIM</th>
                <th>Nilai</th>
                <th>Kelas</th>
                <th>Mata Kuliah</th>
            </tr>
        </thead>
        <tbody>
            <?php
$sql = "SELECT nama, nim, nilai, kelas, matakuliah FROM user";
$result = mysqli_query($sambung, $sql);
if (!$result) {
    die("Query Failed: " . mysqli_error($sambung));
}

$nomor = 1;
if (mysqli_num_rows($result) > 0) {
    while ($data = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $nomor++ . "</td>";
        echo "<td>" . htmlspecialchars($data['nama']) . "</td>";
        echo "<td>" . htmlspecialchars($data['nim']) . "</td>";
        echo "<td>" . htmlspecialchars($data['nilai']) . "</td>";
        echo "<td>" . htmlspecialchars($data['kelas']) . "</td>";
        echo "<td>" . htmlspecialchars($data['matakuliah']) . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='6'>Tidak ada data ditemukan.</td></tr>";
}
?>
        </tbody>
    </table>
    <a href="logot.php" class="btn btn-danger mt-3">Logout</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script
