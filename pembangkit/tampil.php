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
    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive" style="max-height: 500px; overflow-x: auto; overflow-y: auto;">
                <table class="table table-bordered" style="table-layout: fixed; min-width: 1800px;">
                    <thead class="table-dark text-center align-middle">
                        <tr>
                            <th colspan="4" style="min-width: 250px;">Data Pembangkit</th>
                            <th colspan="10" style="min-width: 1500px;">Data Teknis Pembangkit</th>
                            <th rowspan="3" style="min-width: 150px;">Aksi</th>
                        </tr>
                        <tr>
                            <th rowspan="2" style="min-width: 250px;">Nama Perusahaan</th>
                            <th rowspan="2" style="min-width: 250px;">Alamat</th>
                            <th colspan="2" style="min-width: 250px;">Koordinat Pembangkit</th>
                            <th rowspan="2" style="min-width: 250px;">Jenis Pembangkit</th>
                            <th rowspan="2" style="min-width: 250px;">Fungsi</th>
                            <th rowspan="2" style="min-width: 250px;">Kapasitas Terpasang (kW)</th>
                            <th rowspan="2" style="min-width: 250px;">Daya Mampu Netto (kW) </th>
                            <th rowspan="2" style="min-width: 250px;">Jumlah Unit</th>
                            <th rowspan="2" style="min-width: 250px;">No. Unit</th>
                            <th rowspan="2" style="min-width: 250px;">Tahun Operasi</th>
                            <th rowspan="2" style="min-width: 250px;">Status Operasi</th>
                            <th colspan="2" style="min-width: 250px;">Bahan Bakar yang Digunakan</th>
                        </tr>
                        <tr>
                            <th style="min-width: 200px;">Longitude</th>
                            <th style="min-width: 200px;">Latitude</th>
                            <th style="min-width: 250px;">Jenis</th>
                            <th style="min-width: 250px;">Satuan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td style='white-space: normal; word-wrap: break-word;'>" . htmlspecialchars($row['nama_perusahaan']) . "</td>";
                                echo "<td style='white-space: normal; word-wrap: break-word;'>" . htmlspecialchars($row['alamat']) . "</td>";
                                echo "<td style='white-space: normal; word-wrap: break-word;'>" . htmlspecialchars($row['longitude']) . "</td>";
                                echo "<td style='white-space: normal; word-wrap: break-word;'>" . htmlspecialchars($row['latitude']) . "</td>";
                                echo "<td style='white-space: normal; word-wrap: break-word;'>" . htmlspecialchars($row['jenis_pembangkit']) . "</td>";
                                echo "<td style='white-space: normal; word-wrap: break-word;'>" . htmlspecialchars($row['fungsi']) . "</td>";
                                echo "<td style='white-space: normal; word-wrap: break-word;'>" . htmlspecialchars($row['kapasitas_terpasang']) . "</td>";
                                echo "<td style='white-space: normal; word-wrap: break-word;'>" . htmlspecialchars($row['daya_mampu_netto']) . "</td>";
                                echo "<td style='white-space: normal; word-wrap: break-word;'>" . htmlspecialchars($row['jumlah_unit']) . "</td>";
                                echo "<td style='white-space: normal; word-wrap: break-word;'>" . htmlspecialchars($row['no_unit']) . "</td>";
                                echo "<td style='white-space: normal; word-wrap: break-word;'>" . htmlspecialchars($row['tahun_operasi']) . "</td>";
                                echo "<td style='white-space: normal; word-wrap: break-word;'>" . htmlspecialchars($row['status_operasi']) . "</td>";
                                echo "<td style='white-space: normal; word-wrap: break-word;'>" . htmlspecialchars($row['bahan_bakar_jenis']) . "</td>";
                                echo "<td style='white-space: normal; word-wrap: break-word;'>" . htmlspecialchars($row['bahan_bakar_satuan']) . "</td>";
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
            $id_user = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 0;
            ?>
            <a href='?page=pembangkit_tambah&id_user=<?= $id_user ?>' class='btn btn-sm btn-primary mt-3'>Tambah</a>
        </div>
    </div>
</div>