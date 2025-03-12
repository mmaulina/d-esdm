<?php
include 'koneksi.php'; // Pastikan koneksi tersedia

try {
    $db = new Database();
    $conn = $db->getConnection();
    $sql = "SELECT id_user, username, email, role, status FROM users";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
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
                    <?php
                    if (!empty($users)) {
                        $no = 1;
                        foreach ($users as $row) {
                            echo "<tr>
                                    <td>{$no}</td>
                                    <td>" . htmlspecialchars($row['username']) . "</td>
                                    <td>" . htmlspecialchars($row['email']) . "</td>
                                    <td>" . htmlspecialchars($row['role']) . "</td>
                                    <td>" . htmlspecialchars($row['status']) . "</td>
                                    <td>
                                        <a href='?page=pengguna_edit_admin&id_user=" . htmlspecialchars($row['id_user']) . "' class='btn btn-warning btn-sm'>Edit</a>
                                        <a href='?page=pengguna_hapus_admin&id_user=" . htmlspecialchars($row['id_user']) . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Yakin ingin menghapus?\")'>Hapus</a>
                                    </td>
                                  </tr>";
                            $no++;
                        }
                    } else {
                        echo "<tr><td colspan='6' class='text-center'>Tidak ada data pengguna</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
