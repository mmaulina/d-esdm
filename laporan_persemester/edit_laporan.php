<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include_once 'koneksi.php';

if (!isset($_SESSION['id_user'])) {
    echo "<script>alert('Silakan login terlebih dahulu!'); window.location.href='login.php';</script>";
    exit;
}


$id_laporan = isset($_GET['id']) ? $_GET['id'] : null;
$role = isset($_SESSION['role']) ? $_SESSION['role'] : null;


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $database = new Database();
    $db = $database->getConnection();

    function sanitizeInput($input)
    {
        return strip_tags(trim($input));
    }


    $nama_perusahaan = sanitizeInput($_POST['nama_perusahaan']);
    $no_hp_pimpinan = sanitizeInput($_POST['no_hp_pimpinan']);
    $tenaga_teknik = sanitizeInput($_POST['tenaga_teknik']);
    $no_hp_teknik = sanitizeInput($_POST['no_hp_teknik']);
    $nama = sanitizeInput($_POST['nama']);
    $no_hp = sanitizeInput($_POST['no_hp']);
    $no_telp_kantor = sanitizeInput($_POST['no_telp_kantor']);
    $baku_mutu = sanitizeInput($_POST['baku_mutu_so2']);
    $hasil = sanitizeInput($_POST['hasil_so2']);
    $baku_mutu2 = sanitizeInput($_POST['baku_mutu_ho2']);
    $hasil2 = sanitizeInput($_POST['hasil_ho2']);
    $baku_mutu3 = sanitizeInput($_POST['baku_mutu_tsp']);
    $hasil3 = sanitizeInput($_POST['hasil_tsp']);
    $baku_mutu4 = sanitizeInput($_POST['baku_mutu_co']);
    $hasil4 = sanitizeInput($_POST['hasil_co']);
    $baku_mutu5 = sanitizeInput($_POST['baku_mutu_kebisingan']);
    $hasil5 = sanitizeInput($_POST['hasil_kebisingan']);

    $file_laporan = uploadFile('file_laporan');
    $file_lhu = uploadFile('file_lhu');
    $tahun = sanitizeInput($_POST['tahun']);
    $semester_final = sanitizeInput($_POST['semester_final']);

    $updateSQL = "UPDATE laporan_semester SET 
    nama_perusahaan = :nama_perusahaan,
    no_hp_pimpinan=:no_hp_pimpinan, 
    tenaga_teknik=:tenaga_teknik,
    no_hp_teknik=:no_hp_teknik,
    nama=:nama, 
    no_hp=:no_hp, 
    no_telp_kantor=:no_telp_kantor, 
    baku_mutu_so2 = :baku_mutu_so2, 
    hasil_so2 = :hasil_so2,
    baku_mutu_ho2 = :baku_mutu_ho2, 
    hasil_ho2 = :hasil_ho2,
    baku_mutu_tsp = :baku_mutu_tsp, 
    hasil_tsp = :hasil_tsp,
    baku_mutu_co = :baku_mutu_co, 
    hasil_co = :hasil_co,
    baku_mutu_kebisingan = :baku_mutu_kebisingan, 
    hasil_kebisingan = :hasil_kebisingan,
    tahun = :tahun,
    semester = :semester_final,
    status = 'Diajukan',
    keterangan = '-'";

    // Hanya tambahkan file_laporan ke query jika ada file yang diunggah
    if ($file_laporan !== null) {
        $updateSQL .= ", file_laporan = :file_laporan";
    }

    // Hanya tambahkan file_lhu ke query jika ada file yang diunggah
    if ($file_lhu !== null) {
        $updateSQL .= ", file_lhu = :file_lhu";
    }

    $updateSQL .= " WHERE id = :id ";

    $stmt = $db->prepare($updateSQL);

    // Bind parameter yang wajib
    $stmt->bindParam(':id', $id_laporan);
    $stmt->bindParam(':nama_perusahaan', $nama_perusahaan);
    $stmt->bindParam(':no_hp_pimpinan', $no_hp_pimpinan);
    $stmt->bindParam(':tenaga_teknik', $tenaga_teknik);
    $stmt->bindParam(':no_hp_teknik', $no_hp_teknik);
    $stmt->bindParam(':nama', $nama);
    $stmt->bindParam(':no_hp', $no_hp);
    $stmt->bindParam(':no_telp_kantor', $no_telp_kantor);
    $stmt->bindParam(':baku_mutu_so2', $baku_mutu_so2);
    $stmt->bindParam(':hasil_so2', $hasil_so2);
    $stmt->bindParam(':baku_mutu_ho2', $baku_mutu_ho2);
    $stmt->bindParam(':hasil_ho2', $hasil_ho2);
    $stmt->bindParam(':baku_mutu_tsp', $baku_mutu_tsp);
    $stmt->bindParam(':hasil_tsp', $hasil_tsp);
    $stmt->bindParam(':baku_mutu_co', $baku_mutu_co);
    $stmt->bindParam(':hasil_co', $hasil_co);
    $stmt->bindParam(':baku_mutu_kebisingan', $baku_mutu_kebisingan);
    $stmt->bindParam(':hasil_kebisingan', $hasil_kebisingan);

    // Bind parameter hanya jika file diunggah
    if ($file_laporan !== null) {
        $stmt->bindParam(':file_laporan', $file_laporan);
    }

    if ($file_lhu !== null) {
        $stmt->bindParam(':file_lhu', $file_lhu);
    }
    $stmt->bindParam(':tahun', $tahun);
    $stmt->bindParam(':semester_final', $semester_final);

    if ($stmt->execute()) {
        $_SESSION['hasil'] = true;
        $_SESSION['pesan'] = "Berhasil Update Data";
    } else {
        $_SESSION['hasil'] = false;
        $_SESSION['pesan'] = "Gagal Update Data";
    }

    echo "<meta http-equiv='refresh' content='0; url=?page=laporan_persemester'>";
}

