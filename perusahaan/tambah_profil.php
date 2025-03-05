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

    // Fungsi untuk sanitasi input
    function sanitize_input($data)
    {
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }

    $nama_perusahaan = sanitize_input($_POST['nama_perusahaan']);
    $kabupaten = sanitize_input($_POST['kabupaten']);
    $alamat = sanitize_input($_POST['alamat']);
    $jenis_usaha = sanitize_input($_POST['jenis_usaha']);
    $no_telp_kantor = sanitize_input($_POST['no_telp_kantor']);
    $tenaga_teknik = sanitize_input($_POST['tenaga_teknik']);
    $nama = sanitize_input($_POST['nama']);
    $no_hp = sanitize_input($_POST['no_hp']);
    $email = sanitize_input($_POST['email']);

    // Validasi nomor telepon hanya angka
    if (!preg_match('/^[0-9]+$/', $no_telp_kantor) || !preg_match('/^[0-9]+$/', $no_hp)) {
        echo "<script>alert('Kontak hanya boleh berisi angka!');</script>";
        exit();
    }

    // Validasi Kabupaten hanya dari daftar yang diperbolehkan
    $valid_kabupaten = [
        "Balangan",
        "Banjar",
        "Barito Kuala",
        "Hulu Sungai Selatan",
        "Hulu Sungai Tengah",
        "Hulu Sungai Utara",
        "Kotabaru",
        "Tabalong",
        "Tanah Bumbu",
        "Tanah Laut",
        "Tapin",
        "Kota Banjarmasin",
        "Kota Banjarbaru"
    ];
    if (!in_array($kabupaten, $valid_kabupaten)) {
        echo "<script>alert('Kabupaten tidak valid!');</script>";
        exit();
    }

    // Query menggunakan prepared statement
    $sql = "INSERT INTO profil (id_user, nama_perusahaan, kabupaten, alamat, jenis_usaha, no_telp_kantor, tenaga_teknik, nama, no_hp, email) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssssss", $id_user, $nama_perusahaan, $kabupaten, $alamat, $jenis_usaha, $no_telp_kantor, $tenaga_teknik, $nama, $no_hp, $email);

    if ($stmt->execute()) {
        echo "<script>alert('Profil berhasil ditambahkan!'); window.location.href='?page=profil_perusahaan';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan profil. Silakan coba lagi.');</script>";
    }
}
?>

<!-- TAMBAH PROFIL PERUSAHAAN -->
<div class="container mt-4">
    <h2>Tambah Profil Perusahaan</h2>

    <form method="POST">
        <div class="form-group">
            <label>Nama Perusahaan</label>
            <input type="text" class="form-control" name="nama_perusahaan" required maxlength="100">
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
            <textarea class="form-control" name="alamat" required maxlength="200"></textarea>
        </div>

        <div class="form-group">
            <label>Jenis Usaha</label>
            <input type="text" class="form-control" name="jenis_usaha" required maxlength="100">
        </div>

        <div class="form-group">
            <label>Nomor Telepon Kantor</label>
            <input type="number" class="form-control" name="kontak_person" required maxlength="15" pattern="[0-9]+">
        </div>

        <div class="form-group">
            <label>Tenaga Teknik</label>
            <input type="text" class="form-control" name="tenaga_teknik" required maxlength="100">
        </div>

        <div class="card-header mt-3">
            <h6>Kontak Person</h6>
        </div>

        <div class="form-group">
            <label>Nama</label>
            <input type="text" class="form-control" name="nama" required maxlength="100">
        </div>

        <div class="form-group">
            <label>Nomor HP</label>
            <input type="number" class="form-control" name="no_hp" required maxlength="15" pattern="[0-9]+">
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="text" class="form-control" name="email" required maxlength="100">
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="?page=profil_perusahaan" class="btn btn-secondary">Batal</a>
    </form>
</div>