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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $database = new Database();
    $db = $database->getConnection();

    function sanitizeInput($input) {
        return htmlspecialchars(strip_tags(trim($input)));
    }

    function checkEmpty($input) {
        return empty($input) ? "-" : $input;
    }

    $bulan = sanitizeInput($_POST['bulan']);
    $nama_perusahaan = sanitizeInput($_POST['nama_perusahaan']);
    $volume_bb = checkEmpty(sanitizeInput($_POST['volume_bb']));
    $produksi_sendiri = checkEmpty(sanitizeInput($_POST['produksi_sendiri']));
    $pemb_sumber_lain = checkEmpty(sanitizeInput($_POST['pemb_sumber_lain']));
    $susut_jaringan = checkEmpty(sanitizeInput($_POST['susut_jaringan']));
    $penj_ke_pelanggan = checkEmpty(sanitizeInput($_POST['penj_ke_pelanggan']));
    $penj_ke_pln = checkEmpty(sanitizeInput($_POST['penj_ke_pln']));
    $pemakaian_sendiri = checkEmpty(sanitizeInput($_POST['pemakaian_sendiri']));

    $insertSQL = "INSERT INTO laporan_bulanan (id_user, bulan, nama_perusahaan, volume_bb, produksi_sendiri, pemb_sumber_lain, susut_jaringan, penj_ke_pelanggan, penj_ke_pln, pemakaian_sendiri) 
                  VALUES (:id_user, :bulan, :nama_perusahaan, :volume_bb, :produksi_sendiri, :pemb_sumber_lain, :susut_jaringan, :penj_ke_pelanggan, :penj_ke_pln, :pemakaian_sendiri)";
    $stmt = $db->prepare($insertSQL);

    $stmt->bindParam(':id_user', $id_user);
    $stmt->bindParam(':bulan', $bulan);
    $stmt->bindParam(':nama_perusahaan', $nama_perusahaan);
    $stmt->bindParam(':volume_bb', $volume_bb);
    $stmt->bindParam(':produksi_sendiri', $produksi_sendiri);
    $stmt->bindParam(':pemb_sumber_lain', $pemb_sumber_lain);
    $stmt->bindParam(':susut_jaringan', $susut_jaringan);
    $stmt->bindParam(':penj_ke_pelanggan', $penj_ke_pelanggan);
    $stmt->bindParam(':penj_ke_pln', $penj_ke_pln);
    $stmt->bindParam(':pemakaian_sendiri', $pemakaian_sendiri);
    
    if ($stmt->execute()) {
        $_SESSION['hasil'] = true;
        $_SESSION['pesan'] = "Berhasil Simpan Data";
    } else {
        $_SESSION['hasil'] = false;
        $_SESSION['pesan'] = "Gagal Simpan Data";
    }
    echo "<meta http-equiv='refresh' content='0; url=?page=laporan_perbulan'>";
}
?>


<div class="container mt-4">
    <h3 class="text-center mb-3">Tambah Pelaporan Bulanan</h3>
    <hr>
    <div class="card shadow">
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group mb-3">
                    <label>Bulan</label>
                    <select class="form-control" name="bulan" required>
                        <option value="">-- Pilih Bulan --</option>
                        <option value="Januari">Januari</option>
                        <option value="Februari">Februari</option>
                        <option value="Maret">Maret</option>
                        <option value="April">April</option>
                        <option value="Mei">Mei</option>
                        <option value="Juni">Juni</option>
                        <option value="Juli">Juli</option>
                        <option value="Agustus">Agustus</option>
                        <option value="September">September</option>
                        <option value="Oktober">Oktober</option>
                        <option value="November">November</option>
                        <option value="Desember">Desember</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nama Perusahaan</label>
                    <input type="text" name="nama_perusahaan" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Volume Bahan Bakar</label>
                    <input type="number" name="volume_bb" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Produksi Sendiri (kWh)</label>
                    <input type="number" name="produksi_sendiri" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Pembelian Sumber Lain (bila ada) (kWh)</label>
                    <input type="number" name="pemb_sumber_lain" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Susut jaringan (bila ada) (kWh)</label>
                    <input type="number" name="susut_jaringan" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Penjualan ke Pelanggan (bila ada) (kWh)</label>
                    <input type="number" name="penj_ke_pelanggan" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Penjualan ke PLN (bila ada) (kWh)</label>
                    <input type="number" name="penj_ke_pln" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Pemakaian Sendiri (kWh)</label>
                    <input type="number" name="pemakaian_sendiri" class="form-control" required>
                </div>
                
                <button type="submit" class="btn btn-success">Simpan</button>
                <a href="?page=laporan_perbulan" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>