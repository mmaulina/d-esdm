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

// Pastikan ID pembangkit tersedia
if (!isset($_GET['id'])) {
    echo "<script>alert('Data tidak ditemukan!'); window.location.href='?page=pembangkit_admin';</script>";
    exit;
}
$db = new Database();
$conn = $db->getConnection();
$id_pembangkit = intval($_GET['id']);
$query = "SELECT * FROM pembangkit WHERE id = :id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':id', $id_pembangkit, PDO::PARAM_INT);
$stmt->execute();
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location.href='?page=pembangkit_admin';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $fields = ['nama_perusahaan', 'alamat', 'longitude', 'latitude', 'jenis_pembangkit', 'fungsi', 'kapasitas_terpasang', 'daya_mampu_netto', 'jumlah_unit', 'no_unit', 'tahun_operasi', 'status_operasi', 'bahan_bakar_jenis', 'bahan_bakar_satuan'];
    $updateData = [];
    
    foreach ($fields as $field) {
        $updateData[$field] = $_POST[$field] ?? '';
    }
    
    $query = "UPDATE pembangkit SET nama_perusahaan = :nama_perusahaan, alamat = :alamat, longitude = :longitude, latitude = :latitude, jenis_pembangkit = :jenis_pembangkit, fungsi = :fungsi, kapasitas_terpasang = :kapasitas_terpasang, daya_mampu_netto = :daya_mampu_netto, jumlah_unit = :jumlah_unit, no_unit = :no_unit, tahun_operasi = :tahun_operasi, status_operasi = :status_operasi, bahan_bakar_jenis = :bahan_bakar_jenis, bahan_bakar_satuan = :bahan_bakar_satuan WHERE id = :id";
    
    $stmt = $conn->prepare($query);
    
    foreach ($updateData as $key => &$val) {
        $stmt->bindParam(":" . $key, $val, is_numeric($val) ? PDO::PARAM_INT : PDO::PARAM_STR);
    }
    
    $stmt->bindParam(':id', $id_pembangkit, PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil diperbarui!'); window.location.href='?page=pembangkit_admin';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data!');</script>";
    }
}
?>

<div class="container mt-4">
    <h3 class="text-center">Edit Data Pembangkit</h3>
    <hr>
    <div class="card shadow">
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Nama Perusahaan</label>
                    <input type="text" name="nama_perusahaan" class="form-control" value="<?= $data['nama_perusahaan'] ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <input type="text" name="alamat" class="form-control" value="<?= $data['alamat'] ?>" required>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Longitude</label>
                        <input type="text" name="longitude" class="form-control" value="<?= $data['longitude'] ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Latitude</label>
                        <input type="text" name="latitude" class="form-control" value="<?= $data['latitude'] ?>" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Jenis Pembangkit</label>
                    <input type="text" name="jenis_pembangkit" class="form-control" value="<?= $data['jenis_pembangkit'] ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Fungsi</label>
                    <input type="text" name="fungsi" class="form-control" value="<?= $data['fungsi'] ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Kapasitas Terpasang (kW)</label>
                    <input type="text" name="kapasitas_terpasang" class="form-control" value="<?= $data['kapasitas_terpasang'] ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Daya Mampu Netto (kW)</label>
                    <input type="text" name="daya_mampu_netto" class="form-control" value="<?= $data['daya_mampu_netto'] ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Jumlah Unit</label>
                    <input type="number" name="jumlah_unit" class="form-control" value="<?= $data['jumlah_unit'] ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">No. Unit</label>
                    <input type="text" name="no_unit" class="form-control" value="<?= $data['no_unit'] ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tahun Operasi</label>
                    <input type="number" name="tahun_operasi" class="form-control" value="<?= $data['tahun_operasi'] ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status Operasi</label>
                    <input type="text" name="status_operasi" class="form-control" value="<?= $data['status_operasi'] ?>" required>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Jenis Bahan Bakar</label>
                        <input type="text" name="bahan_bakar_jenis" class="form-control" value="<?= $data['bahan_bakar_jenis'] ?>"required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Satuan Bahan Bakar</label>
                        <input type="text" name="bahan_bakar_satuan" class="form-control" value="<?= $data['bahan_bakar_satuan'] ?>" required>
                    </div>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="?page=pembangkit" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
