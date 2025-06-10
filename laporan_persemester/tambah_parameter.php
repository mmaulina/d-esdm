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

// Ambil data profil berdasarkan id_user
$database = new Database();
$db = $database->getConnection();
$query = "SELECT nama_perusahaan FROM profil WHERE id_user = :id_user";
$stmt = $db->prepare($query);
$stmt->bindParam(':id_user', $id_user);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $nama_perusahaan_profil = $result['nama_perusahaan'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $database = new Database();
    $db = $database->getConnection();

    function sanitizeInput($input)
    {
        return strip_tags(trim($input));
    }

    $nama_perusahaan = sanitizeInput($_POST['nama_perusahaan']);
    $tahun = sanitizeInput($_POST['tahun']);
    $semester_final = sanitizeInput($_POST['semester_final']);

    // Prepare the insert statement
    $insertSQL = "INSERT INTO parameter (
    id_user, nama_perusahaan, no_seri_genset, baku_mutu_so2, hasil_so2, rencana_aksi_so2,
    baku_mutu_ho2, hasil_ho2, rencana_aksi_ho2,
    baku_mutu_tsp, hasil_tsp, rencana_aksi_tsp,
    baku_mutu_co, hasil_co, rencana_aksi_co,
    baku_mutu_kebisingan, hasil_kebisingan, rencana_aksi_kebisingan,
    tahun, semester
) VALUES (
    :id_user, :nama_perusahaan, :no_seri_genset,
    :baku_mutu_so2, :hasil_so2, :rencana_aksi_so2,
    :baku_mutu_ho2, :hasil_ho2, :rencana_aksi_ho2,
    :baku_mutu_tsp, :hasil_tsp, :rencana_aksi_tsp,
    :baku_mutu_co, :hasil_co, :rencana_aksi_co,
    :baku_mutu_kebisingan, :hasil_kebisingan, :rencana_aksi_kebisingan,
    :tahun, :semester
)";


    $stmt = $db->prepare($insertSQL);

    // Ambil id_user dari session
    $id_user = $_SESSION['id_user'];

    // Loop through the, baku_mutu, and hasil
    $status = 'diajukan'; // Status diisi otomatis
    $keterangan = '-'; // Keterangan diisi otomatis
    $no_seri_genset = $_POST['no_seri_genset'];
    $baku_mutu_so2 = $_POST['baku_mutu_so2'];
    $hasil_so2 = $_POST['hasil_so2'];
    $rencana_aksi_so2 = $_POST['rencana_aksi_so2'];
    $baku_mutu_ho2 = $_POST['baku_mutu_ho2'];
    $hasil_ho2 = $_POST['hasil_ho2'];
    $rencana_aksi_ho2 = $_POST['rencana_aksi_ho2'];
    $baku_mutu_tsp = $_POST['baku_mutu_tsp'];
    $hasil_tsp = $_POST['hasil_tsp'];
    $rencana_aksi_tsp = $_POST['rencana_aksi_tsp'];
    $baku_mutu_co = $_POST['baku_mutu_co'];
    $hasil_co = $_POST['hasil_co'];
    $rencana_aksi_co = $_POST['rencana_aksi_co'];
    $baku_mutu_kebisingan = $_POST['baku_mutu_kebisingan'];
    $hasil_kebisingan = $_POST['hasil_kebisingan'];
    $rencana_aksi_kebisingan = $_POST['rencana_aksi_kebisingan'];

    // Bind
    $stmt->bindParam(':id_user', $id_user);
    $stmt->bindParam(':nama_perusahaan', $nama_perusahaan);
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

    // Execute the statement
    if (!$stmt->execute()) {
        $_SESSION['hasil'] = false;
        $_SESSION['pesan'] = "Gagal Simpan Data";
    }

    $_SESSION['hasil'] = true;
    $_SESSION['pesan'] = "Berhasil Simpan Data";
    echo "<meta http-equiv='refresh' content='0; url=?page=laporan_persemester'>";
}




