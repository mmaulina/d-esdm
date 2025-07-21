<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

$id_user = $_SESSION['id_user'];
$role = $_SESSION['role'];

$db = new Database();
$conn = $db->getConnection();

// First Query: Fetching semester reports
$query = "SELECT * FROM laporan_semester WHERE 1=1";
$params = [];
if ($role != 'adminsemester' && $role != 'superadmin') {
    $query .= " AND id_user = :id_user";
    $params[':id_user'] = $id_user;
}

// Search functionality
if (!empty($_GET['keyword'])) {
    $keyword = "%" . $_GET['keyword'] . "%";
    $query .= (strpos($query, 'WHERE') === false) ? " WHERE" : " AND";
    $query .= " nama_perusahaan LIKE :keyword";
    $params[':keyword'] = $keyword;
}

// Year and semester filters
$tahun = $_GET['tahun'] ?? '';
if (!empty($tahun)) {
    $query .= " AND tahun = :tahun";
    $params[':tahun'] = $tahun;
}

$semester = $_GET['semester'] ?? '';
if (!empty($semester)) {
    $query .= " AND semester = :semester";
    $params[':semester'] = $semester;
}

// Fetch distinct years and semesters
$tahunStmt = $conn->query("SELECT DISTINCT tahun FROM laporan_semester ORDER BY tahun");
$tahunList = $tahunStmt->fetchAll(PDO::FETCH_COLUMN);

$semesterStmt = $conn->query("SELECT DISTINCT semester FROM laporan_semester ORDER BY semester");
$semesterList = $semesterStmt->fetchAll(PDO::FETCH_COLUMN);

$query .= " ORDER BY FIELD(status, 'diajukan', 'dikembalikan', 'diterima')";

// Execute the first query
$stmt = $conn->prepare($query);
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value, PDO::PARAM_STR);
}
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Process approval/rejection
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['terima_id'])) {
        $id = $_POST['terima_id'];
        $updateQuery = "UPDATE laporan_semester SET status = 'diterima' WHERE id = :id";
    } elseif (isset($_POST['tolak_laporan'])) {
        $id = $_POST['id'];
        $keterangan = $_POST['keterangan'];
        $updateQuery = "UPDATE laporan_semester SET status = 'dikembalikan', keterangan = :keterangan WHERE id = :id";
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

// Check if user has monthly report
$queryCheck = "SELECT COUNT(*) FROM laporan_bulanan WHERE id_user = :id_user";
$stmtCheck = $conn->prepare($queryCheck);
$stmtCheck->bindParam(':id_user', $id_user, PDO::PARAM_INT);
$stmtCheck->execute();
$hasLaporanBulanan = $stmtCheck->fetchColumn() > 0;

// Second Query: Fetching parameter data
$query2 = "SELECT * FROM parameter WHERE 1=1";

$params2 = [];
if ($role != 'adminsemester' && $role != 'superadmin') {
    $query2 .= " AND id_user = :id_user"; // Corrected from $query to $query2
    $params2[':id_user'] = $id_user;
}

// Year and semester filters for the second query
if (!empty($tahun)) {
    $query2 .= " AND tahun = :tahun";
    $params2[':tahun'] = $tahun;
}

if (!empty($semester)) {
    $query2 .= " AND semester = :semester";
    $params2[':semester'] = $semester;
}

// Execute the second query
$stmt2 = $conn->prepare($query2);
foreach ($params2 as $key => $value) {
    $stmt2->bindValue($key, $value, PDO::PARAM_STR);
}
$stmt2->execute();
$result2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['terima_id2'])) {
        $id = $_POST['terima_id2'];
        $updateQuery2 = "UPDATE parameter SET status = 'diterima' WHERE id = :id";
    } elseif (isset($_POST['tolak_laporan2'])) {
        $id = $_POST['id'];
        $keterangan = $_POST['keterangan'];
        $updateQuery2 = "UPDATE parameter SET status = 'dikembalikan', keterangan = :keterangan WHERE id = :id";
    }

    if (isset($updateQuery2)) {
        $updateStmt2 = $conn->prepare($updateQuery2);
        $updateStmt2->bindParam(':id', $id, PDO::PARAM_INT);
        if (isset($keterangan)) {
            $updateStmt2->bindParam(':keterangan', $keterangan, PDO::PARAM_STR);
        }
        $updateStmt2->execute();
        echo "<meta http-equiv='refresh' content='0; url=?page=laporan_persemester'>";
        exit;
    }
}
?>



