<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include "koneksi.php";

// Pastikan pengguna sudah login
if (!isset($_SESSION['id_user'])) {
    echo "<script>alert('Anda harus login terlebih dahulu!'); window.location.href='login/login.php';</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_user = $_SESSION['id_user'];
    $nama_perusahaan = $_POST['nama_perusahaan'];
    $kabupaten = $_POST['kabupaten'];
    $alamat = $_POST['alamat'];
    $tenaga_teknis = $_POST['tenaga_teknis'];
    $kontak_person = $_POST['kontak_person'];
    $nama_direktur = $_POST['nama_direktur'];
    $kontak_direktur = $_POST['kontak_direktur'];

    $sql = "INSERT INTO profil (id_user, nama_perusahaan, kabupaten, alamat, tenaga_teknis, kontak_person, nama_direktur, kontak_direktur) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssssss", $id_user, $nama_perusahaan, $kabupaten, $alamat, $tenaga_teknis, $kontak_person, $nama_direktur, $kontak_direktur);
    
    if ($stmt->execute()) {
        echo "<script>alert('Profil berhasil ditambahkan!'); window.location.href='tampil.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan profil. Silakan coba lagi.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Profil Perusahaan</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

    <div class="container mt-4">
        <h2>Tambah Profil Perusahaan</h2>

        <form method="POST">
            <div class="form-group">
                <label>Nama Perusahaan</label>
                <input type="text" class="form-control" name="nama_perusahaan" required>
            </div>
            <div class="form-group">
                <label>Kabupaten/Kota</label>
                <select class="form-control" name="kabupaten" required>
                    <option value="">-- Pilih Kabupaten/Kota --</option>
                    <option value="Balangan">Balangan</option>
                    <option value="Banjar">Banjar</option>
                    <option value="Barito Kuala">Barito Kuala</option>
                    <option value="Hulu Sungai Selatan">Hulu Sungai Selatan</option>
                    <option value="Hulu Sungai Tengah">Hulu Sungai Tengah</option>
                    <option value="Hulu Sungai Utara">Hulu Sungai Utara</option>
                    <option value="Kotabaru">Kotabaru</option>
                    <option value="Tabalong">Tabalong</option>
                    <option value="Tanah Bumbu">Tanah Bumbu</option>
                    <option value="Tanah Laut">Tanah Laut</option>
                    <option value="Tapin">Tapin</option>
                    <option value="Kota Banjarmasin">Banjarmasin (Kota)</option>
                    <option value="Kota Banjarbaru">Banjarbaru (Kota)</option>
                </select>
            </div>
            <div class="form-group">
                <label>Alamat</label>
                <textarea class="form-control" name="alamat" required></textarea>
            </div>
            <div class="form-group">
                <label>Tenaga Teknis</label>
                <input type="text" class="form-control" name="tenaga_teknis" required>
            </div>
            <div class="form-group">
                <label>Kontak Person</label>
                <input type="text" class="form-control" name="kontak_person" required>
            </div>
            <div class="form-group">
                <label>Nama Direktur</label>
                <input type="text" class="form-control" name="nama_direktur" required>
            </div>
            <div class="form-group">
                <label>Kontak Direktur</label>
                <input type="text" class="form-control" name="kontak_direktur" required>
            </div>
            <button type="submit" class="btn btn-success">Simpan</button>
            <a href="tampil.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>

</body>
</html>
