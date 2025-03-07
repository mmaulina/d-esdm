<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'koneksi.php'; // Pastikan koneksi ke database

// Pastikan pengguna sudah login
if (!isset($_SESSION['id_user'])) {
    echo "<script>alert('Silakan login terlebih dahulu!'); window.location.href='login.php';</script>";
    exit;
}

$id_user = $_SESSION['id_user']; // Ambil id_user dari sesi

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitasi input
    $nama_perusahaan = mysqli_real_escape_string($conn, trim($_POST['nama_perusahaan']));
    $alamat = mysqli_real_escape_string($conn, trim($_POST['alamat']));
    $longitude = mysqli_real_escape_string($conn, trim($_POST['longitude']));
    $latitude = mysqli_real_escape_string($conn, trim($_POST['latitude']));
    $jenis_pembangkit = mysqli_real_escape_string($conn, trim($_POST['jenis_pembangkit']));
    $fungsi = mysqli_real_escape_string($conn, trim($_POST['fungsi']));
    $kapasitas_terpasang = mysqli_real_escape_string($conn, trim($_POST['kapasitas_terpasang']));
    $daya_mampu_netto = mysqli_real_escape_string($conn, trim($_POST['daya_mampu_netto']));
    $jumlah_unit = intval($_POST['jumlah_unit']);
    $no_unit = mysqli_real_escape_string($conn, trim($_POST['no_unit']));
    $tahun_operasi = intval($_POST['tahun_operasi']);
    $status_operasi = mysqli_real_escape_string($conn, trim($_POST['status_operasi']));
    $bahan_bakar_jenis = mysqli_real_escape_string($conn, trim($_POST['bahan_bakar_jenis']));
    $bahan_bakar_satuan = mysqli_real_escape_string($conn, trim($_POST['bahan_bakar_satuan']));

    // Query dengan prepared statement
    $query = "INSERT INTO pembangkit (id_user, nama_perusahaan, alamat, longitude, latitude, jenis_pembangkit, fungsi, kapasitas_terpasang, daya_mampu_netto, jumlah_unit, no_unit, tahun_operasi, status_operasi, bahan_bakar_jenis, bahan_bakar_satuan) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )";
    
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "issssssssssisss", $id_user, $nama_perusahaan, $alamat, $longitude, $latitude, $jenis_pembangkit, $fungsi, $kapasitas_terpasang, $daya_mampu_netto, $jumlah_unit, $no_unit, $tahun_operasi, $status_operasi, $bahan_bakar_jenis, $bahan_bakar_satuan);
    
    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Data berhasil ditambahkan!'); window.location.href='?page=pembangkit';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan data!');</script>";
    }
    mysqli_stmt_close($stmt);
}
?>

<div class="container mt-4">
    <h3 class="text-center">Tambah Data Pembangkit</h3>
    <hr>
    <form method="POST">
    <div class="mb-3">
            <label class="form-label">Nama Perusahaan</label>
            <input type="text" name="nama_perusahaan" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Alamat</label>
            <input type="text" name="alamat" class="form-control" required>
        </div>
        <div class="row">
            <div class="col-md-6">
                <label class="form-label">Longitude</label>
                <input type="text" name="longitude" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Latitude</label>
                <input type="text" name="latitude" class="form-control" required>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Jenis Pembangkit</label>
            <input type="text" name="jenis_pembangkit" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Fungsi</label>
            <input type="text" name="fungsi" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Kapasitas Terpasang (kW)</label>
            <input type="text" name="kapasitas_terpasang" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Daya Mampu Netto (kW)</label>
            <input type="text" name="daya_mampu_netto" class="form-control"  required>
        </div>
        <div class="mb-3">
            <label class="form-label">Jumlah Unit</label>
            <input type="number" name="jumlah_unit" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">No. Unit</label>
            <input type="text" name="no_unit" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Tahun Operasi</label>
            <input type="number" name="tahun_operasi" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Status Operasi</label>
            <input type="text" name="status_operasi" class="form-control" required>
        </div>
        <div class="row">
            <div class="col-md-6">
                <label class="form-label">Jenis Bahan Bakar</label>
                <input type="text" name="bahan_bakar_jenis" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Satuan Bahan Bakar</label>
                <input type="text" name="bahan_bakar_satuan" class="form-control" required>
            </div>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Simpan</button>
        <a href="?page=pembangkit" class="btn btn-secondary mt-3">Batal</a>
    </form>
</div>
