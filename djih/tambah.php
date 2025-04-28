<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include_once 'koneksi.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['id_user'])) {
    echo "<script>alert('Silakan login terlebih dahulu!'); window.location.href='login/login.php';</script>";
    exit;
}



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $database = new Database();
    $db = $database->getConnection();

    function sanitizeInput($input) {
        return htmlspecialchars(strip_tags(trim($input)));
    }

    $caption = sanitizeInput($_POST['caption']);
    $jenis_konten = sanitizeInput($_POST['jenis_konten']);
    $konten = null;
    $tanggal = date('Y-m-d H:i:s'); // Tambahkan datetime

    // Handle berbagai jenis konten
    if ($jenis_konten == 'gambar' || $jenis_konten == 'file') {
        $konten = uploadFile('konten');
    } elseif ($jenis_konten == 'link') {
        $konten = sanitizeInput($_POST['konten']);
    }

    // Simpan data ke database
    $insertSQL = "INSERT INTO djih ( caption, jenis_konten, konten, tanggal) VALUES ( :caption, :jenis_konten, :konten, :tanggal)";
    $stmt = $db->prepare($insertSQL);

    $stmt->bindParam(':caption', $caption);
    $stmt->bindParam(':jenis_konten', $jenis_konten);
    $stmt->bindParam(':konten', $konten);
    $stmt->bindParam(':tanggal', $tanggal);
    
    if ($stmt->execute()) {
        $_SESSION['hasil'] = true;
        $_SESSION['pesan'] = "Berhasil Simpan Data";
    } else {
        $_SESSION['hasil'] = false;
        $_SESSION['pesan'] = "Gagal Simpan Data";
    }
    echo "<meta http-equiv='refresh' content='0; url=?page=tampil_konten_djih'>";
}

// Fungsi untuk upload file
function uploadFile($input_name) {
    if (!empty($_FILES[$input_name]['name'])) {
        $target_dir = "uploads/";
        $file_name = basename($_FILES[$input_name]["name"]);
        $file_name = preg_replace("/[^a-zA-Z0-9.\-_]/", "", $file_name);
        $target_file = $target_dir . time() . "_" . $file_name;
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'xls', 'xlsx'];
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
    <h3 class="text-center mb-3">Tambah Konten</h3>
    <hr>
    <div class="card shadow">
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Caption</label>
                    <input type="text" name="caption" class="form-control" required>
                </div>
                <div class="form-group mb-2">
                    <label>Jenis Konten</label>
                    <select class="form-control" name="jenis_konten" required>
                        <option value="">-- Pilih Jenis Konten --</option>
                        <option value="gambar">Gambar</option>
                        <option value="file">File</option>
                        <option value="link">Link</option>
                        <option value="kosong">Kosong</option>
                    </select>
                </div>
                <div class="mb-3" id="konten_input">
                    <label class="form-label">Konten</label>
                    <input type="file" name="konten" class="form-control" id="konten_file" style="display: none;" accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx">
                    <input type="text" name="konten" class="form-control" id="konten_link" style="display: none;">
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="?page=tampil_konten_djih" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>

<script>
document.querySelector('select[name="jenis_konten"]').addEventListener('change', function() {
    let jenis = this.value;
    document.getElementById('konten_file').style.display = (jenis === 'gambar' || jenis === 'file') ? 'block' : 'none';
    document.getElementById('konten_link').style.display = (jenis === 'link') ? 'block' : 'none';
});
</script>
