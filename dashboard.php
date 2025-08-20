<?php
include "koneksi.php";

try {
    $db = new Database();
    $conn = $db->getConnection();

    // Periksa apakah user adalah 'umum' dan tidak ada di tabel profil
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'umum') {
        $query = "SELECT COUNT(*) AS jumlah FROM profil WHERE id_user = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$_SESSION['id_user']]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row['jumlah'] == 0) {
            echo '<div class="alert alert-warning text-center" role="alert">Anda wajib melengkapi data profil terlebih dahulu! di menu profil perusahaan</div>';
        }
    }

    // Query untuk menghitung jumlah perusahaan
    $sql = "SELECT COUNT(*) AS total_perusahaan FROM profil";
    $stmt = $conn->query($sql);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $total_perusahaan = $row['total_perusahaan'] ?? 0;

    // Query untuk menghitung jumlah perusahaan di kota dan kabupaten lainnya
    $kota_kabupaten = [
        'Kota Banjarbaru',
        'Kota Banjarmasin',
        'Balangan',
        'Banjar',
        'Barito Kuala',
        'Hulu Sungai Selatan',
        'Hulu Sungai Tengah',
        'Hulu Sungai Utara',
        'Kotabaru',
        'Tabalong',
        'Tanah Bumbu',
        'Tanah Laut',
        'Tapin'
    ];

    $jumlah_per_kota = [];
    $total_kota = 0;

    $sql_kota = "SELECT COUNT(*) AS total FROM profil WHERE kabupaten = ?";
    $stmt_kota = $conn->prepare($sql_kota);

    foreach ($kota_kabupaten as $kota) {
        $stmt_kota->execute([$kota]);
        $row_kota = $stmt_kota->fetch(PDO::FETCH_ASSOC);
        $jumlah_per_kota[$kota] = $row_kota['total'] ?? 0;

        if ($row_kota['total'] > 0) {
            $total_kota++;
        }
    }

    // Tandai semua konten baru sebagai dilihat
    $query = "INSERT IGNORE INTO konten_dilihat (id_user, konten_id) 
    SELECT :id_user, id FROM news";
    $stmt = $conn->prepare($query);
    $stmt->execute(['id_user' => $_SESSION['id_user']]);

    // Ambil semua konten
    $sql = "SELECT * FROM news ORDER BY tanggal DESC";
    $stmt = $conn->query($sql);
    $konten_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $grouped_konten = [];
    foreach ($konten_list as $konten) {
        $grouped_konten[$konten['id_title']][] = $konten;
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

$kota_kabupaten_kalsel = [
    'Kota Banjarbaru',
    'Kota Banjarmasin',
    'Balangan',
    'Banjar',
    'Barito Kuala',
    'Hulu Sungai Selatan',
    'Hulu Sungai Tengah',
    'Hulu Sungai Utara',
    'Kotabaru',
    'Tabalong',
    'Tanah Bumbu',
    'Tanah Laut',
    'Tapin'
];

// Variabel untuk menyimpan data laporan yang belum di-upload
$laporan_tidak_upload = [];
$tahun = date('Y'); // Atau sesuaikan dengan tahun yang diinginkan

// Ambil data perusahaan dari tabel profil
$sql_perusahaan = "SELECT id_user, kabupaten, nama_perusahaan FROM profil";
$stmt_perusahaan = $conn->query($sql_perusahaan);
$daftar_perusahaan = $stmt_perusahaan->fetchAll(PDO::FETCH_ASSOC);

foreach ($daftar_perusahaan as $perusahaan) {
    $id_user = $perusahaan['id_user'];
    $kabupaten = $perusahaan['kabupaten'];

    // Cek Semester I
    $stmt1 = $conn->prepare("SELECT COUNT(*) FROM laporan_semester WHERE id_user = :id_user AND semester = :semester");
    $stmt1->execute([
        'id_user' => $id_user,
        'semester' => "Semester I $tahun"
    ]);
    $hasSemester1 = $stmt1->fetchColumn() > 0;

    // Cek Semester II
    $stmt2 = $conn->prepare("SELECT COUNT(*) FROM laporan_semester WHERE id_user = :id_user AND semester = :semester");
    $stmt2->execute([
        'id_user' => $id_user,
        'semester' => "Semester II $tahun"
    ]);
    $hasSemester2 = $stmt2->fetchColumn() > 0;

    // Mengelompokkan laporan berdasarkan kabupaten
    if (!isset($laporan_tidak_upload[$kabupaten])) {
        $laporan_tidak_upload[$kabupaten] = [
            'semester1' => 0,
            'semester2' => 0
        ];
    }

    if (!$hasSemester1) {
        $laporan_tidak_upload[$kabupaten]['semester1']++;
    }
    if (!$hasSemester2) {
        $laporan_tidak_upload[$kabupaten]['semester2']++;
    }
}

$daftarKabupatenKotaKalsel = [
    'Kabupaten Balangan',
    'Kabupaten Banjar',
    'Kabupaten Barito Kuala',
    'Kabupaten Hulu Sungai Selatan',
    'Kabupaten Hulu Sungai Tengah',
    'Kabupaten Hulu Sungai Utara',
    'Kabupaten Kotabaru',
    'Kabupaten Tabalong',
    'Kabupaten Tanah Bumbu',
    'Kabupaten Tanah Laut',
    'Kabupaten Tapin',
    'Kota Banjarbaru',
    'Kota Banjarmasin'
];

function parseNumber($val)
{
    if ($val === '-' || trim($val) === '') return 0;
    // Hilangkan titik ribuan
    $val = str_replace('.', '', $val);
    // Ubah koma desimal menjadi titik
    $val = str_replace(',', '.', $val);
    // Jika setelah konversi bukan angka, kembalikan 0
    return is_numeric($val) ? (float)$val : 0;
}

// List kabupaten/kota di Kalsel tetap
$daftarKabupatenKotaKalsel = [
    'Balangan',
    'Banjar',
    'Barito Kuala',
    'Hulu Sungai Selatan',
    'Hulu Sungai Tengah',
    'Hulu Sungai Utara',
    'Kotabaru',
    'Tabalong',
    'Tanah Bumbu',
    'Tanah Laut',
    'Tapin',
    'Kota Banjarbaru',
    'Kota Banjarmasin'
];

// Inisialisasi variabel array hasil
$totalProduksiPerKota = [];
$totalKonsumsiPerKota = [];
$totalVolumeBBPerKota = [];
$jumlahJenisPembangkitPerKota = [];
$jumlahFungsiPerKota = [];
$jumlahStatusPerKota = [];

$totalProduksi = 0;
$totalKonsumsi = 0;

// Inisialisasi semua kota agar terdefinisi dengan nilai awal 0, agar data konsisten di output
foreach ($daftarKabupatenKotaKalsel as $kota) {
    $totalProduksiPerKota[$kota] = 0;
    $totalKonsumsiPerKota[$kota] = 0;
    $totalVolumeBBPerKota[$kota] = ['Solar' => 0, 'Biomasa' => 0];
    $jumlahJenisPembangkitPerKota[$kota] = ['PLTD' => 0, 'PLTS' => 0];
    $jumlahFungsiPerKota[$kota] = ['utama' => 0, 'cadangan' => 0, 'darurat' => 0];
    $jumlahStatusPerKota[$kota] = ['Beroperasi' => 0, 'Perbaikan' => 0, 'Rusak' => 0, 'Rusak Total' => 0];
}

// Ambil data laporan bulanan untuk tahun saat ini
$tahunSekarang = date('Y');
$sql1 = "SELECT kabupaten, produksi_sendiri, pemb_sumber_lain, penj_ke_pelanggan, penj_ke_pln, pemakaian_sendiri 
         FROM laporan_bulanan 
         WHERE tahun = :tahun";
$stmt1 = $conn->prepare($sql1);
$stmt1->bindParam(':tahun', $tahunSekarang, PDO::PARAM_INT);
$stmt1->execute();
$laporanBulanan = $stmt1->fetchAll(PDO::FETCH_ASSOC);


foreach ($laporanBulanan as $laporan) {
    $kabupaten = trim($laporan['kabupaten']);
    if (!in_array($kabupaten, $daftarKabupatenKotaKalsel)) {
        // Abaikan data jika kabupaten/kota tidak valid
        continue;
    }

    // Hitung total produksi dan konsumsi
    $produksi = parseNumber($laporan['produksi_sendiri']) + parseNumber($laporan['pemb_sumber_lain']);
    $konsumsi = parseNumber($laporan['penj_ke_pelanggan']) + parseNumber($laporan['penj_ke_pln']) + parseNumber($laporan['pemakaian_sendiri']);

    // Tambahkan ke total per kota
    $totalProduksiPerKota[$kabupaten] += $produksi;
    $totalKonsumsiPerKota[$kabupaten] += $konsumsi;

    // Tambahkan ke total keseluruhan
    $totalProduksi += $produksi;
    $totalKonsumsi += $konsumsi;
}

// Fungsi parse number khusus untuk volume bahan bakar (mirip parseNumber)
function parseNumber2($value)
{
    $value = str_replace('.', '', $value);
    $value = str_replace(',', '.', $value);
    return is_numeric($value) ? (float)$value : 0;
}

$bulanFilter = $_GET['bulan'] ?? date('F');
$tahunFilter = $_GET['tahun'] ?? date('Y');

// Pastikan mapping bahasa Inggris ke Indonesia (jika diperlukan)
$bulanMap = [
    'January' => 'Januari',
    'February' => 'Februari',
    'March' => 'Maret',
    'April' => 'April',
    'May' => 'Mei',
    'June' => 'Juni',
    'July' => 'Juli',
    'August' => 'Agustus',
    'September' => 'September',
    'October' => 'Oktober',
    'November' => 'November',
    'December' => 'Desember'
];
if (array_key_exists($bulanFilter, $bulanMap)) {
    $bulanFilter = $bulanMap[$bulanFilter]; // Convert jika perlu
}

// Lanjutkan ke query...
$sql2 = "SELECT kabupaten, bahan_bakar_jenis, volume_bb, jenis_pembangkit, fungsi, status_operasi 
         FROM pembangkit 
         WHERE bulan = :bulan AND tahun = :tahun";
$stmt2 = $conn->prepare($sql2);
$stmt2->bindParam(':bulan', $bulanFilter, PDO::PARAM_STR);
$stmt2->bindParam(':tahun', $tahunFilter, PDO::PARAM_INT);
$stmt2->execute();
$pembangkit = $stmt2->fetchAll(PDO::FETCH_ASSOC);



foreach ($pembangkit as $pb) {
    $kabupaten = trim($pb['kabupaten']);
    if (!in_array($kabupaten, $daftarKabupatenKotaKalsel)) {
        // Abaikan jika kabupaten tidak valid
        continue;
    }

    // Standardisasi format nama bahan bakar
    $bahanBakar = ucfirst(strtolower(trim($pb['bahan_bakar_jenis']))); // contoh: "Solar", "Biomasa"
    $jenis = strtoupper(trim($pb['jenis_pembangkit'])); // contoh: PLTD, PLTS
    $fungsi = strtolower(trim($pb['fungsi'])); // contoh: utama, cadangan, darurat
    $volume = parseNumber2($pb['volume_bb']);
    $status = ucwords(strtolower(trim($pb['status_operasi']))); // contoh: utama, cadangan, darurat

    // Hitung total volume bahan bakar hanya jika bahan bakar valid
    if (in_array($bahanBakar, ['Solar', 'Biomasa'])) {
        $totalVolumeBBPerKota[$kabupaten][$bahanBakar] += $volume;
    }

    // Hitung jumlah jenis pembangkit jika valid
    if (in_array($jenis, ['PLTD', 'PLTS'])) {
        $jumlahJenisPembangkitPerKota[$kabupaten][$jenis]++;
    }

    if (in_array($status, ['Beroperasi', 'Perbaikan', 'Rusak', 'Rusak Total'])) {
        $jumlahStatusPerKota[$kabupaten][$status]++;
    }

    // Hitung jumlah fungsi jika valid
    if (in_array($fungsi, ['utama', 'cadangan', 'darurat'])) {
        $jumlahFungsiPerKota[$kabupaten][$fungsi]++;
    }
}

// Sekarang kamu punya data produksi, konsumsi, dan volume BB per kabupaten/kota di variabel masing-masing

?>
<main>
    <div class="container mt-4">
        <h2 class="text-center mb-3"><i class="fas fa-bolt" style="color: #ffc107;"></i>Welcome to E-WASDAL GATRIK<i class="fas fa-bolt" style="color: #ffc107;"></i></h2>
        <hr>
        <div class="card shadow" style="overflow-x: auto; max-height: calc(100vh - 150px); overflow-y: auto;">
            <div class="card-body">
                <div class="row">
                    <div class="col-md mb-3">
                        <div class="card text-black h-100" style="background-color: #FCDC2A;">
                            <div class="card-body">
                                <h5 class="card-title">Total Perusahaan yang terdaftar di website</h5>
                                <p class="card-text"><?php echo $total_perusahaan; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md mb-3">
                        <div class="card text-black h-100" style="background-color: #008B47;">
                            <div class="card-body">
                                <h5 class="card-title">Total Produksi Listrik (kWh)</h5>
                                <p class="card-text"><?php echo number_format($totalProduksi, 2, ',', '.'); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md mb-3">
                        <div class="card text-black h-100" style="background-color: #E68A00;">
                            <div class="card-body">
                                <h5 class="card-title">Total Konsumsi Listrik (kWh)</h5>
                                <p class="card-text"><?php echo number_format($totalKonsumsi, 2, ',', '.'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Row for Perusahaan Belum Upload Laporan -->
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] !== 'umum' && $_SESSION['role'] !== 'kementerian') : ?>
                    <div class="row mt-3">
                        <div class="col">
                            <h5 class="fw-bold mb-3">Perusahaan Belum Upload Laporan Semester</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-sm" id="tabel-belum-upload">
                                    <thead class="table-dark">
                                        <tr>
                                            <th class="text-center" onclick="sortTable('tabel-belum-upload', 0)">Kabupaten/Kota <i class="fa fa-sort"></th>
                                            </th>
                                            <th class="text-center" onclick="sortTable('tabel-belum-upload', 1)">Belum Upload Semester I (<?php echo $tahun; ?>) <i class="fa fa-sort"></th>
                                            </th>
                                            <th class="text-center" onclick="sortTable('tabel-belum-upload', 2)">Belum Upload Semester II (<?php echo $tahun; ?>) <i class="fa fa-sort"></th>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $total_semester1 = 0;
                                        $total_semester2 = 0;
                                        foreach ($kota_kabupaten_kalsel as $kota):
                                            $data = isset($laporan_tidak_upload[$kota]) ? $laporan_tidak_upload[$kota] : ['semester1' => 0, 'semester2' => 0];

                                            $total_semester1 += $data['semester1'];
                                            $total_semester2 += $data['semester2'];
                                        ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($kota); ?></td>
                                                <td class="text-center"><?php echo $data['semester1']; ?></td>
                                                <td class="text-center"><?php echo $data['semester2']; ?></td>
                                            </tr>
                                        <?php endforeach; ?>

                                        <?php if (empty($laporan_tidak_upload)): ?>
                                            <tr>
                                                <td colspan="3" class="text-center">Semua perusahaan telah mengunggah laporan semester.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr class="table-dark">
                                            <td><strong>Total</strong></td>
                                            <td class="text-center"><strong><?php echo $total_semester1; ?></strong></td>
                                            <td class="text-center"><strong><?php echo $total_semester2; ?></strong></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- TOTAL PRODUKSI & KONSUMSI -->
                <div class="row mt-3">
                    <div class="col">
                        <h5 class="fw-bold mb-3">Total Produksi & Konsumsi per Kabupaten/Kota</h5>
                        <div class="table-responsive" style="overflow-x:auto; -webkit-overflow-scrolling: touch;">
                            <table class="table table-bordered table-striped table-sm" id="tabel-produksi-konsumsi" style="min-width: 800px; white-space: nowrap;">
                                <thead class="table-dark">
                                    <tr class="text-center align-middle">
                                        <th rowspan="2" onclick="sortTable('tabel-produksi-konsumsi', 0)">Kabupaten/Kota <i class="fa fa-sort"></i></th>
                                        <th rowspan="2" onclick="sortTable('tabel-produksi-konsumsi', 1)">Total Produksi Listrik (kWh) <i class="fa fa-sort"></i></th>
                                        <th rowspan="2" onclick="sortTable('tabel-produksi-konsumsi', 2)">Total Konsumsi Listrik (kWh) <i class="fa fa-sort"></i></th>
                                </thead>
                                <tbody>
                                    <?php foreach ($totalProduksiPerKota as $kota => $produksi): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($kota); ?></td>
                                            <td class="text-end"><?= number_format($produksi, 2, ',', '.'); ?></td>
                                            <td class="text-end"><?= number_format($totalKonsumsiPerKota[$kota] ?? 0, 2, ',', '.'); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr class="table-dark">
                                        <td><strong>Total:</strong></td>
                                        <td class="text-end"><strong><?= number_format($totalProduksi, 2, ',', '.'); ?></strong></td>
                                        <td class="text-end"><strong><?= number_format($totalKonsumsi, 2, ',', '.'); ?></strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- TOTAL PEMBANGKIT -->
                <div class="row mt-3">
                    <div class="col">
                        <h5 class="fw-bold mb-3">Total Pembangkit</h5>
                        <div class="table-responsive" style="overflow-x:auto; -webkit-overflow-scrolling: touch;">
                            <form method="GET" class="row g-2 mb-3">
                                <div class="col-md-3">
                                    <label for="filter_bulan" class="form-label">Bulan</label>
                                    <select class="form-select" name="bulan" id="filter_bulan">
                                        <?php
                                        $bulanList = [
                                            'Januari',
                                            'Februari',
                                            'Maret',
                                            'April',
                                            'Mei',
                                            'Juni',
                                            'Juli',
                                            'Agustus',
                                            'September',
                                            'Oktober',
                                            'November',
                                            'Desember'
                                        ];

                                        // Ambil bulan sekarang dalam angka lalu konversi ke nama bulan Indonesia
                                        $bulanSekarang = $bulanList[date('n') - 1];
                                        $bulanDipilih = $_GET['bulan'] ?? $bulanSekarang;

                                        foreach ($bulanList as $bln) {
                                            $selected = ($bln == $bulanDipilih) ? 'selected' : '';
                                            echo "<option value='$bln' $selected>$bln</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="filter_tahun" class="form-label">Tahun</label>
                                    <select class="form-select" name="tahun" id="filter_tahun">
                                        <?php
                                        $tahunSekarang = date('Y');
                                        $tahunDipilih = $_GET['tahun'] ?? $tahunSekarang;

                                        for ($i = $tahunSekarang; $i >= $tahunSekarang - 5; $i--) {
                                            $selected = ($i == $tahunDipilih) ? 'selected' : '';
                                            echo "<option value='$i' $selected>$i</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-3 align-self-end">
                                    <button type="submit" class="btn btn-primary">Tampilkan</button>
                                </div>
                            </form>

                            <table class="table table-bordered table-striped table-sm" id="tabel-produksi-konsumsi" style="min-width: 1000px; white-space: nowrap;">
                                <thead class="table-dark">
                                    <tr class="text-center align-middle">
                                        <th rowspan="2" onclick="sortTable('tabel-produksi-konsumsi', 0)">Kabupaten/Kota <i class="fa fa-sort"></i></th>
                                        <th rowspan="2" onclick="sortTable('tabel-produksi-konsumsi', 3)">Total Konsumsi BBM Solar (L) <i class="fa fa-sort"></i></th>
                                        <th rowspan="2" onclick="sortTable('tabel-produksi-konsumsi', 4)">Total Konsumsi BBM Biomasa (Ton) <i class="fa fa-sort"></i></th>
                                        <th rowspan="2" onclick="sortTable('tabel-produksi-konsumsi', 5)">Total Pembangkit PLTD <i class="fa fa-sort"></i></th>
                                        <th rowspan="2" onclick="sortTable('tabel-produksi-konsumsi', 6)">Total Pembangkit PLTS <i class="fa fa-sort"></i></th>
                                        <th colspan="3">Sifat Penggunaan Pembangkit</th>
                                        <th rowspan="2" onclick="sortTable('tabel-produksi-konsumsi', 10)">Status Pembangkit (Beroperasi) <i class="fa fa-sort"></i></th>
                                        <th rowspan="2" onclick="sortTable('tabel-produksi-konsumsi', 11)">Status Pembangkit (Perbaikan) <i class="fa fa-sort"></i></th>
                                        <th rowspan="2" onclick="sortTable('tabel-produksi-konsumsi', 12)">Status Pembangkit (Rusak) <i class="fa fa-sort"></i></th>
                                        <th rowspan="2" onclick="sortTable('tabel-produksi-konsumsi', 13)">Status Pembangkit (Rusak Total) <i class="fa fa-sort"></i></th>
                                    </tr>
                                    <tr class="text-center">
                                        <th onclick="sortTable('tabel-produksi-konsumsi', 7)">Total Pembangkit UTAMA <i class="fa fa-sort"></i></th>
                                        <th onclick="sortTable('tabel-produksi-konsumsi', 8)">Total Pembangkit CADANGAN <i class="fa fa-sort"></i></th>
                                        <th onclick="sortTable('tabel-produksi-konsumsi', 9)">Total Pembangkit DARURAT <i class="fa fa-sort"></i></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($totalProduksiPerKota as $kota => $produksi): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($kota); ?></td>
                                            <td class="text-end"><?= number_format($totalVolumeBBPerKota[$kota]['Solar'] ?? 0, 2, ',', '.'); ?></td>
                                            <td class="text-end"><?= number_format($totalVolumeBBPerKota[$kota]['Biomasa'] ?? 0, 2, ',', '.'); ?></td>
                                            <td class="text-end"><?= number_format($jumlahJenisPembangkitPerKota[$kota]['PLTD']); ?></td>
                                            <td class="text-end"><?= number_format($jumlahJenisPembangkitPerKota[$kota]['PLTS']); ?></td>
                                            <td class="text-end"><?= ($jumlahFungsiPerKota[$kota]['utama']); ?></td>
                                            <td class="text-end"><?= ($jumlahFungsiPerKota[$kota]['cadangan']); ?></td>
                                            <td class="text-end"><?= ($jumlahFungsiPerKota[$kota]['darurat']); ?></td>
                                            <td class="text-end"><?= ($jumlahStatusPerKota[$kota]['Beroperasi']); ?></td>
                                            <td class="text-end"><?= ($jumlahStatusPerKota[$kota]['Perbaikan']); ?></td>
                                            <td class="text-end"><?= ($jumlahStatusPerKota[$kota]['Rusak']); ?></td>
                                            <td class="text-end"><?= ($jumlahStatusPerKota[$kota]['Rusak Total']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr class="table-dark" style="font-weight: bold;">
                                        <td>Total:</td>
                                        <td class="text-end"><?= number_format(array_sum(array_column($totalVolumeBBPerKota, 'Solar')), 2, ',', '.'); ?></td>
                                        <td class="text-end"><?= number_format(array_sum(array_column($totalVolumeBBPerKota, 'Biomasa')), 2, ',', '.'); ?></td>
                                        <td class="text-end"><?= number_format(array_sum(array_column($jumlahJenisPembangkitPerKota, 'PLTD'))); ?></td>
                                        <td class="text-end"><?= number_format(array_sum(array_column($jumlahJenisPembangkitPerKota, 'PLTS'))); ?></td>
                                        <td class="text-end"><?= number_format(array_sum(array_column($jumlahFungsiPerKota, 'utama'))); ?></td>
                                        <td class="text-end"><?= number_format(array_sum(array_column($jumlahFungsiPerKota, 'cadangan'))); ?></td>
                                        <td class="text-end"><?= number_format(array_sum(array_column($jumlahFungsiPerKota, 'darurat'))); ?></td>
                                        <td class="text-end"><?= number_format(array_sum(array_column($jumlahStatusPerKota, 'Beroperasi'))); ?></td>
                                        <td class="text-end"><?= number_format(array_sum(array_column($jumlahStatusPerKota, 'Perbaikan'))); ?></td>
                                        <td class="text-end"><?= number_format(array_sum(array_column($jumlahStatusPerKota, 'Rusak'))); ?></td>
                                        <td class="text-end"><?= number_format(array_sum(array_column($jumlahStatusPerKota, 'Rusak Total'))); ?></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<script>
    function sortTable(tableId, columnIndex) {
        var table = document.querySelector(`#${tableId} tbody`);
        var rows = Array.from(table.querySelectorAll("tr"));
        var isAscending = table.getAttribute("data-sort-order") === "asc";

        rows.sort((rowA, rowB) => {
            var cellA = rowA.children[columnIndex].textContent.trim().toLowerCase();
            var cellB = rowB.children[columnIndex].textContent.trim().toLowerCase();

            if (!isNaN(cellA) && !isNaN(cellB)) {
                return isAscending ? cellA - cellB : cellB - cellA;
            }

            return isAscending ? cellA.localeCompare(cellB) : cellB.localeCompare(cellA);
        });

        table.innerHTML = "";
        rows.forEach(row => table.appendChild(row));

        table.setAttribute("data-sort-order", isAscending ? "desc" : "asc");

        updateSortIcons(tableId, columnIndex, isAscending);
    }

    function updateSortIcons(tableId, columnIndex, isAscending) {
        var headers = document.querySelectorAll(`#${tableId} thead th i`);
        headers.forEach(icon => icon.className = "fa fa-sort");

        var selectedHeader = document.querySelector(`#${tableId} thead th:nth-child(${columnIndex + 1}) i`);
        if (selectedHeader) {
            selectedHeader.className = isAscending ? "fa fa-sort-up" : "fa fa-sort-down";
        }
    }
</script>