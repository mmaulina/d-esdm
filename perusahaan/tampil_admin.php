<?php
include_once 'koneksi.php'; // Pastikan koneksi.php sudah menggunakan PDO

try {
    $database = new Database();
    $pdo = $database->getConnection(); // Dapatkan koneksi PDO
    $query = "SELECT * FROM profil ORDER BY id_profil ASC";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $profiles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<div class="container mt-4">
    <h3 class="text-center mb-3">Data Profil Perusahaan</h3>
    <hr>
    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive" style="max-height: 500px; overflow-x: auto; overflow-y: auto;">
                <table class="table table-bordered" style="min-width: 1200px; white-space: nowrap;">
                    <thead class="table-dark text-center align-middle">
                        <tr>
                            <th rowspan="2">No.</th>
                            <th rowspan="2">Nama Perusahaan</th>
                            <th rowspan="2">Kabupaten/Kota</th>
                            <th rowspan="2">Alamat</th>
                            <th rowspan="2">Jenis Usaha</th>
                            <th rowspan="2">Nomor Telepon Kantor</th>
                            <th rowspan="2">No. Fax</th>
                            <th rowspan="2">Tenaga Teknik</th>
                            <th colspan="3">Kontak Person</th>
                            <th rowspan="2">Aksi</th>
                        </tr>
                        <tr>
                            <th>Nama</th>
                            <th>No. HP</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($profiles) > 0): ?>
                            <?php $no = 1; foreach ($profiles as $row): ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= htmlspecialchars($row['nama_perusahaan']); ?></td>
                                    <td><?= htmlspecialchars($row['kabupaten']); ?></td>
                                    <td><?= htmlspecialchars($row['alamat']); ?></td>
                                    <td><?= htmlspecialchars($row['jenis_usaha']); ?></td>
                                    <td><?= htmlspecialchars($row['no_telp_kantor']); ?></td>
                                    <td><?= htmlspecialchars($row['no_fax']); ?></td>
                                    <td><?= htmlspecialchars($row['tenaga_teknik']); ?></td>
                                    <td><?= htmlspecialchars($row['nama']); ?></td>
                                    <td><?= htmlspecialchars($row['no_hp']); ?></td>
                                    <td><?= htmlspecialchars($row['email']); ?></td>
                                    <td>
                                        <a href="?page=update_profil_admin&id_profil=<?= htmlspecialchars($row['id_profil']); ?>" class="btn btn-warning btn-sm">Edit</a>
                                        <a href="?page=delete_profil_admin&id_profil=<?= htmlspecialchars($row['id_profil']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="12" class="text-center">Data tidak ditemukan</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>