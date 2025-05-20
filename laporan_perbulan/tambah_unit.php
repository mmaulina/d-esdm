<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'koneksi.php'; // Pastikan koneksi ke database menggunakan PDO

// Pastikan pengguna sudah login
if (!isset($_SESSION['id_user'])) {
    echo "<script>alert('Silakan login terlebih dahulu!'); window.location.href='login.php';</script>";
    exit;
}

$id_user = $_SESSION['id_user']; // Ambil id_user dari sesi

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $db = new Database();
        $conn = $db->getConnection();

        // Sanitasi input
        $nama_perusahaan = htmlspecialchars(trim($_POST['nama_perusahaan']));
        $alamat = htmlspecialchars(trim($_POST['alamat']));
        $no_unit = htmlspecialchars(trim($_POST['no_unit']));
        $tahun_operasi = intval($_POST['tahun_operasi']);
        $status_operasi = htmlspecialchars(trim($_POST['status_operasi']));
        $bahan_bakar_jenis = htmlspecialchars(trim($_POST['bahan_bakar_jenis']));
        $bahan_bakar_satuan = htmlspecialchars(trim($_POST['bahan_bakar_satuan']));

        // Query dengan prepared statement
        $query = "INSERT INTO pembangkit (id_user, nama_perusahaan, alamat, no_unit, tahun_operasi, status_operasi, bahan_bakar_jenis, bahan_bakar_satuan) 
                  VALUES (:id_user, :nama_perusahaan, :alamat, :no_unit, :tahun_operasi, :status_operasi, :bahan_bakar_jenis, :bahan_bakar_satuan)";

        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id_user', $id_user);
        $stmt->bindParam(':no_unit', $no_unit);
        $stmt->bindParam(':tahun_operasi', $tahun_operasi, PDO::PARAM_INT);
        $stmt->bindParam(':status_operasi', $status_operasi);
        $stmt->bindParam(':bahan_bakar_jenis', $bahan_bakar_jenis);
        $stmt->bindParam(':bahan_bakar_satuan', $bahan_bakar_satuan);

        if ($stmt->execute()) {
            echo "<script>alert('Data berhasil ditambahkan!'); window.location.href='?page=pembangkit';</script>";
        } else {
            echo "<script>alert('Gagal menambahkan data!');</script>";
        }
    } catch (PDOException $e) {
        echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
    }
}
?>

<div class="container mt-4">
    <h3 class="text-center">Tambah Unit</h3>
    <hr>
    <div class="card shadow">
        <div class="card-body">
            <div class="mb-3">
                <a href="?page=laporan_perbulan" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> Kembali
                </a>
            </div>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Nama Perusahaan</label>
                    <input type="text" name="nama_perusahaan" class="form-control" placeholder="Masukkan nama perusahaan" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <input type="text" name="alamat" class="form-control" placeholder="Masukkan alamat lengkap" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">No. Unit</label>
                    <input type="text" name="no_unit" class="form-control" placeholder="Masukkan nomor unit" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tahun Operasi</label>
                    <select name="tahun_operasi" class="form-select" required>
                        <option value="">-- Pilih Tahun --</option>
                        <!-- Tahun dari 2030 ke 2000 -->
                        <!-- Kode ini akan diisi otomatis dengan JavaScript -->
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status Operasi</label>
                    <select name="status_operasi" class="form-select" required>
                        <option value="">-- Pilih Status Operasi --</option>
                        <option value="Beroperasi">Beroperasi</option>
                        <option value="Maintenance/Perbaikan">Maintenance/Perbaikan</option>
                        <option value="Rusak">Rusak</option>
                        <option value="Rusak Total">Rusak Total</option>
                    </select>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Jenis Bahan Bakar</label>
                        <select name="bahan_bakar_jenis" class="form-select" required>
                            <option value="">-- Pilih Jenis Bahan Bakar --</option>
                            <option value="Solar">Solar</option>
                            <option value="Biomasa">Biomasa</option>
                        </select>
                        <small class="text-muted">
                            Catatan: <strong>Biomasa</strong> mencakup bahan-bahan organik seperti <em>cangkang sawit</em>, <em>serbuk gergaji</em>, dan <em>sekam padi</em>, dll yang digunakan sebagai bahan bakar alternatif dalam pembangkit listrik.
                        </small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Satuan Bahan Bakar</label>
                        <select name="bahan_bakar_satuan" class="form-select" required>
                            <option value="">-- Pilih Satuan --</option>
                            <option value="Liter">Liter</option>
                            <option value="Ton">Ton</option>
                        </select>
                    </div>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-success">Simpan</button>
                    <a href="?page=pembangkit" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- SCRIPT SIMBOL DERAJAT -->
<script>
    function replaceAsterisk(input) {
        input.value = input.value.replace(/\*/g, "°");
    }
</script>

<!-- SCRIPT PENULISAN ANGKA FORMAT INDONESIA -->
<!-- <script>
    function formatAngkaIndonesia(input) {
        let value = input.value;

        // Hapus semua karakter kecuali angka dan koma
        value = value.replace(/[^0-9,]/g, "");

        // Pisahkan angka dan desimal
        let parts = value.split(",");
        let angka = parts[0];
        let desimal = parts[1] || "";

        // Tambah titik ribuan
        angka = angka.replace(/\B(?=(\d{3})+(?!\d))/g, ".");

        // Gabungkan kembali
        input.value = desimal ? angka + "," + desimal : angka;
    }
</script> -->

<!-- SCRIPT JUMLAH UNIT MAX 200 UNIT -->
<script>
    const jumlahUnitInput = document.getElementById('jumlahUnitInput');
    jumlahUnitInput.addEventListener('input', function() {
        if (parseInt(this.value) > 200) {
            this.value = 200;
        }
    });
</script>

<!-- SCRIPT PILIHAN TAHUN OPERASI -->
<script>
    const selectTahun = document.querySelector('select[name="tahun_operasi"]');
    for (let tahun = 2030; tahun >= 2000; tahun--) {
        const option = document.createElement('option');
        option.value = tahun;
        option.textContent = tahun;
        selectTahun.appendChild(option);
    }
</script>