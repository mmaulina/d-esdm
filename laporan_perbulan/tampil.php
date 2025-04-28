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
$db = new Database();
$conn = $db->getConnection();

// Query dasar
$query = "SELECT * FROM laporan_bulanan";
$params = [];


// Query berdasarkan role
$params = [];
if ($role == 'admin' || $role == 'superadmin') {
    $query = "SELECT * FROM laporan_bulanan ORDER BY FIELD(status, 'diajukan', 'ditolak', 'diterima')";
} else {
    $query = "SELECT * FROM laporan_bulanan WHERE id_user = :id_user ORDER BY FIELD(status, 'ditolak', 'diajukan', 'diterima')";
    $params[':id_user'] = $id_user;
}


// Cek apakah ada keyword pencarian
if (!empty($_GET['keyword'])) {
    $keyword = "%" . $_GET['keyword'] . "%";

    // Tambahkan WHERE jika belum ada, atau AND jika sudah ada filter sebelumnya
    $query .= (strpos($query, 'WHERE') === false) ? " WHERE" : " AND";
    $query .= " nama_perusahaan LIKE :keyword";

    $params[':keyword'] = $keyword;
}

// Persiapkan dan jalankan query
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
        $updateQuery = "UPDATE laporan_bulanan SET status = 'diterima' WHERE id = :id";
    } elseif (isset($_POST['tolak_laporan'])) {
        $id = $_POST['id'];
        $keterangan = $_POST['keterangan'];
        $updateQuery = "UPDATE laporan_bulanan SET status = 'ditolak', keterangan = :keterangan WHERE id = :id";
    }

    if (isset($updateQuery)) {
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bindParam(':id', $id, PDO::PARAM_INT);
        if (isset($keterangan)) {
            $updateStmt->bindParam(':keterangan', $keterangan, PDO::PARAM_STR);
        }
        $updateStmt->execute();
        echo "<meta http-equiv='refresh' content='0; url=?page=laporan_perbulan'>";
        exit;
    }
}

// Cek apakah id_user ada di laporan_bulanan
$queryCheck = "SELECT COUNT(*) FROM laporan_bulanan WHERE id_user = :id_user";
$stmtCheck = $conn->prepare($queryCheck);
$stmtCheck->bindParam(':id_user', $id_user, PDO::PARAM_INT);
$stmtCheck->execute();
$hasprofil = $stmtCheck->fetchColumn() > 0;
?>

