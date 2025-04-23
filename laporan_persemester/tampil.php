<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Cek apakah pengguna sudah login
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

$id_user = $_SESSION['id_user'];
$role = $_SESSION['role'];

$db = new Database();
$conn = $db->getConnection();

// Query berdasarkan role
$params = [];
if ($role == 'admin' || $role == 'superadmin') {
    $query = "SELECT * FROM laporan_semester ORDER BY FIELD(status, 'diajukan', 'ditolak', 'diterima')";
} else {
    $query = "SELECT * FROM laporan_semester WHERE id_user = :id_user ORDER BY FIELD(status, 'ditolak', 'diajukan', 'diterima')";
    $params[':id_user'] = $id_user;
}

// Cek pencarian
if (!empty($_GET['keyword'])) {
    $keyword = "%" . $_GET['keyword'] . "%";
    $query .= (strpos($query, 'WHERE') === false) ? " WHERE" : " AND";
    $query .= " nama_perusahaan LIKE :keyword";
    $params[':keyword'] = $keyword;
}

// Jalankan Query
$stmt = $conn->prepare($query);
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value, PDO::PARAM_STR);
}
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Proses Persetujuan/Tolak
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['terima_id'])) {
        $id = $_POST['terima_id'];
        $updateQuery = "UPDATE laporan_semester SET status = 'diterima' WHERE id = :id";
    } elseif (isset($_POST['tolak_laporan'])) {
        $id = $_POST['id'];
        $keterangan = $_POST['keterangan'];
        $updateQuery = "UPDATE laporan_semester SET status = 'ditolak', keterangan = :keterangan WHERE id = :id";
    }

    if (isset($updateQuery)) {
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bindParam(':id', $id, PDO::PARAM_INT);
        if (isset($keterangan)) {
            $updateStmt->bindParam(':keterangan', $keterangan, PDO::PARAM_STR);
        }
        $updateStmt->execute();
        echo "<meta http-equiv='refresh' content='0; url=?page=laporan_persemester'>";
        exit;
    }
}

// Cek apakah id_user ada di laporan_bulanan
$queryCheck = "SELECT COUNT(*) FROM laporan_bulanan WHERE id_user = :id_user";
$stmtCheck = $conn->prepare($queryCheck);
$stmtCheck->bindParam(':id_user', $id_user, PDO::PARAM_INT);
$stmtCheck->execute();
$hasLaporanBulanan = $stmtCheck->fetchColumn() > 0;
?>


