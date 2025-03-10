<?php
include 'koneksi.php'; // Pastikan file koneksi ke database sudah ada

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bulan = htmlspecialchars($_POST['bulan']);
    $nama_perusahaan = htmlspecialchars($_POST['nama_perusahaan']);
    $volume = htmlspecialchars($_POST['volume']);
    $produksi_listrik = htmlspecialchars($_POST['produksi_listrik']);
    $susut_jaringan = htmlspecialchars($_POST['susut_jaringan']);
    $konsumsi_listrik = htmlspecialchars($_POST['konsumsi_listrik']);

    $sql = "INSERT INTO users (username, email, password, role, status) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $username, $email, $password, $role, $status);

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil ditambahkan!'); window.location='?page=pengguna';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan data!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Pengguna</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Tambah Laporan Persemester</h4>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Semester</label>
                        <select name="semester" class="form-control" required>
                            <option value="">Pilih Semester</option>
                            <option value="01">Januari - Juni</option>
                            <option value="02">Juli - Desember</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Perusahaan</label>
                        <input type="nama_perusahaan" name="nama_perusahaan" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Volume Bahan Bakar</label>
                        <input type="volume" name="volume" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Produksi Listrik</label>
                        <input type="produksi_listrik" name="produksi_listrik" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Susut Jaringan (bila ada) (kWh)</label>
                        <!-- tidak pakai required supaya bisa dikosongkan -->
                        <input type="produksi_listrik" name="produksi_listrik" class="form-control"> 
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Konsumsi Listrik</label>
                        <input type="konsumsi_listrik" name="konsumsi_listrik" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-success">Simpan</button>
                    <a href="?page=pengguna" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</body>

</html>