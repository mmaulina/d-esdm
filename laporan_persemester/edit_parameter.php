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

// Ambil data parameter berdasarkan id
$database = new Database();
$db = $database->getConnection();

$parameter_id = $_GET['id'] ?? null; // Get the parameter ID from the URL
$parameter_data = null;

if ($parameter_id) {
    $query = "SELECT * FROM parameter WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $parameter_id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $parameter_data = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        echo "<script>alert('Data tidak ditemukan!'); window.location.href='?page=laporan_persemester';</script>";
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    function sanitizeInput($input)
    {
        return strip_tags(trim($input));
    }

    $no_seri_genset = sanitizeInput($_POST['no_seri_genset']);
    $baku_mutu_so2 = sanitizeInput($_POST['baku_mutu_so2']);
    $hasil_so2 = sanitizeInput($_POST['hasil_so2']);
    $rencana_aksi_so2 = sanitizeInput($_POST['rencana_aksi_so2']);
    $baku_mutu_ho2 = sanitizeInput($_POST['baku_mutu_ho2']);
    $hasil_ho2 = sanitizeInput($_POST['hasil_ho2']);
    $rencana_aksi_ho2 = sanitizeInput($_POST['rencana_aksi_ho2']);
    $baku_mutu_tsp = sanitizeInput($_POST['baku_mutu_tsp']);
    $hasil_tsp = sanitizeInput($_POST['hasil_tsp']);
    $rencana_aksi_tsp = sanitizeInput($_POST['rencana_aksi_tsp']);
    $baku_mutu_co = sanitizeInput($_POST['baku_mutu_co']);
    $hasil_co = sanitizeInput($_POST['hasil_co']);
    $rencana_aksi_co = sanitizeInput($_POST['rencana_aksi_co']);
    $baku_mutu_kebisingan = sanitizeInput($_POST['baku_mutu_kebisingan']);
    $hasil_kebisingan = sanitizeInput($_POST['hasil_kebisingan']);
    $rencana_aksi_kebisingan = sanitizeInput($_POST['rencana_aksi_kebisingan']);
    $tahun = sanitizeInput($_POST['tahun']);
    $semester_final = sanitizeInput($_POST['semester_final']);

    // Prepare the update statement
    $updateSQL = "UPDATE parameter SET 
        no_seri_genset = :no_seri_genset,
        baku_mutu_so2 = :baku_mutu_so2,
        hasil_so2 = :hasil_so2,
        rencana_aksi_so2 = :rencana_aksi_so2,
        baku_mutu_ho2 = :baku_mutu_ho2,
        hasil_ho2 = :hasil_ho2,
        rencana_aksi_ho2 = :rencana_aksi_ho2,
        baku_mutu_tsp = :baku_mutu_tsp,
        hasil_tsp = :hasil_tsp,
        rencana_aksi_tsp = :rencana_aksi_tsp,
        baku_mutu_co = :baku_mutu_co,
        hasil_co = :hasil_co,
        rencana_aksi_co = :rencana_aksi_co,
        baku_mutu_kebisingan = :baku_mutu_kebisingan,
        hasil_kebisingan = :hasil_kebisingan,
        rencana_aksi_kebisingan = :rencana_aksi_kebisingan,
        tahun = :tahun,
        semester = :semester,
        status = 'Diajukan',
        keterangan = '-'
        WHERE id = :id";

    $stmt = $db->prepare($updateSQL);
    $stmt->bindParam(':no_seri_genset', $no_seri_genset);
    $stmt->bindParam(':baku_mutu_so2', $baku_mutu_so2);
    $stmt->bindParam(':hasil_so2', $hasil_so2);
    $stmt->bindParam(':rencana_aksi_so2', $rencana_aksi_so2);
    $stmt->bindParam(':baku_mutu_ho2', $baku_mutu_ho2);
    $stmt->bindParam(':hasil_ho2', $hasil_ho2);
    $stmt->bindParam(':rencana_aksi_ho2', $rencana_aksi_ho2);
    $stmt->bindParam(':baku_mutu_tsp', $baku_mutu_tsp);
    $stmt->bindParam(':hasil_tsp', $hasil_tsp);
    $stmt->bindParam(':rencana_aksi_tsp', $rencana_aksi_tsp);
    $stmt->bindParam(':baku_mutu_co', $baku_mutu_co);
    $stmt->bindParam(':hasil_co', $hasil_co);
    $stmt->bindParam(':rencana_aksi_co', $rencana_aksi_co);
    $stmt->bindParam(':baku_mutu_kebisingan', $baku_mutu_kebisingan);
    $stmt->bindParam(':hasil_kebisingan', $hasil_kebisingan);
    $stmt->bindParam(':rencana_aksi_kebisingan', $rencana_aksi_kebisingan);
    $stmt->bindParam(':tahun', $tahun);
    $stmt->bindParam(':semester', $semester_final);
    $stmt->bindParam(':id', $parameter_id);

    // Execute the statement
    if ($stmt->execute()) {
        $_SESSION['hasil'] = true;
        $_SESSION['pesan'] = "Berhasil Update Data";
    } else {
        $_SESSION['hasil'] = false;
        $_SESSION['pesan'] = "Gagal Update Data";
    }
    echo "<meta http-equiv='refresh' content='0; url=?page=laporan_persemester'>";
}
?>

