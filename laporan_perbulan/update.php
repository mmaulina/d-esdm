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
        return strip_tags(trim($input));
    }

    function checkEmpty($input)
    {
        return empty($input) ? "-" : $input;
    }

    $nama_perusahaan = checkEmpty(sanitizeInput($_POST['nama_perusahaan'] ?? ''));
    $no_hp_pimpinan = checkEmpty(sanitizeInput($_POST['no_hp_pimpinan'] ?? ''));
    $tenaga_teknik = checkEmpty(sanitizeInput($_POST['tenaga_teknik'] ?? ''));
    $no_hp_teknik = checkEmpty(sanitizeInput($_POST['no_hp_teknik'] ?? ''));
    $nama = checkEmpty(sanitizeInput($_POST['nama'] ?? ''));
    $no_hp = checkEmpty(sanitizeInput($_POST['no_hp'] ?? ''));
    $no_telp_kantor = checkEmpty(sanitizeInput($_POST['no_telp_kantor'] ?? ''));
    $tahun = checkEmpty(sanitizeInput($_POST['tahun'] ?? ''));
    $bulan = checkEmpty(sanitizeInput($_POST['bulan'] ?? ''));
    $kabupaten = checkEmpty(sanitizeInput($_POST['kabupaten'] ?? ''));
    $produksi_sendiri = checkEmpty(sanitizeInput($_POST['produksi_sendiri'] ?? ''));
    $pemb_sumber_lain = checkEmpty(sanitizeInput($_POST['pemb_sumber_lain'] ?? ''));
    $susut_jaringan = checkEmpty(sanitizeInput($_POST['susut_jaringan'] ?? ''));
    $penj_ke_pelanggan = checkEmpty(sanitizeInput($_POST['penj_ke_pelanggan'] ?? ''));
    $penj_ke_pln = checkEmpty(sanitizeInput($_POST['penj_ke_pln'] ?? ''));
    $pemakaian_sendiri = checkEmpty(sanitizeInput($_POST['pemakaian_sendiri'] ?? ''));

    $status = 'diajukan'; // Status diisi otomatis
    $keterangan = '-';    // Keterangan diisi otomatis


    $updateSQL = "UPDATE laporan_bulanan SET nama_perusahaan = :nama_perusahaan,no_hp_pimpinan=:no_hp_pimpinan, tenaga_teknik=:tenaga_teknik, no_hp_teknik=:no_hp_teknik, nama=:nama, no_hp=:no_hp, no_telp_kantor=:no_telp_kantor, tahun = :tahun, bulan = :bulan, kabupaten = :kabupaten, produksi_sendiri = :produksi_sendiri, pemb_sumber_lain = :pemb_sumber_lain, susut_jaringan = :susut_jaringan, penj_ke_pelanggan = :penj_ke_pelanggan, penj_ke_pln = :penj_ke_pln, pemakaian_sendiri = :pemakaian_sendiri, status = :status, keterangan = :keterangan WHERE id = :id";
    $stmt = $db->prepare($updateSQL);

    $stmt->bindParam(':nama_perusahaan', $nama_perusahaan);
    $stmt->bindParam(':no_hp_pimpinan', $no_hp_pimpinan);
    $stmt->bindParam(':tenaga_teknik', $tenaga_teknik);
    $stmt->bindParam(':no_hp_teknik', $no_hp_teknik);
    $stmt->bindParam(':nama', $nama);
    $stmt->bindParam(':no_hp', $no_hp);
    $stmt->bindParam(':no_telp_kantor', $no_telp_kantor);
    $stmt->bindParam(':tahun', $tahun);
    $stmt->bindParam(':bulan', $bulan);
    $stmt->bindParam(':kabupaten', $kabupaten);
    $stmt->bindParam(':produksi_sendiri', $produksi_sendiri);
    $stmt->bindParam(':pemb_sumber_lain', $pemb_sumber_lain);
    $stmt->bindParam(':susut_jaringan', $susut_jaringan);
    $stmt->bindParam(':penj_ke_pelanggan', $penj_ke_pelanggan);
    $stmt->bindParam(':penj_ke_pln', $penj_ke_pln);
    $stmt->bindParam(':pemakaian_sendiri', $pemakaian_sendiri);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':keterangan', $keterangan);
    $stmt->bindParam(':id', $id_laporan);

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
    <div class="card shadow" style="overflow-x: auto; max-height: calc(100vh - 150px); overflow-y: auto;">
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Nama Perusahaan</label>
                    <?php if ($role === 'superadmin') : ?>
                        <input type="text" name="nama_perusahaan" class="form-control" placeholder="Masukan nama perusahaan" value="<?= htmlspecialchars($data['nama_perusahaan']) ?>" required>
                    <?php elseif ($role === 'umum') : ?>
                        <input type="text" name="nama_perusahaan" class="form-control" value="<?= htmlspecialchars($data['nama_perusahaan']) ?>" readonly>
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label class="form-label">No Hp Pimpinan</label>
                    <input type="text" name="no_hp_pimpinan" class="form-control" value="<?= htmlspecialchars($data['no_hp_pimpinan']) ?>" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tenaga Teknik</label>
                    <input type="text" name="tenaga_teknik" class="form-control" value="<?= htmlspecialchars($data['tenaga_teknik']) ?>" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">No Hp Tenaga Teknik</label>
                    <input type="text" name="no_hp_teknik" class="form-control" value="<?= htmlspecialchars($data['no_hp_teknik']) ?>" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nama Admin</label>
                    <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($data['nama']) ?>" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">No Hp Admin</label>
                    <input type="text" name="no_hp" class="form-control" value="<?= htmlspecialchars($data['no_hp']) ?>" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">No Telpon Kantor</label>
                    <input type="text" name="no_telp_kantor" class="form-control" value="<?= htmlspecialchars($data['no_telp_kantor']) ?>" readonly>
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
                        <option value="">-- Pilih Bulan --</option>
                        <option value="Januari" <?= ($data['bulan'] == 'Januari') ? 'selected' : '' ?>>Januari</option>
                        <option value="Februari" <?= ($data['bulan'] == 'Februari') ? 'selected' : '' ?>>Februari</option>
                        <option value="Maret" <?= ($data['bulan'] == 'Maret') ? 'selected' : '' ?>>Maret</option>
                        <option value="April" <?= ($data['bulan'] == 'April') ? 'selected' : '' ?>>April</option>
                        <option value="Mei" <?= ($data['bulan'] == 'Mei') ? 'selected' : '' ?>>Mei</option>
                        <option value="Juni" <?= ($data['bulan'] == 'Juni') ? 'selected' : '' ?>>Juni</option>
                        <option value="Juli" <?= ($data['bulan'] == 'Juli') ? 'selected' : '' ?>>Juli</option>
                        <option value="Agustus" <?= ($data['bulan'] == 'Agustus') ? 'selected' : '' ?>>Agustus</option>
                        <option value="September" <?= ($data['bulan'] == 'September') ? 'selected' : '' ?>>September</option>
                        <option value="Oktober" <?= ($data['bulan'] == 'Oktober') ? 'selected' : '' ?>>Oktober</option>
                        <option value="November" <?= ($data['bulan'] == 'November') ? 'selected' : '' ?>>November</option>
                        <option value="Desember" <?= ($data['bulan'] == 'Desember') ? 'selected' : '' ?>>Desember</option>
                    </select>
                </div>
                <div class="form-group mb-2">
                    <label>Kabupaten/Kota</label>
                    <select class="form-control" name="kabupaten" required>
                        <option value="">-- Pilih Kabupaten/Kota --</option>
                        <option value="Balangan" <?= ($data['kabupaten'] == 'Balangan') ? 'selected' : '' ?>>Balangan</option>
                        <option value="Banjar" <?= ($data['kabupaten'] == 'Banjar') ? 'selected' : '' ?>>Banjar</option>
                        <option value="Barito Kuala" <?= ($data['kabupaten'] == 'Barito Kuala') ? 'selected' : '' ?>>Barito Kuala</option>
                        <option value="Hulu Sungai Selatan" <?= ($data['kabupaten'] == 'Hulu Sungai Selatan') ? 'selected' : '' ?>>Hulu Sungai Selatan</option>
                        <option value="Hulu Sungai Tengah" <?= ($data['kabupaten'] == 'Hulu Sungai Tengah') ? 'selected' : '' ?>>Hulu Sungai Tengah</option>
                        <option value="Hulu Sungai Utara" <?= ($data['kabupaten'] == 'Hulu Sungai Utara') ? 'selected' : '' ?>>Hulu Sungai Utara</option>
                        <option value="Kotabaru" <?= ($data['kabupaten'] == 'Kotabaru') ? 'selected' : '' ?>>Kotabaru</option>
                        <option value="Tabalong" <?= ($data['kabupaten'] == 'Tabalong') ? 'selected' : '' ?>>Tabalong</option>
                        <option value="Tanah Bumbu" <?= ($data['kabupaten'] == 'Tanah Bumbu') ? 'selected' : '' ?>>Tanah Bumbu</option>
                        <option value="Tanah Laut" <?= ($data['kabupaten'] == 'Tanah Laut') ? 'selected' : '' ?>>Tanah Laut</option>
                        <option value="Tapin" <?= ($data['kabupaten'] == 'Tapin') ? 'selected' : '' ?>>Tapin</option>
                        <option value="Kota Banjarmasin" <?= ($data['kabupaten'] == 'Kota Banjarmasin') ? 'selected' : '' ?>>Banjarmasin (Kota)</option>
                        <option value="Kota Banjarbaru" <?= ($data['kabupaten'] == 'Kota Banjarbaru') ? 'selected' : '' ?>>Banjarbaru (Kota)</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Produksi Sendiri (kWh)</label>
                    <input type="text" name="produksi_sendiri" class="form-control" placeholder="Masukkan produksi sendiri" value="<?= htmlspecialchars($data['produksi_sendiri']) ?>" required>
                    <small class="text-danger">Catatan : Gunakan titik untuk ribuan dan koma untuk desimal.</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Pembelian Sumber Lain (kWh)
                        <small class="text-primary">*bila ada</small>
                    </label>
                    <input type="text" name="pemb_sumber_lain" class="form-control" placeholder="Masukkan pembelian sumber lain" value="<?= htmlspecialchars($data['pemb_sumber_lain']) ?>">
                    <small class="text-danger">Catatan : Gunakan titik untuk ribuan dan koma untuk desimal.</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Susut jaringan (kWh)
                        <small class="text-primary">*bila ada</small>
                    </label>
                    <input type="text" name="susut_jaringan" class="form-control" placeholder="Masukkan susut jaringan" value="<?= htmlspecialchars($data['susut_jaringan']) ?>">
                    <small class="text-danger">Catatan : Gunakan titik untuk ribuan dan koma untuk desimal.</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Penjualan ke Pelanggan (kWh)
                        <small class="text-primary">*bila ada</small>
                    </label>
                    <input type="text" name="penj_ke_pelanggan" class="form-control" placeholder="Masukkan penjualan ke pelanggan" value="<?= htmlspecialchars($data['penj_ke_pelanggan']) ?>">
                    <small class="text-danger">Catatan : Gunakan titik untuk ribuan dan koma untuk desimal.</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Penjualan ke PLN (kWh)
                        <small class="text-primary">*bila ada</small>
                    </label>
                    <input type="text" name="penj_ke_pln" class="form-control" placeholder="Masukkan penjualan ke PLN" value="<?= htmlspecialchars($data['penj_ke_pln']) ?>">
                    <small class="text-danger">Catatan : Gunakan titik untuk ribuan dan koma untuk desimal.</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Pemakaian Sendiri (kWh)</label>
                    <input type="text" name="pemakaian_sendiri" class="form-control" placeholder="Masukkan pemakaian sendiri" value="<?= htmlspecialchars($data['pemakaian_sendiri']) ?>" required>
                    <small class="text-danger">Catatan : Gunakan titik untuk ribuan dan koma untuk desimal.</small>
                </div>
                <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                <a href="?page=laporan_perbulan" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>

<!-- SCRIPT SIMBOL DERAJAT -->
<script>
    function replaceAsterisk(input) {
        // Ganti semua * dengan °
        input.value = input.value.replace(/\*/g, "°");
    }
</script>

<!-- SCRIPT PILIHAN TAHUN OPERASI -->
<script>
    const selectTahun = document.querySelector('select[name="tahun_operasi"]');
    const tahunTerpilih = "<?= $data['tahun_operasi'] ?>"; // ambil dari database

    for (let tahun = 2030; tahun >= 2000; tahun--) {
        const option = document.createElement('option');
        option.value = tahun;
        option.textContent = tahun;
        if (tahun == tahunTerpilih) {
            option.selected = true;
        }
        selectTahun.appendChild(option);
    }
</script>

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