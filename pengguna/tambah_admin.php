<?php
include 'koneksi.php'; // Pastikan file koneksi ke database sudah ada

$error_message = ""; // Variabel untuk menyimpan pesan kesalahan

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $no_hp = htmlspecialchars($_POST['no_hp']);
    $password = password_hash(htmlspecialchars($_POST['password']), PASSWORD_BCRYPT); // Hash password menggunakan Bcrypt
    $role = htmlspecialchars($_POST['role']);
    $status = htmlspecialchars($_POST['status']);

    // Validasi: Cek apakah username dan email sama
    if ($username === $email) {
        $error_message = "Username atau email sudah digunakan.";
    } else {
        try {
            $db = new Database();
            $conn = $db->getConnection();
            $sql = "INSERT INTO users (username, email, password, no_hp, role, status) VALUES (:username, :email, :password, :no_hp, :role, :status)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':no_hp', $no_hp);
            $stmt->bindParam(':role', $role);
            $stmt->bindParam(':status', $status);

            if ($stmt->execute()) {
                echo "<script>alert('Data berhasil ditambahkan!'); window.location='?page=pengguna';</script>";
            } else {
                echo "<script>alert('Gagal menambahkan data!');</script>";
            }
        } catch (PDOException $e) {
            echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
        }
    }
}
?>




<div class="container mt-4">
    <h3 class="text-center">Tambah Data Pengguna</h3>
    <hr>
    <div class="card shadow" style="overflow-x: auto; max-height: calc(100vh - 150px); overflow-y: auto;">
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" required>
                    <?php if ($error_message): ?>
                        <div class="text-danger"><?php echo $error_message; ?></div>
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                    <?php if ($error_message): ?>
                        <div class="text-danger"><?php echo $error_message; ?></div>
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">No. HP</label>
                    <input name="no_hp" type="number" pattern="[0-9]+" class="form-control" required>
                    <?php if (strpos($error_message, 'No. HP') !== false): ?>
                        <div class="text-danger"><?php echo $error_message; ?></div>
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-control" required>
                            <?php if ($_SESSION['role'] == 'superadmin') { ?>
                                <option value="superadmin">SuperAdmin</option>
                                <option value="kementerian">Kementerian</option>
                            <?php } ?>
                            <option value="admin">Admin</option>
                            <option value="adminbulanan">Admin Bulanan</option>
                            <option value="adminsemester">Admin Semester</option>
                            <option value="umum">Umum</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control" required>
                        <option value="diajukan">Diajukan</option>
                        <option value="diverifikasi">Diverifikasi</option>
                        <option value="ditolak">Ditolak</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-success">Simpan</button>
                <a href="?page=pengguna" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>