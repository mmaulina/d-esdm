<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include_once 'koneksi.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['id_user'])) {
    echo "<script>alert('Silakan login terlebih dahulu!'); window.location.href='login.php';</script>";
    exit;
}

$id_user = $_SESSION['id_user'];

// Ambil ID laporan dari URL
$id_laporan = isset($_GET['id']) ? $_GET['id'] : null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $database = new Database();
    $db = $database->getConnection();

    function sanitizeInput($input) {
        return htmlspecialchars(strip_tags(trim($input)));
    }

    $nama_perusahaan = sanitizeInput($_POST['nama_perusahaan']);
    $parameter = sanitizeInput($_POST['parameter']);
    $buku_mutu = sanitizeInput($_POST['buku_mutu']);
    $hasil = sanitizeInput($_POST['hasil']);

    // Handle file upload
    $file_laporan = uploadFile('file_laporan');
    $file_lhu = uploadFile('file_lhu');

    // Update data ke database
    $updateSQL = "UPDATE laporan_semester SET 
                    nama_perusahaan = :nama_perusahaan, 
                    parameter = :parameter, 
                    buku_mutu = :buku_mutu, 
                    hasil = :hasil, 
                    file_laporan = COALESCE(:file_laporan, file_laporan), 
                    file_lhu = COALESCE(:file_lhu, file_lhu) 
                  WHERE id = :id AND id_user = :id_user";
    $stmt = $db->prepare($updateSQL);

    $stmt->bindParam(':id', $id_laporan);
    $stmt->bindParam(':id_user', $id_user);
    $stmt->bindParam(':nama_perusahaan', $nama_perusahaan);
    $stmt->bindParam(':parameter', $parameter);
    $stmt->bindParam(':buku_mutu', $buku_mutu);
    $stmt->bindParam(':hasil', $hasil);
    $stmt->bindParam(':file_laporan', $file_laporan);
    $stmt->bindParam(':file_lhu', $file_lhu);
    
    if ($stmt->execute()) {
        $_SESSION['hasil'] = true;
        $_SESSION['pesan'] = "Berhasil Update Data";
    } else {
        $_SESSION['hasil'] = false;
        $_SESSION['pesan'] = "Gagal Update Data";
    }
    echo "<meta http-equiv='refresh' content='0; url=?page=laporan_persemester'>";
}

// Ambil data laporan untuk ditampilkan di form
$database = new Database();
$db = $database->getConnection();
$query = "SELECT * FROM laporan_semester WHERE id = :id AND id_user = :id_user";
$stmt = $db->prepare($query);
$stmt->bindParam(':id', $id_laporan);
$stmt->bindParam(':id_user', $id_user);
$stmt->execute();
$laporan = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$laporan) {
    echo "<script>alert('Data tidak ditemukan!'); window.location.href='?page=laporan_persemester';</script>";
    exit;
}

function uploadFile($input_name) {
    if (!empty($_FILES[$input_name]['name'])) {
        $target_dir = "uploads/";
        $file_name = basename($_FILES[$input_name]["name"]);
        $file_name = preg_replace("/[^a-zA-Z0-9.\-_]/", "", $file_name); // Hapus karakter berbahaya
        $target_file = $target_dir . time() . "_" . $file_name; // Rename file untuk menghindari duplikasi
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Format file yang diizinkan (PDF, Word, Excel)
        $allowed_types = ['pdf', 'doc', 'docx', 'xls', 'xlsx'];
        if (!in_array($file_type, $allowed_types)) {
            $_SESSION['pesan'] = "Format file tidak diizinkan!";
            return null;
        }

        if (move_uploaded_file($_FILES[$input_name]["tmp_name"], $target_file)) {
            return $target_file;
        }
    }
    return null;
}
?>

<div class="container mt-4">
    <h3 class="text-center mb-3">Update Laporan Semester</h3>
    <hr>
    <div class="card shadow">
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Nama Perusahaan</label>
                    <input type="text" name="nama_perusahaan" class="form-control" value="<?= htmlspecialchars($laporan['nama_perusahaan']) ?>" required>
                </div>
                <div class="form-group mb-2">
                    <label>parameter</label>
                    <select class="form-control" name="parameter" required>
                        <option value="<?php echo $laporan['parameter']; ?>" selected><?php echo $laporan['parameter']; ?></option>
                        <option value="SO2">SO2</option>
                        <option value="HO2">HO2</option>
                        <option value="TSP/DEBU">TSP/DEBU</option>
                        <option value="CO">CO</option>
                        <option value="kebisingan">Kebisingan</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Buku Mutu</label>
                    <input type="text" name="buku_mutu" class="form-control" value="<?= htmlspecialchars($laporan['buku_mutu']) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Hasil</label>
                    <input type="text" name="hasil" class="form-control" value="<?= htmlspecialchars($laporan['hasil']) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Upload Laporan (PDF, DOC, DOCX, XLS, XLSX)</label>
                    <input type="file" name="file_laporan" class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx">
                    <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah file.</small>
                    <?php if ($laporan['file_laporan']): ?>
                        <p>File yang sudah di-upload: <a href="<?= htmlspecialchars($laporan['file_laporan']) ?>" target="_blank">Download</a></p>
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label class="form-label">Upload LHU (PDF, DOC, DOCX, XLS, XLSX)</label>
                    <input type="file" name="file_lhu" class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx">
                    <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah file.</small>
                    <?php if ($laporan['file_lhu']): ?>
                        <p>File yang sudah di-upload: <a href="<?= htmlspecialchars($laporan['file_lhu']) ?>" target="_blank">Download</a></p>
                    <?php endif; ?>
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="?page=laporan_persemester" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>