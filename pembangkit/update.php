<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'koneksi.php'; // Pastikan koneksi ke database menggunakan PDO

// Pastikan pengguna sudah login
if (!isset($_SESSION['id_user'])) {
    echo "<script>alert('Silakan login terlebih dahulu!'); window.location.href='login.php';</script>";
    exit;
}

// Pastikan ID pembangkit tersedia
if (!isset($_GET['id'])) {
    echo "<script>alert('Data tidak ditemukan!'); window.location.href='?page=pembangkit';</script>";
    exit;
}
$db = new Database();
$conn = $db->getConnection();
$id_pembangkit = intval($_GET['id']);
$query = "SELECT * FROM pembangkit WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->execute([$id_pembangkit]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location.href='?page=laporan_perbulan';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitasi input
    $nama_perusahaan = trim($_POST['nama_perusahaan']);
    $alamat = trim($_POST['alamat']);
    $kabupaten = trim($_POST['kabupaten']);
    $longitude = trim($_POST['longitude']);
    $latitude = trim($_POST['latitude']);
    $jenis_pembangkit = trim($_POST['jenis_pembangkit']);
    $fungsi = trim($_POST['fungsi']);
    $kapasitas_terpasang = trim($_POST['kapasitas_terpasang']);
    $daya_mampu_netto = trim($_POST['daya_mampu_netto']);
    $jumlah_unit = intval($_POST['jumlah_unit']);
    $no_unit = trim($_POST['no_unit']);
    $tahun_operasi = intval($_POST['tahun_operasi']);
    $status_operasi = trim($_POST['status_operasi']);
    $bahan_bakar_jenis = trim($_POST['bahan_bakar_jenis']);
    $bahan_bakar_satuan = trim($_POST['bahan_bakar_satuan']);
    $volume_bb = trim($_POST['volume_bb']);

    // Query update
    $query = "UPDATE pembangkit SET nama_perusahaan=?, alamat=?, kabupaten=? longitude=?, latitude=?, jenis_pembangkit=?, fungsi=?, kapasitas_terpasang=?, daya_mampu_netto=?, jumlah_unit=?, no_unit=?, tahun_operasi=?, status_operasi=?, bahan_bakar_jenis=?, bahan_bakar_satuan=?, volume_bb=? WHERE id =?";
    $stmt = $conn->prepare($query);
    $success = $stmt->execute([$nama_perusahaan, $alamat, $kabupaten, $longitude, $latitude, $jenis_pembangkit, $fungsi, $kapasitas_terpasang, $daya_mampu_netto, $jumlah_unit, $no_unit, $tahun_operasi, $status_operasi, $bahan_bakar_jenis, $bahan_bakar_satuan, $volume_bb, $id_pembangkit]);

    if ($success) {
        echo "<script>alert('Data berhasil diperbarui!'); window.location.href='?page=laporan_perbulan';</script>";
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
                    <input type="text" name="nama_perusahaan" class="form-control" placeholder="Masukkan jenis pembangkit" value="<?= $data['nama_perusahaan'] ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <input type="text" name="alamat" class="form-control" placeholder="Masukkan alamat lengkap" value="<?= $data['alamat'] ?>" required>
                </div>
                <div class="form-group mb-2">
                    <label>Kabupaten/Kota</label>
                    <select class="form-control" name="kabupaten" >
                        <option value="<?php echo $data['kabupaten']; ?>" selected><?php echo $data['kabupaten']; ?></option>
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
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Latitude</label>
                        <input type="text" name="latitude" class="form-control" placeholder="Contoh : 3°26&#39;43&quot;LS" value="<?= $data['latitude'] ?>" required oninput="replaceAsterisk(this)">
                        <small class="text-muted">Gunakan tanda * sebagai pengganti derajat (°). Contoh: 3*26'43"LS</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Longitude</label>
                        <input type="text" name="longitude" class="form-control" placeholder="Contoh : 114°50&#39;21&quot;BT" value="<?= $data['longitude'] ?>" required oninput="replaceAsterisk(this)">
                        <small class="text-muted">Gunakan tanda * sebagai pengganti derajat (°). Contoh: 114*50'21"BT</small>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Jenis Pembangkit</label>
                    <select class="form-control" name="jenis_pembangkit" required>
                        <option value="<?php echo $data['jenis_pembangkit']; ?>" selected><?php echo $data['jenis_pembangkit']; ?></option>
                        <option value="PLTD">PLTD</option>
                        <option value="PLTS">PLTS</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Fungsi</label>
                    <select class="form-control" name="fungsi" required>
                        <option value="<?php echo $data['fungsi']; ?>" selected><?php echo $data['fungsi']; ?></option>
                        <option value="Utama">Utama</option>
                        <option value="Darurat">Darurat</option>
                        <option value="Cadangan">Cadangan</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Kapasitas Terpasang (MW)</label>
                    <input type="text" name="kapasitas_terpasang" class="form-control" value="<?= $data['kapasitas_terpasang'] ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Daya Mampu Netto (MW)</label>
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
                    <select class="form-control" name="tahun_operasi" required>
                        <option value="<?= $data['tahun_operasi']; ?>" selected><?= $data['tahun_operasi']; ?></option>
                        <?php
                        // Menambahkan opsi tahun dari 2000 hingga 2030
                        for ($tahun = 2030; $tahun >= 2000; $tahun--) {
                            echo '<option value="' . $tahun . '">' . $tahun . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status Operasi</label>
                    <select class="form-control" name="status_operasi" required>
                        <option value="<?php echo $data['status_operasi']; ?>" selected><?php echo $data['status_operasi']; ?></option>
                        <option value="Beroperasi">Beroperasi</option>
                        <option value="Maintenance/Perbaikan">Maintenance/Perbaikan</option>
                        <option value="Rusak">Rusak</option>
                        <option value="Rusak Total">Rusak Total</option>
                    </select>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Jenis Bahan Bakar</label>
                    <select class="form-control" name="bahan_bakar_jenis" required>
                        <option value="<?php echo $data['bahan_bakar_jenis']; ?>" selected><?php echo $data['bahan_bakar_jenis']; ?></option>
                        <option value="Solar">Solar</option>
                        <option value="Biomasa">Biomasa</option>
                    </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Satuan Bahan Bakar</label>
                    <select class="form-control" name="bahan_bakar_satuan" required>
                        <option value="<?php echo $data['bahan_bakar_satuan']; ?>" selected><?php echo $data['bahan_bakar_satuan']; ?></option>
                        <option value="Liter">Liter</option>
                        <option value="Ton">Ton</option>
                    </select>
                    </div>
                </div>
                <div class="col-md-6">
                        <label class="form-label">Volume Bahan Bakar</label>
                        <input type="text" name="volume_bb" class="form-control" value="<?= $data['volume_bb'] ?>" required>
                    </div>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                    <a href="?page=laporan_perbulan" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- SCRIPT SIMBOL DERAJAT -->
<script>
    function replaceAsterisk(input) {
        input.value = input.value.replace(/\*/g, "°");
    }
</script>

<!-- SCRIPT PENULISAN ANGKA FORMAT INDONESIA -->
<script>
    function formatAngkaIndonesia(input) {
        let value = input.value;

        // Hapus semua karakter kecuali angka dan koma
        value = value.replace(/[^0-9,]/g, "");

        // Pisahkan angka dan desimal
        let parts = value.split(",");
        let angka = parts[0];
        let desimal = parts[1] || "";

        // Tambah titik ribuan
        angka = angka.replace(/\B(?=(\d{3})+(?!\d))/g, ".");

        // Gabungkan kembali
        input.value = desimal ? angka + "," + desimal : angka;
    }
</script>

<!-- SCRIPT JUMLAH UNIT MAX 200 UNIT -->
<script>
    const jumlahUnitInput = document.getElementById('jumlahUnitInput');
    jumlahUnitInput.addEventListener('input', function() {
        if (parseInt(this.value) > 200) {
            this.value = 200;
        }
    });
</script>

<!-- SCRIPT PILIHAN TAHUN OPERASI -->
<script>
    const selectTahun = document.querySelector('select[name="tahun_operasi"]');
    for (let tahun = 2030; tahun >= 2000; tahun--) {
        const option = document.createElement('option');
        option.value = tahun;
        option.textContent = tahun;
        selectTahun.appendChild(option);
    }
</script>