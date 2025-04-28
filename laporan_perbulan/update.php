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
$id_laporan = isset($_GET['id']) ? $_GET['id'] : null;
$role = isset($_SESSION['role']) ? $_SESSION['role'] : null;
$database = new Database();
$db = $database->getConnection();

// Ambil ID laporan dari parameter URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('ID Laporan tidak ditemukan!'); window.location.href='?page=laporan_perbulan';</script>";
    exit;
}

$id_laporan = $_GET['id'];

// Ambil data laporan berdasarkan ID
$query = "SELECT * FROM laporan_bulanan WHERE id = :id";
$stmt = $db->prepare($query);
$stmt->bindParam(':id', $id_laporan);
$stmt->execute();
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location.href='?page=laporan_perbulan';</script>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $database = new Database();
    $db = $database->getConnection();

    function sanitizeInput($input)
    {
        return htmlspecialchars(strip_tags(trim($input)));
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


    $updateSQL = "UPDATE laporan_bulanan SET nama_perusahaan = :nama_perusahaan, tahun = :tahun, bulan = :bulan, alamat = :alamat, latitude = :latitude, longitude = :longitude, jenis_pembangkit = :jenis_pembangkit, fungsi = :fungsi, kapasitas_terpasang = :kapasitas_terpasang, daya_mampu_netto = :daya_mampu_netto, jumlah_unit = :jumlah_unit, no_unit = :no_unit, tahun_operasi = :tahun_operasi, status_operasi = :status_operasi, bahan_bakar_jenis = :bahan_bakar_jenis, bahan_bakar_satuan = :bahan_bakar_satuan, volume_bb = :volume_bb, produksi_sendiri = :produksi_sendiri, pemb_sumber_lain = :pemb_sumber_lain, susut_jaringan = :susut_jaringan, penj_ke_pelanggan = :penj_ke_pelanggan, penj_ke_pln = :penj_ke_pln, pemakaian_sendiri = :pemakaian_sendiri, status = :status, keterangan = :keterangan WHERE id = :id AND id_user = :id_user";
    $stmt = $db->prepare($updateSQL);

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
    $stmt->bindParam(':jumlah_unit', $jumlah_unit);
    $stmt->bindParam(':no_unit', $no_unit);
    $stmt->bindParam(':tahun_operasi', $tahun_operasi);
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
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':keterangan', $keterangan);
    $stmt->bindParam(':id', $id_laporan);
    $stmt->bindParam(':id_user', $id_user);

    if ($stmt->execute()) {
        $_SESSION['hasil'] = true;
        $_SESSION['pesan'] = "Berhasil Mengupdate Data";
    } else {
        $_SESSION['hasil'] = false;
        $_SESSION['pesan'] = "Gagal Mengupdate Data";
    }
    echo "<meta http-equiv='refresh' content='0; url=?page=laporan_perbulan'>";
}
?>

