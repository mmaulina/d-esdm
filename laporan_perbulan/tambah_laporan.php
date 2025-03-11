<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'koneksi.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['id_user'])) {
    echo "<script>alert('Silakan login terlebih dahulu!'); window.location.href='login.php';</script>";
    exit;
}

$id_user = $_SESSION['id_user'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $parameter = $_POST['parameter'];
    $buku_mutu = $_POST['buku_mutu'];
    $hasil = $_POST['hasil'];
    
    $file_laporan = $_FILES['file_laporan'];
    $file_lhu = $_FILES['file_lhu'];
    
    $upload_dir = 'uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $timestamp = date('Ymd-His');
    $file_laporan_ext = pathinfo($file_laporan['name'], PATHINFO_EXTENSION);
    $file_lhu_ext = pathinfo($file_lhu['name'], PATHINFO_EXTENSION);
    
    $file_laporan_name = "file_laporan_bulanan-{$timestamp}.{$file_laporan_ext}";
    $file_lhu_name = "file_LHU-{$timestamp}.{$file_lhu_ext}";
    
    $file_laporan_path = $upload_dir . $file_laporan_name;
    $file_lhu_path = $upload_dir . $file_lhu_name;
    
    move_uploaded_file($file_laporan['tmp_name'], $file_laporan_path);
    move_uploaded_file($file_lhu['tmp_name'], $file_lhu_path);
    
    $query = "INSERT INTO laporan_bulanan (id_user, parameter, buku_mutu, hasil, file_laporan, file_lhu) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isssss", $id_user, $parameter, $buku_mutu, $hasil, $file_laporan_path, $file_lhu_path);
    $stmt->execute();
    
    echo "<script>alert('Laporan berhasil ditambahkan!'); window.location.href='?page=laporan_perbulan';</script>";
}
?>

<div class="container mt-4">
    <h3 class="text-center mb-3">Tambah Laporan Bulanan</h3>
    <hr>
    <div class="card shadow">
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Parameter</label>
                    <input type="text" name="parameter" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Buku Mutu</label>
                    <input type="text" name="buku_mutu" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Hasil</label>
                    <input type="text" name="hasil" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Upload Laporan</label>
                    <input type="file" name="file_laporan" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Upload LHU</label>
                    <input type="file" name="file_lhu" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="?page=laporan_perbulan" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>
