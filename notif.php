<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'koneksi.php';

// Cek apakah pengguna adalah admin
if (!isset($_SESSION['id_user']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Akses ditolak!'); window.location.href='login/login.php';</script>";
    exit;
}

// Buat koneksi menggunakan PDO
$database = new Database();
$conn = $database->getConnection();

// Ambil laporan dengan status 'diajukan'
$query = "SELECT * FROM laporan_semester WHERE status = 'diajukan'";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Proses persetujuan dengan metode POST
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['terima_id'])) {
    $id = $_POST['terima_id'];

    $updateQuery = "UPDATE laporan_semester SET status = 'diterima' WHERE id = :id";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bindParam(':id', $id);
    $updateStmt->execute();

    echo "<script>alert('Laporan diterima!'); window.location.href='?page=notif';</script>";
}

// Proses penolakan dengan metode POST
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id']) && isset($_POST['keterangan'])) {
    $id = $_POST['id'];
    $keterangan = $_POST['keterangan'];

    $updateQuery = "UPDATE laporan_semester SET status = 'ditolak', keterangan = :keterangan WHERE id = :id";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bindParam(':id', $id);
    $updateStmt->bindParam(':keterangan', $keterangan);
    $updateStmt->execute();

    echo "<script>alert('Laporan ditolak!'); window.location.href='?page=notif';</script>";
}

?>

<div class="container mt-4">
    <h3 class="text-center mb-3">Notifikasi Pengajuan Laporan Persemester</h3>
    <hr>
    <div class="card shadow">
        <div class="card-body">
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
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
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
                                        <a href="<?php echo htmlspecialchars($row['file_laporan']); ?>" target="_blank" class="btn btn-sm btn-info">
                                            <i class="fas fa-file-alt"></i> Lihat
                                        </a>
                                    <?php else : ?>
                                        <span class="text-danger">Tidak ada file</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if (!empty($row['file_lhu'])) : ?>
                                        <a href="<?php echo htmlspecialchars($row['file_lhu']); ?>" target="_blank" class="btn btn-sm btn-info">
                                            <i class="fas fa-file-alt"></i> Lihat
                                        </a>
                                    <?php else : ?>
                                        <span class="text-danger">Tidak ada file</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <!-- Tombol Terima menggunakan POST -->
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="terima_id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" class="btn btn-success btn-sm">Terima</button>
                                    </form>

                                    <!-- Tombol Tolak dengan Modal -->
                                    <a href="#" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modalTolak<?php echo $row['id']; ?>">Tolak</a>
                                </td>
                            </tr>

                            <!-- Modal untuk Tolak -->
                            <div class="modal fade" id="modalTolak<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="modalTolakLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalTolakLabel">Tolak Laporan</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="" method="POST">
                                            <div class="modal-body">
                                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                <div class="form-group">
                                                    <label for="keterangan">Keterangan Penolakan</label>
                                                    <textarea class="form-control" id="keterangan" name="keterangan" rows="3" required></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-danger">Tolak</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
