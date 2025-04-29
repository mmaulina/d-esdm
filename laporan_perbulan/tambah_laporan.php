<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include_once 'koneksi.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['id_user'])) {
    echo "<script>alert('Silakan login terlebih dahulu!'); window.location.href='login.php';</script>";
    exit;
}

$id_user = $_SESSION['id_user'];
$role = $_SESSION['role'] ?? '';

$nama_perusahaan_profil = '';

// Ambil nama_perusahaan berdasarkan id_user
$database = new Database();
$db = $database->getConnection();
$query = "SELECT nama_perusahaan FROM profil WHERE id_user = :id_user";
$stmt = $db->prepare($query);
$stmt->bindParam(':id_user', $id_user);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $nama_perusahaan_profil = $result['nama_perusahaan'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $database = new Database();
    $db = $database->getConnection();

    function sanitizeInput($input)
    {
        return strip_tags(trim($input));
    }
    

    function checkEmpty($input)
    {
        return empty($input) ? "-" : $input;
    }

    $nama_perusahaan = checkEmpty(sanitizeInput($_POST['nama_perusahaan'] ?? ''));
    $tahun = checkEmpty(sanitizeInput($_POST['tahun'] ?? ''));
    $bulan = checkEmpty(sanitizeInput($_POST['bulan'] ?? ''));
    $alamat = checkEmpty(sanitizeInput($_POST['alamat'] ?? ''));
    $latitude = checkEmpty(sanitizeInput($_POST['latitude'] ?? ''));
    $longitude = checkEmpty(sanitizeInput($_POST['longitude'] ?? ''));
    $jenis_pembangkit = checkEmpty(sanitizeInput($_POST['jenis_pembangkit'] ?? ''));
    $fungsi = checkEmpty(sanitizeInput($_POST['fungsi'] ?? ''));
    $kapasitas_terpasang = checkEmpty(sanitizeInput($_POST['kapasitas_terpasang'] ?? ''));
    $daya_mampu_netto = checkEmpty(sanitizeInput($_POST['daya_mampu_netto'] ?? ''));
    $jumlah_unit = checkEmpty(sanitizeInput($_POST['jumlah_unit'] ?? ''));
    $no_unit = checkEmpty(sanitizeInput($_POST['no_unit'] ?? ''));
    $tahun_operasi = checkEmpty(sanitizeInput($_POST['tahun_operasi'] ?? ''));
    $status_operasi = checkEmpty(sanitizeInput($_POST['status_operasi'] ?? ''));
    $bahan_bakar_jenis = checkEmpty(sanitizeInput($_POST['bahan_bakar_jenis'] ?? ''));
    $bahan_bakar_satuan = checkEmpty(sanitizeInput($_POST['bahan_bakar_satuan'] ?? ''));
    $volume_bb = checkEmpty(sanitizeInput($_POST['volume_bb'] ?? ''));
    $produksi_sendiri = checkEmpty(sanitizeInput($_POST['produksi_sendiri'] ?? ''));
    $pemb_sumber_lain = checkEmpty(sanitizeInput($_POST['pemb_sumber_lain'] ?? ''));
    $susut_jaringan = checkEmpty(sanitizeInput($_POST['susut_jaringan'] ?? ''));
    $penj_ke_pelanggan = checkEmpty(sanitizeInput($_POST['penj_ke_pelanggan'] ?? ''));
    $penj_ke_pln = checkEmpty(sanitizeInput($_POST['penj_ke_pln'] ?? ''));
    $pemakaian_sendiri = checkEmpty(sanitizeInput($_POST['pemakaian_sendiri'] ?? ''));

    $status = 'diajukan'; // Status diisi otomatis
    $keterangan = '-';    // Keterangan diisi otomatis


    $insertSQL = "INSERT INTO laporan_bulanan (id_user, nama_perusahaan, tahun, bulan, alamat, latitude longitude, jenis_pembangkit, fungsi, kapasitas_terpasang, daya_mampu_netto, jumlah_unit, no_unit, tahun_operasi, status_operasi, bahan_bakar_jenis, bahan_bakar_satuan, volume_bb, produksi_sendiri, pemb_sumber_lain, susut_jaringan, penj_ke_pelanggan, penj_ke_pln, pemakaian_sendiri, status, keterangan) 
                  VALUES (:id_user, :nama_perusahaan, :tahun, :bulan, :alamat, :latitude, :longitude, :jenis_pembangkit, :fungsi, :kapasitas_terpasang, :daya_mampu_netto, :jumlah_unit, :no_unit, :tahun_operasi, :status_operasi, :bahan_bakar_jenis, :bahan_bakar_satuan, :volume_bb, :produksi_sendiri, :pemb_sumber_lain, :susut_jaringan, :penj_ke_pelanggan, :penj_ke_pln, :pemakaian_sendiri, :status, :keterangan)";
    $stmt = $db->prepare($insertSQL);

    $stmt->bindParam(':id_user', $id_user);
    $stmt->bindParam(':nama_perusahaan', $nama_perusahaan);
    $stmt->bindParam(':tahun', $tahun);
    $stmt->bindParam(':bulan', $bulan);
    $stmt->bindParam(':alamat', $alamat);
    $stmt->bindParam(':latitude', $latitude);
    $stmt->bindParam(':longitude', $longitude);
    $stmt->bindParam(':jenis_pembangkit', $jenis_pembangkit);
    $stmt->bindParam(':fungsi', $fungsi);
    $stmt->bindParam(':kapasitas_terpasang', $kapasitas_terpasang);
    $stmt->bindParam(':daya_mampu_netto', $daya_mampu_netto);
    $stmt->bindParam(':jumlah_unit', $jumlah_unit,);
    $stmt->bindParam(':no_unit', $no_unit);
    $stmt->bindParam(':tahun_operasi', $tahun_operasi,);
    $stmt->bindParam(':status_operasi', $status_operasi);
    $stmt->bindParam(':bahan_bakar_jenis', $bahan_bakar_jenis);
    $stmt->bindParam(':bahan_bakar_satuan', $bahan_bakar_satuan);
    $stmt->bindParam(':volume_bb', $volume_bb);
    $stmt->bindParam(':produksi_sendiri', $produksi_sendiri);
    $stmt->bindParam(':pemb_sumber_lain', $pemb_sumber_lain);
    $stmt->bindParam(':susut_jaringan', $susut_jaringan);
    $stmt->bindParam(':penj_ke_pelanggan', $penj_ke_pelanggan);
    $stmt->bindParam(':penj_ke_pln', $penj_ke_pln);
    $stmt->bindParam(':pemakaian_sendiri', $pemakaian_sendiri);

    if ($stmt->execute()) {
        $_SESSION['hasil'] = true;
        $_SESSION['pesan'] = "Berhasil Simpan Data";
    } else {
        $_SESSION['hasil'] = false;
        $_SESSION['pesan'] = "Gagal Simpan Data";
    }
    echo "<meta http-equiv='refresh' content='0; url=?page=laporan_perbulan'>";
}
?>


