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
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<div>
    <!-- Tampilkan Konten -->
    <div class="row">
        <div class="col-12">
            <h2 class="text-center mt-4">DJIH</h2>
            <hr>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] !== 'umum') : ?>
                <div class="btn-group d-inline-flex">
                    <a href="?page=tabel_djih" class="btn btn-success">Tabel Konten</a>
                </div>
            <?php endif; ?>
        </div>
    </div>


    <div class="container">
        <div class="timeline position-relative">
            <?php foreach ($konten_list as $konten) : ?>
                <div class="timeline-item d-flex flex-column align-items-center text-center position-relative">
                    <div class="circle bg-dark rounded-circle position-absolute" style="width: 15px; height: 15px; left: -10px; top: 50%; transform: translateY(-50%);"></div>
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
                    <div class="line position-absolute bg-dark" style="width: 2px; height: 100%; left: 0px; top: 0;"></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

</div>