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
$role = $_SESSION['role'] ?? '';

$nama_perusahaan_profil = '';
$no_hp_pimpinan = '';
$tenaga_teknik = '';
$no_hp_teknik = '';
$nama = '';
$no_hp = '';
$no_telp_kantor = '';

// Ambil data profil berdasarkan id_user
$database = new Database();
$db = $database->getConnection();
$query = "SELECT nama_perusahaan, no_hp_pimpinan, tenaga_teknik, no_hp_teknik, nama, no_hp, no_telp_kantor 
          FROM profil WHERE id_user = :id_user";
$stmt = $db->prepare($query);
$stmt->bindParam(':id_user', $id_user);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $nama_perusahaan_profil = $result['nama_perusahaan'];
    $no_hp_pimpinan = $result['no_hp_pimpinan'];
    $tenaga_teknik = $result['tenaga_teknik'];
    $no_hp_teknik = $result['no_hp_teknik'];
    $nama = $result['nama'];
    $no_hp = $result['no_hp'];
    $no_telp_kantor = $result['no_telp_kantor'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $database = new Database();
    $db = $database->getConnection();

    function sanitizeInput($input)
    {
        return strip_tags(trim($input));
    }

    $no_hp_pimpinan = sanitizeInput($_POST['no_hp_pimpinan'] ?? '');
    $tenaga_teknik = sanitizeInput($_POST['tenaga_teknik'] ?? '');
    $no_hp_teknik = sanitizeInput($_POST['no_hp_teknik'] ?? '');
    $nama = sanitizeInput($_POST['nama'] ?? '');
    $no_hp = sanitizeInput($_POST['no_hp'] ?? '');
    $no_telp_kantor = sanitizeInput($_POST['no_telp_kantor'] ?? '');
    $nama_perusahaan = sanitizeInput($_POST['nama_perusahaan']);
    $tahun = sanitizeInput($_POST['tahun']);
    $semester_final = sanitizeInput($_POST['semester_final']);

    // Handle file upload
    $file_laporan = uploadFile('file_laporan');
    $file_lhu = uploadFile('file_lhu');

    // Prepare the insert statement
    $insertSQL = "INSERT INTO laporan_semester (id_user, nama_perusahaan, no_hp_pimpinan, tenaga_teknik, no_hp_teknik, nama, no_hp, no_telp_kantor, parameter, baku_mutu, hasil, status, keterangan, file_laporan, file_lhu, tahun, semester) 
                  VALUES (:id_user, :nama_perusahaan, :no_hp_pimpinan, :tenaga_teknik, :no_hp_teknik, :nama, :no_hp, :no_telp_kantor, :parameter, :baku_mutu, :hasil, :status, :keterangan, :file_laporan, :file_lhu, :tahun, :semester)";

    $stmt = $db->prepare($insertSQL);

    // Ambil id_user dari session
    $id_user = $_SESSION['id_user'];
    $status = 'diajukan'; // Status diisi otomatis
    $keterangan = '-'; // Keterangan diisi otomatis

    // Loop through the parameters, baku_mutu, and hasil
    $parameters = $_POST['parameter'] ?? [];
    $baku_mutus = $_POST['baku_mutu'] ?? [];
    $hasils = $_POST['hasil'] ?? [];

    foreach ($parameters as $index => $parameter) {
        // Sanitize each input
        $baku_mutu = sanitizeInput($baku_mutus[$index] ?? '');
        $hasil = sanitizeInput($hasils[$index] ?? '');

        // Bind parameters
        $stmt->bindParam(':id_user', $id_user);
        $stmt->bindParam(':nama_perusahaan', $nama_perusahaan);
        $stmt->bindParam(':no_hp_pimpinan', $no_hp_pimpinan);
        $stmt->bindParam(':tenaga_teknik', $tenaga_teknik);
        $stmt->bindParam(':no_hp_teknik', $no_hp_teknik);
        $stmt->bindParam(':nama', $nama);
        $stmt->bindParam(':no_hp', $no_hp);
        $stmt->bindParam(':no_telp_kantor', $no_telp_kantor);
        $stmt->bindParam(':parameter', $parameter);
        $stmt->bindParam(':baku_mutu', $baku_mutu);
        $stmt->bindParam(':hasil', $hasil);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':keterangan', $keterangan);
        $stmt->bindParam(':file_laporan', $file_laporan);
        $stmt->bindParam(':file_lhu', $file_lhu);
        $stmt->bindParam(':tahun', $tahun);
        $stmt->bindParam(':semester', $semester_final);

        // Execute the statement
        if (!$stmt->execute()) {
            $_SESSION['hasil'] = false;
            $_SESSION['pesan'] = "Gagal Simpan Data untuk parameter: $parameter";
            break; // Stop the loop if there's an error
        }
    }

    $_SESSION['hasil'] = true;
    $_SESSION['pesan'] = "Berhasil Simpan Data";
    echo "<meta http-equiv='refresh' content='0; url=?page=laporan_persemester'>";
}


