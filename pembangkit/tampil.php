<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'koneksi.php'; // Pastikan file koneksi ke database sudah disertakan

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

    if ($role === 'admin') {
        // Admin melihat semua data
        $stmt = $conn->prepare("SELECT * FROM pembangkit");
    } else {
        // Role umum hanya melihat data miliknya
        $stmt = $conn->prepare("SELECT * FROM pembangkit WHERE id_user = :id_user");
        $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
    }

    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<div class="container mt-4">
    <h3 class="text-center mb-3">Data Pembangkit dan Data Teknis Pembangkit</h3>
    <hr>
    <div class="card shadow">
        <div class="card-body">
            <?php if ($role !== 'admin'): // Tombol Tambah hanya untuk umum 
            ?>
                <div class="mb-3">
                    <a href='?page=pembangkit_tambah&id_user=<?= $id_user ?>' class='btn btn-primary'>Tambah Data</a>
                </div>
            <?php endif; ?>

            <div class="table-responsive" style="max-height: 500px; overflow-x: auto; overflow-y: auto;">
                <table class="table table-bordered" style="table-layout: fixed; min-width: 1800px;">
                    <thead class="table-dark text-center align-middle">
                        <tr>
                            <th rowspan="3">No.</th>
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