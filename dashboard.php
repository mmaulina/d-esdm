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
        'Kota Banjarbaru', 'Kota Banjarmasin', 'Balangan', 'Banjar', 'Barito Kuala',
        'Hulu Sungai Selatan', 'Hulu Sungai Tengah', 'Hulu Sungai Utara', 'Kotabaru',
        'Tabalong', 'Tanah Bumbu', 'Tanah Laut', 'Tapin'
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

        <div class="row">
            <div class="col-md-6">
                <div class="card text-black mb-3" style="background-color: #FCDC2A;">
                    <div class="card-body">
                        <h5 class="card-title">Total Perusahaan</h5>
                        <p class="card-text"><?php echo $total_perusahaan; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-black mb-3" style="background-color: #008B47;">
                    <div class="card-body">
                        <h5 class="card-title">Total Kota dengan Perusahaan</h5>
                        <p class="card-text"><?php echo $total_kota; ?></p>
                    </div>
                </div>
            </div>
        </div>

<!-- Tampilkan Konten -->
<div class="row mt-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') : ?>
            <div class="btn-group d-inline-flex">
                <a href="?page=tabel" class="btn btn-success">Tabel Konten</a>
            </div>
        <?php endif; ?>
        <h5 class="fw-bold mb-0">News</h5>
    </div>
</div>
</div>

<div class="container">
    <div class="timeline position-relative">
        <?php foreach ($konten_list as $konten) : ?>
            <div class="timeline-item d-flex flex-column align-items-center text-center position-relative">
                <div class="circle bg-dark rounded-circle position-absolute" style="width: 15px; height: 15px; left: -10px; top: 50%; transform: translateY(-50%);"></div>
                <div class="content w-75">
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
                <div class="line position-absolute bg-dark" style="width: 2px; height: 100%; left: -2px; top: 0;"></div>
            </div>
        <?php endforeach; ?>
    </div>
</div>



    </div>
</main>
