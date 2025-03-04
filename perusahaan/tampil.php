<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include "koneksi.php"; 

// Pastikan pengguna sudah login
if (!isset($_SESSION['id_user'])) {
    echo "<script>alert('Anda harus login terlebih dahulu!'); window.location.href='login/login.php';</script>";
    exit();
}

$id_user = $_SESSION['id_user'];

// Ambil data profil perusahaan dari database
$sql = "SELECT * FROM profil WHERE id_user = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();
$profil = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Info Profil Perusahaan</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Informasi Profil Perusahaan</h2>
        
        <?php if ($profil): ?>
            <table class="table table-bordered">
                <tr>
                    <th>Nama Perusahaan</th>
                    <td><?php echo htmlspecialchars($profil['nama_perusahaan']); ?></td>
                </tr>
                <tr>
                    <th>Kabupaten</th>
                    <td><?php echo htmlspecialchars($profil['kabupaten']); ?></td>
                </tr>
                <tr>
                    <th>Alamat</th>
                    <td><?php echo htmlspecialchars($profil['alamat']); ?></td>
                </tr>
                <tr>
                    <th>Tenaga Teknis</th>
                    <td><?php echo htmlspecialchars($profil['tenaga_teknis']); ?></td>
                </tr>
                <tr>
                    <th>Kontak Person</th>
                    <td><?php echo htmlspecialchars($profil['kontak_person']); ?></td>
                </tr>
                <tr>
                    <th>Nama Direktur</th>
                    <td><?php echo htmlspecialchars($profil['nama_direktur']); ?></td>
                </tr>
                <tr>
                    <th>Kontak Direktur</th>
                    <td><?php echo htmlspecialchars($profil['kontak_direktur']); ?></td>
                </tr>
            </table>
            <a href="update_profil.php" class="btn btn-warning">Update Profil</a>
        <?php else: ?>
            <p>Profil perusahaan belum diisi.</p>
            <a href="?page=tambah_profil" class="btn btn-primary">Isi Profil</a>
        <?php endif; ?>
    </div>
</body>
</html>
