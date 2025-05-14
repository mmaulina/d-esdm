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
$no_hp_pimpinan = '';
$tenaga_teknik = '';
$no_hp_teknik = '';
$nama = '';
$no_hp = '';
$no_telp_kantor = '';

// Ambil data profil berdasarkan id_user
$database = new Database();
$db = $database->getConnection();
$query = "SELECT nama_perusahaan, no_hp_pimpinan, tenaga_teknik, no_hp_teknik, nama, no_hp, no_telp_kantor 
          FROM profil WHERE id_user = :id_user";
$stmt = $db->prepare($query);
$stmt->bindParam(':id_user', $id_user);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $nama_perusahaan_profil = $result['nama_perusahaan'];
    $no_hp_pimpinan = $result['no_hp_pimpinan'];
    $tenaga_teknik = $result['tenaga_teknik'];
    $no_hp_teknik = $result['no_hp_teknik'];
    $nama = $result['nama'];
    $no_hp = $result['no_hp'];
    $no_telp_kantor = $result['no_telp_kantor'];
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

    $alamat_arr = $_POST['alamat'] ?? [];
    $latitude_arr = $_POST['latitude'] ?? [];
    $longitude_arr = $_POST['longitude'] ?? [];
    $jenis_pembangkit_arr = $_POST['jenis_pembangkit'] ?? [];
    $fungsi_arr = $_POST['fungsi'] ?? [];
    $kapasitas_terpasang_arr = $_POST['kapasitas_terpasang'] ?? [];
    $daya_mampu_netto_arr = $_POST['daya_mampu_netto'] ?? [];
    $jumlah_unit = checkEmpty(sanitizeInput($_POST['jumlah_unit'] ?? ''));
    $no_unit_arr = $_POST['no_unit'] ?? [];
    $tahun_operasi_arr = $_POST['tahun_operasi'] ?? [];
    $status_operasi_arr = $_POST['status_operasi'] ?? [];
    $bahan_bakar_jenis_arr = $_POST['bahan_bakar_jenis'] ?? [];
    $bahan_bakar_satuan_arr = $_POST['bahan_bakar_satuan'] ?? [];
    $volume_bb_arr = $_POST['volume_bb'] ?? [];

    $produksi_sendiri = checkEmpty(sanitizeInput($_POST['produksi_sendiri'] ?? ''));
    $pemb_sumber_lain = checkEmpty(sanitizeInput($_POST['pemb_sumber_lain'] ?? ''));
    $susut_jaringan = checkEmpty(sanitizeInput($_POST['susut_jaringan'] ?? ''));
    $penj_ke_pelanggan = checkEmpty(sanitizeInput($_POST['penj_ke_pelanggan'] ?? ''));
    $penj_ke_pln = checkEmpty(sanitizeInput($_POST['penj_ke_pln'] ?? ''));
    $pemakaian_sendiri = checkEmpty(sanitizeInput($_POST['pemakaian_sendiri'] ?? ''));

    $status = 'diajukan'; // Status diisi otomatis
    $keterangan = '-';    // Keterangan diisi otomatis

    $laporan_bulanan = isset($_POST['laporan_bulanan']);
    $data_pembangkit = isset($_POST['data_pembangkit']);

    if ($laporan_bulanan) {
        $query = "INSERT INTO laporan_bulanan (
        id_user, nama_perusahaan, no_hp_pimpinan, tenaga_teknik, no_hp_teknik, nama, no_hp, no_telp_kantor, tahun, bulan, kabupaten, produksi_sendiri, pemb_sumber_lain, susut_jaringan,
        penj_ke_pelanggan, penj_ke_pln, pemakaian_sendiri,
        status, keterangan
    ) 
    VALUES (
        :id_user, :nama_perusahaan, :no_hp_pimpinan, :tenaga_teknik, :no_hp_teknik, :nama, :no_hp, :no_telp_kantor, :tahun, :bulan, :kabupaten, :produksi_sendiri, :pemb_sumber_lain, :susut_jaringan,
        :penj_ke_pelanggan, :penj_ke_pln, :pemakaian_sendiri,
        :status, :keterangan
    )";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id_user', $id_user);
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
    }

    if ($data_pembangkit) {
        $querypembangkit = "INSERT INTO pembangkit (
                id_user, nama_perusahaan, alamat, longitude, latitude, jenis_pembangkit, fungsi, kapasitas_terpasang, 
                daya_mampu_netto, jumlah_unit, no_unit, tahun_operasi, status_operasi, bahan_bakar_jenis, bahan_bakar_satuan, volume_bb) 
            VALUES (
                :id_user, :nama_perusahaan, :alamat, :longitude, :latitude, :jenis_pembangkit, :fungsi, :kapasitas_terpasang, 
                :daya_mampu_netto, :jumlah_unit, :no_unit, :tahun_operasi, :status_operasi, :bahan_bakar_jenis, :bahan_bakar_satuan, :volume_bb
            )";

        $allPembangkitSaved = true; // Tambahkan di awal sebelum for
        for ($i = 0; $i < count($alamat_arr); $i++) {
            $alamat = $alamat_arr[$i];
            $latitude = $latitude_arr[$i];
            $longitude = $longitude_arr[$i];
            $jenis_pembangkit = $jenis_pembangkit_arr[$i];
            $fungsi = $fungsi_arr[$i];
            $kapasitas_terpasang = $kapasitas_terpasang_arr[$i];
            $daya_mampu_netto = $daya_mampu_netto_arr[$i];
            $no_unit = $no_unit_arr[$i];
            $tahun_operasi = $tahun_operasi_arr[$i];
            $status_operasi = $status_operasi_arr[$i];
            $bahan_bakar_jenis = $bahan_bakar_jenis_arr[$i];
            $bahan_bakar_satuan = $bahan_bakar_satuan_arr[$i];
            $volume_bb = $volume_bb_arr[$i];

            $stmt2 = $db->prepare($querypembangkit);
            $stmt2->bindParam(':nama_perusahaan', $nama_perusahaan);
            $stmt2->bindParam(':id_user', $id_user);
            $stmt2->bindParam(':alamat', $alamat);
            $stmt2->bindParam(':latitude', $latitude);
            $stmt2->bindParam(':longitude', $longitude);
            $stmt2->bindParam(':jenis_pembangkit', $jenis_pembangkit);
            $stmt2->bindParam(':fungsi', $fungsi);
            $stmt2->bindParam(':kapasitas_terpasang', $kapasitas_terpasang);
            $stmt2->bindParam(':daya_mampu_netto', $daya_mampu_netto);
            $stmt2->bindParam(':jumlah_unit', $jumlah_unit);
            $stmt2->bindParam(':no_unit', $no_unit);
            $stmt2->bindParam(':tahun_operasi', $tahun_operasi);
            $stmt2->bindParam(':status_operasi', $status_operasi);
            $stmt2->bindParam(':bahan_bakar_jenis', $bahan_bakar_jenis);
            $stmt2->bindParam(':bahan_bakar_satuan', $bahan_bakar_satuan);
            $stmt2->bindParam(':volume_bb', $volume_bb);

            if (!$stmt2->execute()) {
                $allPembangkitSaved = false;
            }
        }
    }



    if (($laporan_bulanan && $stmt->execute()) || ($data_pembangkit && $allPembangkitSaved)) {
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
                        <input type="text" name="nama_perusahaan" class="form-control" placeholder="Masukan nama perusahaan" value="<?= htmlspecialchars($nama_perusahaan_profil) ?>" required>
                    <?php else : ?>
                        <input type="text" name="nama_perusahaan" class="form-control" value="<?= htmlspecialchars($nama_perusahaan_profil) ?>" readonly>
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label class="form-label">No Hp Pimpinan</label>
                    <?php if ($role === 'superadmin') : ?>
                        <input type="text" name="no_hp_pimpinan" class="form-control" placeholder="Masukan nomor HP pimpinan" value="<?= htmlspecialchars($no_hp_pimpinan) ?>" required>
                    <?php else : ?>
                        <input type="text" name="no_hp_pimpinan" class="form-control" value="<?= htmlspecialchars($no_hp_pimpinan) ?>" readonly>
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tenaga Teknik</label>
                    <input type="text" name="tenaga_teknik" class="form-control" value="<?= htmlspecialchars($tenaga_teknik) ?>" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">No Hp Tenaga Teknik</label>
                    <input type="text" name="no_hp_teknik" class="form-control" value="<?= htmlspecialchars($no_hp_teknik) ?>" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nama Admin</label>
                    <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($nama) ?>" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">No Hp Admin</label>
                    <input type="text" name="no_hp" class="form-control" value="<?= htmlspecialchars($no_hp) ?>" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">No Telpon Kantor</label>
                    <input type="text" name="no_telp_kantor" class="form-control" value="<?= htmlspecialchars($no_telp_kantor) ?>" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tahun</label>
                    <select class="form-control" name="tahun" id="tahun" >
                        <option value="">-- Pilih Tahun --</option>
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label>Bulan</label>
                    <select class="form-control" name="bulan" >
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
                <div class="form-group mb-2">
                    <label>Kabupaten/Kota</label>
                    <select class="form-control" name="kabupaten" >
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
                <div class="mb-3">
                    <small class="text-danger">Kosongan Jika Mau Mengisi Laporan Bulanan Saja, Langsung isi Produksi Sendiri</small><br>
                    <label class="form-label">Jumlah Unit</label>
                    <input type="number" name="jumlah_unit" class="form-control" placeholder="Masukkan jumlah unit (1-200)" min="1" max="200">
                </div>

                <div class="mb-3">
                    <button type="button" class="btn btn-primary" onclick="tambahUnit()">Tambah Unit</button>
                    <button type="button" class="btn btn-danger ms-2" onclick="resetSemua()">Reset Semua</button>
                </div>

                <div id="formContainer"></div>
                <div class="mb-3">
                    <label class="form-label">Produksi Sendiri (kWh)</label>
                    <input type="text" name="produksi_sendiri" class="form-control" placeholder="Masukkan produksi sendiri" >
                </div>
                <div class="mb-3">
                    <label class="form-label">Pembelian Sumber Lain (kWh)
                        <small class="text-primary">*bila ada</small>
                    </label>
                    <input type="text" name="pemb_sumber_lain" class="form-control" placeholder="Masukkan pembelian sumber lain">
                </div>
                <div class="mb-3">
                    <label class="form-label">Susut jaringan (kWh)
                        <small class="text-primary">*bila ada</small>
                    </label>
                    <input type="text" name="susut_jaringan" class="form-control" placeholder="Masukkan susut jaringan">
                </div>
                <div class="mb-3">
                    <label class="form-label">Penjualan ke Pelanggan (kWh)
                        <small class="text-primary">*bila ada</small>
                    </label>
                    <input type="text" name="penj_ke_pelanggan" class="form-control" placeholder="Masukkan penjualan ke pelanggan">
                </div>
                <div class="mb-3">
                    <label class="form-label">Penjualan ke PLN (kWh)
                        <small class="text-primary">*bila ada</small>
                    </label>
                    <input type="text" name="penj_ke_pln" class="form-control" placeholder="Masukkan penjualan ke PLN">
                </div>
                <div class="mb-3">
                    <label class="form-label">Pemakaian Sendiri (kWh)</label>
                    <input type="text" name="pemakaian_sendiri" class="form-control" placeholder="Masukkan pemakaian sendiri" >
                </div>
                <div>
                    <label>
                        <input type="checkbox" name="laporan_bulanan" value="1"> Laporan Bulanan
                    </label>
                    <label>
                        <input type="checkbox" name="data_pembangkit" value="1"> Data Pembangkit
                    </label>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="dataCheck" />
                    <label class="form-check-label" for="dataCheck">Saya pastikan semua data terisi dengan benar</label>
                </div>
                <button type="submit" class="btn btn-success" id="submitBtn" disabled>Simpan</button>
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

<!-- SCRIPT CHECKBOX PENGISIAN DATA-->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkbox = document.getElementById('dataCheck');
        const submitBtn = document.getElementById('submitBtn');
        checkbox.addEventListener('change', function() {
            submitBtn.disabled = !checkbox.checked;
        });
    });
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
<script>
    function replaceAsterisk(input) {
        console.log("Input before replacement:", input.value); // Debugging
        input.value = input.value.replace(/\*/g, "°");
        console.log("Input after replacement:", input.value); // Debugging
    }

    function addInputListeners() {
        const latitudeInputs = document.querySelectorAll('input[name="latitude[]"]');
        const longitudeInputs = document.querySelectorAll('input[name="longitude[]"]');

        latitudeInputs.forEach(input => {
            input.addEventListener('input', function() {
                console.log("Latitude input detected:", this.value); // Debugging
                replaceAsterisk(this);
            });
        });

        longitudeInputs.forEach(input => {
            input.addEventListener('input', function() {
                console.log("Longitude input detected:", this.value); // Debugging
                replaceAsterisk(this);
            });
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        addInputListeners(); // Tambahkan listener untuk input yang sudah ada
    });

    let unitCounter = 0;

    function generateYearsOptions() {
        let options = "";
        for (let year = 2030; year >= 2000; year--) {
            options += `<option value="${year}">${year}</option>`;
        }
        return options;
    }

    function tambahUnit() {
        unitCounter++;
        const i = unitCounter;
        const tahunOptions = generateYearsOptions();
        const formHTML = `
        <div class="unit-form border rounded p-3 mb-4" id="unit_${i}">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5>Unit ${i}</h5>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="hapusUnit(${i})">Hapus</button>
            </div>
            <div class="mb-3">
                <label class="form-label">Alamat Pembangkit</label>
                <input type="text" name="alamat[]" class="form-control" placeholder="Masukkan Alamat" required>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label class="form-label">Latitude</label>
                    <input type="text" name="latitude[]" class="form-control" placeholder="Contoh: 3*26'43&quot;LS" required>
                    <small class="text-danger">Gunakan tanda * untuk derajat.</small>
                </div>
                <div class="col">
                    <label class="form-label">Longitude</label>
                    <input type="text" name="longitude[]" class="form-control" placeholder="Contoh: 114*50'21&quot;BT" required>
                    <small class="text-danger">Gunakan tanda * untuk derajat.</small>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Jenis Pembangkit</label>
                <select name="jenis_pembangkit[]" class="form-select">
                    <option value="">-- Pilih Jenis Pembangkit --</option>
                    <option value="PLTD">PLTD</option>
                    <option value="PLTS">PLTS</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Fungsi</label>
                <select name="fungsi[]" class="form-select" required>
                    <option value="">-- Pilih Fungsi --</option>
                    <option value="Utama">Utama</option>
                    <option value="Darurat">Darurat</option>
                    <option value="Cadangan">Cadangan</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Kapasitas Terpasang (MW)</label>
                <input type="text" name="kapasitas_terpasang[]" class="form-control" placeholder="Contoh: 1.250,75" required>
                <small class="text-danger">Titik = ribuan, koma = desimal.</small>
            </div>
            <div class="mb-3">
                <label class="form-label">Daya Mampu Netto (MW)</label>
                <input type="text" name="daya_mampu_netto[]" class="form-control" placeholder="Contoh: 1.250,75" required>
            </div>
            <div class="mb-3">
                <label class="form-label">No. Unit</label>
                <input type="text" name="no_unit[]" class="form-control" placeholder="Masukkan nomor unit" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Tahun Operasi</label>
                <select name="tahun_operasi[]" class="form-select" required>
                    <option value="">-- Pilih Tahun --</option>
                    ${tahunOptions}
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Status Operasi</label>
                <select name="status_operasi[]" class="form-select" required>
                    <option value="">-- Pilih Status Operasi --</option>
                    <option value="Beroperasi">Beroperasi</option>
                    <option value="Maintenance/Perbaikan">Maintenance/Perbaikan</option>
                    <option value="Rusak">Rusak</option>
                </select>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label class="form-label">Jenis Bahan Bakar</label>
                    <select name="bahan_bakar_jenis[]" class="form-select" required>
                        <option value="">-- Pilih Jenis Bahan Bakar --</option>
                        <option value="Solar">Solar</option>
                        <option value="Biomasa">Biomasa</option>
                    </select>
                </div>
                <div class="col">
                    <label class="form-label">Satuan Bahan Bakar</label>
                    <select name="bahan_bakar_satuan[]" class="form-select" required>
                        <option value="">-- Pilih Satuan --</option>
                        <option value="Liter">Liter</option>
                        <option value="Ton">Ton</option>
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Volume Bahan Bakar</label>
                <input type="text" name="volume_bb[]" class="form-control" placeholder="Masukkan volume bahan bakar" required>
            </div>
        </div>`;

        document.getElementById('formContainer').insertAdjacentHTML('beforeend', formHTML);
        addInputListeners(); // Tambahkan listener untuk input baru
        alert(`Unit ${i} berhasil ditambahkan!`); // Umpan balik kepada pengguna
    }

    function hapusUnit(id) {
        const unitElement = document.getElementById(`unit_${id}`);
        if (unitElement) {
            unitElement.remove();
            alert(`Unit ${id} berhasil dihapus!`); // Umpan balik kepada pengguna
        }
    }

    function resetSemua() {
        if (confirm("Yakin ingin menghapus semua unit?")) {
            document.getElementById('formContainer').innerHTML = "";
            unitCounter = 0;
            alert("Semua unit berhasil direset!"); // Umpan balik kepada pengguna
        }
    }
</script>