// Fungsi untuk upload file dengan validasi format
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
    <h3 class="text-center mb-3"><i class="fas fa-bolt" style="color: #ffc107;"></i>Tambah Pelaporan Semester<i class="fas fa-bolt" style="color: #ffc107;"></i></h3>
    <hr>
    <div class="card shadow" style="overflow-x: auto; max-height: calc(100vh - 150px); overflow-y: auto;">
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Nama Perusahaan</label>
                    <?php if ($role === 'superadmin') : ?>
                        <input type="text" name="nama_perusahaan" class="form-control" placeholder="Masukkan nama perusahaan" value="<?= htmlspecialchars($nama_perusahaan_profil) ?>">
                    <?php else : ?>
                        <input type="text" name="nama_perusahaan" class="form-control" value="<?= htmlspecialchars($nama_perusahaan_profil) ?>" readonly>
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label class="form-label">No Hp Pimpinan</label>
                    <?php if ($role === 'superadmin') : ?>
                        <input type="text" name="no_hp_pimpinan" class="form-control" placeholder="Masukkan nomor HP pimpinan" value="<?= htmlspecialchars($no_hp_pimpinan) ?>">
                    <?php else : ?>
                        <input type="text" name="no_hp_pimpinan" class="form-control" value="<?= htmlspecialchars($no_hp_pimpinan) ?>" readonly>
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tenaga Teknik</label>
                    <?php if ($role === 'superadmin') : ?>
                        <input type="text" name="tenaga_teknik" class="form-control" placeholder="Masukkan nama tenaga teknik" value="<?= htmlspecialchars($tenaga_teknik) ?>">
                    <?php else : ?>
                        <input type="text" name="tenaga_teknik" class="form-control" value="<?= htmlspecialchars($tenaga_teknik) ?>" readonly>
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label class="form-label">No Hp Tenaga Teknik</label>
                    <?php if ($role === 'superadmin') : ?>
                        <input type="text" name="no_hp_teknik" class="form-control" placeholder="Masukkan nomor HP tenaga teknik" value="<?= htmlspecialchars($no_hp_teknik) ?>">
                    <?php else : ?>
                        <input type="text" name="no_hp_teknik" class="form-control" value="<?= htmlspecialchars($no_hp_teknik) ?>" readonly>
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nama Admin</label>
                    <?php if ($role === 'superadmin') : ?>
                        <input type="text" name="nama" class="form-control" placeholder="Masukkan nama admin" value="<?= htmlspecialchars($nama) ?>">
                    <?php else : ?>
                        <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($nama) ?>" readonly>
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label class="form-label">No Hp Admin</label>
                    <?php if ($role === 'superadmin') : ?>
                        <input type="text" name="no_hp" class="form-control" placeholder="Masukkan nomor HP admin" value="<?= htmlspecialchars($no_hp) ?>">
                    <?php else : ?>
                        <input type="text" name="no_hp" class="form-control" value="<?= htmlspecialchars($no_hp) ?>" readonly>
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label class="form-label">No Telpon Kantor</label>
                    <?php if ($role === 'superadmin') : ?>
                        <input type="text" name="no_telp_kantor" class="form-control" placeholder="Masukkan nomor telepon kantor/perusahaan" value="<?= htmlspecialchars($no_telp_kantor) ?>">
                    <?php else : ?>
                        <input type="text" name="no_telp_kantor" class="form-control" value="<?= htmlspecialchars($no_telp_kantor) ?>" readonly>
                    <?php endif; ?>
                </div>

                <div id="dynamicFields">
                    <div class="mb-3">
                        <label class="form-label">Parameter</label>
                        <select class="form-control" name="parameter[]" required>
                            <option value="">-- Pilih Parameter --</option>
                            <option value="SO2">SO2</option>
                            <option value="HO2">HO2</option>
                            <option value="TSP/DEBU">TSP/DEBU</option>
                            <option value="CO">CO</option>
                            <option value="kebisingan">Kebisingan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Baku Mutu</label>
                        <input type="text" name="baku_mutu[]" class="form-control" placeholder="Masukkan baku mutu" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Hasil</label>
                        <input type="text" name="hasil[]" class="form-control" placeholder="Masukkan hasil" required>
                    </div>
                </div>

                <button type="button" class="btn btn-primary mb-3" id="addFields">Tambah Parameter</button>

                <div class="mb-3">
                    <label class="form-label">Upload Laporan (PDF, DOC, DOCX, XLS, XLSX)</label>
                    <input type="file" name="file_laporan" class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx">
                    <small class="text-danger">Max File 10Mb</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Upload LHU (PDF, DOC , DOCX, XLS, XLSX)</label>
                    <input type="file" name="file_lhu" class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx">
                    <small class="text-danger">Max File 5Mb</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tahun</label>
                    <select class="form-control" name="tahun" id="tahun" required>
                        <option value="">-- Pilih Tahun --</option>
                    </select>
                </div>

                <div class="form-group mb-2">
                    <label>Semester</label>
                    <select class="form-control" name="semester" id="semester" required>
                        <option value="">-- Pilih Semester --</option>
                        <option value="Semester I" id="semester1">Semester I ( Januari - Juni )</option>
                        <option value="Semester II" id="semester2">Semester II ( Juli - Desember ) </option>
                    </select>
                    <p style="color: red; font-size: 0.875em; margin-top: 5px;">
                        * Untuk semester yang sudah terlewat, pengisian tidak dapat dilakukan
                    </p>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="dataCheck" />
                    <label class="form-check-label" for="dataCheck">Saya pastikan semua data terisi dengan benar</label>
                </div>
                <input type="hidden" name="semester_final" id="semester_final">
                <button type="submit" class="btn btn-success" id="submitBtn" disabled>Simpan</button>
                <a href="?page=laporan_persemester" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>

