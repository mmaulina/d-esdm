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
$totalProduksi = 0;
$totalKonsumsi = 0;
$totalProduksiPerKota = [];
$totalKonsumsiPerKota = [];

// Fungsi parsing angka format IDR
function parseNumber($val) {
    if ($val === '-' || trim($val) === '') return 0;
    $val = str_replace('.', '', $val);     // hapus titik ribuan
    $val = str_replace(',', '.', $val);    // ubah koma jadi titik (desimal)
    return (float) $val;
}

// Ambil data laporan
$sql = "SELECT kabupaten, produksi_sendiri, pemb_sumber_lain, penj_ke_pelanggan, penj_ke_pln, pemakaian_sendiri FROM laporan_bulanan";
$stmt = $conn->query($sql);

foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $laporan) {
    $kabupaten = $laporan['kabupaten'];

    $produksi = parseNumber($laporan['produksi_sendiri']) + parseNumber($laporan['pemb_sumber_lain']);
    $konsumsi = parseNumber($laporan['penj_ke_pelanggan']) + parseNumber($laporan['penj_ke_pln']) + parseNumber($laporan['pemakaian_sendiri']);

    $totalProduksiPerKota[$kabupaten] = ($totalProduksiPerKota[$kabupaten] ?? 0) + $produksi;
    $totalKonsumsiPerKota[$kabupaten] = ($totalKonsumsiPerKota[$kabupaten] ?? 0) + $konsumsi;

    $totalProduksi += $produksi;
    $totalKonsumsi += $konsumsi;
}

// Pastikan semua kabupaten/kota ada
foreach ($daftarKabupatenKotaKalsel as $kota) {
    if (!isset($totalProduksiPerKota[$kota])) {
        $totalProduksiPerKota[$kota] = 0;
    }
    if (!isset($totalKonsumsiPerKota[$kota])) {
        $totalKonsumsiPerKota[$kota] = 0;
    }
}


?>
<main>
    <div class="container mt-4">
        <!-- Tambahan Welcome Text -->
        <h2 class="text-center mb-3">Welcome to Dashboard</h2>
        <hr>
        <div class="card shadow" style="overflow-x: auto; max-height: calc(100vh - 150px); overflow-y: auto;">
            <div class="card-body">
                <!-- Row for Total Perusahaan and Total Kota -->
                <div class="row">
                    <div class="col mb-3">
                        <div class="card text-black" style="background-color: #FCDC2A;">
                            <div class="card-body">
                                <h5 class="card-title">Total Perusahaan</h5>
                                <p class="card-text"><?php echo $total_perusahaan; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col mb-3">
                        <div class="card text-black" style="background-color: #008B47;">
                            <div class="card-body">
                                <h5 class="card-title">Total Kota dengan Perusahaan</h5>
                                <p class="card-text"><?php echo $total_kota; ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Row for Perusahaan Belum Upload Laporan -->
                <div class="row mt-3">
                    <div class="col">
                        <h5 class="fw-bold mb-3">Perusahaan Belum Upload Laporan Semester</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Kabupaten/Kota</th>
                                        <th>Belum Upload Semester I (<?php echo $tahun; ?>)</th>
                                        <th>Belum Upload Semester II (<?php echo $tahun; ?>)</th>
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
                                                <td><?php echo $data['semester1']; ?></td>
                                                <td><?php echo $data['semester2']; ?></td>
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
                                        <td><strong><?php echo $total_semester1; ?></strong></td>
                                        <td><strong><?php echo $total_semester2; ?></strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
<!-- Ringkasan Produksi dan Konsumsi per Kota -->
                <div class="row mt-3">
                    <div class="col">
                        <h5 class="fw-bold mb-3">Total Produksi & Konsumsi per Kabupaten/Kota</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead class="table-dark">
                                    <tr class="text-center">
                                        <th>Kabupaten/Kota</th>
                                        <th>Total Produksi (kWh)</th>
                                        <th>Total Konsumsi (kWh)</th>
                                    </tr>
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
                                <tfoot class="table-light fw-bold">
                                    <tr>
                                        <td class="text-end">Total:</td>
                                        <td class="text-end"><?= number_format($totalProduksi, 2, ',', '.'); ?></td>
                                        <td class="text-end"><?= number_format($totalKonsumsi, 2, ',', '.'); ?></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- Row for News Content -->
                <div class="row mt-4">
                    <div class="col-12 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">News</h5>
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] !== 'umum') : ?>
                            <div class="btn-group d-inline-flex">
                                <a href="?page=tabel" class="btn btn-success">Tabel Konten</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12">
                        <div class="timeline position-relative">
                            <?php foreach ($grouped_konten as $id_title => $kontens) : ?>
                                <div class="timeline-item d-flex flex-column align-items-center text-center position-relative">
                                    <div class="circle bg-dark rounded-circle position-absolute" style="width: 15px; height: 15px; left: -10px; top: 50%; transform: translateY(-50%);"></div>
                                    <div class="content w-75 ms-3">

                                        <!-- Title tampil sekali -->
                                        <h5 class="card-text mt-3"><?php echo htmlspecialchars($kontens[0]['title']); ?></h5>

                                        <!-- Konten berdampingan -->
                                        <div class="d-flex flex-wrap justify-content-center gap-3 mt-3">
                                            <?php foreach ($kontens as $konten) : ?>
                                                <?php if ($konten['jenis_konten'] === 'gambar') : ?>
                                                    <img src="<?php echo htmlspecialchars($konten['konten']); ?>" class="img-fluid rounded" style="width: 150px; height: auto;" alt="Konten Gambar">
                                                <?php elseif ($konten['jenis_konten'] === 'file') : ?>
                                                    <a href="<?php echo htmlspecialchars($konten['konten']); ?>" class="btn btn-secondary" style="width: 150px;">Download File</a>
                                                <?php elseif ($konten['jenis_konten'] === 'link') : ?>
                                                    <a href="<?php echo htmlspecialchars($konten['konten']); ?>" target="_blank" class="btn btn-info" style="width: 150px;">Lihat Link</a>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>

                                        <!-- Caption tampil sekali -->
                                        <p class="card-text mt-3"><?php echo nl2br(htmlspecialchars($kontens[0]['caption'])); ?></p>

                                        <!-- Tanggal dari konten pertama -->
                                        <p class="card-text"><small class="text-muted">Diupload pada: <?php echo $kontens[0]['tanggal']; ?></small></p>

                                    </div>
                                    <div class="line position-absolute bg-dark" style="width: 2px; height: 100%; left: 0px; top: 0;"></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>