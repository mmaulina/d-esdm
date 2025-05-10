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
if ($role != 'adminbulanan' && $role != 'superadmin') {
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

// Ambil data pembangkit berdasarkan nama_perusahaan
$pembangkitData = [];
if (count($result) > 0) {
    $namaPerusahaanList = array_unique(array_column($result, 'nama_perusahaan'));
    $placeholders = implode(',', array_fill(0, count($namaPerusahaanList), '?'));
    $queryPembangkit = "SELECT * FROM pembangkit WHERE nama_perusahaan IN ($placeholders)";
    $stmtPembangkit = $conn->prepare($queryPembangkit);
    foreach ($namaPerusahaanList as $index => $np) {
        $stmtPembangkit->bindValue($index + 1, $np, PDO::PARAM_STR);
    }
    $stmtPembangkit->execute();
    $pembangkitData = $stmtPembangkit->fetchAll(PDO::FETCH_ASSOC);
}

// Kelompokkan data pembangkit berdasarkan nama_perusahaan
$pembangkitGrouped = [];
foreach ($pembangkitData as $pembangkit) {
    $pembangkitGrouped[$pembangkit['nama_perusahaan']][] = $pembangkit;
}

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
}

// Cek apakah id_user ada di laporan_bulanan
$queryCheck = "SELECT COUNT(*) FROM profil WHERE id_user = :id_user AND status = 'diterima'";
$stmtCheck = $conn->prepare($queryCheck);
$stmtCheck->bindParam(':id_user', $id_user, PDO::PARAM_INT);
$stmtCheck->execute();
$hasprofil = $stmtCheck->fetchColumn() > 0;
?>

