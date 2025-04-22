<?php
try {
    $database = new Database();
    $pdo = $database->getConnection(); // Dapatkan koneksi PDO

    // Query utama
    $query = "SELECT * FROM profil WHERE 1=1";
    $params = [];

    // Filter pencarian berdasarkan nama perusahaan, kabupaten, jenis usaha
    if (!empty($_GET['keyword'])) {
        $keyword = "%" . $_GET['keyword'] . "%";
        $query .= " AND (nama_perusahaan LIKE :keyword OR kabupaten LIKE :keyword OR jenis_usaha LIKE :keyword)";
        $params[':keyword'] = $keyword;
    }

    // Eksekusi Query
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $profiles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<div class="container mt-4">
    <h3 class="text-center mb-3">Data Profil Perusahaan</h3>
    <hr>
    <div class="card shadow">
        <div class="card-body">
            <!-- Fitur pencarian dan filter -->
            <form method="GET" class="mb-2">
                <input type="hidden" name="page" value="profil_admin">
                <div class="input-group">
                    <input type="text" name="keyword" class="form-control" placeholder="Cari .." value="<?= isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '' ?>">
                    <button type="submit" class="btn btn-success">Cari</button>
                    <a href="?page=profil_perusahaan" class="btn btn-secondary">Reset</a>
                </div>
            </form>
            <div class="row mb-3">
                <small class="text-muted">Cari berdasarkan Nama Perusahaan, Kabupaten/Kota, atau Jenis Usaha.</small>
            </div>

            <!-- Tombol Tambah & Export Spreadsheet -->
            <div class="mb-3">
                <?php if ($_SESSION['role'] !== 'admin') { ?> <!-- hanya admin yang tidak bisa mengakses ini -->
                    <a href="?page=tambah_profil" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Data
                    </a>
                <?php } ?>
                <a href="?page=excel_profil" class="btn btn-success">Ekspor ke Spreadsheet</a>
            </div>

            <div class="table-responsive" style="max-height: 500px; overflow-x: auto; overflow-y: auto;">
                <table class="table table-bordered" style="min-width: 1800px; white-space: nowrap;">
                    <thead class="table-dark text-center align-middle">
                        <tr>
                            <th rowspan="2" style="width: 5%;">No.</th>
                            <th rowspan="2" onclick="sortTable(1)">Nama Perusahaan <i class="fa fa-sort"></i></th>
                            <th rowspan="2" onclick="sortTable(2)">Kabupaten/Kota <i class="fa fa-sort"></i></th>
                            <th rowspan="2" onclick="sortTable(3)">Alamat <i class="fa fa-sort"></i></th>
                            <th rowspan="2" onclick="sortTable(4)">Jenis Usaha <i class="fa fa-sort"></i></th>
                            <th rowspan="2" onclick="sortTable(5)">Nomor Telepon Kantor <i class="fa fa-sort"></i></th>
                            <th rowspan="2" onclick="sortTable(6)">No. Fax <i class="fa fa-sort"></i></th>
                            <th rowspan="2" onclick="sortTable(7)">Tenaga Teknik <i class="fa fa-sort"></i></th>
                            <th colspan="3">Kontak Person</th>
                            <th rowspan="2">Aksi</th>
                        </tr>
                        <tr>
                            <th onclick="sortTable(8)">Nama <i class="fa fa-sort"></i></th>
                            <th onclick="sortTable(9)">No. HP <i class="fa fa-sort"></i></th>
                            <th onclick="sortTable(10)">Email <i class="fa fa-sort"></i></th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <?php if (count($profiles) > 0): ?>
                            <?php $no = 1;
                            foreach ($profiles as $row): ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= htmlspecialchars($row['nama_perusahaan']); ?></td>
                                    <td><?= htmlspecialchars($row['kabupaten']); ?></td>
                                    <td><?= htmlspecialchars($row['alamat']); ?></td>
                                    <td><?= htmlspecialchars($row['jenis_usaha']); ?></td>
                                    <td><?= htmlspecialchars($row['no_telp_kantor']); ?></td>
                                    <td><?= htmlspecialchars($row['no_fax']); ?></td>
                                    <td><?= htmlspecialchars($row['tenaga_teknik']); ?></td>
                                    <td><?= htmlspecialchars($row['nama']); ?></td>
                                    <td><?= htmlspecialchars($row['no_hp']); ?></td>
                                    <td><?= htmlspecialchars($row['email']); ?></td>
                                    <td>
                                        <a href="?page=update_profil_admin&id_profil=<?= htmlspecialchars($row['id_profil']); ?>" class="btn btn-warning btn-sm">Edit</a>
                                        <a href="?page=delete_profil_admin&id_profil=<?= htmlspecialchars($row['id_profil']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="12" class="text-center">Data tidak ditemukan</td>
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