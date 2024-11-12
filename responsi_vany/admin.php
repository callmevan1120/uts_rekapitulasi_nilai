<?php
include "config2.php";

// Memeriksa apakah tabel ada, jika tidak ada, buat tabel
$tableCheckQuery = "SHOW TABLES LIKE 'data_mahasiswa'";
$tableCheckResult = mysqli_query($sambung, $tableCheckQuery);

if (isset($_GET['delete_nim'])) {
    $delete_nim = $_GET['delete_nim'];

    // Query untuk menghapus data berdasarkan NIM
    $deleteQuery = "DELETE FROM user WHERE nim = ?";
    $stmt = $sambung->prepare($deleteQuery);
    $stmt->bind_param("s", $delete_nim);

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil dihapus.'); window.location.href = 'admin.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data.'); window.location.href = 'admin.php';</script>";
    }

    $stmt->close();
}

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

    // Tambahkan data ke tabel user, termasuk kolom kelas dan matakuliah
    $stmt = $sambung->prepare("INSERT INTO user (nama, nim, kelas, matakuliah, nilai) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $nama, $nim, $kelas, $matakuliah, $nilai);

    // Eksekusi query
    if ($stmt->execute()) {
        header("location: admin.php");
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
    <title>Dashboard Admin - Data Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
    body {
        font-family: 'Arial', sans-serif;
        background: #f5f0ff;
        color: #3c0f66;
        animation: fadeIn 1s;
        padding: 20px;
    }

    .dashboard-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
    }

    /* Header Style */
    .header {
        text-align: center;
        font-size: 32px;
        font-weight: 700;
        color: #5e3c8e;
        margin-bottom: 30px;
    }

    /* Form and Table Container */
    .content-container {
        background-color: #ffffff;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    /* Subheading */
    h2 {
        color: #5e3c8e;
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 20px;
    }

    /* Form Controls */
    .form-label {
        color: #5e3c8e;
        font-weight: bold;
    }

    .form-control {
        border-radius: 8px;
        border: 1px solid #d8c7e5;
        padding: 10px;
        font-size: 14px;
    }

    .form-control:focus {
        border-color: #8c5ba4;
        box-shadow: 0 0 6px rgba(140, 91, 164, 0.3);
    }

    /* Buttons */
    .btn-primary {
        background-color: #8c5ba4;
        border: none;
        padding: 12px 20px;
        font-size: 15px;
        font-weight: bold;
        border-radius: 8px;
        transition: background-color 0.3s, transform 0.3s;
        width: 100%;
        max-width: 180px;
    }

    .btn-primary:hover {
        background-color: #723a87;
        transform: translateY(-2px);
    }

    .btn-danger-custom {
        background-color: #ff5252;
        color: white;
        border-radius: 8px;
        font-size: 14px;
        padding: 8px 16px;
        margin-top: 10px;
        display: block;
        text-align: center;
        width: 100%;
        max-width: 120px;
        transition: background-color 0.3s;
    }

    .btn-danger-custom:hover {
        background-color: #e04c4c;
    }

    /* Table Styling */
    .table {
        margin-top: 20px;
        border-radius: 8px;
        overflow: hidden;
    }

    .table thead th {
        background-color: #ede5f6;
        color: #5e3c8e;
        font-weight: bold;
    }

    .table-bordered th, .table-bordered td {
        border: 1px solid #d8c7e5;
        text-align: center;
    }

    /* Logout Link */
    .logout-link {
        text-align: right;
        margin-top: 20px;
    }
</style>

</head>
<body>
    <div class="dashboard-container">
        <!-- Dashboard Header -->
        <div class="header">Dashboard Admin</div>

        <!-- Form to Add Data -->
        <div class="content-container">
            <h2>Tambah Data Mahasiswa</h2>
            <form method="POST" action="admin.php">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="nim" class="form-label">NIM</label>
                        <input type="text" class="form-control" id="nim" name="nim" required>
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
                <button type="submit" class="btn btn-primary">Tambah Data</button>
            </form>
        </div>

        <!-- Data Table -->
        <div class="content-container mt-4">
            <h2>Data Mahasiswa</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>NIM</th>
                        <th>Kelas</th>
                        <th>Mata Kuliah</th>
                        <th>Nilai</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
// Fetch data from user table
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
        echo "<td>" . htmlspecialchars($data['kelas']) . "</td>";
        echo "<td>" . htmlspecialchars($data['matakuliah']) . "</td>";
        echo "<td>" . htmlspecialchars($data['nilai']) . "</td>";
        echo "<td>
        <a href='javascript:void(0)' onclick='confirmDelete(\"" . $data['nim'] . "\")' class='btn btn-danger'><i class='bi bi-trash-fill'></i></a>
        <a href='javascript:void(0)' onclick='openEditModal(\"" . $data['nim'] . "\", \"" . htmlspecialchars($data['nama']) . "\", \"" . htmlspecialchars($data['kelas']) . "\", \"" . htmlspecialchars($data['matakuliah']) . "\")' class='btn btn-warning btn-sm'><i class='bi bi-pencil-fill'></i></a>
        </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='7'>Tidak ada data ditemukan.</td></tr>";
}
?>
                </tbody>
            </table>

            <!-- Logout Button -->
            <div class="logout-link">
                <a href="logot.php" class="btn-danger-custom">Logout</a>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editForm" method="POST" action="edit.php">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Data Mahasiswa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="editNim" name="nim">
                        <div class="mb-3">
                            <label for="editNama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="editNama" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="editKelas" class="form-label">Kelas</label>
                            <input type="text" class="form-control" id="editKelas" name="kelas" required>
                        </div>
                        <div class="mb-3">
                            <label for="editMatakuliah" class="form-label">Mata Kuliah</label>
                            <input type="text" class="form-control" id="editMatakuliah" name="matakuliah" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmDelete(nim) {
            if (confirm("Apakah Anda yakin ingin menghapus data ini?")) {
                window.location.href = "admin.php?delete_nim=" + nim;
            }
        }

        function openEditModal(nim, nama, kelas, matakuliah) {
            document.getElementById("editNim").value = nim;
            document.getElementById("editNama").value = nama;
            document.getElementById("editKelas").value = kelas;
            document.getElementById("editMatakuliah").value = matakuliah;
            new bootstrap.Modal(document.getElementById("editModal")).show();
        }
    </script>
</body>
</html>


<?php
// Tutup koneksi
mysqli_close($sambung); // Pastikan untuk menutup koneksi dengan benar
?>
