<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Pastikan hanya superadmin yang dapat mengakses halaman ini
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'superadmin') {
    echo "<script>alert('Anda tidak memiliki akses!'); window.location.href=?page=kontak';</script>";
    exit();
}

require_once 'koneksi.php'; // pastikan file koneksi disertakan
$database = new Database();
$pdo = $database->getConnection();

$id_user = $_SESSION['id_user'];

// Ambil data kontak superadmin (id_user = 1)
$sql = "SELECT email, no_hp FROM users WHERE id_user = 1";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$kontak = $stmt->fetch(PDO::FETCH_ASSOC);

// Proses update jika form dikirim
$alert = null;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $no_hp = trim($_POST['no_hp']);

    if (!empty($email) && !empty($no_hp)) {
        $update_sql = "UPDATE users SET email = :email, no_hp = :no_hp WHERE id_user = 1";
        $update_stmt = $pdo->prepare($update_sql);
        $update_stmt->bindParam(':email', $email);
        $update_stmt->bindParam(':no_hp', $no_hp);

        if ($update_stmt->execute()) {
            $alert = [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Kontak admin telah diperbarui.',
                'redirect' => '?page=kontak'
            ];
        } else {
            $alert = [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Terjadi kesalahan saat memperbarui kontak.'
            ];
        }
    } else {
        $alert = [
            'icon' => 'warning',
            'title' => 'Peringatan!',
            'text' => 'Mohon isi semua bidang!'
        ];
    }
}
?>


<div class="container mt-5">
    <div class="card shadow" style="overflow-x: auto; max-height: calc(100vh - 150px); overflow-y: auto;">
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
                <a href="?page=kontak" class="btn btn-secondary">
                    <i class="fa-solid fa-arrow-left"></i> Kembali
                </a>
            </form>
        </div>
    </div>
</div>

<?php if ($alert): ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: '<?= $alert['icon'] ?>',
            title: '<?= $alert['title'] ?>',
            text: '<?= $alert['text'] ?>'
        }).then(() => {
            <?php if (isset($alert['redirect'])): ?>
                window.location.href = '<?= $alert['redirect'] ?>';
            <?php endif; ?>
        });
    </script>
<?php endif; ?>