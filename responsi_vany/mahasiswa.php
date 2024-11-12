<?php
include "config2.php";

// Memeriksa apakah tabel ada, jika tidak ada, buat tabel
$tableCheckQuery = "SHOW TABLES LIKE 'data_mahasiswa'";
$tableCheckResult = mysqli_query($sambung, $tableCheckQuery);

// Memeriksa apakah form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $nama = isset($_POST["nama"]) ? trim($_POST["nama"]) : '';
    $nim = isset($_POST["nim"]) ? trim($_POST["nim"]) : '';
    $kelas = isset($_POST["kelas"]) ? trim($_POST["kelas"]) : '';
    $matakuliah = isset($_POST["matakuliah"]) ? trim($_POST["matakuliah"]) : '';

    $nilai = ''; // Jika nilai tidak diinputkan, bisa diisi default atau kosong

    // Validasi NIM (hanya angka)
    if (!empty($nim) && !is_numeric($nim)) {
        header("location: gagalregister.php?error=nim_invalid");
        exit;
    }

    // Buat prepared statement
    $stmt = $sambung->prepare("INSERT INTO user (nama, nim, nilai) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $nama, $nim, $nilai); // ssi: string, string, integer (nilai sebagai integer, atau ganti sesuai tipe data)

    // Eksekusi query
    if ($stmt->execute()) {
        header("location: mahasiswa.php");
        exit;
    } else {
        header("location: gagalregister.php");
        exit;
    }

    $stmt->close(); // Tutup statement
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #e6e6fa, #b8b8d9); /* Gradien ungu lembut */
            color: #4b0082; /* Warna utama lebih gelap */
            animation: fadeIn 1s;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .dashboard-header {
            text-align: center;
            font-size: 32px;
            font-weight: 700;
            color: #6a0dad; /* Warna ungu cerah */
            margin-top: 20px;
        }

        .container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
            width: 100%;
            max-width: 800px; /* Lebar tabel lebih besar */
        }

        h2 {
            color: #4b0082;
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: 600;
        }

        .form-label {
            color: #6a0dad; /* Ungu medium */
            font-weight: bold;
        }

        .form-select, .form-control {
            border: 1px solid #d8bfd8; /* Ungu lembut */
            border-radius: 12px;
            font-size: 14px;
            padding: 12px;
            transition: border 0.3s, box-shadow 0.3s;
        }

        .form-select:focus, .form-control:focus {
            border: 1px solid #ba55d3; /* Ungu lebih cerah */
            outline: none;
            box-shadow: 0 0 8px rgba(186, 85, 211, 0.3);
        }

        .btn {
            background-color: #ba55d3; /* Ungu cerah */
            color: #ffffff;
            border: none;
            padding: 12px;
            font-size: 15px;
            border-radius: 12px;
            transition: background-color 0.3s, transform 0.3s;
            cursor: pointer;
            width: 100%;
        }

        .btn:hover {
            background-color: #6a0dad; /* Ungu medium */
            transform: translateY(-2px);
        }

        .btn-danger {
            background-color: #dd6b20; /* Oranye terbakar */
        }

        .btn-danger:hover {
            background-color: #c05621; /* Oranye lebih gelap */
        }

        .table-container {
            margin-top: 30px;
        }

        .table {
            border-radius: 12px;
            overflow: hidden;
            width: 100%;
        }

        .table thead th {
            background-color: #e6e6fa; /* Ungu lembut */
            color: #4b0082;
            font-weight: 600;
        }

        .table-bordered > tbody > tr > td {
            background-color: #ffffff;
        }

        .table-bordered {
            border: 1px solid #d8bfd8;
        }

        .table-bordered th, .table-bordered td {
            border: 1px solid #d8bfd8;
        }

        .error-message {
            color: #c53030; /* Merah gelap */
            font-weight: bold;
            background-color: #fbd38d; /* Kuning lembut */
            padding: 12px;
            border-radius: 12px;
            border: 1px solid #f6ad55; /* Oranye lembut */
            margin-top: 10px;
        }

        a {
            color: #ba55d3; /* Ungu cerah */
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
            color: #6a0dad; /* Ungu medium */
        }
    </style>
</head>
<body>

<div class="dashboard-header">Dashboard Mahasiswa</div>

<div class="container">
    <h2>Data Mahasiswa</h2>
    <div class="table-container">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nomor</th>
                    <th>Nama</th>
                    <th>NIM</th>
                    <th>Nilai</th>
                    <th>Kelas</th>
                    <th>Matakuliah</th>
                </tr>
            </thead>
            <tbody>
                <?php
// Ambil data dari tabel user
$sql = "SELECT * FROM user"; // Pastikan nama tabel sudah benar
$result = mysqli_query($sambung, $sql);

// Cek apakah query berhasil
if (!$result) {
    die("Query Failed: " . mysqli_error($sambung));
}

$nomor = 1; // Inisialisasi nomor
if (mysqli_num_rows($result) > 0) {
    while ($data = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $nomor++ . "</td>"; // Tampilkan nomor urut
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
    </div>
    <a href="logot.php" class="btn btn-danger mt-3">Logout</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Tutup koneksi
mysqli_close($sambung); // Pastikan untuk menutup koneksi dengan benar
?>