<!-- SCRIPT CHECKBOX PENGISIAN DATA -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkbox = document.getElementById('dataCheck');
        const submitBtn = document.getElementById('submitBtn');
        checkbox.addEventListener('change', function() {
            submitBtn.disabled = !checkbox.checked;
        });
    });
</script>

<script>
    document.getElementById('addFields').addEventListener('click', function() {
        const dynamicFields = document.getElementById('dynamicFields');
        const newFields = `
            <div class="mb-3">
                <label class="form-label">Parameter</label>
                            <select class="form-control" name="parameter[]" required>
                            <option value="">-- Pilih Parameter --</option>
                            <option value="SO2">SO2</option>
                            <option value="HO2">HO2</option>
                            <option value="TSP/DEBU">TSP/DEBU</option>
                            <option value="CO">CO</option>
                            <option value="kebisingan">Kebisingan</option>
                        </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Baku Mutu</label>
                <input type="text" name="baku_mutu[]" class="form-control" placeholder="Masukkan baku mutu" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Hasil</label>
                <input type="text" name="hasil[]" class="form-control" placeholder="Masukkan hasil" required>
            </div>`;
        dynamicFields.insertAdjacentHTML('beforeend', newFields);
    });

    const monthNow = new Date().getMonth() + 1; // getMonth() = 0 (Jan) s.d. 11 (Des)
    const semester1 = document.getElementById('semester1');
    const semester2 = document.getElementById('semester2');

    if (monthNow >= 1 && monthNow <= 6) {
        semester2.disabled = true;
    } else {
        semester1.disabled = true;
    }

    const tahunSelect = document.getElementById('tahun');
    const semesterSelect = document.getElementById('semester');
    const semesterFinal = document.getElementById('semester_final');

    const currentYear = new Date().getFullYear();
    const endYear = currentYear + 10;

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
        const maxFileSizeLaporan = 10 * 1024 * 1024; // 10 MB
        const maxFileSizeLHU = 5 * 1024 * 1024; // 5 MB
        const fileLaporan = document.querySelector('input[name="file_laporan"]');
        const fileLHU = document.querySelector('input[name="file_lhu"]');

        if (fileLaporan.files[0] && fileLaporan.files[0].size > maxFileSize) {
            alert("File laporan terlalu besar! Maksimal 10MB.");
            e.preventDefault();
            return;
        }

        if (fileLHU.files[0] && fileLHU.files[0].size > maxFileSize) {
            alert("File LHU terlalu besar! Maksimal 5MB.");
            e.preventDefault();
            return;
        }
    });
</script>