<div class="container mt-4">
    <h3 class="text-center mb-3">Tambah Pelaporan Bulanan</h3>
    <hr>
    <div class="card shadow" style="overflow-x: auto; max-height: calc(100vh - 150px); overflow-y: auto;">
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Nama Perusahaan</label>
                    <?php if ($role === 'superadmin') : ?>
                        <input type="text" name="nama_perusahaan" class="form-control" value="<?= htmlspecialchars($nama_perusahaan_profil) ?>">
                    <?php else : ?>
                        <input type="text" name="nama_perusahaan" class="form-control" value="<?= htmlspecialchars($nama_perusahaan_profil) ?>" readonly>
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tahun</label>
                    <select class="form-control" name="tahun" id="tahun" required>
                        <option value="">-- Pilih Tahun --</option>
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label>Bulan</label>
                    <select class="form-control" name="bulan" required>
                        <option value="">-- Pilih Bulan --</option>
                        <option value="Januari">Januari</option>
                        <option value="Februari">Februari</option>
                        <option value="Maret">Maret</option>
                        <option value="April">April</option>
                        <option value="Mei">Mei</option>
                        <option value="Juni">Juni</option>
                        <option value="Juli">Juli</option>
                        <option value="Agustus">Agustus</option>
                        <option value="September">September</option>
                        <option value="Oktober">Oktober</option>
                        <option value="November">November</option>
                        <option value="Desember">Desember</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <input type="text" name="alamat" class="form-control" placeholder="Masukkan alamat lengkap" required>
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label">Latitude</label>
                        <input type="text" name="latitude" class="form-control" placeholder="Contoh : 3°26&#39;43&quot;LS" required>
                        <small class="text-danger">Catatan : Gunakan tanda * sebagai pengganti derajat (°). Contoh: 3*26'43,25"LS</small>
                    </div>
                    <div class="col">
                        <label class="form-label">Longitude</label>
                        <input type="text" name="longitude" class="form-control" placeholder="Contoh : 114°50&#39;21&quot;BT" required>
                        <small class="text-danger">Catatan : Gunakan tanda * sebagai pengganti derajat (°). Contoh: 114*50'21,15"BT</small>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Jenis Pembangkit</label>
                    <input type="text" name="jenis_pembangkit" class="form-control" placeholder="Masukkan jenis pembangkit" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Fungsi</label>
                    <select name="fungsi" class="form-select" required>
                        <option value="">-- Pilih Fungsi --</option>
                        <option value="Utama">Utama</option>
                        <option value="Darurat">Darurat</option>
                        <option value="Cadangan">Cadangan</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Kapasitas Terpasang (MW)</label>
                    <input type="text" name="kapasitas_terpasang" class="form-control"
                        placeholder="Contoh: 1.250,75" required oninput="formatAngkaIndonesia(this)">
                    <small class="text-danger">Catatan : Gunakan titik untuk ribuan dan koma untuk desimal.</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Daya Mampu Netto (MW)</label>
                    <input type="text" name="daya_mampu_netto" class="form-control"
                        placeholder="Contoh: 1.250,75" required oninput="formatAngkaIndonesia(this)">
                    <small class="text-danger">Catatan : Gunakan titik untuk ribuan dan koma untuk desimal.</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Jumlah Unit</label>
                    <input type="number" name="jumlah_unit" class="form-control" id="jumlahUnitInput" placeholder="Masukkan jumlah unit" required min="1" max="200">
                    <small class="text-danger">Catatan : Max. 200 unit</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">No. Unit</label>
                    <input type="text" name="no_unit" class="form-control" placeholder="Masukkan nomor unit" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tahun Operasi</label>
                    <select name="tahun_operasi" class="form-select" required>
                        <option value="">-- Pilih Tahun --</option>
                        <!-- Tahun dari 2030 ke 2000 -->
                        <!-- Kode ini akan diisi otomatis dengan JavaScript -->
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status Operasi</label>
                    <select name="status_operasi" class="form-select" required>
                        <option value="">-- Pilih Status Operasi --</option>
                        <option value="Beroperasi">Beroperasi</option>
                        <option value="Maintenance/Perbaikan">Maintenance/Perbaikan</option>
                        <option value="Rusak">Rusak</option>
                    </select>
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label">Jenis Bahan Bakar</label>
                        <select name="bahan_bakar_jenis" class="form-select" required>
                            <option value="">-- Pilih Jenis Bahan Bakar --</option>
                            <option value="Solar">Solar</option>
                            <option value="Biomasa">Biomasa</option>
                        </select>
                        <small class="text-danger">
                            Catatan: <strong>Biomasa</strong> mencakup bahan-bahan organik seperti <em>cangkang sawit</em>, <em>serbuk gergaji</em>, dan <em>sekam padi</em>, dll yang digunakan sebagai bahan bakar alternatif dalam pembangkit listrik.
                        </small>
                    </div>
                    <div class="col">
                        <label class="form-label">Satuan Bahan Bakar</label>
                        <select name="bahan_bakar_satuan" class="form-select" required>
                            <option value="">-- Pilih Satuan --</option>
                            <option value="Liter">Liter</option>
                            <option value="Ton">Ton</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Volume Bahan Bakar</label>
                    <input type="number" name="volume_bb" class="form-control" placeholder="Masukkan volume bahan bakar" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Produksi Sendiri (kWh)</label>
                    <input type="number" name="produksi_sendiri" class="form-control" placeholder="Masukkan produksi sendiri" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Pembelian Sumber Lain (kWh)
                        <small class="text-primary">*bila ada</small>
                    </label>
                    <input type="number" name="pemb_sumber_lain" class="form-control" placeholder="Masukkan pembelian sumber lain">
                </div>
                <div class="mb-3">
                    <label class="form-label">Susut jaringan (kWh)
                        <small class="text-primary">*bila ada</small>
                    </label>
                    <input type="number" name="susut_jaringan" class="form-control" placeholder="Masukkan susut jaringan">
                </div>
                <div class="mb-3">
                    <label class="form-label">Penjualan ke Pelanggan (kWh)
                        <small class="text-primary">*bila ada</small>
                    </label>
                    <input type="number" name="penj_ke_pelanggan" class="form-control" placeholder="Masukkan penjualan ke pelanggan">
                </div>
                <div class="mb-3">
                    <label class="form-label">Penjualan ke PLN (kWh)
                        <small class="text-primary">*bila ada</small>
                    </label>
                    <input type="number" name="penj_ke_pln" class="form-control" placeholder="Masukkan penjualan ke PLN">
                </div>
                <div class="mb-3">
                    <label class="form-label">Pemakaian Sendiri (kWh)</label>
                    <input type="number" name="pemakaian_sendiri" class="form-control" placeholder="Masukkan pemakaian sendiri" required>
                </div>

                <button type="submit" class="btn btn-success">Simpan</button>
                <a href="?page=laporan_perbulan" class="btn btn-secondary">Kembali</a>
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

<!-- SCRIPT TAHUN OTOMATIS -->
<script>
    const tahunSelect = document.getElementById('tahun');

    const currentYear = new Date().getFullYear(); // Tahun sekarang (otomatis)
    const endYear = currentYear + 10;

    // Isi dropdown tahun dari currentYear sampai endYear
    for (let year = currentYear; year <= endYear; year++) {
        const option = document.createElement("option");
        option.value = year;
        option.text = year;
        tahunSelect.appendChild(option);
    }
</script>