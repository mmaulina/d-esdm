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
                <th>Jenis Usaha</th>
                <td><?php echo htmlspecialchars($profil['jenis_usaha']); ?></td>
            </tr>
            <tr>
                <th>Nomor Telepon Kantor</th>
                <td><?php echo htmlspecialchars($profil['no_telp_kantor']); ?></td>
            </tr>
            <tr>
                <th>Tenaga Teknik</th>
                <td><?php echo htmlspecialchars($profil['tenaga_teknik']); ?></td>
            </tr>
            <tr>
                <th>Nama</th>
                <td><?php echo htmlspecialchars($profil['nama']); ?></td>
            </tr>
            <tr>
                <th>Nomor HP</th>
                <td><?php echo htmlspecialchars($profil['no_hp']); ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?php echo htmlspecialchars($profil['email']); ?></td>
            </tr>
        </table>
        <a href="?page=update_profil&id_user=<?php echo $_SESSION['id_user']; ?>" class="btn btn-warning">Update Profil</a>
        <a href="?page=delete_profil&id_user=<?= $_SESSION['id_user']; ?>" 
            onclick="return confirmHapus(event)" 
            class="btn btn-danger">
            Hapus Profil
            </a>
    <?php else: ?>
        <p>Profil perusahaan belum diisi.</p>
        <a href="?page=tambah_profil&id_user=<?php echo $_SESSION['id_user']; ?>" class="btn btn-primary">Isi Profil</a>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmHapus(event) {
    event.preventDefault(); // Mencegah link langsung terbuka
    
    Swal.fire({
        title: "Apakah Anda yakin?",
        text: "Profil Anda akan dihapus secara permanen!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Ya, hapus!",
        cancelButtonText: "Batal"
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = event.target.href; // Melanjutkan ke halaman hapus_profil.php jika dikonfirmasi
        }
    });

    return false; // Menghentikan eksekusi default onclick
}
</script>