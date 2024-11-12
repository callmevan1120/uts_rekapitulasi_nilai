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
    $nilai = isset($_POST["nilai"]) ? trim($_POST["nilai"]) : ''; // Ambil nilai dari form

    // Validasi NIM (hanya angka)
    if (!empty($nim) && !is_numeric($nim)) {
        header("location: gagalregister.php?error=nim_invalid");
        exit;
    }

    // Buat prepared statement untuk update nilai
    $stmt = $sambung->prepare("UPDATE user SET nilai = ? WHERE nim = ?"); // Hanya update nilai berdasarkan NIM
    $stmt->bind_param("is", $nilai, $nim); // 'i' untuk integer dan 's' untuk string

    // Eksekusi query
    if ($stmt->execute()) {
        header("location: dosen.php"); // Redirect setelah berhasil
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
    <title>Dashboard Dosen - Data Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #e0e7ff, #c7d2fe); /* Gradien ungu pastel */
            color: #1a202c;
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

        .header-container {
            text-align: center;
            padding: 20px;
            background-color: #b19cd9; /* Warna ungu pastel */
            color: #ffffff;
            font-size: 28px;
            font-weight: 700;
            letter-spacing: 1.5px;
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            width: 90%;
            max-width: 600px;
        }

        h2 {
            color: #1a202c;
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: 600;
        }

        .form-label {
            color: #7c3aed; /* Ungu medium */
            font-weight: bold;
        }

        .form-select, .form-control {
            border: 1px solid #d8b4fe; /* Ungu pastel */
            border-radius: 12px;
            font-size: 14px;
            padding: 12px;
            transition: border 0.3s, box-shadow 0.3s;
        }

        .form-select:focus, .form-control:focus {
            border: 1px solid #a78bfa; /* Ungu medium */
            outline: none;
            box-shadow: 0 0 8px rgba(167, 139, 250, 0.3);
        }

        .btn {
            background-color: #a78bfa; /* Ungu medium */
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
            background-color: #7c3aed; /* Ungu lebih gelap */
            transform: translateY(-2px);
        }

        .btn-danger {
            background-color: #f57c00; /* Oranye terang untuk logout */
        }

        .btn-danger:hover {
            background-color: #e65100; /* Oranye gelap untuk logout */
        }

        .table {
            margin-top: 20px;
            border-radius: 12px;
            overflow: hidden;
        }

        .table thead th {
            background-color: #ede9fe; /* Ungu lembut */
            color: #1a202c;
            font-weight: 600;
        }

        .table-bordered {
            border: 1px solid #d8b4fe; /* Ungu pastel */
        }

        .table-bordered th, .table-bordered td {
            border: 1px solid #d8b4fe; /* Ungu pastel */
        }
    </style>
</head>
<body>

    <div class="header-container">Dashboard Dosen</div> <!-- Header terpisah di atas form -->

    <div class="container">
        <h2>Ubah Nilai Mahasiswa</h2>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="nama" class="form-label">Nama</label>
                <select class="form-select" id="nama" name="nama" required onchange="updateNim()">
                    <option value="">Pilih Nama</option>
                    <?php
// Ambil data nama dan NIM dari tabel user
$sql = "SELECT nama, nim FROM user";
$result = mysqli_query($sambung, $sql);
if (!$result) {
    die("Query Failed: " . mysqli_error($sambung));
}

// Tampilkan nama dalam dropdown
while ($data = mysqli_fetch_assoc($result)) {
    echo "<option value=\"" . htmlspecialchars($data['nim']) . "\">" . htmlspecialchars($data['nama']) . "</option>";
}
?>
                </select>
                <input type="hidden" id="nim" name="nim" value="">
            </div>
            <div class="mb-3">
                <label for="nilai" class="form-label">Nilai</label>
                <input type="number" class="form-control" id="nilai" name="nilai" required>
            </div>
            <button type="submit" class="btn btn-primary">Ubah Nilai</button>
        </form>

        <h2 class="mt-5">Data Mahasiswa</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>NIM</th>
                    <th>Nilai</th>
                    <th>Kelas</th>
                    <th>Mata Kuliah</th>
                </tr>
            </thead>
            <tbody>
                <?php
// Ambil data dari tabel user
$sql = "SELECT * FROM user";
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function updateNim() {
            const namaSelect = document.getElementById('nama');
            const nimInput = document.getElementById('nim');
            nimInput.value = namaSelect.value;
        }
    </script>
</body>
</html>
<?php
// Tutup koneksi
mysqli_close($sambung); // Pastikan untuk menutup koneksi dengan benar
?>
