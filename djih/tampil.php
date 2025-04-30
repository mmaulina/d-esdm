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

try {
    $db = new Database();
    $conn = $db->getConnection();

    // Tandai semua konten baru sebagai dilihat
    $query = "INSERT IGNORE INTO djih_dilihat (id_user, konten_id) 
              SELECT :id_user, id FROM djih";
    $stmt = $conn->prepare($query);
    $stmt->execute(['id_user' => $_SESSION['id_user']]);

    // Ambil semua konten
    $sql = "SELECT * FROM djih ORDER BY tanggal DESC";
    $stmt = $conn->query($sql);
    $konten_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Kelompokkan berdasarkan id_title
    $grouped_konten = [];
    foreach ($konten_list as $konten) {
        $grouped_konten[$konten['id_title']][] = $konten;
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<div>
    <!-- Tampilkan Konten -->
    <div class="container mt-4">
        <h2 class="text-center mt-4"><i class="fas fa-bolt" style="color: #ffc107;"></i> DJIH <i class="fas fa-bolt" style="color: #ffc107;"></i> </h2>
        <hr>
        <div class="card shadow" style="overflow-x: auto; max-height: calc(100vh - 150px); overflow-y: auto;">
            <div class="card-body">
                <div class="row mt-3">
                    <div class="col-12 d-flex justify-content-between align-items-center">
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] !== 'umum' && $_SESSION['role'] !== 'kementerian') : ?>
                            <div class="btn-group d-inline-flex">
                                <a href="?page=tabel_djih" class="btn btn-success">Tabel Konten</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="container">
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
                                    <?php if (!empty($data)): ?>
                                        <p><?php echo nl2br($data['caption']); ?></p>
                                    <?php else: ?>
                                        <p><em>Data tidak ditemukan.</em></p>
                                    <?php endif; ?>
            
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