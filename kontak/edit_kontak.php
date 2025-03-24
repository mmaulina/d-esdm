<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Pastikan hanya admin yang dapat mengakses halaman ini
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Anda tidak memiliki akses!'); window.location.href='tampil.php';</script>";
    exit();
}

$id_user = $_SESSION['id_user'];

$database = new Database();
$pdo = $database->getConnection();

// Ambil data kontak admin (id_user = 1)
$sql = "SELECT email, no_hp FROM users WHERE id_user = 1";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$kontak = $stmt->fetch(PDO::FETCH_ASSOC);

// Proses update jika form dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $no_hp = trim($_POST['no_hp']);

    if (!empty($email) && !empty($no_hp)) {
        $update_sql = "UPDATE users SET email = :email, no_hp = :no_hp WHERE id_user = 1";
        $update_stmt = $pdo->prepare($update_sql);
        $update_stmt->bindParam(':email', $email);
        $update_stmt->bindParam(':no_hp', $no_hp);

        if ($update_stmt->execute()) {
            echo "<script>
                    Swal.fire({
                        title: 'Berhasil!',
                        text: 'Kontak admin telah diperbarui.',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = 'tampil.php';
                    });
                  </script>";
        } else {
            echo "<script>Swal.fire('Gagal!', 'Terjadi kesalahan saat memperbarui kontak.', 'error');</script>";
        }
    } else {
        echo "<script>Swal.fire('Peringatan!', 'Mohon isi semua bidang!', 'warning');</script>";
    }
}
?>

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-body">
            <h2>Update Kontak Admin</h2>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($kontak['email'] ?? '') ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nomor HP / WhatsApp</label>
                    <input type="text" name="no_hp" class="form-control" value="<?= htmlspecialchars($kontak['no_hp'] ?? '') ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-save"></i> Simpan Perubahan
                </button>
                <a href="tampil.php" class="btn btn-secondary">
                    <i class="fa-solid fa-arrow-left"></i> Kembali
                </a>
            </form>
        </div>
    </div>
</div>
