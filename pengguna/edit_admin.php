<?php
include 'koneksi.php'; // Pastikan koneksi sudah tersedia

// Pastikan ID ada di URL
if (!isset($_GET['id_user'])) {
    echo "<script>alert('ID pengguna tidak ditemukan!'); window.location='?page=pengguna';</script>";
    exit();
}

$id_user = $_GET['id_user'];

$db = new Database();
$conn = $db->getConnection();
// Ambil data pengguna berdasarkan ID
$sql = "SELECT username, email, role, status FROM users WHERE id_user = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id_user]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    if ($_SESSION['role'] == 'admin') { //hanya admin yang bisa mengakses ini
        echo "<script>alert('Pengguna tidak ditemukan!'); window.location='?page=pengguna';</script>";
        exit();
    }
    if ($_SESSION['role'] == 'umum') { //hanya umum yang bisa mengakses ini
        echo "<script>alert('Pengguna tidak ditemukan!'); window.location='?page=dashboard';</script>";
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $status = htmlspecialchars($_POST['status']);
    $role = htmlspecialchars($_POST['role']);

    // Periksa apakah password diubah
    // if (!empty($_POST['password'])) {
    //     $password = htmlspecialchars($_POST['password'],);
    //     $sql = "UPDATE users SET username = ?, email = ?, password = ?, role = ?, status = ? WHERE id_user = ?";
    //     $stmt = $conn->prepare($sql);
    //     $execute = $stmt->execute([$username, $email, $password, $role, $status, $id_user]);
    // } else {
    $sql = "UPDATE users SET username = ?, email = ?, role = ?, status = ? WHERE id_user = ?";
    $stmt = $conn->prepare($sql);
    $execute = $stmt->execute([$username, $email, $role, $status, $id_user]);
    // }

    if ($execute) {
        if ($_SESSION['role'] == 'admin') { //hanya admin yang bisa mengakses ini
            echo "<script>alert('Data berhasil diperbarui!'); window.location='?page=pengguna';</script>";
        }
        if ($_SESSION['role'] == 'umum') { //hanya umum yang bisa mengakses ini
            echo "<script>alert('Data berhasil diperbarui!'); window.location='?page=dashboard';</script>";
        }
    } else {
        echo "<script>alert('Gagal memperbarui data!');</script>";
    }
}
?>

<div class="container mt-4">
    <h3 class="text-center">Edit Data Pengguna</h3>
    <hr>
    <div class="card shadow">
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($data['username']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($data['email']); ?>" required>
                </div>
                <!-- <div class="mb-3">
                    <label class="form-label">Password (Kosongkan jika tidak diubah)</label>
                    <input type="password" name="password" class="form-control">
                </div> -->
                <?php if ($_SESSION['role'] == 'admin') { ?> <!-- hanya admin yang bisa mengakses ini -->
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-control" required>
                            <option value="admin" <?= $data['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                            <option value="umum" <?= $data['role'] == 'umum' ? 'selected' : ''; ?>>Umum</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control" required>
                            <option value="diajukan" <?= $data['status'] == 'diajukan' ? 'selected' : ''; ?>>Diajukan</option>
                            <option value="diverifikasi" <?= $data['status'] == 'diverifikasi' ? 'selected' : ''; ?>>Diverifikasi</option>
                            <option value="ditolak" <?= $data['status'] == 'ditolak' ? 'selected' : ''; ?>>Ditolak</option>
                        </select>
                    </div>
                <?php } ?>
                <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                <?php if ($_SESSION['role'] == 'admin') { ?> <!-- hanya admin yang bisa mengakses button ini -->
                    <a href="?page=pengguna" class="btn btn-secondary">Kembali</a>
                <?php } ?>
                <?php if ($_SESSION['role'] == 'umum') { ?> <!-- hanya umum yang bisa mengakses button ini -->
                    <a href="?page=dashboard" class="btn btn-secondary">Kembali</a>
                <?php } ?>
            </form>
        </div>
    </div>
</div>