<div class="container mt-4">
    <h3 class="text-center mb-3"><i class="fas fa-bolt" style="color: #ffc107;"></i>Edit Parameter<i class="fas fa-bolt" style="color: #ffc107;"></i></h3>
    <hr>
    <div class="card shadow" style="overflow-x: auto; max-height: calc(100vh - 150px); overflow-y: auto;">
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Nama Perusahaan</label>
                    <input type="text" name="nama_perusahaan" class="form-control" value="<?= htmlspecialchars($parameter_data['nama_perusahaan']) ?>" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">No Seri Genset</label>
                    <input type="text" name="no_seri_genset" class="form-control" value="<?= htmlspecialchars($parameter_data['no_seri_genset']) ?>" required>
                </div>
                <div class="card-header mt-4">
                    <h6>Parameter SO2</h6>
                    <div class="mb-3">
                        <label class="form-label">Baku Mutu</label>
                        <input type="text" name="baku_mutu_so2" class="form-control" value="<?= htmlspecialchars($parameter_data['baku_mutu_so2']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Hasil</label>
                        <input type="text" name="hasil_so2" class="form-control" value="<?= htmlspecialchars($parameter_data['hasil_so2']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Rencana Aksi</label>
                        <input type="text" name="rencana_aksi_so2" class="form-control" value="<?= htmlspecialchars($parameter_data['rencana_aksi_so2']) ?>" required>
                        <small class="text-danger">Isi (-) jika mau dikosongkan</small>
                    </div>
                </div>
                <div class="card-header mt-4">
                    <h6>Parameter HO2</h6>
                    <div class="mb-3">
                        <label class="form-label">Baku Mutu</label>
                        <input type="text" name="baku_mutu_ho2" class="form-control" value="<?= htmlspecialchars($parameter_data['baku_mutu_ho2']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Hasil</label>
                        <input type="text" name="hasil_ho2" class="form-control" value="<?= htmlspecialchars($parameter_data['hasil_ho2']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Rencana Aksi</label>
                        <input type="text" name="rencana_aksi_ho2" class="form-control" value="<?= htmlspecialchars($parameter_data['rencana_aksi_ho2']) ?>" required>
                        <small class="text-danger">Isi (-) jika mau dikosongkan</small>
                    </div>
                </div>
                <div class="card-header mt-4">
                    <h6>Parameter TSP/Debu</h6>
                    <div class="mb-3">
                        <label class="form-label">Baku Mutu</label>
                        <input type="text" name="baku_mutu_tsp" class="form-control" value="<?= htmlspecialchars($parameter_data['baku_mutu_tsp']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Hasil</label>
                        <input type="text" name="hasil_tsp" class="form-control" value="<?= htmlspecialchars($parameter_data['hasil_tsp']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Rencana Aksi</label>
                        <input type="text" name="rencana_aksi_tsp" class="form-control" value="<?= htmlspecialchars($parameter_data['rencana_aksi_tsp']) ?>" required>
                        <small class="text-danger">Isi (-) jika mau dikosongkan</small>
                    </div>
                </div>
                <div class="card-header mt-4">
                    <h6>Parameter CO</h6>
                    <div class="mb-3">
                        <label class="form-label">Baku Mutu</label>
                        <input type="text" name="baku_mutu_co" class="form-control" value="<?= htmlspecialchars($parameter_data['baku_mutu_co']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Hasil</label>
                        <input type="text" name="hasil_co" class="form-control" value="<?= htmlspecialchars($parameter_data['hasil_co']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Rencana Aksi</label>
                        <input type="text" name="rencana_aksi_co" class="form-control" value="<?= htmlspecialchars($parameter_data['rencana_aksi_co']) ?>" required>
                        <small class="text-danger">Isi (-) jika mau dikosongkan</small>
                    </div>
                </div>
                <div class="card-header mt-4 mb-3">
                    <h6>Parameter Kebisingan</h6>
                    <div class="mb-3">
                        <label class="form-label">Baku Mutu</label>
                        <input type="text" name="baku_mutu_kebisingan" class="form-control" value="<?= htmlspecialchars($parameter_data['baku_mutu_kebisingan']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Hasil</label>
                        <input type="text" name="hasil_kebisingan" class="form-control" value="<?= htmlspecialchars($parameter_data['hasil_kebisingan']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Rencana Aksi</label>
                        <input type="text" name="rencana_aksi_kebisingan" class="form-control" value="<?= htmlspecialchars($parameter_data['rencana_aksi_kebisingan']) ?>" required>
                        <small class="text-danger">Isi (-) jika mau dikosongkan</small>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tahun</label>
                    <select class="form-control" name="tahun" id="tahun" required>
                        <option value="">-- Pilih Tahun --</option>
                        <?php
                        $currentYear = date("Y");
                        for ($year = $currentYear - 1; $year <= $currentYear + 10; $year++) {
                            $selected = ($year == $parameter_data['tahun']) ? 'selected' : '';
                            echo "<option value='$year' $selected>$year</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group mb-2">
                    <label>Semester</label>
                    <select class="form-control" name="semester" id="semester" required>
                        <option value="">-- Pilih Semester --</option>
                        <option value="Semester I" <?= ($parameter_data['semester'] == 'Semester I') ? 'selected' : '' ?>id="semester1">Semester I ( Januari - Juni )</option>
                        <option value="Semester II" <?= ($parameter_data['semester'] == 'Semester II') ? 'selected' : '' ?>id="semester2">Semester II ( Juli - Desember )</option>
                    </select>
                    <p style="color: red; font-size: 0.875em; margin-top: 5px;">
                        * Untuk semester yang sudah terlewat, pengisian tidak dapat dilakukan
                    </p>
                </div>
                <input type="hidden" name="semester_final" id="semester_final">
                <button type="submit" class="btn btn-success">Simpan</button>
                <a href="?page=laporan_persemester" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>

<!-- SCRIPT CHECKBOX PENGISIAN DATA -->
<script>
 const monthNow = new Date().getMonth() + 1; // getMonth() = 0 (Jan) s.d. 11 (Des)
const semester1 = document.getElementById('semester1');
const semester2 = document.getElementById('semester2');

// Atur semua aktif dulu
semester1.disabled = false;
semester2.disabled = false;

if (monthNow >= 2 && monthNow <= 6) {
    // Februari s.d. Juni: hanya semester 1
    semester2.disabled = true;
} else if (monthNow >= 8 && monthNow <= 12) {
    // Agustus s.d. Desember: hanya semester 2
    semester1.disabled = true;
}
// Januari (1) dan Juli (7): keduanya aktif

    const tahunSelect = document.getElementById('tahun');
    const semesterSelect = document.getElementById('semester');
    const semesterFinal = document.getElementById('semester_final');

    semesterSelect.addEventListener('change', function() {
        semesterFinal.value = semesterSelect.value; // Menyimpan pilihan semester di input hidden
    });


    const currentYear = new Date().getFullYear();
const startYear = currentYear - 1; // 1 tahun sebelum tahun sekarang
const endYear = currentYear + 10;  // 10 tahun ke depan

for (let year = startYear; year <= endYear; year++) {
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

</script>
