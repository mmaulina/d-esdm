<?php
try {
    $database = new Database();
    $pdo = $database->getConnection(); // Dapatkan koneksi PDO
    
    // Query utama
    $query = "SELECT * FROM profil WHERE 1=1"; 
    $params = [];

    // Fitur pencarian nama perusahaan
    if (!empty($_GET['keyword'])) {
        $keyword = "%" . $_GET['keyword'] . "%";
        $query .= " AND nama_perusahaan LIKE :keyword";
        $params[':keyword'] = $keyword;
    }

    // Filter berdasarkan jenis usaha
    if (!empty($_GET['jenis_usaha'])) {
        $query .= " AND jenis_usaha = :jenis_usaha";
        $params[':jenis_usaha'] = $_GET['jenis_usaha'];
    }

    // Filter berdasarkan kabupaten/kota
    if (!empty($_GET['kabupaten'])) {
        $query .= " AND kabupaten = :kabupaten";
        $params[':kabupaten'] = $_GET['kabupaten'];
    }

    $stmt = $pdo->prepare($query);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value, PDO::PARAM_STR);
    }
    $stmt->execute();
    $profiles = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Ambil daftar jenis usaha dan kabupaten/kota untuk dropdown filter
    $jenisUsahaStmt = $pdo->query("SELECT DISTINCT jenis_usaha FROM profil ORDER BY jenis_usaha");
    $jenisUsahaList = $jenisUsahaStmt->fetchAll(PDO::FETCH_COLUMN);

    $kabupatenStmt = $pdo->query("SELECT DISTINCT kabupaten FROM profil ORDER BY kabupaten");
    $kabupatenList = $kabupatenStmt->fetchAll(PDO::FETCH_COLUMN);

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
            <form method="GET" class="mb-3">
                <input type="hidden" name="page" value="profil_admin">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" name="keyword" class="form-control" placeholder="Cari berdasarkan nama perusahaan..." value="<?= isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '' ?>">
                    </div>
                    <div class="col-md-3">
                        <select name="jenis_usaha" class="form-select">
                            <option value="">-- Pilih Jenis Usaha --</option>
                            <?php foreach ($jenisUsahaList as $jenis): ?>
                                <option value="<?= htmlspecialchars($jenis) ?>" <?= (isset($_GET['jenis_usaha']) && $_GET['jenis_usaha'] == $jenis) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($jenis) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="kabupaten" class="form-select">
                            <option value="">-- Pilih Kabupaten/Kota --</option>
                            <?php foreach ($kabupatenList as $kab): ?>
                                <option value="<?= htmlspecialchars($kab) ?>" <?= (isset($_GET['kabupaten']) && $_GET['kabupaten'] == $kab) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($kab) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-success">Filter</button>
                        <a href="?page=profil_admin" class="btn btn-secondary">Reset</a>
                    </div>
                </div>
            </form>

            <!-- Tombol export spreadsheet -->
            <a href="?page=excel_profil" class="btn btn-success mb-3">Ekspor ke Spreadsheet</a>

            <div class="table-responsive" style="max-height: 500px; overflow-x: auto; overflow-y: auto;">
                <table class="table table-bordered" style="min-width: 1200px; white-space: nowrap;">
                    <thead class="table-dark text-center align-middle">
                        <tr>
                            <th rowspan="2">No.</th>
                            <th rowspan="2">Nama Perusahaan</th>
                            <th rowspan="2">Kabupaten/Kota</th>
                            <th rowspan="2">Alamat</th>
                            <th rowspan="2">Jenis Usaha</th>
                            <th rowspan="2">Nomor Telepon Kantor</th>
                            <th rowspan="2">No. Fax</th>
                            <th rowspan="2">Tenaga Teknik</th>
                            <th colspan="3">Kontak Person</th>
                            <th rowspan="2">Aksi</th>
                        </tr>
                        <tr>
                            <th>Nama</th>
                            <th>No. HP</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
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
