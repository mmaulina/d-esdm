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

// Buat koneksi menggunakan PDO
$database = new Database();
$conn = $database->getConnection();

$query = "SELECT * FROM laporan_semester WHERE id_user = :id_user";
$stmt = $conn->prepare($query);
$stmt->bindParam(":id_user", $id_user, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-4">
    <h3 class="text-center mb-3">Pelaporan Semester</h3>
    <hr>
    <div class="card shadow">
        <div class="card-body">
            <div class="mb-3">
                <a href="?page=tambah_laporan_persemester" class="btn btn-primary">Tambah Data</a>
            </div>
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
                            <th>Status</th> <!-- Tambahkan kolom status -->
                            <th>Keterangan</th>
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
                                    <a href="?page=edit_laporan_persemester&id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="?page=hapus_laporan_persemester&id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?');">Hapus</a>
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