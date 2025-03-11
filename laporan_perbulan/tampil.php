<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'koneksi.php';

if (!isset($_SESSION['id_user'])) {
    echo "<script>alert('Silakan login terlebih dahulu!'); window.location.href='login.php';</script>";
    exit;
}

$id_user = $_SESSION['id_user'];
$query = "SELECT * FROM laporan_bulanan WHERE id_user = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container mt-4">
    <h3 class="text-center mb-3">Laporan Bulanan</h3>
    <hr>
    <div class="card shadow">
        <div class="card-body">
            <div class="mb-3">
                <a href="?page=tambah_laporan_perbulan" class="btn btn-primary">Tambah Data</a>
            </div>
            <div class="table-responsive" style="max-height: 500px; overflow-x: auto; overflow-y: auto;">
                <table class="table table-bordered" style="min-width: 1200px; white-space: nowrap;">
                    <thead class="table-dark text-center align-middle">
                        <tr>
                            <th>No.</th>
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
                        while ($row = $result->fetch_assoc()) {
                        ?>
                            <tr>
                                <td class="text-center"><?php echo $no++; ?></td>
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
                                    <a href="?page=edit_laporan&id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="hapus_laporan.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?');">Hapus</a>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
