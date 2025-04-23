<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Pastikan pengguna sudah login
if (!isset($_SESSION['id_user']) || !isset($_SESSION['role'])) {
    echo "<script>alert('Silakan login terlebih dahulu!'); window.location.href='login.php';</script>";
    exit;
}

$id_user = $_SESSION['id_user']; // Ambil id_user dari sesi
$role = $_SESSION['role']; // Ambil peran pengguna dari sesi

try {
    $db = new Database();
    $conn = $db->getConnection();

    if ($role == 'admin' || $role == 'superadmin') {
        // Admin dan Superadmin melihat semua data
        $stmt = $conn->prepare("SELECT * FROM pembangkit");
    } else {
        // Role umum hanya melihat data miliknya
        $stmt = $conn->prepare("SELECT * FROM pembangkit WHERE id_user = :id_user");
        $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
    }        

    $query = "SELECT * FROM pembangkit";
    $params = [];

    if (!in_array($role, ['admin', 'superadmin'])) {
        $query .= " WHERE id_user = :id_user";
        $params[':id_user'] = $id_user;
    }

    if (!empty($_GET['keyword'])) {
        $keyword = "%" . $_GET['keyword'] . "%";

        if (in_array($role, ['admin', 'superadmin'])) {
            $query .= " WHERE nama_perusahaan LIKE :keyword";
        } else {
            $query .= " AND nama_perusahaan LIKE :keyword";
        }
        $params[':keyword'] = $keyword;
    }
    $stmt = $conn->prepare($query);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value, PDO::PARAM_STR);
    }

    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}


// Cek apakah id_user ada di laporan_bulanan
$queryCheck = "SELECT COUNT(*) FROM profil WHERE id_user = :id_user";
$stmtCheck = $conn->prepare($queryCheck);
$stmtCheck->bindParam(':id_user', $id_user, PDO::PARAM_INT);
$stmtCheck->execute();
$hasprofil = $stmtCheck->fetchColumn() > 0;
?>

<div class="container mt-4">
    <h3 class="text-center mb-3">Data Pembangkit dan Data Teknis Pembangkit</h3>
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
                        Anda harus melengkapi <strong>Profil Perusahaan</strong> terlebih dahulu sebelum dapat menambahkan Data Pembangkit.
                    </div>
                <?php endif; ?>
                <div class="mb-3">
                <?php if ($hasprofil && $role == 'umum') : ?>
                    <a href="?page=pembangkit_tambah" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Data
                    </a>
                <?php endif; ?>
                        <?php if ($_SESSION['role'] == 'superadmin') { ?> 
                        <a href="?page=pembangkit_tambah" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Data
                        </a>
                        <?php } ?>
                        <a href="?page=pembangkit_export" class="btn btn-success">Ekspor ke Spreadsheet</a>
                    </div>
            </div>
            <div class="table-responsive" style="max-height: 500px; overflow-x: auto; overflow-y: auto;">
                <table class="table table-bordered" style="table-layout: fixed; min-width: 1800px;">
                    <thead class="table-dark text-center align-middle">
                        <tr>
                            <th rowspan="3" style="width: 3%;">No.</th>
                            <th rowspan="3">Nama Perusahaan</th>
                            <th colspan="4" style="min-width: 250px;">Data Pembangkit</th>
                            <th colspan="9" style="min-width: 1500px;">Data Teknis Pembangkit</th>
                            <th rowspan="3" style="min-width: 150px;">Aksi</th>
                        </tr>
                        <tr>
                            <th rowspan="2">Alamat</th>
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
                        </tr>
                        <tr>
                            <th>Longitude</th>
                            <th>Latitude</th>
                            <th>Jenis</th>
                            <th>Satuan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($result) > 0): ?>
                            <?php
                            $no = 1;
                            foreach ($result as $row):
                            ?>
                                <tr>
                                    <td class="text-center"><?php echo $no++; ?></td>
                                    <td><?= htmlspecialchars($row['nama_perusahaan']) ?></td>
                                    <td><?= htmlspecialchars($row['alamat']) ?></td>
                                    <td><?= htmlspecialchars($row['longitude']) ?></td>
                                    <td><?= htmlspecialchars($row['latitude']) ?></td>
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
                                    <td>
                                        <a href='?page=pembangkit_edit&id=<?= $row['id'] ?>' class='btn btn-sm btn-warning'>Edit</a>
                                        <a href='?page=pembangkit_hapus&id=<?= $row['id'] ?>' class='btn btn-sm btn-danger' onclick='return confirm("Hapus data ini?")'>Hapus</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
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