<div class="container mt-4">
    <h3 class="text-center mb-3">
        <i class="fas fa-bolt" style="color: #ffc107;"></i> Pelaporan Semester dan Tabel Parameter
        <i class="fas fa-bolt" style="color: #ffc107;"></i>
    </h3>
    <hr>
    <div class="card shadow" style="overflow-x: auto; max-height: calc(100vh - 150px); overw-y: auto;">
        <div class="card-body">
            <!-- Search and Filter Features -->
            <form method="GET" class="mb-2">
                <input type="hidden" name="page" value="laporan_persemester">
                <div class="input-group mb-2">
                    <input type="text" name="keyword" class="form-control" placeholder="Cari berdasarkan nama perusahaan..."
                        value="<?= isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '' ?>">
                    <button type="submit" class="btn btn-success">Cari</button>
                    <a href="?page=laporan_persemester" class="btn btn-secondary">Reset</a>
                </div>
                <div class="row mb-3 align-items-end">
                    <div class="col">
                        <label for="tahun" class="form-label">Tahun</label>
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
                        <label for="semester" class="form-label">Semester</label>
                        <select name="semester" id="semester" class="form-select">
                            <option value="">-- Pilih Semester --</option>
                            <?php foreach ($semesterList as $semester): ?>
                                <option value="<?= htmlspecialchars($semester) ?>" <?= (isset($_GET['semester']) && $_GET['semester'] == $semester) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($semester) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col d-flex gap-2">
                        <button type="submit" class="btn btn-success w-100">Filter</button>
                        <a href="?page=laporan_persemester" class="btn btn-secondary w-100">Reset</a>
                    </div>
                </div>
            </form>

            <!-- Alert for Monthly Report Requirement -->
            <?php if (!$hasLaporanBulanan && $role == 'umum') : ?>
                <div class="alert alert-warning text-center" role="alert">
                    Anda harus mengisi <strong>Laporan Bulanan</strong> terlebih dahulu sebelum dapat menambahkan Laporan Semester.
                </div>
            <?php endif; ?>

            <!-- Buttons for Adding Data -->
            <?php if ($hasLaporanBulanan || $_SESSION['role'] == 'superadmin') : ?>
                <a href="?page=tambah_laporan_persemester" class="btn btn-primary mb-3">
                    <i class="fas fa-plus"></i> Tambah Data
                </a>
                <a href="?page=tambah_parameter" class="btn btn-primary mb-3">
                    <i class="fas fa-plus"></i> Tambah Data Parameter
                </a>
            <?php endif; ?>

            <!-- Table for Semester Reports -->
            <div class="table-responsive" style="max-height: 500px; overflow-x: auto; overflow-y: auto;">
                <table class="table table-bordered" style="min-width: 1200px; white-space: nowrap;">
                    <thead class="table-dark text-center align-middle">
                        <tr>
                            <th rowspan="3" onclick="sortTable(0)">No. <i class="fa fa-sort"></i></th>
                            <th rowspan="3" onclick="sortTable(1)">Nama Perusahaan <i class="fa fa-sort"></i></th>
                            <?php if ($_SESSION['role'] == 'superadmin') : ?>
                                <th rowspan="3" onclick="sortTable(2)">No Hp Pimpinan <i class="fa fa-sort"></i></th>
                                <th rowspan="3" onclick="sortTable(3)">Tenaga Teknik <i class="fa fa-sort"></i></th>
                                <th rowspan="3" onclick="sortTable(4)">No Hp Tenaga Teknik <i class="fa fa-sort"></i></th>
                                <th rowspan="3" onclick="sortTable(5)">Nama Admin <i class="fa fa-sort"></i></th>
                                <th rowspan="3" onclick="sortTable(6)">Nomor Admin <i class="fa fa-sort"></i></th>
                            <?php endif; ?>
                            <th rowspan="3" onclick="sortTable(7)">Nomor Telepon Kantor <i class="fa fa-sort"></i></th>
                            <th rowspan="3">Laporan Semester</th>
                            <th rowspan="3">Laporan Hasil Uji Parameter</th>
                            <th rowspan="3" onclick="sortTable(10)">Tahun <i class="fa fa-sort"></i></th>
                            <th rowspan="3" onclick="sortTable(11)">Semester <i class="fa fa-sort"></i></th>
                            <th rowspan="3" onclick="sortTable(12)">Status <i class="fa fa-sort"></i></th>
                            <th rowspan="3" onclick="sortTable(13)">Keterangan <i class="fa fa-sort"></i></th>
                            <th rowspan="3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($result) > 0): ?>
                            <?php
                            // Kelompokkan data berdasarkan id_user
                            $groupedData = [];
                            foreach ($result as $row) {
                                if (isset($row['id_user'])) {
                                    $groupedData[$row['id_user']][] = $row;
                                } else {
                                    // Tangani kasus di mana id_user tidak ditemukan
                                    echo "id_user tidak ditemukan untuk baris: " . json_encode($row);
                                }
                            }

                            foreach ($groupedData as $id_user => $rows):
                                $no = 1;
                                // Ambil nama_perusahaan dari baris pertama
                                $nama_perusahaan = htmlspecialchars($rows[0]['nama_perusahaan']);
                                // Baris header grup dengan id_user dan nama_perusahaan
                                echo "<tr><td colspan='16' class='fw-bold bg-light'>NAMA PERUSAHAAN = ($nama_perusahaan) </td></tr>";
                                foreach ($rows as $row):
                            ?>
                                    <tr>
                                        <td class="text-center"><?= $no++; ?></td>
                                        <td><?= htmlspecialchars($row['nama_perusahaan']); ?></td>
                                        <?php if ($_SESSION['role'] == 'superadmin') : ?>
                                            <td><?= htmlspecialchars($row['no_hp_pimpinan']); ?></td>
                                            <td><?= htmlspecialchars($row['tenaga_teknik']); ?></td>
                                            <td><?= htmlspecialchars($row['no_hp_teknik']); ?></td>
                                            <td><?= htmlspecialchars($row['nama']); ?></td>
                                            <td><?= htmlspecialchars($row['no_hp']); ?></td>
                                        <?php endif; ?>
                                        <td><?= htmlspecialchars($row['no_telp_kantor']); ?></td>
                                        <td class="text-center">
                                            <?php if (!empty($row['file_laporan'])) : ?>
                                                <a href="<?= htmlspecialchars($row['file_laporan']); ?>" target="_blank" class="btn btn-sm btn-dark">
                                                    <i class="fas fa-file-alt"></i> Lihat
                                                </a>
                                            <?php else : ?>
                                                <span class="text-danger">Tidak ada file</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if (!empty($row['file_lhu'])) : ?>
                                                <a href="<?= htmlspecialchars($row['file_lhu']); ?>" target="_blank" class="btn btn-sm btn-dark">
                                                    <i class="fas fa-file-alt"></i> Lihat
                                                </a>
                                            <?php else : ?>
                                                <span class="text-danger">Tidak ada file</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($row['tahun']); ?></td>
                                        <td><?= htmlspecialchars($row['semester']); ?></td>
                                        <td class="text-center">
                                            <?php
                                            // Tampilkan status dengan ikon dan warna
                                            switch ($row['status']) {
                                                case 'diajukan':
                                                    echo '<i class="fas fa-clock" style="color: yellow;"></i> Diajukan';
                                                    break;
                                                case 'diterima':
                                                    echo '<i class="fas fa-check" style="color: green;"></i> Diterima';
                                                    break;
                                                case 'dikembalikan':
                                                    echo '<i class="fas fa-times" style="color: red;"></i> Dikembalikan';
                                                    break;
                                                default:
                                                    echo '<span class="text-muted">Status tidak diketahui</span>';
                                            }
                                            ?>
                                        </td>
                                        <td><?= htmlspecialchars($row['keterangan']); ?></td>
                                        <td class="text-center">
                                            <?php if (($role == 'adminsemester' || $role == 'superadmin') && $row['status'] == 'diajukan'): ?>
                                                <form method="POST" style="display: inline;">
                                                    <input type="hidden" name="terima_id" value="<?= $row['id']; ?>">
                                                    <button type="submit" class="btn btn-success btn-sm">Terima</button>
                                                </form>
                                                <a href="#" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modalTolak<?= $row['id']; ?>">Kembalikan</a>
                                            <?php endif; ?>

                                            <?php if (($row['status'] == 'diterima' || $row['status'] == 'dikembalikan') && $role == 'superadmin'): ?>
                                                <a href="?page=edit_laporan_persemester&id=<?= $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                                <a href="?page=hapus_laporan_persemester&id=<?= $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?');">Hapus</a>
                                                <?php if ($row['status'] == 'diterima'): ?>
                                                    <a href="#" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalTolak<?= $row['id']; ?>">Kembalikan</a>
                                                <?php endif; ?>
                                            <?php endif; ?>

                                            <?php if ($role == 'umum' && $row['status'] == 'dikembalikan'): ?>
                                                <a href="?page=edit_laporan_persemester&id=<?= $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                                <a href="?page=hapus_laporan_persemester&id=<?= $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?');">Hapus</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <!-- Modal untuk Menolak Laporan -->
                                    <div class="modal fade" id="modalTolak<?= $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="modalTolakLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalTolakLabel">Kembalikan Laporan</h5>
                                                </div>
                                                <form action="" method="POST">
                                                    <div class="modal-body">
                                                        <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                                        <div class="form-group">
                                                            <label for="keterangan<?= $row['id']; ?>">Keterangan di Kembalikan</label>
                                                            <textarea class="form-control" id="keterangan<?= $row['id']; ?>" name="keterangan" rows="3" required></textarea>
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
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan='15' class='text-center'>Data tidak ditemukan</td>
                            </tr>
                        <?php endif; ?>

                    </tbody>
                </table>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col">
                <h5 class="fw-bold mb-3 text-center">Tabel Parameter</h5>
                <div class="table-responsive" style="overflow-x:auto; -webkit-overflow-scrolling: touch;">
                    <table class="table table-bordered table-striped table-sm" id="tabel-produksi-konsumsi" style="min-width: 1000px; white-space: nowrap;">
                        <thead class="table-dark">
                            <tr>
                                <th rowspan="3">No.</th>
                                <th rowspan="3">Nama Perusahaan</th>
                                <th rowspan="3">No Seri Genset</th>
                                <th colspan="3">Parameter SO₂</th>
                                <th colspan="3">Parameter HO₂</th>
                                <th colspan="3">Parameter TSP/Debu</th>
                                <th colspan="3">Parameter CO</th>
                                <th colspan="3">Parameter Kebisingan</th>
                                <th rowspan="3">Tahun</th>
                                <th rowspan="3">Semester</th>
                                <th rowspan="3">Status</th>
                                <th rowspan="3">Keterangan</th>
                                <th rowspan="3">Aksi</th>
                            </tr>
                            <tr>
                                <th>Baku Mutu</th>
                                <th>Hasil</th>
                                <th>Rencana Aksi</th>
                                <th>Baku Mutu</th>
                                <th>Hasil</th>
                                <th>Rencana Aksi</th>
                                <th>Baku Mutu</th>
                                <th>Hasil</th>
                                <th>Rencana Aksi</th>
                                <th>Baku Mutu</th>
                                <th>Hasil</th>
                                <th>Rencana Aksi</th>
                                <th>Baku Mutu</th>
                                <th>Hasil</th>
                                <th>Rencana Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $modalList = []; ?>
                            <?php if (count($result2) > 0): ?>
                                <?php
                                $groupedData = [];
                                foreach ($result2 as $row) {
                                    if (isset($row['id_user'])) {
                                        $groupedData[$row['id_user']][$row['nama_perusahaan']][] = $row;
                                    }
                                }
                                $modalList = []; // Simpan ID untuk modal
                                foreach ($groupedData as $companies) :
                                    foreach ($companies as $nama_perusahaan => $rows) : ?>
                                        <tr>
                                            <td colspan="25" class="fw-bold bg-light">NAMA PERUSAHAAN = <?= htmlspecialchars($nama_perusahaan); ?></td>
                                        </tr>
                                        <?php $no = 1;
                                        foreach ($rows as $row) :
                                            $modalList[] = $row; // Simpan untuk modal nanti
                                        ?>
                                            <tr>
                                                <td class="text-center"><?= $no++; ?></td>
                                                <td><?= htmlspecialchars($row['nama_perusahaan']); ?></td>
                                                <td><?= htmlspecialchars($row['no_seri_genset']); ?></td>
                                                <td><?= htmlspecialchars($row['baku_mutu_so2']); ?></td>
                                                <td><?= htmlspecialchars($row['hasil_so2']); ?></td>
                                                <td><?= htmlspecialchars($row['rencana_aksi_so2']); ?></td>
                                                <td><?= htmlspecialchars($row['baku_mutu_ho2']); ?></td>
                                                <td><?= htmlspecialchars($row['hasil_ho2']); ?></td>
                                                <td><?= htmlspecialchars($row['rencana_aksi_ho2']); ?></td>
                                                <td><?= htmlspecialchars($row['baku_mutu_tsp']); ?></td>
                                                <td><?= htmlspecialchars($row['hasil_tsp']); ?></td>
                                                <td><?= htmlspecialchars($row['rencana_aksi_tsp']); ?></td>
                                                <td><?= htmlspecialchars($row['baku_mutu_co']); ?></td>
                                                <td><?= htmlspecialchars($row['hasil_co']); ?></td>
                                                <td><?= htmlspecialchars($row['rencana_aksi_co']); ?></td>
                                                <td><?= htmlspecialchars($row['baku_mutu_kebisingan']); ?></td>
                                                <td><?= htmlspecialchars($row['hasil_kebisingan']); ?></td>
                                                <td><?= htmlspecialchars($row['rencana_aksi_kebisingan']); ?></td>
                                                <td><?= htmlspecialchars($row['tahun']); ?></td>
                                                <td><?= htmlspecialchars($row['semester']); ?></td>
                                                <td class="text-center">
                                                    <?php
                                                    switch ($row['status']) {
                                                        case 'diajukan':
                                                            echo '<i class="fas fa-clock text-warning"></i> Diajukan';
                                                            break;
                                                        case 'diterima':
                                                            echo '<i class="fas fa-check text-success"></i> Diterima';
                                                            break;
                                                        case 'dikembalikan':
                                                            echo '<i class="fas fa-times text-danger"></i> Dikembalikan';
                                                            break;
                                                        default:
                                                            echo '<span class="text-muted">Status tidak diketahui</span>';
                                                    }
                                                    ?>
                                                </td>
                                                <td><?= htmlspecialchars($row['keterangan']); ?></td>
                                                <td class="text-center">
                                                    <?php if (($role == 'adminsemester' || $role == 'superadmin') && $row['status'] == 'diajukan'): ?>
                                                        <form method="POST" style="display: inline;">
                                                            <input type="hidden" name="terima_id2" value="<?= $row['id']; ?>">
                                                            <button type="submit" class="btn btn-success btn-sm">Terima</button>
                                                        </form>
                                                        <a href="#" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modalTolak2<?= $row['id']; ?>">Kembalikan</a>
                                                    <?php endif; ?>

                                                    <?php if (($row['status'] == 'diterima' || $row['status'] == 'dikembalikan') && $role == 'superadmin'): ?>
                                                        <a href="?page=edit_parameter&id=<?= $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                                        <a href="?page=hapus_parameter&id=<?= $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?');">Hapus</a>
                                                        <?php if ($row['status'] == 'diterima'): ?>
                                                            <a href="#" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalTolak2<?= $row['id']; ?>">Kembalikan</a>
                                                        <?php endif; ?>
                                                    <?php endif; ?>

                                                    <?php if ($role == 'umum' && $row['status'] == 'dikembalikan'): ?>
                                                        <a href="?page=edit_parameter&id=<?= $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                                        <a href="?page=hapus_parameter&id=<?= $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?');">Hapus</a>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                <?php endforeach;
                                endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="25" class="text-center">Data tidak ditemukan</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- MODAL KEMBALIKAN -->
        <?php foreach ($modalList as $row): ?>
            <?php if (($role == 'adminsemester' || $role == 'superadmin') && in_array($row['status'], ['diajukan', 'diterima'])): ?>
                <div class="modal fade" id="modalTolak2<?= $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="modalTolakLabel2<?= $row['id']; ?>" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form action="" method="POST">
                                <div class="modal-header">
                                    <h5 class="modal-title">Kembalikan Laporan</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                    <div class="form-group">
                                        <label for="keterangan<?= $row['id']; ?>">Keterangan Dikembalikan</label>
                                        <textarea class="form-control" id="keterangan<?= $row['id']; ?>" name="keterangan" rows="3" required></textarea>
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
            <?php endif; ?>
        <?php endforeach; ?>


    </div>
