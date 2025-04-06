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

// Jika bukan admin, tambahkan filter berdasarkan id_user
if ($role !== 'admin') {
    $query .= " WHERE id_user = :id_user";
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

// Cek apakah id_user ada di laporan_bulanan
$queryCheck = "SELECT COUNT(*) FROM profil WHERE id_user = :id_user";
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
                <input type="hidden" name="page" value="pembangkit">
                <div class="input-group">
                    <input type="text" name="keyword" class="form-control" placeholder="Cari berdasarkan nama perusahaan..." value="<?= isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '' ?>">
                    <button type="submit" class="btn btn-success">Cari</button>
                    <a href="?page=pembangkit" class="btn btn-secondary">Reset</a>
                </div>
            </form>
            <div class="mb-3">
            <?php if (!$hasprofil && $role == 'umum') : ?>
                <div class="alert alert-warning text-center" role="alert">
                    Anda harus melengkapi <strong>Profil Perusahaan</strong> terlebih dahulu sebelum dapat menambahkan Laporan Bulanan.
                </div>
                <?php endif; ?>
                <?php if ($hasprofil && $role == 'umum') : ?>
            <div class="mb-3 text-end">
                <a href="?page=tambah_laporan_perbulan" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Data
                </a>
                <a href="?page=excel_laporan_bulanan" class="btn btn-success">Ekspor ke Spreadsheet</a>
            </div>
                <?php endif; ?>
            </div>
            <div class="table-responsive" style="max-height: 500px; overflow-x: auto; overflow-y: auto;">
                <table class="table table-bordered" style="min-width: 1200px; white-space: nowrap;">
                    <thead class="table-dark text-center align-middle">
                        <tr>
                            <th rowspan="2">No.</th>
                            <th rowspan="2">Bulan</th>
                            <th rowspan="2">Nama Perusahaan</th>
                            <th rowspan="2">Volume Bahan Bakar</th>
                            <th colspan="2">Produksi Listrik</th>
                            <th rowspan="2">Susut Jaringan (bila ada) (kWh)</th>
                            <th colspan="3">Konsumsi Listrik</th>
                            <th rowspan="2">Aksi</th>
                        </tr>
                        <tr>
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
                                    <td><?php echo htmlspecialchars($row['bulan']); ?> </td>
                                    <td><?php echo htmlspecialchars($row['nama_perusahaan']); ?> </td>
                                    <td><?php echo htmlspecialchars($row['volume_bb']); ?> </td>
                                    <td><?php echo htmlspecialchars($row['produksi_sendiri']); ?> </td>
                                    <td><?php echo htmlspecialchars($row['pemb_sumber_lain']); ?> </td>
                                    <td><?php echo htmlspecialchars($row['susut_jaringan']); ?> </td>
                                    <td><?php echo htmlspecialchars($row['penj_ke_pelanggan']); ?> </td>
                                    <td><?php echo htmlspecialchars($row['penj_ke_pln']); ?> </td>
                                    <td><?php echo htmlspecialchars($row['pemakaian_sendiri']); ?> </td>
                                    <td class="text-center">
                                        <a href="?page=edit_laporan_perbulan&id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                        <a href="?page=hapus_laporan_perbulan&id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?');">Hapus</a>
                                    </td>
                                </tr>
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