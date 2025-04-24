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

?>
<main>
    <div class="container">
        <!-- Tambahan Welcome Text -->
        <div class="row">
            <div class="col-12">
                <h2 class="text-center mt-4">Welcome to Dashboard</h2>
                <hr>
            </div>
        </div>

        <!-- Row for Total Perusahaan and Total Kota -->
        <div class="row">
            <div class="col-12 col-md-6 mb-3">
                <div class="card text-black" style="background-color: #FCDC2A;">
                    <div class="card-body">
                        <h5 class="card-title">Total Perusahaan</h5>
                        <p class="card-text"><?php echo $total_perusahaan; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 mb-3">
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
            <div class="col-6">
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
                    <?php foreach ($konten_list as $konten) : ?>
                        <div class="timeline-item d-flex flex-column align-items-center text-center position-relative">
                            <div class="circle bg-dark rounded-circle position-absolute" style="width: 15px; height: 15px; left: 0; top: 50%; transform: translateY(-50%);"></div>
                            <div class="content w-75 ms-3">
                                <?php if ($konten['jenis_konten'] === 'gambar') : ?>
                                    <img src="<?php echo htmlspecialchars($konten['konten']); ?>" class="img-fluid w-50 rounded" alt="Konten Gambar">
                                <?php elseif ($konten['jenis_konten'] === 'file') : ?>
                                    <a href="<?php echo htmlspecialchars($konten['konten']); ?>" class="btn btn-secondary" download>Download File</a>
                                <?php elseif ($konten['jenis_konten'] === 'link') : ?>
                                    <a href="<?php echo htmlspecialchars($konten['konten']); ?>" target="_blank" class="btn btn-info">Lihat Link</a>
                                <?php endif; ?>
                                <p class="card-text mt-3"> <?php echo htmlspecialchars($konten['caption']); ?> </p>
                                <p class="card-text"><small class="text-muted">Diupload pada: <?php echo $konten['tanggal']; ?></small></p>
                            </div>
                            <div class="line position-absolute bg-dark" style="width: 2px; height: 100%; left: 0; top: 0;"></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</main>

