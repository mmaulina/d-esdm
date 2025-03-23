<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'koneksi.php';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['id_user'])) {
    echo "<script>alert('Silakan login terlebih dahulu!'); window.location.href='login.php';</script>";
    exit;
}

$id_user = $_SESSION['id_user'];
$role = $_SESSION['role']; // Pastikan role sudah tersimpan di session

// Buat koneksi menggunakan PDO
$database = new Database();
$conn = $database->getConnection();

if ($role == 'admin') {
    // Admin melihat semua data, dengan 'Diajukan' di atas dan sisanya urut abjad
    $query = "SELECT * FROM laporan_semester 
              ORDER BY FIELD(status, 'diajukan') DESC, status ASC";
    $stmt = $conn->prepare($query);
} else {
    // User umum melihat data mereka sendiri, diurutkan sesuai urutan yang diinginkan
    $query = "SELECT * FROM laporan_semester 
              WHERE id_user = :id_user 
              ORDER BY FIELD(status, 'ditolak', 'diajukan', 'diterima')";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":id_user", $id_user, PDO::PARAM_INT);
}

// Proses persetujuan dengan metode POST
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['terima_id'])) {
    $id = $_POST['terima_id'];

    $updateQuery = "UPDATE laporan_semester SET status = 'diterima' WHERE id = :id";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bindParam(':id', $id);
    $updateStmt->execute();

    echo "<script>alert('Laporan diterima!'); window.location.href='?page=laporan_persemester';</script>";
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

    echo "<script>alert('Laporan ditolak!'); window.location.href='?page=notifikasi';</script>";
}

$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-4">
    <h3 class="text-center mb-3">Pelaporan Semester</h3>
    <hr>
    <div class="card shadow">
        <div class="card-body">
            <?php if ($role != 'admin') : ?>
                <div class="mb-3">
                    <a href="?page=tambah_laporan_persemester" class="btn btn-primary">Tambah Data</a>
                </div>
            <?php endif; ?>

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
                                        <?php if ($row['status'] == 'diajukan'): ?>
                                            <!-- Tombol Terima menggunakan POST -->
                                            <form method="POST" style="display: inline;">
                                                <input type="hidden" name="terima_id" value="<?php echo $row['id']; ?>">
                                                <button type="submit" class="btn btn-success btn-sm">Terima</button>
                                            </form>
                                            <!-- Tombol Tolak dengan Modal -->
                                            <a href="#" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modalTolak<?php echo $row['id']; ?>">Tolak</a>
                                            <!-- Tombol edit dan hapus -->
                                        <?php elseif ($row['status'] == 'diterima' || $row['status'] == 'ditolak'): ?>
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
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
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
                                                    <button type="submit" name="id" class="btn btn-danger">Tolak</button>
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