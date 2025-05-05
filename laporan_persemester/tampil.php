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

$query = "SELECT * FROM laporan_semester WHERE 1=1";
// Query berdasarkan role
$params = [];
if ($role != 'adminsemester' && $role != 'superadmin') {
    $query .= " AND id_user = :id_user";
    $params[':id_user'] = $id_user;
}

// Cek pencarian
if (!empty($_GET['keyword'])) {
    $keyword = "%" . $_GET['keyword'] . "%";
    $query .= (strpos($query, 'WHERE') === false) ? " WHERE" : " AND";
    $query .= " nama_perusahaan LIKE :keyword";
    $params[':keyword'] = $keyword;
}

// Filter
$parameter = $_GET['parameter'] ?? '';
if (!empty($parameter)) {
    $query .= " AND parameter = :parameter";
    $params[':parameter'] = $parameter;
}

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

// Ambil daftar untuk dropdown filter
$parameterStmt = $conn->query("SELECT DISTINCT parameter FROM laporan_semester ORDER BY parameter");
$parameterList = $parameterStmt->fetchAll(PDO::FETCH_COLUMN);

$tahunStmt = $conn->query("SELECT DISTINCT tahun FROM laporan_semester ORDER BY tahun");
$tahunList = $tahunStmt->fetchAll(PDO::FETCH_COLUMN);

$semesterStmt = $conn->query("SELECT DISTINCT semester FROM laporan_semester ORDER BY semester");
$semesterList = $semesterStmt->fetchAll(PDO::FETCH_COLUMN);

$query .= " ORDER BY FIELD(status, 'diajukan', 'dikembalikan', 'diterima')";

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

// Cek apakah id_user ada di laporan_bulanan
$queryCheck = "SELECT COUNT(*) FROM laporan_bulanan WHERE id_user = :id_user";
$stmtCheck = $conn->prepare($queryCheck);
$stmtCheck->bindParam(':id_user', $id_user, PDO::PARAM_INT);
$stmtCheck->execute();
$hasLaporanBulanan = $stmtCheck->fetchColumn() > 0;
?>


<div class="container mt-4">
    <h3 class="text-center mb-3"><i class="fas fa-bolt" style="color: #ffc107;"></i> Pelaporan Semester <i class="fas fa-bolt" style="color: #ffc107;"></i></h3>
    <hr>
    <div class="card shadow" style="overflow-x: auto; max-height: calc(100vh - 150px); overflow-y: auto;">
        <div class="card-body">
            <!-- Fitur pencarian dan filter -->
            <form method="GET" class="mb-2">
                <input type="hidden" name="page" value="laporan_persemester">
                <div class="input-group mb-2">
                    <input type="text" name="keyword" class="form-control" placeholder="Cari berdasarkan nama perusahaan..." value="<?= isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '' ?>">
                    <button type="submit" class="btn btn-success">Cari</button>
                    <a href="?page=laporan_persemester" class="btn btn-secondary">Reset</a>
                </div>
                <div class="row mb-3 align-items-end">
                    <div class="col">
                        <label for="parameter" class="form-label">Parameter</label>
                        <select name="parameter" id="parameter" class="form-select">
                            <option value="">-- Pilih Parameter --</option>
                            <?php foreach ($parameterList as $parameter): ?>
                                <option value="<?= htmlspecialchars($parameter) ?>" <?= (isset($_GET['parameter']) && $_GET['parameter'] == $parameter) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($parameter) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
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
                            <th onclick="sortTable(0)">No. <i class="fa fa-sort"></i></th>
                            <th onclick="sortTable(1)">Nama Perusahaan <i class="fa fa-sort"></th>
                            <th onclick="sortTable(2)">Parameter <i class="fa fa-sort"></th>
                            <th onclick="sortTable(3)">Baku Mutu <i class="fa fa-sort"></th>
                            <th onclick="sortTable(4)">Hasil <i class="fa fa-sort"></th>
                            <th>Laporan</th>
                            <th>LHU</th>
                            <th onclick="sortTable(5)">Tahun <i class="fa fa-sort"></th>
                            <th onclick="sortTable(6)">Semester <i class="fa fa-sort"></th>
                            <th onclick="sortTable(7)">Status <i class="fa fa-sort"></th>
                            <th onclick="sortTable(8)">Keterangan <i class="fa fa-sort"></th>
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
                                    <td><?php echo htmlspecialchars($row['baku_mutu']); ?></td>
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
                                    <td><?php echo htmlspecialchars($row['tahun']); ?></td>
                                    <td><?php echo htmlspecialchars($row['semester']); ?></td>
                                    <td class="text-center">
                                        <?php
                                        // Menampilkan status dengan ikon dan warna
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
                                        <?php if (($role == 'adminsemester' || $role == 'superadmin') && $row['status'] == 'diajukan'): ?>
                                            <!-- Tombol Terima menggunakan POST -->
                                            <form method="POST" style="display: inline;">
                                                <input type="hidden" name="terima_id" value="<?php echo $row['id']; ?>">
                                                <button type="submit" class="btn btn-success btn-sm">Terima</button>
                                            </form>
                                            <!-- Tombol Tolak dengan Modal -->
                                            <a href="" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modalTolak<?php echo $row['id']; ?>">Tolak</a>
                                        <?php endif; ?>

                                        <?php if ($row['status'] == 'diterima' && $row['status'] == 'dikembalikan'&& $role == 'superadmin'): ?>
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