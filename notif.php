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

// Ambil laporan bulanan yang diajukan
$queryLapBulanan = "SELECT * FROM laporan_bulanan WHERE status = 'diajukan'";
$stmtLapBulanan = $conn->prepare($queryLapBulanan);
$stmtLapBulanan->execute();
$resultLapBulanan = $stmtLapBulanan->fetchAll(PDO::FETCH_ASSOC);

// Ambil laporan semester yang diajukan
$queryLapSemester = "SELECT * FROM laporan_semester WHERE status = 'diajukan'";
$stmtLapSemester = $conn->prepare($queryLapSemester);
$stmtLapSemester->execute();
$resultLapSemester = $stmtLapSemester->fetchAll(PDO::FETCH_ASSOC);

// Ambil profil perusahaan yang diajukan
$queryperusahaan = "SELECT * FROM profil WHERE status = 'diajukan'";
$stmt_perusahaan = $conn->prepare($queryperusahaan);
$stmt_perusahaan->execute();
$resultperusahaan = $stmt_perusahaan->fetchAll(PDO::FETCH_ASSOC);
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
                        foreach ($resultLapBulanan as $lapBulanan) {
                        ?>
                            <tr>
                                <td class="text-center"><?php echo $no++; ?></td>
                                <td>Pelaporan Bulanan</td>
                                <td><?php echo htmlspecialchars($lapBulanan['nama_perusahaan']); ?></td>
                                <td class="text-center">
                                    <a href="?page=laporan_perbulan" class="btn btn-sm btn-info">Lihat <i class="fa-solid fa-eye"></i>
                                </td>
                            </tr>
                        <?php }
                        foreach ($resultLapSemester as $lapSemester) {
                        ?>
                            <tr>
                                <td class="text-center"><?php echo $no++; ?></td>
                                <td>Pelaporan Semester</td>
                                <td><?php echo htmlspecialchars($lapSemester['nama_perusahaan']); ?></td>
                                <td class="text-center">
                                    <a href="?page=laporan_persemester" class="btn btn-sm btn-info">Lihat <i class="fa-solid fa-eye"></i>
                                </td>
                            </tr>
                        <?php }
                        foreach ($resultperusahaan as $perusahaan) {
                        ?>
                            <tr>
                                <td class="text-center"><?php echo $no++; ?></td>
                                <td>Perusahaan di ajukan</td>
                                <td><?php echo htmlspecialchars($perusahaan['nama_perusahaan']); ?></td>
                                <td class="text-center">
                                    <a href="?page=profil_admin" class="btn btn-sm btn-info">Lihat <i class="fa-solid fa-eye"></i>
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