</div>



<?php

// Cek apakah user sudah login sebagai admin atau superadmin
if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'superadmin'):

    include_once 'koneksi.php';
    $database = new Database();
    $db = $database->getConnection();

    $tahun_sekarang = date("Y");
    $bulan_sekarang = date("n");

    // Ambil daftar user yang sudah membayar denda
    $querySudahBayar = $db->prepare("
    SELECT id_profil_perusahaan 
    FROM denda_laporan_semester 
    WHERE keterangan = 'Sudah Dibayar'
");
    $querySudahBayar->execute();
    $dataSudahBayar = $querySudahBayar->fetchAll(PDO::FETCH_COLUMN);

    // Ambil semua data perusahaan
    $query = "SELECT id_user, nama_perusahaan FROM profil";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $data_perusahaan = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $keterangan = [];

    foreach ($data_perusahaan as $perusahaan) {
        $id_user = $perusahaan['id_user'];
        $nama_perusahaan = $perusahaan['nama_perusahaan'];

        // Ambil id_profil untuk pengecekan sudah bayar
        $getProfil = $db->prepare("SELECT id_profil FROM profil WHERE id_user = :id_user");
        $getProfil->bindParam(':id_user', $id_user);
        $getProfil->execute();
        $id_profil = $getProfil->fetchColumn();

        if (in_array($id_profil, $dataSudahBayar)) {
            continue; // Lewati jika sudah membayar denda
        }

        // Cek laporan Semester I
        $cekSemester1 = $db->prepare("
        SELECT COUNT(*) 
        FROM laporan_semester 
        WHERE id_user = :id_user AND semester = 'Semester I' AND tahun = :tahun
    ");
        $cekSemester1->bindParam(':id_user', $id_user);
        $cekSemester1->bindParam(':tahun', $tahun_sekarang);
        $cekSemester1->execute();
        $adaSemester1 = $cekSemester1->fetchColumn() > 0;

        // Cek laporan Semester II
        $cekSemester2 = $db->prepare("
        SELECT COUNT(*) 
        FROM laporan_semester 
        WHERE id_user = :id_user AND semester = 'Semester II' AND tahun = :tahun
    ");
        $cekSemester2->bindParam(':id_user', $id_user);
        $cekSemester2->bindParam(':tahun', $tahun_sekarang);
        $cekSemester2->execute();
        $adaSemester2 = $cekSemester2->fetchColumn() > 0;

        // Menentukan apakah telat
        $telatSemester1 = (!$adaSemester1 && $bulan_sekarang >= 7);
        $telatSemester2 = (!$adaSemester2 && $tahun_sekarang > $tahun_sekarang);

        // Buat keterangan
        if ($telatSemester1 && $telatSemester2) {
            $ket = "Belum mengupload Semester I & II (Telat)";
        } elseif ($telatSemester1) {
            $ket = "Belum mengupload Semester I (Telat)";
        } elseif ($telatSemester2) {
            $ket = "Belum mengupload Semester II (Telat)";
        } elseif (!$adaSemester1 && !$adaSemester2) {
            $ket = "Belum mengupload Semester I & II";
        } elseif (!$adaSemester1) {
            $ket = "Belum mengupload Semester I";
        } elseif (!$adaSemester2) {
            $ket = "Belum mengupload Semester II";
        } else {
            $ket = "Sudah mengupload kedua semester";
        }

        $keterangan[] = [
            'id_user' => $id_user,
            'nama_perusahaan' => $nama_perusahaan,
            'keterangan' => $ket,
            'denda' => ($telatSemester1 || $telatSemester2)
        ];
    }

    // Proses pembayaran denda
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bayar_denda'])) {
        $id_user = $_POST['id_user'];
        $nama_perusahaan = $_POST['nama_perusahaan'];

        // Cek laporan Semester I
        $cekSemester1 = $db->prepare("
        SELECT COUNT(*) 
        FROM laporan_semester 
        WHERE id_user = :id_user AND semester = 'Semester I' AND tahun = :tahun
    ");
        $cekSemester1->bindParam(':id_user', $id_user);
        $cekSemester1->bindParam(':tahun', $tahun_sekarang);
        $cekSemester1->execute();
        $adaSemester1 = $cekSemester1->fetchColumn() > 0;

        // Cek laporan Semester II
        $cekSemester2 = $db->prepare("
        SELECT COUNT(*) 
        FROM laporan_semester 
        WHERE id_user = :id_user AND semester = 'Semester II' AND tahun = :tahun
    ");
        $cekSemester2->bindParam(':id_user', $id_user);
        $cekSemester2->bindParam(':tahun', $tahun_sekarang);
        $cekSemester2->execute();
        $adaSemester2 = $cekSemester2->fetchColumn() > 0;

        // Menentukan denda yang harus dibayar
        $denda = [];

        if (!$adaSemester1 && $bulan_sekarang >= 7) {
            $denda[] = "Semester I $tahun_sekarang";
        }
        if (!$adaSemester2 && $tahun_sekarang > $tahun_sekarang) {
            $denda[] = "Semester II " . ($tahun_sekarang - 1);
        }

        $denda_text = implode(', ', $denda);

        // Ambil id_profil
        $getProfil = $db->prepare("SELECT id_profil FROM profil WHERE id_user = :id_user");
        $getProfil->bindParam(':id_user', $id_user);
        $getProfil->execute();
        $id_profil = $getProfil->fetchColumn();

        // Insert ke denda_laporan_semester
        $insert = $db->prepare("
        INSERT INTO denda_laporan_semester (id_profil_perusahaan, nama_perusahaan, denda, keterangan)
        VALUES (:id_profil, :nama_perusahaan, :denda, 'Sudah Dibayar')
    ");
        $insert->bindParam(':id_profil', $id_profil);
        $insert->bindParam(':nama_perusahaan', $nama_perusahaan);
        $insert->bindParam(':denda', $denda_text);
        $insert->execute();

        echo "<meta http-equiv='refresh' content='0; url=?page=laporan_persemester'>";

        // Menampilkan alert dan melakukan reload setelah 1 detik
        echo "<div class='alert alert-success' id='alertDenda'>
    Pembayaran Denda berhasil disimpan untuk <b>$nama_perusahaan</b>
    </div>";

        echo "<script type='text/javascript'>
    setTimeout(function() {
        document.getElementById('alertDenda').style.display = 'none'; // Menyembunyikan alert setelah 1 detik
    }, 2000); // 1 detik
    </script>";

        exit;
    }

    // Filter hanya data yang telat
    $keterangan = array_filter($keterangan, function ($item) {
        return $item['denda'];
    });
?>

    <div class="container mt-4">
        <div class="card shadow" style="overflow-x: auto; max-height: calc(100vh - 150px); overflow-y: auto;">
            <div class="card-body">
                <h3 class="text-center mb-3">Status Laporan Semester Tahun <?= $tahun_sekarang ?></h3>
                <hr>
                <div class="table-responsive" style="max-height: 500px; overflow-x: auto; overflow-y: auto;">
                    <table class="table table-bordered" style="min-width: 1200px; white-space: nowrap;">
                        <thead class="table-dark">
                            <tr>
                                <th onclick="sortTable(0)">No <i class="fa fa-sort"></i></th>
                                <th onclick="sortTable(1)">Nama Perusahaan <i class="fa fa-sort"></i></th>
                                <th onclick="sortTable(2)">Keterangan <i class="fa fa-sort"></i></th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($keterangan)): ?>
                                <tr>
                                    <td colspan="4" class="text-center">Tidak ada perusahaan yang telat mengupload laporan.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($keterangan as $index => $data): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><?= htmlspecialchars($data['nama_perusahaan']) ?></td>
                                        <td><?= htmlspecialchars($data['keterangan']) ?></td>
                                        <td>
                                            <form method="post">
                                                <input type="hidden" name="id_user" value="<?= $data['id_user'] ?>">
                                                <input type="hidden" name="nama_perusahaan" value="<?= htmlspecialchars($data['nama_perusahaan']) ?>">
                                                <button type="submit" name="bayar_denda" class="btn btn-success btn-sm">Sudah Bayar</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<?php endif; ?>

<!-- JAVASCRIPT FILTER -->
<script>
    function sortTable(columnIndex) {
        var table = document.querySelector("table tbody");
        var rows = Array.from(table.querySelectorAll("tr"));
        var isAscending = table.getAttribute("data-sort-order") === "asc";

        // Sort rows
        rows.sort((rowA, rowB) => {
            var cellA = rowA.children[columnIndex].textContent.trim().toLowerCase();
            var cellB = rowB.children[columnIndex].textContent.trim().toLowerCase();

            if (!isNaN(cellA) && !isNaN(cellB)) {
                return isAscending ? cellA - cellB : cellB - cellA;
            }
            return isAscending ? cellA.localeCompare(cellB) : cellB.localeCompare(cellA);
        });

        // Remove existing rows
        table.innerHTML = "";

        // Append sorted rows
        rows.forEach(row => table.appendChild(row));

        // Toggle sorting order
        table.setAttribute("data-sort-order", isAscending ? "desc" : "asc");

        // Update icon
        updateSortIcons(columnIndex, isAscending);
    }

    function updateSortIcons(columnIndex, isAscending) {
        var headers = document.querySelectorAll("thead th i");
        headers.forEach(icon => icon.className = "fa fa-sort"); // Reset semua ikon

        var selectedHeader = document.querySelector(`thead th:nth-child(${columnIndex + 1}) i`);
        if (selectedHeader) {
            selectedHeader.className = isAscending ? "fa fa-sort-up" : "fa fa-sort-down";
        }
    }
</script>



<!-- Bootstrap CSS -->
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.3/css/bootstrap.min.css"> -->

<!-- jQuery dan Bootstrap JS -->
<!-- <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.3/js/bootstrap.bundle.min.js"></script> -->