$database = new Database();
$db = $database->getConnection();
$query = "SELECT * FROM laporan_semester WHERE id = :id";
$stmt = $db->prepare($query);
$stmt->bindParam(':id', $id_laporan);
$stmt->execute();
$laporan = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$laporan) {
    echo "<script>alert('Data tidak ditemukan!'); window.location.href='?page=laporan_persemester';</script>";
    exit;
}


function uploadFile($input_name)
{
    if (!empty($_FILES[$input_name]['name'])) {
        $maxSize = 10 * 1024 * 1024; // 10MB
        if ($_FILES[$input_name]['size'] > $maxSize) {
            $_SESSION['pesan'] = "File " . $input_name . " terlalu besar! Maksimal 10MB.";
            return null;
        }

        $target_dir = "uploads/";
        $file_name = basename($_FILES[$input_name]["name"]);
        $file_name = preg_replace("/[^a-zA-Z0-9.\-_]/", "", $file_name);
        $target_file = $target_dir . time() . "_" . $file_name;
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

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
    <h3 class="text-center mb-3"><i class="fas fa-bolt" style="color: #ffc107;"></i> Update Laporan Semester <i class="fas fa-bolt" style="color: #ffc107;"></i></h3>
    <hr>
    <div class="card shadow" style="overflow-x: auto; max-height: calc(100vh - 150px); overflow-y: auto;">
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Nama Perusahaan</label>
                    <?php if ($role === 'superadmin') : ?>
                        <input type="text" name="nama_perusahaan" class="form-control" placeholder="Masukkan nama perusahaan" value="<?= htmlspecialchars($laporan['nama_perusahaan']) ?>" required>
                    <?php elseif ($role === 'umum') : ?>
                        <input type="text" name="nama_perusahaan" class="form-control" value="<?= htmlspecialchars($laporan['nama_perusahaan']) ?>" readonly>
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label class="form-label">No Hp Pimpinan</label>
                    <input type="text" name="no_hp_pimpinan" class="form-control" value="<?= htmlspecialchars($laporan['no_hp_pimpinan']) ?>" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tenaga Teknik</label>
                    <input type="text" name="tenaga_teknik" class="form-control" value="<?= htmlspecialchars($laporan['tenaga_teknik']) ?>" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">No Hp Tenaga Teknik</label>
                    <input type="text" name="no_hp_teknik" class="form-control" value="<?= htmlspecialchars($laporan['no_hp_teknik']) ?>" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nama Admin</label>
                    <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($laporan['nama']) ?>" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">No Hp Admin</label>
                    <input type="text" name="no_hp" class="form-control" value="<?= htmlspecialchars($laporan['no_hp']) ?>" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">No Telpon Kantor</label>
                    <input type="text" name="no_telp_kantor" class="form-control" value="<?= htmlspecialchars($laporan['no_telp_kantor']) ?>" readonly>
                </div>
                <div class="card-header mt-4">
                    <h6>Parameter SO2</h6>
                    <div class="mb-3">
                        <label class="form-label">Baku Mutu</label>
                        <input type="text" name="baku_mutu_so2" class="form-control" placeholder="Masukkan baku mutu" value="<?= htmlspecialchars($laporan['baku_mutu_so2']) ?>" required>
                        <small class="text-danger">Titik = ribuan, koma = desimal.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Hasil</label>
                        <input type="text" name="hasil_ho2" class="form-control" placeholder="Masukkan hasil" value="<?= htmlspecialchars($laporan['hasil_ho2']) ?>" required>
                        <small class="text-danger">Titik = ribuan, koma = desimal.</small>
                    </div>
                </div>
                <div class="card-header mt-4">
                    <h6>Parameter HO2</h6>
                    <div class="mb-3">
                        <label class="form-label">Baku Mutu</label>
                        <input type="text" name="baku_mutu_ho2" class="form-control" placeholder="Masukkan baku mutu" value="<?= htmlspecialchars($laporan['baku_mutu_ho2']) ?>" required>
                        <small class="text-danger">Titik = ribuan, koma = desimal.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Hasil</label>
                        <input type="text" name="hasil_ho2" class="form-control" placeholder="Masukkan hasil" value="<?= htmlspecialchars($laporan['hasil_ho2']) ?>" required>
                        <small class="text-danger">Titik = ribuan, koma = desimal.</small>
                    </div>
                </div>
                <div class="card-header mt-4">
                    <h6>Parameter TSP/Debu</h6>
                    <div class="mb-3">
                        <label class="form-label">Baku Mutu</label>
                        <input type="text" name="baku_mutu_tsp" class="form-control" placeholder="Masukkan baku mutu" value="<?= htmlspecialchars($laporan['baku_mutu_tsp']) ?>" required>
                        <small class="text-danger">Titik = ribuan, koma = desimal.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Hasil</label>
                        <input type="text" name="hasil_tsp" class="form-control" placeholder="Masukkan hasil" value="<?= htmlspecialchars($laporan['hasil_tsp']) ?>" required>
                        <small class="text-danger">Titik = ribuan, koma = desimal.</small>
                    </div>
                </div>
                <div class="card-header mt-4">
                    <h6>Parameter CO</h6>
                    <div class="mb-3">
                        <label class="form-label">Baku Mutu</label>
                        <input type="text" name="baku_mutu_co" class="form-control" placeholder="Masukkan baku mutu" value="<?= htmlspecialchars($laporan['baku_mutu_co']) ?>" required>
                        <small class="text-danger">Titik = ribuan, koma = desimal.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Hasil</label>
                        <input type="text" name="hasil_co" class="form-control" placeholder="Masukkan hasil" value="<?= htmlspecialchars($laporan['hasil_co']) ?>" required>
                        <small class="text-danger">Titik = ribuan, koma = desimal.</small>
                    </div>
                </div>
                <div class="card-header mt-4 mb-3">
                    <h6>Parameter Kebisingan</h6>
                    <div class="mb-3">
                        <label class="form-label">baku Mutu</label>
                        <input type="text" name="baku_mutu_kebisingan" class="form-control" placeholder="Masukkan baku mutu" value="<?= htmlspecialchars($laporan['baku_mutu_kebisingan']) ?>" required>
                        <small class="text-danger">Titik = ribuan, koma = desimal.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Hasil</label>
                        <input type="text" name="hasil_kebisingan" class="form-control" placeholder="Masukkan hasil" value="<?= htmlspecialchars($laporan['hasil_kebisingan']) ?>" required>
                        <small class="text-danger">Titik = ribuan, koma = desimal.</small>
                    </div>
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
                <div class="mb-3">
                    <label class="form-label">Tahun</label>
                    <select class="form-control" name="tahun" id="tahun" required>
                        <option value="">-- Pilih Tahun --</option>
                        <?php
                        // Isi dropdown tahun dari currentYear sampai endYear
                        for ($year = 2025; $year <= 2035; $year++) {
                            $selected = ($laporan['tahun'] == $year) ? 'selected' : '';
                            echo "<option value='$year' $selected>$year</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group mb-2">
                    <label>Semester</label>
                    <select class="form-control" name="semester" id="semester" required>
                        <option value="">-- Pilih Semester --</option>
                        <option value="Semester I" <?php echo ($laporan['semester'] == 'Semester I') ? 'selected' : ''; ?> id="semester1">Semester I (Januari - Juni)</option>
                        <option value="Semester II" <?php echo ($laporan['semester'] == 'Semester II') ? 'selected' : ''; ?> id="semester2">Semester II (Juli - Desember)</option>
                    </select>
                    <p style="color: red; font-size: 0.875em; margin-top: 5px;">
                        * Untuk semester yang sudah terlewat, pengisian tidak dapat dilakukan
                    </p>
                </div>
                <input type="hidden" name="semester_final" id="semester_final">
                <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                <a href="?page=laporan_persemester" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>


<script>
    const monthNow = new Date().getMonth() + 1; // getMonth() = 0 (Jan) s.d. 11 (Des)
    const semester1 = document.getElementById('semester1');
    const semester2 = document.getElementById('semester2');

    // Jika bulan sekarang Januari - Juni (1-6), Semester II dikunci
    if (monthNow >= 1 && monthNow <= 6) {
        semester2.disabled = true;
    }
    // Jika bulan sekarang Juli - Desember (7-12), Semester I dikunci
    else {
        semester1.disabled = true;
    }

    const tahunSelect = document.getElementById('tahun');
    const semesterSelect = document.getElementById('semester');
    const semesterFinal = document.getElementById('semester_final');

    semesterSelect.addEventListener('change', function() {
        semesterFinal.value = semesterSelect.value; // Menyimpan pilihan semester di input hidden
    });


    const currentYear = new Date().getFullYear(); // Tahun sekarang (otomatis)
    const endYear = currentYear + 10;

    // Isi dropdown tahun dari currentYear sampai endYear
    for (let year = currentYear; year <= endYear; year++) {
        const option = document.createElement("option");
        option.value = year;
        option.text = year;
        tahunSelect.appendChild(option);
    }

    function updateSemesterFinal() {
        const tahun = tahunSelect.value;
        const semester = semesterSelect.value;
        if (tahun && semester) {
            semesterFinal.value = `${semester} ${tahun}`;
        } else {
            semesterFinal.value = "";
        }
    }

    tahunSelect.addEventListener('change', updateSemesterFinal);
    semesterSelect.addEventListener('change', updateSemesterFinal);

    document.querySelector("form").addEventListener("submit", function(e) {
        const maxFileSize = 10 * 1024 * 1024; // 10 MB
        const fileLaporan = document.querySelector('input[name="file_laporan"]');
        const fileLHU = document.querySelector('input[name="file_lhu"]');

        if (fileLaporan.files[0] && fileLaporan.files[0].size > maxFileSize) {
            alert("File laporan terlalu besar! Maksimal 10MB.");
            e.preventDefault();
            return;
        }

        if (fileLHU.files[0] && fileLHU.files[0].size > maxFileSize) {
            alert("File LHU terlalu besar! Maksimal 10MB.");
            e.preventDefault();
            return;
        }
    });
</script>