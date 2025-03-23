<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'koneksi.php';

// Buat koneksi menggunakan PDO
$database = new Database();
$conn = $database->getConnection();

// Ambil data users yang diajukan
$queryUser = "SELECT * FROM users WHERE status = 'diajukan'";
$stmtUser = $conn->prepare($queryUser);
$stmtUser->execute();
$resultUser = $stmtUser->fetchAll(PDO::FETCH_ASSOC);

// Ambil laporan semester yang diajukan
$queryLaporan = "SELECT * FROM laporan_semester WHERE status = 'diajukan'";
$stmtLaporan = $conn->prepare($queryLaporan);
$stmtLaporan->execute();
$resultLaporan = $stmtLaporan->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-4">
    <h3 class="text-center mb-3">Notifikasi Pengajuan</h3>
    <hr>
    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive" style="max-height: 500px; overflow-x: auto; overflow-y: auto;">
                <table class="table table-bordered" style="min-width: 1200px; white-space: nowrap;">
                    <thead class="table-dark text-center align-middle">
                        <tr>
                            <th>No.</th>
                            <th>Jenis</th>
                            <th>Nama</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        foreach ($resultUser as $user) {
                        ?>
                            <tr>
                                <td class="text-center"><?php echo $no++; ?></td>
                                <td>Pengguna</td>
                                <td><?php echo htmlspecialchars($user['username']); ?></td>
                                <td class="text-center">
                                    <a href="?page=pengguna" class="btn btn-sm btn-info">Lihat <i class="fa-solid fa-eye"></i>
                                </td>
                            </tr>
                        <?php }
                        foreach ($resultLaporan as $laporan) {
                        ?>
                            <tr>
                                <td class="text-center"><?php echo $no++; ?></td>
                                <td>Laporan Semester</td>
                                <td><?php echo htmlspecialchars($laporan['nama_perusahaan']); ?></td>
                                <td class="text-center">
                                    <a href="?page=laporan_persemester" class="btn btn-sm btn-info">Lihat <i class="fa-solid fa-eye"></i>
                                </td>
                            </tr>
                        <?php }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
