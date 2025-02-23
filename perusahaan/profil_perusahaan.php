<?php
// session_start(); // Mulai session
include '../koneksi.php'; 

// // Pastikan pengguna sudah login
// if (!isset($_SESSION['id_user'])) {
//     echo "<script>alert('Anda harus login terlebih dahulu!'); window.location.href='login/login.php';</script>";
//     exit();
// }

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $id_user = $_SESSION['id_user']; // Ambil id_user dari session
//     $nama_perusahaan = $_POST['nama_perusahaan'];
//     $kabupaten = $_POST['kabupaten'];
//     $alamat = $_POST['alamat'];
//     $tenaga_teknis = $_POST['tenaga_teknis'];
//     $kontak_person = $_POST['kontak_person'];
//     $nama_direktur = $_POST['nama_direktur'];
//     $kontak_direktur = $_POST['kontak_direktur'];

//     $sql = "INSERT INTO profil (id_user, nama_perusahaan, kabupaten, alamat, tenaga_teknis, kontak_person, nama_direktur, kontak_direktur) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param("isssssss", $id_user, $nama_perusahaan, $kabupaten, $alamat, $tenaga_teknis, $kontak_person, $nama_direktur, $kontak_direktur);
    
//     if ($stmt->execute()) {
//         echo "<script>alert('Profil perusahaan berhasil disimpan!'); window.location.href='profil_perusahaan.php';</script>";
//     } else {
//         echo "Error: " . $stmt->error;
//     }
// }
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Perusahaan</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">Dinas Energi dan Sumber Daya Mineral</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="perusahaan/tampil.php">Profile</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Settings</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="login/login.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <h2>Profil Perusahaan</h2>
        <form method="POST">
            <div class="form-group">
                <label>Nama Perusahaan</label>
                <input type="text" name="nama_perusahaan" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Kabupaten</label>
                <select name="kabupaten" class="form-control" required>
                    <option value="Kota Banjarmasin">Banjarmasin</option>
                    <option value="Kota Banjarbaru">Banjarbaru</option>
                    <option value="Banjar">Banjar</option>
                    <option value="Barito Kuala">Barito Kuala</option>
                    <option value="Tapin">Tapin</option>
                    <option value="Hulu Sungai Selatan">Hulu Sungai Selatan</option>
                    <option value="Hulu Sungai Tengah">Hulu Sungai Tengah</option>
                    <option value="Hulu Sungai Utara">Hulu Sungai Utara</option>
                    <option value="Tabalong">Tabalong</option>
                    <option value="Balangan">Balangan</option>
                    <option value="Kotabaru">Kotabaru</option>
                    <option value="Tanah Laut">Tanah Laut</option>
                    <option value="Tanah Bumbu">Tanah Bumbu</option>
                </select>
            </div>
            <div class="form-group">
                <label>Alamat</label>
                <input type="text" name="alamat" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Tenaga Teknis</label>
                <input type="text" name="tenaga_teknis" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Kontak Person</label>
                <input type="text" name="kontak_person" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Nama Direktur</label>
                <input type="text" name="nama_direktur" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Kontak Direktur</label>
                <input type="text" name="kontak_direktur" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</body>
</html>