<div class="container mt-4">
    <h3 class="text-center mb-3">Edit Pelaporan Bulanan</h3>
    <hr>
    <div class="card shadow">
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Nama Perusahaan</label>
                    <?php if ($role === 'superadmin') : ?>
                        <input type="text" name="nama_perusahaan" class="form-control" value="<?= htmlspecialchars($data['nama_perusahaan']) ?>" required>
                    <?php elseif ($role === 'umum') : ?>
                        <input type="text" name="nama_perusahaan" class="form-control" value="<?= htmlspecialchars($data['nama_perusahaan']) ?>" required readonly>
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tahun</label>
                    <select class="form-control" name="tahun" id="tahun" required>
                        <option value="">-- Pilih Tahun --</option>
                        <?php
                        // Isi dropdown tahun dari currentYear sampai endYear
                        for ($year = 2025; $year <= 2035; $year++) {
                            $selected = ($data['tahun'] == $year) ? 'selected' : '';
                            echo "<option value='$year' $selected>$year</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label>Bulan</label>
                    <select class="form-control" name="bulan" required>
                        <option value="Januari" <?= $data['bulan'] == 'Januari' ? 'selected' : '' ?>>Januari</option>
                        <option value="Februari" <?= $data['bulan'] == 'Februari' ? 'selected' : '' ?>>Februari</option>
                        <option value="Maret" <?= $data['bulan'] == 'Maret' ? 'selected' : '' ?>>Maret</option>
                        <option value="April" <?= $data['bulan'] == 'April' ? 'selected' : '' ?>>April</option>
                        <option value="Mei" <?= $data['bulan'] == 'Mei' ? 'selected' : '' ?>>Mei</option>
                        <option value="Juni" <?= $data['bulan'] == 'Juni' ? 'selected' : '' ?>>Juni</option>
                        <option value="Juli" <?= $data['bulan'] == 'Juli' ? 'selected' : '' ?>>Juli</option>
                        <option value="Agustus" <?= $data['bulan'] == 'Agustus' ? 'selected' : '' ?>>Agustus</option>
                        <option value="September" <?= $data['bulan'] == 'September' ? 'selected' : '' ?>>September</option>
                        <option value="Oktober" <?= $data['bulan'] == 'Oktober' ? 'selected' : '' ?>>Oktober</option>
                        <option value="November" <?= $data['bulan'] == 'November' ? 'selected' : '' ?>>November</option>
                        <option value="Desember" <?= $data['bulan'] == 'Desember' ? 'selected' : '' ?>>Desember</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <input type="text" name="alamat" class="form-control" placeholder="Masukkan alamat lengkap" value="<?= htmlspecialchars($data['alamat']) ?>" required>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Latitude</label>
                        <input type="text" name="latitude" class="form-control" placeholder="Contoh : 3°26&#39;43&quot;LS" value="<?= htmlspecialchars($data['latitude']) ?>" required>
                        <small class="text-muted">Gunakan tanda * sebagai pengganti derajat (°). Contoh: 3*26'43"LS</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Longitude</label>
                        <input type="text" name="longitude" class="form-control" placeholder="Contoh : 114°50&#39;21&quot;BT" value="<?= htmlspecialchars($data['longitude']) ?>" required>
                        <small class="text-muted">Gunakan tanda * sebagai pengganti derajat (°). Contoh: 114*50'21"BT</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jenis Pembangkit</label>
                        <input type="text" name="jenis_pembangkit" class="form-control" placeholder="Masukkan jenis pembangkit" value="<?= htmlspecialchars($data['jenis_pembangkit']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fungsi</label>
                        <input type="text" name="fungsi" class="form-control" value="<?= htmlspecialchars($data['fungsi']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kapasitas Terpasang (MW)</label>
                        <input type="text" name="kapasitas_terpasang" class="form-control" value="<?= htmlspecialchars($data['kapasitas_terpasang']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Daya Mampu Netto (MW)</label>
                        <input type="text" name="daya_mampu_netto" class="form-control" value="<?= htmlspecialchars($data['daya_mampu_netto']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jumlah Unit</label>
                        <input type="number" name="jumlah_unit" class="form-control" value="<?= htmlspecialchars($data['jumlah_unit']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No. Unit</label>
                        <input type="text" name="no_unit" class="form-control" value="<?= htmlspecialchars($data['no_unit']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tahun Operasi</label>
                        <input type="number" name="tahun_operasi" class="form-control" value="<?= htmlspecialchars($data['tahun_operasi']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status Operasi</label>
                        <input type="text" name="status_operasi" class="form-control" value="<?= htmlspecialchars($data['status_operasi']) ?>" required>
                    </div>
                    <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Jenis Bahan Bakar</label>
                        <input type="text" name="bahan_bakar_jenis" class="form-control" value="<?= htmlspecialchars($data['bahan_bakar_jenis']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Satuan Bahan Bakar</label>
                        <input type="text" name="bahan_bakar_satuan" class="form-control" value="<?= htmlspecialchars($data['bahan_bakar_satuan']) ?>" required>
                    </div>
                </div>
                </div>
                <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                <a href="?page=laporan_perbulan" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>

<!-- SCRIPT TAHUN -->
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