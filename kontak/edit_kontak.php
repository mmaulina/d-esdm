<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Pastikan hanya admin yang dapat mengakses halaman ini
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Anda tidak memiliki akses!'); window.location.href='?page=kontak';</script>";
    exit();
}

$alert = null;
$redirectAfterAlert = false;

$database = new Database();
$pdo = $database->getConnection();

// Ambil semua admin dari database
$sql = "SELECT id_user, email, no_hp FROM users WHERE role = 'admin'";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$admins = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ambil data kontak admin yang dipilih
$selected_id = isset($_POST['contact_id']) ? $_POST['contact_id'] : (count($admins) > 0 ? $admins[0]['id_user'] : null);

// Cegah hilangnya pilihan setelah update
if (isset($_POST['update'])) {
    $selected_id = $_POST['selected_id'];
}

$sql_contact = "SELECT email, no_hp FROM users WHERE id_user = :id_user";
$stmt_contact = $pdo->prepare($sql_contact);
$stmt_contact->bindParam(':id_user', $selected_id);
$stmt_contact->execute();
$kontak = $stmt_contact->fetch(PDO::FETCH_ASSOC);

// Proses update jika form dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $email = trim($_POST['email']);
    $no_hp = trim($_POST['no_hp']);

    if (!empty($email) && !empty($no_hp)) {
        $update_sql = "UPDATE users SET email = :email, no_hp = :no_hp WHERE id_user = :id_user";
        $update_stmt = $pdo->prepare($update_sql);
        $update_stmt->bindParam(':email', $email);
        $update_stmt->bindParam(':no_hp', $no_hp);
        $update_stmt->bindParam(':id_user', $selected_id);

        if ($update_stmt->execute()) {
            $alert = [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Kontak admin telah diperbarui.'
            ];
            $redirectAfterAlert = true;
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
    <div class="card shadow">
        <div class="card-body">
            <h2>Update Kontak Admin</h2>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Pilih Admin:</label>
                    <select name="contact_id" id="contact_id" class="form-select" onchange="this.form.submit()">
                        <?php foreach ($admins as $admin) { ?>
                            <option value="<?= $admin['id_user']; ?>" <?= ($admin['id_user'] == $selected_id) ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($admin['email']); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <input type="hidden" name="selected_id" value="<?= $selected_id ?>">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($kontak['email'] ?? '') ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nomor HP / WhatsApp</label>
                    <input type="text" name="no_hp" class="form-control" value="<?= htmlspecialchars($kontak['no_hp'] ?? '') ?>" required>
                </div>
                <button type="submit" name="update" class="btn btn-primary">
                    <i class="fa-solid fa-save"></i> Simpan Perubahan
                </button>
                <a href="?page=kontak" class="btn btn-secondary">
                    <i class="fa-solid fa-arrow-left"></i> Kembali
                </a>
            </form>
        </div>
    </div>
</div>

<!-- Tambahkan SweetAlert dan Font Awesome -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<?php if ($alert): ?>
<script>
    Swal.fire({
        icon: '<?= $alert['icon'] ?>',
        title: '<?= $alert['title'] ?>',
        text: '<?= $alert['text'] ?>'
    }).then(() => {
        <?php if ($redirectAfterAlert): ?>
        window.location.href = '?page=kontak';
        <?php endif; ?>
    });
</script>
<?php endif; ?>
