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

// Query dasar untuk laporan bulanan
$query = "SELECT * FROM laporan_bulanan WHERE 1=1";
$params = [];

// Query berdasarkan role
if ($role != 'adminbulanan' && $role != 'superadmin' && $role != 'kementerian') {
    $query .= " AND id_user = :id_user";
    $params[':id_user'] = $id_user;
}

// Cek apakah ada keyword pencarian
if (!empty($_GET['keyword'])) {
    $keyword = "%" . $_GET['keyword'] . "%";
    $query .= " AND nama_perusahaan LIKE :keyword"; //fitur cari berdasarkan nama_perusahaan
    $params[':keyword'] = $keyword;
}

// Filter
$tahun = $_GET['tahun'] ?? '';
if (!empty($tahun)) {
    $query .= " AND tahun = :tahun";
    $params[':tahun'] = $tahun;
}

$bulan = $_GET['bulan'] ?? '';
if (!empty($bulan)) {
    $query .= " AND bulan = :bulan";
    $params[':bulan'] = $bulan;
}

$kabupaten = $_GET['kabupaten'] ?? '';
if (!empty($kabupaten)) {
    $query .= " AND kabupaten = :kabupaten";
    $params[':kabupaten'] = $kabupaten;
}

// Ambil daftar untuk dropdown filter
$tahunStmt = $conn->query("SELECT DISTINCT tahun FROM laporan_bulanan ORDER BY tahun");
$tahunList = $tahunStmt->fetchAll(PDO::FETCH_COLUMN);

$bulanStmt = $conn->query("SELECT DISTINCT bulan FROM laporan_bulanan ORDER BY bulan");
$bulanList = $bulanStmt->fetchAll(PDO::FETCH_COLUMN);

$kabupatenStmt = $conn->query("SELECT DISTINCT kabupaten FROM laporan_bulanan ORDER BY kabupaten");
$kabupatenList = $kabupatenStmt->fetchAll(PDO::FETCH_COLUMN);

$query .= " ORDER BY FIELD(status, 'diajukan', 'dikembalikan', 'diterima')";

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
        $updateQuery = "UPDATE laporan_bulanan SET status = 'dikembalikan', keterangan = :keterangan WHERE id = :id";
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

    if (isset($_POST['terima_id2'])) {
        $id = $_POST['terima_id2'];
        $updatequerypembangkit = "UPDATE pembangkit SET status = 'diterima' WHERE id = :id";
    } elseif (isset($_POST['tolak_laporan2'])) {
        $id = $_POST['id'];
        $keterangan = $_POST['keterangan'];
        $updatequerypembangkit = "UPDATE pembangkit SET status = 'dikembalikan', keterangan = :keterangan WHERE id = :id";
    }

    if (isset($updatequerypembangkit)) {
        $updatestmt2 = $conn->prepare($updatequerypembangkit);
        $updatestmt2->bindParam(':id', $id, PDO::PARAM_INT);
        if (isset($keterangan)) {
            $updatestmt2->bindParam(':keterangan', $keterangan, PDO::PARAM_STR);
        }
        $updatestmt2->execute();
        echo "<meta http-equiv='refresh' content='0; url=?page=laporan_perbulan'>";
        exit;
    }
}

if ($role == 'adminbulanan' || $role == 'superadmin' || $role == 'kementerian') {
    // Admin dan Superadmin melihat semua data
    $stmtpembangkit = $conn->prepare("SELECT * FROM pembangkit");
} else {
    // Role umum hanya melihat data miliknya
    $stmtpembangkit = $conn->prepare("SELECT * FROM pembangkit WHERE id_user = :id_user");
    $stmtpembangkit->bindParam(':id_user', $id_user, PDO::PARAM_INT);
}
$querypembangkit = "SELECT * FROM pembangkit WHERE 1=1";
$paramspembangkit = [];

// Cek apakah ada keyword pencarian
if (!empty($_GET['keyword2'])) {
    $keyword = "%" . $_GET['keyword2'] . "%";
    $querypembangkit .= " AND nama_perusahaan LIKE :keyword2"; // fitur cari berdasarkan nama_perusahaan
    $paramspembangkit[':keyword2'] = $keyword;
}

// Siapkan dan eksekusi query
$stmtpembangkit = $conn->prepare($querypembangkit);
foreach ($paramspembangkit as $key => $value2) {
    $stmtpembangkit->bindValue($key, $value2, PDO::PARAM_STR);
}
$stmtpembangkit->execute();

// Ambil hasil
$resultpembangkit = $stmtpembangkit->fetchAll(PDO::FETCH_ASSOC);


