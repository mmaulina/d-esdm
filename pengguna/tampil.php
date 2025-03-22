<?php
try {
    $db = new Database();
    $conn = $db->getConnection();
    $sql = "SELECT id_user, username, email, role, status FROM users 
            ORDER BY FIELD(status, 'diajukan') DESC, status ASC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// Proses persetujuan (Terima) dengan metode POST
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['terima_id'])) {
    $id = $_POST['terima_id']; // Pastikan variabel ini sesuai dengan input hidden di form

    $updateQuery = "UPDATE users SET status = 'diverifikasi' WHERE id_user = :id_user";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bindParam(':id_user', $id);
    $updateStmt->execute();

    echo "<script>alert('Pengguna telah diverifikasi!'); window.location.href='?page=pengguna';</script>";
}

// Proses penolakan (Tolak) dengan metode POST
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['tolak_id'])) {
    $id = $_POST['tolak_id']; // Pastikan variabel ini sesuai dengan input hidden di form

    $updateQuery = "UPDATE users SET status = 'ditolak' WHERE id_user = :id_user";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bindParam(':id_user', $id);
    $updateStmt->execute();

    echo "<script>alert('Pengguna telah ditolak!'); window.location.href='?page=pengguna';</script>";
}
?>

<div class="container mt-4">
    <h3 class="text-center mb-3">Data Pengguna</h3>
    <hr>
    <div class="card shadow">
        <div class="card-body">
            <table class="table table-striped table-bordered">
                <div class="mb-3">
                    <a href="?page=pengguna_tambah_admin" class="btn btn-primary">Tambah Data</a>
                </div>
                <thead class="table-dark text-white">
                    <tr>
                        <th>No.</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)): ?>
                        <?php
                        $no = 1;
                        foreach ($users as $row):
                        ?>
                            <tr>
                                <td class="text-center"><?php echo $no++; ?></td>
                                <td><?php echo htmlspecialchars($row['username']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><?php echo htmlspecialchars($row['role']); ?></td>
                                <td><?php echo htmlspecialchars($row['status']); ?></td>
                                <td class="text-center">
                                    <?php if ($row['status'] == 'diajukan'): ?>
                                        <!-- Tombol Terima menggunakan POST -->
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="terima_id" value="<?php echo $row['id_user']; ?>">
                                            <button type="submit" class="btn btn-success btn-sm">Verifikasi</button>
                                        </form>

                                        <!-- Tombol Tolak menggunakan POST -->
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="tolak_id" value="<?php echo $row['id_user']; ?>">
                                            <button type="submit" class="btn btn-danger btn-sm">Tolak</button>
                                        </form>
                                    <?php elseif ($row['status'] == 'diverifikasi' || $row['status'] == 'ditolak'): ?>
                                        <a href="?page=pengguna_edit_admin&id_user=<?php echo htmlspecialchars($row['id_user']); ?>" class="btn btn-warning btn-sm">Edit</a>
                                        <a href="?page=pengguna_hapus_admin&id_user=<?php echo htmlspecialchars($row['id_user']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?');">Hapus</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data pengguna</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>