<div class="container mt-4">
    <h3 class="text-center mb-3">Pelaporan Bulanan</h3>
    <hr>
    <div class="card shadow">
        <div class="card-body">
            <!-- Fitur pencarian -->
            <form method="GET" class="mb-3">
                <input type="hidden" name="page" value="laporan_bulanan">
                <div class="input-group">
                    <input type="text" name="keyword" class="form-control" placeholder="Cari berdasarkan nama perusahaan..." value="<?= isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '' ?>">
                    <button type="submit" class="btn btn-success">Cari</button>
                    <a href="?page=laporan_bulanan" class="btn btn-secondary">Reset</a>
                </div>
            </form>
            <div class="mb-3">
                <?php if (!$hasprofil && $role == 'umum') : ?>
                    <div class="alert alert-warning text-center" role="alert">
                        Anda harus melengkapi <strong>Profil Perusahaan</strong> terlebih dahulu sebelum dapat menambahkan Laporan Bulanan.
                    </div>
                <?php endif; ?>
                <?php if ($hasprofil && $role == 'umum') : ?>
                    <a href="?page=tambah_laporan_perbulan" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Data
                    </a>
                <?php endif; ?>
                <?php if ($_SESSION['role'] == 'superadmin') { ?>
                    <a href="?page=tambah_laporan_perbulan" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Data
                    </a>
                <?php } ?>
                <a href="?page=excel_laporan_bulanan" class="btn btn-success">Ekspor ke Spreadsheet</a>
            </div>
            <div class="table-responsive" style="max-height: 500px; overflow-x: auto; overflow-y: auto;">
                <table class="table table-bordered" style="min-width: 1200px; white-space: nowrap;">
                    <thead class="table-dark text-center align-middle">
                        <tr>
                            <th rowspan="3" style="width: 3%;">No.</th>
                            <th rowspan="3">Nama Perusahaan</th>
                            <th rowspan="3">Tahun</th>
                            <th rowspan="3">Bulan</th>
                            <th colspan="3" style="min-width: 250px;">Data Pembangkit</th>
                            <th colspan="10" style="min-width: 1500px;">Data Teknis Pembangkit</th>
                            <th colspan="7" style="min-width: 250px;">Pelaporan Bulanan</th>
                            <th rowspan="3" style="min-width: 150px;">Status</th>
                            <th rowspan="3" style="min-width: 150px;">Keterangan</th>
                            <th rowspan="3" style="min-width: 150px;">Aksi</th>
                        </tr>
                        <tr>
                            <th rowspan="2">Alamat Pembangkit</th>
                            <th colspan="2">Koordinat Pembangkit</th>
                            <th rowspan="2">Jenis Pembangkit</th>
                            <th rowspan="2">Fungsi</th>
                            <th rowspan="2">Kapasitas Terpasang (MW)</th>
                            <th rowspan="2">Daya Mampu Netto (MW)</th>
                            <th rowspan="2">Jumlah Unit</th>
                            <th rowspan="2">No. Unit</th>
                            <th rowspan="2">Tahun Operasi</th>
                            <th rowspan="2">Status Operasi</th>
                            <th colspan="2">Bahan Bakar yang Digunakan</th>
                            <th rowspan="2">Volume Bahan Bakar</th>
                            <th colspan="2">Produksi Listrik</th>
                            <th rowspan="2">Susut Jaringan (bila ada) (kWh)</th>
                            <th colspan="3">Konsumsi Listrik</th>
                        </tr>
                        <tr>
                            <th>Latitude</th>
                            <th>Longitude</th>
                            <th>Jenis</th>
                            <th>Satuan</th>
                            <th>Produksi Sendiri (kWh)</th>
                            <th>Pembelian Sumber Lain (bila ada) (kWh)</th>
                            <th>Penjualan ke Pelanggan (bila ada) (kWh)</th>
                            <th>Penjualan ke PLN (bila ada) (kWh)</th>
                            <th>Pemakaian Sendiri (kWh)</th>
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
                                    <td><?php echo htmlspecialchars($row['nama_perusahaan']); ?> </td>
                                    <td><?php echo htmlspecialchars($row['tahun']); ?> </td>
                                    <td><?php echo htmlspecialchars($row['bulan']); ?> </td>
                                    <td><?= htmlspecialchars($row['alamat']) ?></td>
                                    <td><?= htmlspecialchars($row['latitude']) ?></td>
                                    <td><?= htmlspecialchars($row['longitude']) ?></td>
                                    <td><?= htmlspecialchars($row['jenis_pembangkit']) ?></td>
                                    <td><?= htmlspecialchars($row['fungsi']) ?></td>
                                    <td><?= htmlspecialchars($row['kapasitas_terpasang']) ?></td>
                                    <td><?= htmlspecialchars($row['daya_mampu_netto']) ?></td>
                                    <td><?= htmlspecialchars($row['jumlah_unit']) ?></td>
                                    <td><?= htmlspecialchars($row['no_unit']) ?></td>
                                    <td><?= htmlspecialchars($row['tahun_operasi']) ?></td>
                                    <td><?= htmlspecialchars($row['status_operasi']) ?></td>
                                    <td><?= htmlspecialchars($row['bahan_bakar_jenis']) ?></td>
                                    <td><?= htmlspecialchars($row['bahan_bakar_satuan']) ?></td>
                                    <td><?php echo htmlspecialchars($row['volume_bb']); ?> </td>
                                    <td><?php echo htmlspecialchars($row['produksi_sendiri']); ?> </td>
                                    <td><?php echo htmlspecialchars($row['pemb_sumber_lain']); ?> </td>
                                    <td><?php echo htmlspecialchars($row['susut_jaringan']); ?> </td>
                                    <td><?php echo htmlspecialchars($row['penj_ke_pelanggan']); ?> </td>
                                    <td><?php echo htmlspecialchars($row['penj_ke_pln']); ?> </td>
                                    <td><?php echo htmlspecialchars($row['pemakaian_sendiri']); ?> </td>
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
                                        <?php if ($role == 'admin'||$role == 'superadmin' && $row['status'] == 'diajukan'): ?>
                                            <!-- Tombol Terima menggunakan POST -->
                                            <form method="POST" style="display: inline;">
                                                <input type="hidden" name="terima_id" value="<?php echo $row['id']; ?>">
                                                <button type="submit" class="btn btn-success btn-sm">Terima</button>
                                            </form>
                                            <!-- Tombol Tolak dengan Modal -->
                                            <a href="" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modalTolak<?php echo $row['id']; ?>">Tolak</a>
                                        <?php endif; ?>

                                        <?php if ($row['status'] == 'diterima' || $row['status'] == 'ditolak'): ?>
                                            <a href="?page=edit_laporan_perbulan&id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                            <a href="?page=hapus_laporan_perbulan&id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?');">Hapus</a>
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
                                <td colspan='11' class='text-center'>Data tidak ditemukan</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>