// Cek apakah id_user ada di laporan_bulanan
$queryCheck = "SELECT COUNT(*) FROM profil WHERE id_user = :id_user AND status = 'diterima'";
$stmtCheck = $conn->prepare($queryCheck);
$stmtCheck->bindParam(':id_user', $id_user, PDO::PARAM_INT);
$stmtCheck->execute();
$hasprofil = $stmtCheck->fetchColumn() > 0;


?>
<div class="container mt-4">
    <div class="card shadow" style="overflow-x: auto; max-height: calc(100vh - 150px); overflow-y: auto;">
        <div class="card-body">
            <h3 class="text-center mb-3">Pelaporan Bulanan</h3>
            <hr>
            <!-- Fitur pencarian dan filter -->
            <form method="GET" class="mb-3">
                <input type="hidden" name="page" value="laporan_perbulan">
                <div class="input-group mb-2">
                    <input type="text" name="keyword" class="form-control" placeholder="Cari berdasarkan nama perusahaan..." value="<?= isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '' ?>">
                    <button type="submit" class="btn btn-success">Cari</button>
                    <a href="?page=laporan_perbulan" class="btn btn-secondary">Reset</a>
                </div>

                <div class="row mb-3 align-items-end">
                    <div class="col">
                        <label for="tahun" class="form-label">Filter Tahun</label>
                        <select name="tahun" id="tahun" class="form-select">
                            <option value="">-- Pilih Tahun --</option>
                            <?php foreach ($tahunList as $tahun): ?>
                                <option value="<?= htmlspecialchars($tahun) ?>" <?= (isset($_GET['tahun']) && $_GET['tahun'] == $tahun) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($tahun) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col">
                        <label for="bulan" class="form-label">Filter Bulan</label>
                        <select name="bulan" id="bulan" class="form-select">
                            <option value="">-- Pilih Bulan --</option>
                            <?php foreach ($bulanList as $bulan): ?>
                                <option value="<?= htmlspecialchars($bulan) ?>" <?= (isset($_GET['bulan']) && $_GET['bulan'] == $bulan) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($bulan) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col">
                        <label for="kabupaten" class="form-label">Filter Kabupaten/Kota</label>
                        <select name="kabupaten" id="kabupaten" class="form-select">
                            <option value="">-- Pilih Kabupaten/Kota --</option>
                            <?php foreach ($kabupatenList as $kab): ?>
                                <option value="<?= htmlspecialchars($kab) ?>" <?= (isset($_GET['kabupaten']) && $_GET['kabupaten'] == $kab) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($kab) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col d-flex gap-2">
                        <button type="submit" class="btn btn-success w-100">Filter</button>
                        <a href="?page=laporan_perbulan" class="btn btn-secondary w-100">Reset</a>
                    </div>
                </div>
            </form>
            <div class="mb-3">
                <?php if (!$hasprofil && $role == 'umum') : ?>
                    <div class="alert alert-warning text-center" role="alert">
                        Anda harus melengkapi <strong>Profil Perusahaan</strong> dan verifikasi terlebih dahulu sebelum dapat menambahkan Laporan Bulanan.
                    </div>
                <?php endif; ?>
                <?php if ($hasprofil && $role == 'umum') : ?>
                    <a href="?page=tambah_laporan_perbulan" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Data
                    </a>
                    <!-- <a href="?page=tambah_laporan_perbulan2" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Data Opsi 2
                    </a> -->
                <?php endif; ?>
                <?php if ($_SESSION['role'] == 'superadmin') { ?>
                    <a href="?page=tambah_laporan_perbulan" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Data
                    </a>
                    <!-- <a href="?page=tambah_laporan_perbulan2" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Data Opsi 2
                    </a> -->
                <?php } ?>
                <?php if ($_SESSION['role'] == 'superadmin' || $_SESSION['role'] == 'adminbulanan') { ?>
                    <a href="?page=excel_laporan_bulanan" class="btn btn-success">Ekspor ke Spreadsheet</a>
                <?php } ?>
            </div>
            <div class="table-responsive" style="max-height: 500px; overflow-x: auto; overflow-y: auto;">
                <table class="table table-bordered" style="min-width: 1200px; white-space: nowrap;">
                    <thead class="table-dark text-center align-middle">
                        <tr>
                            <th rowspan="3" style="width: 3%;" onclick="sortTable(0)">No. <i class="fa fa-sort"></i></th>
                            <th rowspan="3" onclick="sortTable(1)">Nama Perusahaan <i class="fa fa-sort"></i></th>
                            <?php if ($_SESSION['role'] == 'superadmin') { ?>
                                <th rowspan="3" onclick="sortTable(2)">No Hp Pimpinan<i class="fa fa-sort"></i></th>
                                <th rowspan="3" onclick="sortTable(3)">Tenaga Teknik <i class="fa fa-sort"></i></th>
                                <th rowspan="3" onclick="sortTable(4)">No Hp Tenaga Teknik <i class="fa fa-sort"></i></th>
                                <th rowspan="3" onclick="sortTable(5)">Nama Admin <i class="fa fa-sort"></i></th>
                                <th rowspan="3" onclick="sortTable(6)">Nomor Admin <i class="fa fa-sort"></i></th>
                            <?php } ?>
                            <th rowspan="3" onclick="sortTable(7)">Nomor Telepon Kantor <i class="fa fa-sort"></i></th>
                            <th rowspan="3" onclick="sortTable(8)">Tahun <i class="fa fa-sort"></i></th>
                            <th rowspan="3" onclick="sortTable(9)">Bulan <i class="fa fa-sort"></i></th>
                            <th rowspan="3" onclick="sortTable(10)">Kabupaten <i class="fa fa-sort"></i></th>
                            <th colspan="2">Produksi Listrik</th>
                            <th rowspan="3" onclick="sortTable(11)">Susut Jaringan <i class="fa fa-sort"></i></th>
                            <th colspan="3">Konsumsi Listrik</th>
                            <th rowspan="3" style="min-width: 150px;" onclick="sortTable(12)">Status <i class="fa fa-sort"></i></th>
                            <th rowspan="3" style="min-width: 150px;" onclick="sortTable(13)">Keterangan <i class="fa fa-sort"></i></th>
                            <th rowspan="3" style="min-width: 150px;">Aksi</th>
                        </tr>
                        <tr>
                            <th onclick="sortTable(14)">Produksi Sendiri (kWh) <i class="fa fa-sort"></i></th>
                            <th onclick="sortTable(15)">Pembelian Sumber Lain (kWh) <i class="fa fa-sort"></i></th>
                            <th onclick="sortTable(16)">Penjualan ke Pelanggan (kWh) <i class="fa fa-sort"></i></th>
                            <th onclick="sortTable(17)">Penjualan ke PLN (kWh) <i class="fa fa-sort"></i></th>
                            <th onclick="sortTable(18)">Pemakaian Sendiri (kWh) <i class="fa fa-sort"></i></th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (count($result) > 0): ?>
                            <?php
                            // Group data by id_user
                            $groupedData = [];
                            foreach ($result as $row) {
                                if (isset($row['id_user'])) {
                                    $groupedData[$row['id_user']][] = $row;
                                } else {
                                    // Tangani kasus di mana id_user tidak ada
                                    echo "id_user tidak ditemukan untuk baris: " . json_encode($row);
                                }
                            }


                            foreach ($groupedData as $id_user => $rows):
                                $no = 1;
                                // Get nama_perusahaan from the first row
                                $nama_perusahaan = htmlspecialchars($rows[0]['nama_perusahaan']);
                                // Group header row with id_user and nama_perusahaan
                                echo "<tr><td colspan='16' class='fw-bold bg-light'>NAMA PERUSAHAAN = ($nama_perusahaan) </td></tr>";
                                foreach ($rows as $row):
                            ?>
                                    <tr>
                                        <td class="text-center"><?php echo $no++; ?></td>
                                        <td><?php echo htmlspecialchars($row['nama_perusahaan']); ?> </td>
                                        <?php if ($_SESSION['role'] == 'superadmin') { ?>
                                            <td><?= htmlspecialchars($row['no_hp_pimpinan']); ?></td>
                                            <td><?= htmlspecialchars($row['tenaga_teknik']); ?></td>
                                            <td><?= htmlspecialchars($row['no_hp_teknik']); ?></td>
                                            <td><?= htmlspecialchars($row['nama']); ?></td>
                                            <td><?= htmlspecialchars($row['no_hp']); ?></td>
                                        <?php } ?>
                                        <td><?= htmlspecialchars($row['no_telp_kantor']); ?></td>
                                        <td><?php echo htmlspecialchars($row['tahun']); ?> </td>
                                        <td><?php echo htmlspecialchars($row['bulan']); ?> </td>
                                        <td><?= htmlspecialchars($row['kabupaten']) ?></td>
                                        <td><?= htmlspecialchars($row['produksi_sendiri']); ?></td>
                                        <td><?= htmlspecialchars($row['pemb_sumber_lain']); ?></td>
                                        <td><?= htmlspecialchars($row['susut_jaringan']); ?></td>
                                        <td><?= htmlspecialchars($row['penj_ke_pelanggan']); ?></td>
                                        <td><?= htmlspecialchars($row['penj_ke_pln']); ?></td>
                                        <td><?= htmlspecialchars($row['pemakaian_sendiri']); ?></td>

                                        <td class="text-center">
                                            <?php
                                            if ($row['status'] == 'diajukan') {
                                                echo '<i class="fas fa-clock" style="color: yellow;"></i> Diajukan';
                                            } elseif ($row['status'] == 'diterima') {
                                                echo '<i class="fas fa-check" style="color: green;"></i> Diterima';
                                            } elseif ($row['status'] == 'dikembalikan') {
                                                echo '<i class="fas fa-times" style="color: red;"></i> Dikembalikan';
                                            } else {
                                                echo '<span class="text-muted">Status tidak diketahui</span>';
                                            }
                                            ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($row['keterangan']); ?></td>
                                        <td class="text-center">
                                            <?php if (($role == 'adminbulanan' || $role == 'superadmin') && $row['status'] == 'diajukan'): ?>
                                                <form method="POST" style="display: inline;">
                                                    <input type="hidden" name="terima_id" value="<?php echo $row['id']; ?>">
                                                    <button type="submit" class="btn btn-success btn-sm">Terima</button>
                                                </form>
                                                <a href="" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modalTolak<?php echo $row['id']; ?>">Dikembalikan</a>
                                            <?php endif; ?>
                                            <?php if (($role == 'superadmin' && in_array($row['status'], ['dikembalikan', 'diterima'])) || ($role == 'umum' && in_array($row['status'], ['dikembalikan']))): ?>
                                                <a href="?page=edit_laporan_perbulan&id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                                <a href="?page=hapus_laporan_perbulan&id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?');">Hapus</a>
                                                <?php if (($role == 'superadmin') && $row['status'] == 'diterima'): ?>
                                                    <a href="" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalTolak<?php echo $row['id']; ?>">Dikembalikan</a>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <!-- Modal untuk Tolak -->
                                    <div class="modal fade" id="modalTolak<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="modalTolakLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalTolakLabel">Kembalikan Laporan</h5>
                                                </div>
                                                <form action="" method="POST">
                                                    <div class="modal-body">
                                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                        <div class="form-group">
                                                            <label for="keterangan<?php echo $row['id']; ?>">Keterangan di Kembalikan</label>
                                                            <textarea class="form-control" id="keterangan" name="keterangan" rows="3" required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                        <button type="submit" name="tolak_laporan" class="btn btn-danger">Kembalikan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                            <?php endforeach;
                            endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan='16' class='text-center'>Data tidak ditemukan</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- PEMBANGKIT -->
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-body">
            <h3 class="text-center mb-3">Data Pembangkit dan Data Teknis Pembangkit</h3>
            <hr>
            <form method="GET" class="mb-2">
                <input type="hidden" name="page" value="laporan_perbulan">
                <div class="input-group mb-2">
                    <input type="text" name="keyword2" class="form-control" placeholder="Cari berdasarkan nama perusahaan..." value="<?= isset($_GET['keyword2']) ? htmlspecialchars($_GET['keyword2']) : '' ?>">
                    <button type="submit" class="btn btn-success">Cari</button>
                    <a href="?page=laporan_perbulan" class="btn btn-secondary">Reset</a>
                </div>
            </form>
            <div class="mb-3">
                <?php if (!$hasprofil && $role == 'umum') : ?>
                    <div class="alert alert-warning text-center" role="alert">
                        Anda harus melengkapi <strong>Profil Perusahaan</strong> terlebih dahulu sebelum dapat menambahkan Data Pembangkit.
                    </div>
                <?php endif; ?>
                <div class="table-responsive" style="max-height: 500px; overflow-x: auto; overflow-y: auto;">
                    <table class="table table-bordered" style="table-layout: fixed; min-width: 2000px;">
                        <thead class="table-dark text-center align-middle">
                            <tr>
                                <th rowspan="3" style="width: 3%;">No.</th>
                                <th rowspan="3">Nama Perusahaan</th>
                                <th colspan="4" style="min-width: 250px;">Data Pembangkit</th>
                                <th colspan="10" style="min-width: 1500px;">Data Teknis Pembangkit</th>
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
                            </tr>
                            <tr>
                                <th>Latitude</th>
                                <th>Longitude</th>
                                <th>Jenis</th>
                                <th>Satuan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($resultpembangkit) > 0): ?>
                                <?php
                                // Group data by id_user
                                $groupedData = [];
                                foreach ($resultpembangkit as $row) {
                                    $groupedData[$row['id_user']][] = $row;
                                }


                                foreach ($groupedData as $id_user => $rows):
                                    $no = 1;
                                    // Get nama_perusahaan from first row
                                    $nama_perusahaan = htmlspecialchars($rows[0]['nama_perusahaan']);
                                    // Group header row with user_id and nama_perusahaan
                                    echo "<tr><td colspan='16' class='fw-bold bg-light'>NAMA PERUSAHAAN = ($nama_perusahaan) </td></tr>";
                                    foreach ($rows as $row):
                                ?>
                                        <tr>
                                            <td class="text-center"><?php echo $no++; ?></td>
                                            <td><?= htmlspecialchars($row['nama_perusahaan']) ?></td>
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
                                            <td><?= htmlspecialchars($row['volume_bb']) ?></td>
                                            <td class="text-center">
                                                <?php
                                                if ($row['status'] == 'diajukan') {
                                                    echo '<i class="fas fa-clock" style="color: yellow;"></i> Diajukan';
                                                } elseif ($row['status'] == 'diterima') {
                                                    echo '<i class="fas fa-check" style="color: green;"></i> Diterima';
                                                } elseif ($row['status'] == 'dikembalikan') {
                                                    echo '<i class="fas fa-times" style="color: red;"></i> Dikembalikan';
                                                } else {
                                                    echo '<span class="text-muted">Status tidak diketahui</span>';
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($row['keterangan']); ?></td>
                                            <td class="text-center">
                                                <?php if (($role == 'adminbulanan' || $role == 'superadmin') && $row['status'] == 'diajukan'): ?>
                                                    <form method="POST" style="display: inline;">
                                                        <input type="hidden" name="terima_id2" value="<?php echo $row['id']; ?>">
                                                        <button type="submit" class="btn btn-success btn-sm">Terima</button>
                                                    </form>
                                                    <a href="" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modalTolak2<?php echo $row['id']; ?>">Kembalikan</a>
                                                <?php endif; ?>
                                                <?php if (($role == 'superadmin' && in_array($row['status'], ['dikembalikan', 'diterima'])) || ($role == 'umum' && in_array($row['status'], ['dikembalikan']))): ?>
                                                    <a href='?page=pembangkit_edit&id=<?= $row['id'] ?>' class='btn btn-sm btn-warning mb-2 me-2'>Edit</a>
                                                    <a href='?page=pembangkit_hapus&id=<?= $row['id'] ?>' class='btn btn-sm btn-danger mb-2' onclick='return confirm("Hapus data ini?")'>Hapus</a>
                                                    <?php if (($role == 'superadmin') && $row['status'] == 'diterima'): ?>
                                                        <a href="" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalTolak2<?php echo $row['id']; ?>">Kembalikan</a>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <!-- Modal untuk Tolak -->
                                        <div class="modal fade" id="modalTolak2<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="modalTolakLabel2" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalTolakLabel2">Kembalikan Pembangkit</h5>
                                                    </div>
                                                    <form action="" method="POST">
                                                        <div class="modal-body">
                                                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                            <div class="form-group">
                                                                <label for="keterangan<?php echo $row['id']; ?>">Keterangan di Kembalikan</label>
                                                                <textarea class="form-control" id="keterangan" name="keterangan" rows="3" required></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                            <button type="submit" name="tolak_laporan2" class="btn btn-danger">Kembalikan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                <?php endforeach;
                                endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan='16' class='text-center'>Data tidak ditemukan</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function sortTable(columnIndex) {
        var table = document.querySelector("table tbody");
        var rows = Array.from(table.querySelectorAll("tr"));
        var isAscending = table.getAttribute("data-sort-order") === "asc";

        rows.sort((rowA, rowB) => {
            var cellA = rowA.children[columnIndex].textContent.trim().toLowerCase();
            var cellB = rowB.children[columnIndex].textContent.trim().toLowerCase();
            if (!isNaN(cellA) && !isNaN(cellB)) {
                return isAscending ? cellA - cellB : cellB - cellA;
            }
            return isAscending ? cellA.localeCompare(cellB) : cellB.localeCompare(cellA);
        });

        table.innerHTML = "";
        rows.forEach(row => table.appendChild(row));
        table.setAttribute("data-sort-order", isAscending ? "desc" : "asc");

        var headers = document.querySelectorAll("thead th i");
        headers.forEach(icon => icon.className = "fa fa-sort");

        var selectedHeader = document.querySelector(`thead th:nth-child(${columnIndex + 1}) i`);
        if (selectedHeader) {
            selectedHeader.className = isAscending ? "fa fa-sort-up" : "fa fa-sort-down";
        }
    }
</script>