<div class="container mt-4">
    <h3 class="text-center mb-3"><i class="fas fa-bolt" style="color: #ffc107;"></i>Pelaporan Bulanan<i class="fas fa-bolt" style="color: #ffc107;"></i></h3>
    <hr>
    <div class="card shadow" style="overflow-x: auto; max-height: calc(100vh - 150px); overflow-y: auto;">
        <div class="card-body">
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
                        <label for="bulan" class="form-label">Bulan</label>
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
                        <label for="kabupaten" class="form-label">Kabupaten/Kota</label>
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
                            <th rowspan="3" style="width: 3%;" onclick="sortTable(0)">No. <i class="fa fa-sort"></i></th>
                            <th rowspan="3" onclick="sortTable(1)">Nama Perusahaan <i class="fa fa-sort"></th>
                            <?php if ($_SESSION['role'] == 'superadmin') { ?>
                            <th rowspan="3" onclick="sortTable(2)">No Hp Pimpinan<i class="fa fa-sort"></i></th>
                            <th rowspan="3" onclick="sortTable(3)">Tenaga Teknik <i class="fa fa-sort"></i></th>
                            <th rowspan="3" onclick="sortTable(4)">No Hp Tenaga Teknik <i class="fa fa-sort"></i></th>
                            <th rowspan="3" onclick="sortTable(5)">Nama Admin <i class="fa fa-sort"></i></th>
                            <th rowspan="3" onclick="sortTable(6)">Nomor Admin <i class="fa fa-sort"></i></th>
                            <?php } ?>
                            <th rowspan="3" onclick="sortTable(7)">Nomor Telepon Kantor <i class="fa fa-sort"></i></th>
                            <th rowspan="3" onclick="sortTable(8)">Tahun <i class="fa fa-sort"></th>
                            <th rowspan="3" onclick="sortTable(9)">Bulan <i class="fa fa-sort"></th>
                            <th rowspan="3" onclick="sortTable(10)">Kabupaten <i class="fa fa-sort"></th>
                            <th colspan="3" style="min-width: 250px;">Data Pembangkit</th>
                            <th colspan="10" style="min-width: 1500px;">Data Teknis Pembangkit</th>
                            <th colspan="7" style="min-width: 250px;">Pelaporan Bulanan</th>
                            <th rowspan="3" style="min-width: 150px;" onclick="sortTable(5)">Status <i class="fa fa-sort"></th>
                            <th rowspan="3" style="min-width: 150px;" onclick="sortTable(6)">Keterangan <i class="fa fa-sort"></th>
                            <th rowspan="3" style="min-width: 150px;">Aksi</th>
                        </tr>
                        <tr>
                            <th rowspan="2" onclick="sortTable(11)">Alamat Pembangkit <i class="fa fa-sort"></th>
                            <th colspan="2">Koordinat Pembangkit</th>
                            <th rowspan="2" onclick="sortTable(12)">Jenis Pembangkit <i class="fa fa-sort"></th>
                            <th rowspan="2" onclick="sortTable(13)">Fungsi <i class="fa fa-sort"></th>
                            <th rowspan="2" onclick="sortTable(14)">Kapasitas Terpasang (MW) <i class="fa fa-sort"></th>
                            <th rowspan="2" onclick="sortTable(15)">Daya Mampu Netto (MW) <i class="fa fa-sort"></th>
                            <th rowspan="2" onclick="sortTable(16)">Jumlah Unit <i class="fa fa-sort"></th>
                            <th rowspan="2" onclick="sortTable(17)">No. Unit <i class="fa fa-sort"></th>
                            <th rowspan="2" onclick="sortTable(18)"> Tahun Operasi <i class="fa fa-sort"></th>
                            <th rowspan="2" onclick="sortTable(19)">Status Operasi <i class="fa fa-sort"></th>
                            <th colspan="2">Bahan Bakar yang Digunakan</th>
                            <th rowspan="2" onclick="sortTable(20)">Volume Bahan Bakar <i class="fa fa-sort"></th>
                            <th colspan="2">Produksi Listrik</th>
                            <th rowspan="2" onclick="sortTable(21)">Susut Jaringan (bila ada) (kWh) <i class="fa fa-sort"></th>
                            <th colspan="3">Konsumsi Listrik</th>
                        </tr>
                        <tr>
                            <th onclick="sortTable(22)">Latitude <i class="fa fa-sort"></th>
                            <th onclick="sortTable(23)">Longitude <i class="fa fa-sort"></th>
                            <th onclick="sortTable(24)">Jenis <i class="fa fa-sort"></th>
                            <th onclick="sortTable(25)">Satuan <i class="fa fa-sort"></th>
                            <th onclick="sortTable(26)">Produksi Sendiri (kWh) <i class="fa fa-sort"></th>
                            <th onclick="sortTable(27)">Pembelian Sumber Lain (bila ada) (kWh) <i class="fa fa-sort"></th>
                            <th onclick="sortTable(28)">Penjualan ke Pelanggan (bila ada) (kWh) <i class="fa sort"></th>
                            <th onclick="sortTable(29)">Penjualan ke PLN (bila ada) (kWh) <i class="fa fa-sort"></th>
                            <th onclick="sortTable(30)">Pemakaian Sendiri (kWh) <i class="fa fa-sort"></th>
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
                                    <td>
                                        <?php
                                        $namaPerusahaan = $row['nama_perusahaan'];
                                        if (isset($pembangkitGrouped[$namaPerusahaan])) {
                                                $noPembangkit = 1;
                                            foreach ($pembangkitGrouped[$namaPerusahaan] as $pembangkit) {
                                                echo htmlspecialchars($pembangkit['alamat']) . " <br> " ;
                                                $noPembangkit++;
                                            }
                                        } else {
                                            echo "-";
                                        }
                                        ?>
                                    </td>
                                        <td>
                                        <?php
                                        $namaPerusahaan = $row['nama_perusahaan'];
                                        if (isset($pembangkitGrouped[$namaPerusahaan])) {
                                            $noPembangkit = 1; // Inisialisasi penghitung untuk pembangkit
                                            foreach ($pembangkitGrouped[$namaPerusahaan] as $pembangkit) {
                                                echo htmlspecialchars($pembangkit['latitude']) . " <br> " ;
                                                $noPembangkit++;
                                            }
                                        } else {
                                            echo "-";
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        $namaPerusahaan = $row['nama_perusahaan'];
                                        if (isset($pembangkitGrouped[$namaPerusahaan])) {
                                            $noPembangkit = 1; // Inisialisasi penghitung untuk pembangkit
                                            foreach ($pembangkitGrouped[$namaPerusahaan] as $pembangkit) {
                                                echo htmlspecialchars($pembangkit['longitude']) . " <br> " ;
                                                $noPembangkit++;
                                            }
                                        } else {
                                            echo "-";
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        $namaPerusahaan = $row['nama_perusahaan'];
                                        if (isset($pembangkitGrouped[$namaPerusahaan])) {
                                            $noPembangkit = 1; // Inisialisasi penghitung untuk pembangkit
                                            foreach ($pembangkitGrouped[$namaPerusahaan] as $pembangkit) {
                                                echo htmlspecialchars($pembangkit['jenis_pembangkit']) . " <br> " ;
                                                $noPembangkit++;
                                            }
                                        } else {
                                            echo "-";
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        $namaPerusahaan = $row['nama_perusahaan'];
                                        if (isset($pembangkitGrouped[$namaPerusahaan])) {
                                            $noPembangkit = 1; // Inisialisasi penghitung untuk pembangkit
                                            foreach ($pembangkitGrouped[$namaPerusahaan] as $pembangkit) {
                                                echo htmlspecialchars($pembangkit['fungsi']) . " <br> " ;
                                                $noPembangkit++;
                                            }
                                        } else {
                                            echo "-";
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        $namaPerusahaan = $row['nama_perusahaan'];
                                        if (isset($pembangkitGrouped[$namaPerusahaan])) {
                                            $noPembangkit = 1; // Inisialisasi penghitung untuk pembangkit
                                            foreach ($pembangkitGrouped[$namaPerusahaan] as $pembangkit) {
                                                echo htmlspecialchars($pembangkit['kapasitas_terpasang']) . " <br> " ;
                                                $noPembangkit++;
                                            }
                                        } else {
                                            echo "-";
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        $namaPerusahaan = $row['nama_perusahaan'];
                                        if (isset($pembangkitGrouped[$namaPerusahaan])) {
                                            $noPembangkit = 1; // Inisialisasi penghitung untuk pembangkit
                                            foreach ($pembangkitGrouped[$namaPerusahaan] as $pembangkit) {
                                                echo htmlspecialchars($pembangkit['daya_mampu_netto']) . " <br> " ;
                                                $noPembangkit++;
                                            }
                                        } else {
                                            echo "-";
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        $namaPerusahaan = $row['nama_perusahaan'];
                                        if (isset($pembangkitGrouped[$namaPerusahaan])) {
                                            $noPembangkit = 1; // Inisialisasi penghitung untuk pembangkit
                                            foreach ($pembangkitGrouped[$namaPerusahaan] as $pembangkit) {
                                                echo htmlspecialchars($pembangkit['jumlah_unit']) . " <br> " ;
                                                $noPembangkit++;
                                            }
                                        } else {
                                            echo "-";
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        $namaPerusahaan = $row['nama_perusahaan'];
                                        if (isset($pembangkitGrouped[$namaPerusahaan])) {
                                            $noPembangkit = 1; // Inisialisasi penghitung untuk pembangkit
                                            foreach ($pembangkitGrouped[$namaPerusahaan] as $pembangkit) {
                                                echo htmlspecialchars($pembangkit['no_unit']) . " <br> " ;
                                                $noPembangkit++;
                                            }
                                        } else {
                                            echo "-";
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        $namaPerusahaan = $row['nama_perusahaan'];
                                        if (isset($pembangkitGrouped[$namaPerusahaan])) {
                                            $noPembangkit = 1; // Inisialisasi penghitung untuk pembangkit
                                            foreach ($pembangkitGrouped[$namaPerusahaan] as $pembangkit) {
                                                echo htmlspecialchars($pembangkit['tahun_operasi']) . " <br> " ;
                                                $noPembangkit++;
                                            }
                                        } else {
                                            echo "-";
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        $namaPerusahaan = $row['nama_perusahaan'];
                                        if (isset($pembangkitGrouped[$namaPerusahaan])) {
                                            $noPembangkit = 1; // Inisialisasi penghitung untuk pembangkit
                                            foreach ($pembangkitGrouped[$namaPerusahaan] as $pembangkit) {
                                                echo htmlspecialchars($pembangkit['status_operasi']) . " <br> " ;
                                                $noPembangkit++;
                                            }
                                        } else {
                                            echo "-";
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        $namaPerusahaan = $row['nama_perusahaan'];
                                        if (isset($pembangkitGrouped[$namaPerusahaan])) {
                                            $noPembangkit = 1; // Inisialisasi penghitung untuk pembangkit
                                            foreach ($pembangkitGrouped[$namaPerusahaan] as $pembangkit) {
                                                echo htmlspecialchars($pembangkit['bahan_bakar_jenis']) . " <br> " ;
                                                $noPembangkit++;
                                            }
                                        } else {
                                            echo "-";
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        $namaPerusahaan = $row['nama_perusahaan'];
                                        if (isset($pembangkitGrouped[$namaPerusahaan])) {
                                            $noPembangkit = 1; // Inisialisasi penghitung untuk pembangkit
                                            foreach ($pembangkitGrouped[$namaPerusahaan] as $pembangkit) {
                                                echo htmlspecialchars($pembangkit['bahan_bakar_satuan']) . " <br> " ;
                                                $noPembangkit++;
                                            }
                                        } else {
                                            echo "-";
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        $namaPerusahaan = $row['nama_perusahaan'];
                                        if (isset($pembangkitGrouped[$namaPerusahaan])) {
                                            $noPembangkit = 1; // Inisialisasi penghitung untuk pembangkit
                                            foreach ($pembangkitGrouped[$namaPerusahaan] as $pembangkit) {
                                                echo htmlspecialchars($pembangkit['volume_bb']) . " <br> " ;
                                                $noPembangkit++;
                                            }
                                        } else {
                                            echo "-";
                                        }
                                        ?>
                                    </td>
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
                                            echo '<span class="text-muted">Status tidak diketahui</ span>';
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
                                            <a href="" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modalTolak<?php echo $row['id']; ?>">Tolak</a>
                                        <?php endif; ?>

                                        <?php if ($role == 'superadmin' && in_array($row['status'], ['dikembalikan', 'diterima'])): ?>
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
                                <td colspan='11' class='text-center'>Data tidak ditemukan</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
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