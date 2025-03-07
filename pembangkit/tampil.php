<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'koneksi.php'; // Pastikan file koneksi ke database sudah disertakan

// Pastikan pengguna sudah login
if (!isset($_SESSION['id_user'])) {
    echo "<script>alert('Silakan login terlebih dahulu!'); window.location.href='login.php';</script>";
    exit;
}

$id_user = $_SESSION['id_user']; // Ambil id_user dari sesi

$query = "SELECT * FROM pembangkit WHERE id_user = '$id_user'";
$result = mysqli_query($conn, $query);
?>

<div class="container mt-4">
    <h3 class="text-center mb-3">Data Pembangkit dan Data Teknis Pembangkit</h3>
    <hr>
    <div class="table-responsive" style="max-height: 500px; overflow-x: auto; overflow-y: auto;">
        <table class="table table-bordered" style="table-layout: fixed; min-width: 1500px;">
            <thead class="table-dark text-center align-middle">
                <tr>
                    <th colspan="3" style="min-width: 200px;">Data Pembangkit</th>
                    <th colspan="10" style="min-width: 1000px;">Data Teknis Pembangkit</th>
                    <th rowspan="3" style="min-width: 120px;">Aksi</th>
                </tr>
                <tr>
                    <th rowspan="2" style="min-width: 200px;">Alamat</th>
                    <th colspan="2" style="min-width: 200px;">Koordinat Pembangkit</th>
                    <th rowspan="2" style="min-width: 200px;">Jenis Pembangkit</th>
                    <th rowspan="2" style="min-width: 200px;">Fungsi</th>
                    <th rowspan="2" style="min-width: 200px;">Kapasitas Terpasang (kW)</th>
                    <th rowspan="2" style="min-width: 200px;">Daya Mampu Netto (kW) *)</th>
                    <th rowspan="2" style="min-width: 200px;">Jumlah Unit</th>
                    <th rowspan="2" style="min-width: 200px;">No. Unit</th>
                    <th rowspan="2" style="min-width: 200px;">Tahun Operasi</th>
                    <th rowspan="2" style="min-width: 200px;">Status Operasi</th>
                    <th colspan="2" style="min-width: 200px;">Bahan Bakar yang Digunakan</th>
                </tr>
                <tr>
                    <th style="min-width: 150px;">Longitude</th>
                    <th style="min-width: 150px;">Latitude</th>
                    <th style="min-width: 200px;">Jenis</th>
                    <th style="min-width: 200px;">Satuan</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td style='white-space: nowrap;'>" . htmlspecialchars($row['alamat']) . "</td>";
                        echo "<td style='white-space: nowrap;'>" . htmlspecialchars($row['longitude']) . "</td>";
                        echo "<td style='white-space: nowrap;'>" . htmlspecialchars($row['latitude']) . "</td>";
                        echo "<td style='white-space: nowrap;'>" . htmlspecialchars($row['jenis_pembangkit']) . "</td>";
                        echo "<td style='white-space: nowrap;'>" . htmlspecialchars($row['fungsi']) . "</td>";
                        echo "<td style='white-space: nowrap;'>" . htmlspecialchars($row['kapasitas_terpasang']) . "</td>";
                        echo "<td style='white-space: nowrap;'>" . htmlspecialchars($row['daya_mampu_netto']) . "</td>";
                        echo "<td style='white-space: nowrap;'>" . htmlspecialchars($row['jumlah_unit']) . "</td>";
                        echo "<td style='white-space: nowrap;'>" . htmlspecialchars($row['no_unit']) . "</td>";
                        echo "<td style='white-space: nowrap;'>" . htmlspecialchars($row['tahun_operasi']) . "</td>";
                        echo "<td style='white-space: nowrap;'>" . htmlspecialchars($row['status_operasi']) . "</td>";
                        echo "<td style='white-space: nowrap;'>" . htmlspecialchars($row['bahan_bakar_jenis']) . "</td>";
                        echo "<td style='white-space: nowrap;'>" . htmlspecialchars($row['bahan_bakar_satuan']) . "</td>";
                        echo "<td>
                                <a href='?page=edit_pembangkit&id=" . $row['id'] . "' class='btn btn-sm btn-primary'>Edit</a>
                                <a href='?page=hapus_pembangkit&id=" . $row['id'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Hapus data ini?\")'>Hapus</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='14' class='text-center'>Data tidak ditemukan</td></tr>";
                }
                ?>
            </tbody>
        </table>
    
    </div>
    <?php
    // Ambil id_user jika ada session pengguna
    $id_user = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
    ?>

    <a href='?page=pembangkit_tambah&id_user=<?= $id_user ?>' class='btn btn-sm btn-primary mt-3'>Tambah</a>
</div>
