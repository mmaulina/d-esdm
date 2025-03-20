<?php

// Buat instance database dan ambil koneksi
$database = new Database();
$conn = $database->getConnection();

// Mengambil nilai id_user di URL, lalu disimpan di $id_user
$id_user = isset($_GET['id_user']) ? intval($_GET['id_user']) : 0;

// Periksa jika tombol simpan diklik
if (isset($_POST['btn_simpan'])) {
    $password_lama = $_POST['password_lama'];
    $password_baru = $_POST['password_baru'];

    // Cek jika id_user valid
    if ($id_user > 0) {
        // Query untuk mendapatkan password lama
        $stmt = $conn->prepare("SELECT password FROM users WHERE id_user = :id_user");
        $stmt->bindParam(":id_user", $id_user, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifikasi password lama dan update password baru
        if ($data && password_verify($password_lama, $data['password'])) {
            // Hash password baru menggunakan Bcrypt
            $hashed_password_baru = password_hash($password_baru, PASSWORD_BCRYPT);
            
            $stmt = $conn->prepare("UPDATE users SET password = :password WHERE id_user = :id_user");
            $stmt->bindParam(":password", $hashed_password_baru, PDO::PARAM_STR);
            $stmt->bindParam(":id_user", $id_user, PDO::PARAM_INT);
            $query = $stmt->execute();

            if ($query) {
                echo "<script>alert('Password berhasil diperbarui!');</script>";
                echo "<meta http-equiv='refresh' content='0;url=?page=dashboard'>";
            } else {
                echo "<script>alert('Password gagal diperbarui!');</script>";
                echo "<meta http-equiv='refresh' content='0;url=?page=password_edit'>";
            }
        } else {
            echo "<script>alert('Password lama salah!');</script>";
            echo "<meta http-equiv='refresh' content='0;url=?page=password_edit'>";
        }
    }
}
?>

<div class="container mt-4">
    <h3 class="text-center">Ganti Password</h3>
    <hr>
    <div class="card shadow">
        <!-- Form untuk mengganti password -->
        <div class="card-body">
            <form action="" method="post">
                <div class="mb-3">
                    <label for="inputPasswordLama" class="form-label">Password Lama</label>
                    <input type="password" class="form-control" id="inputPasswordLama" required name="password_lama">
                </div>
                <div class="mb-3">
                    <label for="inputPasswordBaru" class="form-label">Password Baru</label>
                    <input type="password" class="form-control" id="inputPasswordBaru" required name="password_baru">
                </div>
                <button type="submit" class="btn btn-success" name="btn_simpan">Simpan</button>
                <button type="reset" class="btn btn-danger">Batal</button>
            </form>
        </div>
    </div>
</div>