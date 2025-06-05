<?php
try {
    $db = new Database();
    $conn = $db->getConnection();

    $id_user = $_SESSION['id_user'];
    $role = $_SESSION['role'];

    $params = [];

    if ($role == 'admin' || $role == 'adminbulanan' || $role == 'adminsemester') {
        $sql = "SELECT id_user, username, email, no_hp, role, status 
                FROM users 
                WHERE role != 'superadmin'";
    } else {
        $sql = "SELECT id_user, username, email, no_hp, role, status 
                FROM users 
                WHERE 1"; // placeholder WHERE agar bisa ditambahkan kondisi AND nanti
    }
    
    // Fitur pencarian
    if (!empty($_GET['keyword'])) {
        $keyword = "%" . $_GET['keyword'] . "%";
        $sql .= " AND username LIKE :keyword";
        $params[':keyword'] = $keyword;
    }
    
    // Tambahkan ORDER BY di bagian akhir
    $sql .= " ORDER BY FIELD(status, 'diajukan') DESC, status ASC";
    


    // Persiapkan statement
    $stmt = $conn->prepare($sql);

    // Bind parameter jika ada pencarian
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value, PDO::PARAM_STR);
    }

    // Eksekusi query
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// Proses persetujuan (Verifikasi)
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['terima_id'])) {
    $id = $_POST['terima_id'];

    $updateQuery = "UPDATE users SET status = 'diverifikasi' WHERE id_user = :id_user";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bindParam(':id_user', $id);
    $updateStmt->execute();

    echo "<script>alert('Pengguna telah diverifikasi!'); window.location.href='?page=pengguna';</script>";
}

// Proses penolakan
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['tolak_id'])) {
    $id = $_POST['tolak_id'];

    $updateQuery = "UPDATE users SET status = 'ditolak' WHERE id_user = :id_user";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bindParam(':id_user', $id);
    $updateStmt->execute();

    echo "<script>alert('Pengguna telah ditolak!'); window.location.href='?page=pengguna';</script>";
}
?>


<div class="container mt-4">
    <h3 class="text-center mb-3"><i class="fas fa-bolt" style="color: #ffc107;"></i>Data Pengguna<i class="fas fa-bolt" style="color: #ffc107;"></i></h3>
    <hr>
    <div class="card shadow">
        <div class="card-body">
            <!-- Fitur pencarian -->
            <form method="GET" class="mb-3">
            <input type="hidden" name="page" value="pengguna">
                <div class="input-group">
                    <input type="text" name="keyword" class="form-control" placeholder="Cari berdasarkan username..." value="<?= isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '' ?>">
                    <button type="submit" class="btn btn-success">Cari</button>
                    <a href="?page=pengguna" class="btn btn-secondary">Reset</a>
                </div>
            </form>
            <div class="mt-3 mb-3">
                <a href="?page=pengguna_tambah_admin" class="btn btn-primary">Tambah Data</a>
            </div>
            <div class="table-responsive" style="max-height: 500px; overflow-x: auto; overflow-y: auto;">
                <table class="table table-bordered" style="table-layout: fixed; min-width: 1800px;">
                    <thead class="table-dark text-center align-middle">
                        <tr>
                            <th style="width: 5%;" onclick="sortTable(0)">No. <i class="fa fa-sort"></th>
                            <th style="width: 20%;" onclick="sortTable(1)">Username <i class="fa fa-sort"></th>
                            <th style="width: 25%;" onclick="sortTable(2)">Email <i class="fa fa-sort"></th>
                            <th style="width: 15%;" onclick="sortTable(3)">No. HP <i class="fa fa-sort"></th>
                            <th style="width: 15%;" onclick="sortTable(4)">Role <i class="fa fa-sort"></th>
                            <th style="width: 15%;" onclick="sortTable(5)">Status <i class="fa fa-sort"></th>
                            <th style="width: 20%;">Aksi</th>
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
                                    <td><?php echo htmlspecialchars($row['no_hp']); ?></td>
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
                                            <?php if ($_SESSION['role'] == 'superadmin'): ?>
                                                <a href="?page=pengguna_edit_admin&id_user=<?= htmlspecialchars($row['id_user']); ?>" class="btn btn-warning btn-sm">Edit</a>
                                                
                                                <?php if ($row['id_user'] != 1): ?>
                                                    <a href="?page=pengguna_hapus_admin&id_user=<?= htmlspecialchars($row['id_user']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?');">Hapus</a>
                                                <?php endif; ?>
                                                
                                            <?php endif; ?>
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
</div>

<!-- JAVASCRIPT FILTER -->
<script>
    function sortTable(columnIndex) {
        var table = document.querySelector("table tbody");
        var rows = Array.from(table.querySelectorAll("tr"));
        var isAscending = table.getAttribute("data-sort-order") === "asc";

        // Sort rows
        rows.sort((rowA, rowB) => {
            var cellA = rowA.children[columnIndex].textContent.trim().toLowerCase();
            var cellB = rowB.children[columnIndex].textContent.trim().toLowerCase();

            if (!isNaN(cellA) && !isNaN(cellB)) {
                return isAscending ? cellA - cellB : cellB - cellA;
            }
            return isAscending ? cellA.localeCompare(cellB) : cellB.localeCompare(cellA);
        });

        // Remove existing rows
        table.innerHTML = "";

        // Append sorted rows
        rows.forEach(row => table.appendChild(row));

        // Toggle sorting order
        table.setAttribute("data-sort-order", isAscending ? "desc" : "asc");

        // Update icon
        updateSortIcons(columnIndex, isAscending);
    }

    function updateSortIcons(columnIndex, isAscending) {
        var headers = document.querySelectorAll("thead th i");
        headers.forEach(icon => icon.className = "fa fa-sort"); // Reset semua ikon

        var selectedHeader = document.querySelector(`thead th:nth-child(${columnIndex + 1}) i`);
        if (selectedHeader) {
            selectedHeader.className = isAscending ? "fa fa-sort-up" : "fa fa-sort-down";
        }
    }
</script>