<div class="container mt-4">
    <h3 class="text-center mb-3">Pelaporan Semester</h3>
    <hr>
    <div class="card shadow">
        <div class="card-body">
            <form method="GET" class="mb-3">
                <input type="hidden" name="page" value="laporan_persemester">
                <div class="input-group">
                    <input type="text" name="keyword" class="form-control" placeholder="Cari berdasarkan nama perusahaan..." value="<?= isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '' ?>">
                    <button type="submit" class="btn btn-success">Cari</button>
                    <a href="?page=laporan_persemester" class="btn btn-secondary">Reset</a>
                </div>
            </form>
            <?php if (!$hasLaporanBulanan && $role == 'umum') : ?>
                <div class="alert alert-warning text-center" role="alert">
                    Anda harus mengisi <strong>Laporan Bulanan</strong> terlebih dahulu sebelum dapat menambahkan Laporan Semester.
                </div>
            <?php endif; ?>
                <?php if ($hasLaporanBulanan && $role == 'umum') : ?>
                    <a href="?page=tambah_laporan_persemester" class="btn btn-primary mb-3">
                        <i class="fas fa-plus"></i> Tambah Data
                    </a>
                <?php endif; ?>
                <?php if ($_SESSION['role'] == 'superadmin') { ?> 
                        <a href="?page=tambah_laporan_persemester" class="btn btn-primary mb-3">
                            <i class="fas fa-plus"></i> Tambah Data
                        </a>
                        <?php } ?>
            <div class="table-responsive" style="max-height: 500px; overflow-x: auto; overflow-y: auto;">
                <table class="table table-bordered" style="min-width: 1200px; white-space: nowrap;">
                    <thead class="table-dark text-center align-middle">
                        <tr>
                            <th>No.</th>
                            <th>Nama Perusahaan</th>
                            <th>Parameter</th>
                            <th>Buku Mutu</th>
                            <th>Hasil</th>
                            <th>Laporan</th>
                            <th>LHU</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($result) > 0): ?>
                            <?php
                            $no = 1;
                            foreach ($result as $row) {
                            ?>
                                <tr>
                                    <td class="text-center"><?php echo $no++; ?></td>
                                    <td><?php echo htmlspecialchars($row['nama_perusahaan']); ?></td>
                                    <td><?php echo htmlspecialchars($row['parameter']); ?></td>
                                    <td><?php echo htmlspecialchars($row['buku_mutu']); ?></td>
                                    <td><?php echo htmlspecialchars($row['hasil']); ?></td>
                                    <td class="text-center">
                                        <?php if (!empty($row['file_laporan'])) : ?>
                                            <a href="<?php echo htmlspecialchars($row['file_laporan']); ?>" target="_blank" class="btn btn-sm btn-dark">
                                                <i class="fas fa-file-alt"></i> Lihat
                                            </a>
                                        <?php else : ?>
                                            <span class="text-danger">Tidak ada file</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if (!empty($row['file_lhu'])) : ?>
                                            <a href="<?php echo htmlspecialchars($row['file_lhu']); ?>" target="_blank" class="btn btn-sm btn-dark">
                                                <i class="fas fa-file-alt"></i> Lihat
                                            </a>
                                        <?php else : ?>
                                            <span class="text-danger">Tidak ada file</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                        // Menampilkan status dengan ikon dan warna
                                        if ($row['status'] == 'diajukan') {
                                            echo '<i class="fas fa-clock" style="color: yellow;"></i> Diajukan';
                                        } elseif ($row['status'] == 'diterima') {
                                            echo '<i class="fas fa-check" style="color: green;"></i> Diterima';
                                        } elseif ($row['status'] == 'ditolak') {
                                            echo '<i class="fas fa-times" style="color: red;"></i> Ditolak';
                                        } else {
                                            echo '<span class="text-muted">Status tidak diketahui</span>';
                                        }
                                        ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($row['keterangan']); ?></td>
                                    <td class="text-center">
                                        <?php if ($role == 'admin' && $row['status'] == 'diajukan'): ?>
                                            <!-- Tombol Terima menggunakan POST -->
                                            <form method="POST" style="display: inline;">
                                                <input type="hidden" name="terima_id" value="<?php echo $row['id']; ?>">
                                                <button type="submit" class="btn btn-success btn-sm">Terima</button>
                                            </form>
                                            <!-- Tombol Tolak dengan Modal -->
                                            <a href="" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modalTolak<?php echo $row['id']; ?>">Tolak</a>
                                        <?php endif; ?>

                                        <?php if ($row['status'] == 'diterima' || $row['status'] == 'ditolak'): ?>
                                            <a href="?page=edit_laporan_persemester&id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                            <a href="?page=hapus_laporan_persemester&id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?');">Hapus</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <!-- Modal untuk Tolak -->
                                <div class="modal fade" id="modalTolak<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="modalTolakLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalTolakLabel">Tolak Laporan</h5>
                                            </div>
                                            <form action="" method="POST">
                                                <div class="modal-body">
                                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                    <div class="form-group">
                                                        <label for="keterangan<?php echo $row['id']; ?>">Keterangan Penolakan</label>
                                                        <textarea class="form-control" id="keterangan" name="keterangan" rows="3" required></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                    <button type="submit" name="tolak_laporan" class="btn btn-danger">Tolak</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php else: ?>
                            <tr>
                                <td colspan='15' class='text-center'>Data tidak ditemukan</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap CSS -->
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.3/css/bootstrap.min.css"> -->

<!-- jQuery dan Bootstrap JS -->
<!-- <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.3/js/bootstrap.bundle.min.js"></script> -->