?>
<div class="container mt-4">
    <h3 class="text-center mb-3"><i class="fas fa-bolt" style="color: #ffc107;"></i>Tambah Parameter<i class="fas fa-bolt" style="color: #ffc107;"></i></h3>
    <hr>
    <div class="card shadow" style="overflow-x: auto; max-height: calc(100vh - 150px); overflow-y: auto;">
        <div class="card-body">
            <!-- <div class="mb-3">
                <a href="tampil_data.php" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> Kembali
                </a>
            </div> -->
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
                        <label class="form-label">No Seri Genset</label>
                        <input type="text" name="no_seri_genset" class="form-control" placeholder="Masukkan No Seri Genset" required>
                    </div>
                <div class="card-header mt-4">
                    <h6>Parameter SO2</h6>
                    <div class="mb-3">
                        <label class="form-label">Baku Mutu</label>
                        <input type="text" name="baku_mutu_so2" class="form-control" placeholder="Masukkan baku mutu" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Hasil</label>
                        <input type="text" name="hasil_so2" class="form-control" placeholder="Masukkan hasil" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Rencana Aksi</label>
                        <input type="text" name="rencana_aksi_so2" class="form-control" placeholder="Masukkan hasil" required>
                        <small class="text-danger">Isi (-) jika mau dikosongkan</small>
                    </div>
                </div>
                <div class="card-header mt-4">
                    <h6>Parameter HO2</h6>
                    <div class="mb-3">
                        <label class="form-label">Baku Mutu</label>
                        <input type="text" name="baku_mutu_ho2" class="form-control" placeholder="Masukkan baku mutu" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Hasil</label>
                        <input type="text" name="hasil_ho2" class="form-control" placeholder="Masukkan hasil" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Rencana Aksi</label>
                        <input type="text" name="rencana_aksi_ho2" class="form-control" placeholder="Masukkan hasil" required>
                        <small class="text-danger">Isi (-) jika mau dikosongkan</small>
                    </div>
                </div>
                <div class="card-header mt-4">
                    <h6>Parameter TSP/Debu</h6>
                    <div class="mb-3">
                        <label class="form-label">Baku Mutu</label>
                        <input type="text" name="baku_mutu_tsp" class="form-control" placeholder="Masukkan baku mutu" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Hasil</label>
                        <input type="text" name="hasil_tsp" class="form-control" placeholder="Masukkan hasil" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Rencana Aksi</label>
                        <input type="text" name="rencana_aksi_tsp" class="form-control" placeholder="Masukkan hasil" required>
                        <small class="text-danger">Isi (-) jika mau dikosongkan</small>
                    </div>
                </div>
                <div class="card-header mt-4">
                    <h6>Parameter CO</h6>
                    <div class="mb-3">
                        <label class="form-label">Baku Mutu</label>
                        <input type="text" name="baku_mutu_co" class="form-control" placeholder="Masukkan baku mutu" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Hasil</label>
                        <input type="text" name="hasil_co" class="form-control" placeholder="Masukkan hasil" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Rencana Aksi</label>
                        <input type="text" name="rencana_aksi_co" class="form-control" placeholder="Masukkan hasil" required>
                        <small class="text-danger">Isi (-) jika mau dikosongkan</small>
                    </div>
                </div>
                <div class="card-header mt-4 mb-3">
                    <h6>Parameter Kebisingan</h6>
                    <div class="mb-3">
                        <label class="form-label">Baku Mutu</label>
                        <input type="text" name="baku_mutu_kebisingan" class="form-control" placeholder="Masukkan baku mutu" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Hasil</label>
                        <input type="text" name="hasil_kebisingan" class="form-control" placeholder="Masukkan hasil" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Rencana Aksi</label>
                        <input type="text" name="rencana_aksi_kebisingan" class="form-control" placeholder="Masukkan hasil" required>
                        <small class="text-danger">Isi (-) jika mau dikosongkan</small>
                    </div>
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