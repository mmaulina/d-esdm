<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include_once 'koneksi.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['id_user'])) {
    echo "<script>alert('Silakan login terlebih dahulu!'); window.location.href='login.php';</script>";
    exit;
}

$id_user = $_SESSION['id_user'];
$database = new Database();
$db = $database->getConnection();

// Ambil ID laporan dari parameter URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('ID Laporan tidak ditemukan!'); window.location.href='?page=laporan_perbulan';</script>";
    exit;
}

$id_laporan = $_GET['id'];

// Ambil data laporan berdasarkan ID
$query = "SELECT * FROM laporan_bulanan WHERE id = :id";
$stmt = $db->prepare($query);
$stmt->bindParam(':id_laporan', $id_laporan);
$stmt->bindParam(':id_user', $id_user);
$stmt->execute();
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location.href='?page=laporan_perbulan';</script>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    function sanitizeInput($input) {
        return htmlspecialchars(strip_tags(trim($input)));
    }

    function checkEmpty($input) {
        return empty($input) ? "-" : $input;
    }

    $bulan = sanitizeInput($_POST['bulan']);
    $nama_perusahaan = sanitizeInput($_POST['nama_perusahaan']);
    $volume_bb = checkEmpty(sanitizeInput($_POST['volume_bb']));
    $produksi_sendiri = checkEmpty(sanitizeInput($_POST['produksi_sendiri']));
    $pemb_sumber_lain = checkEmpty(sanitizeInput($_POST['pemb_sumber_lain']));
    $susut_jaringan = checkEmpty(sanitizeInput($_POST['susut_jaringan']));
    $penj_ke_pelanggan = checkEmpty(sanitizeInput($_POST['penj_ke_pelanggan']));
    $penj_ke_pln = checkEmpty(sanitizeInput($_POST['penj_ke_pln']));
    $pemakaian_sendiri = checkEmpty(sanitizeInput($_POST['pemakaian_sendiri']));

    $updateSQL = "UPDATE laporan_bulanan SET bulan = :bulan, nama_perusahaan = :nama_perusahaan, volume_bb = :volume_bb, produksi_sendiri = :produksi_sendiri, 
                  pemb_sumber_lain = :pemb_sumber_lain, susut_jaringan = :susut_jaringan, penj_ke_pelanggan = :penj_ke_pelanggan, penj_ke_pln = :penj_ke_pln, 
                  pemakaian_sendiri = :pemakaian_sendiri WHERE id_laporan = :id_laporan AND id_user = :id_user";
    $stmt = $db->prepare($updateSQL);

    $stmt->bindParam(':bulan', $bulan);
    $stmt->bindParam(':nama_perusahaan', $nama_perusahaan);
    $stmt->bindParam(':volume_bb', $volume_bb);
    $stmt->bindParam(':produksi_sendiri', $produksi_sendiri);
    $stmt->bindParam(':pemb_sumber_lain', $pemb_sumber_lain);
    $stmt->bindParam(':susut_jaringan', $susut_jaringan);
    $stmt->bindParam(':penj_ke_pelanggan', $penj_ke_pelanggan);
    $stmt->bindParam(':penj_ke_pln', $penj_ke_pln);
    $stmt->bindParam(':pemakaian_sendiri', $pemakaian_sendiri);
    $stmt->bindParam(':id_laporan', $id_laporan);
    $stmt->bindParam(':id_user', $id_user);
    
    if ($stmt->execute()) {
        $_SESSION['hasil'] = true;
        $_SESSION['pesan'] = "Berhasil Mengupdate Data";
    } else {
        $_SESSION['hasil'] = false;
        $_SESSION['pesan'] = "Gagal Mengupdate Data";
    }
    echo "<meta http-equiv='refresh' content='0; url=?page=laporan_perbulan'>";
}
?>

<div class="container mt-4">
    <h3 class="text-center mb-3">Edit Pelaporan Bulanan</h3>
    <hr>
    <div class="card shadow">
        <div class="card-body">
            <form method="POST">
                <div class="form-group mb-3">
                    <label>Bulan</label>
                    <select class="form-control" name="bulan" required>
                        <option value="Januari" <?= $data['bulan'] == 'Januari' ? 'selected' : '' ?>>Januari</option>
                        <option value="Februari" <?= $data['bulan'] == 'Februari' ? 'selected' : '' ?>>Februari</option>
                        <option value="Maret" <?= $data['bulan'] == 'Maret' ? 'selected' : '' ?>>Maret</option>
                        <option value="April" <?= $data['bulan'] == 'April' ? 'selected' : '' ?>>April</option>
                        <option value="Mei" <?= $data['bulan'] == 'Mei' ? 'selected' : '' ?>>Mei</option>
                        <option value="Juni" <?= $data['bulan'] == 'Juni' ? 'selected' : '' ?>>Juni</option>
                        <option value="Juli" <?= $data['bulan'] == 'Juli' ? 'selected' : '' ?>>Juli</option>
                        <option value="Agustus" <?= $data['bulan'] == 'Agustus' ? 'selected' : '' ?>>Agustus</option>
                        <option value="September" <?= $data['bulan'] == 'September' ? 'selected' : '' ?>>September</option>
                        <option value="Oktober" <?= $data['bulan'] == 'Oktober' ? 'selected' : '' ?>>Oktober</option>
                        <option value="November" <?= $data['bulan'] == 'November' ? 'selected' : '' ?>>November</option>
                        <option value="Desember" <?= $data['bulan'] == 'Desember' ? 'selected' : '' ?>>Desember</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nama Perusahaan</label>
                    <input type="text" name="nama_perusahaan" class="form-control" value="<?= $data['nama_perusahaan'] ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="?page=laporan_perbulan" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>