<div class="container mt-4">
    <h3 class="text-center mb-3">Data Pembangkit dan Data Teknis Pembangkit</h3>
    <hr>
    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive" style="max-height: 500px; overflow-x: auto; overflow-y: auto;">
                <table class="table table-bordered" style="min-width: 1500px; white-space: nowrap;">
                    <thead class="table-dark text-center align-middle">
                        <tr>
                            <th colspan="4">Data Pembangkit</th>
                            <th colspan="10">Data Teknis Pembangkit</th>
                            <th rowspan="3">Aksi</th>
                        </tr>
                        <tr>
                            <th rowspan="2">Nama Perusahaan</th>
                            <th rowspan="2">Alamat</th>
                            <th colspan="2">Koordinat Pembangkit</th>
                            <th rowspan="2">Jenis Pembangkit</th>
                            <th rowspan="2">Fungsi</th>
                            <th rowspan="2">Kapasitas Terpasang (kW)</th>
                            <th rowspan="2">Daya Mampu Netto (kW) *)</th>
                            <th rowspan="2">Jumlah Unit</th>
                            <th rowspan="2">No. Unit</th>
                            <th rowspan="2">Tahun Operasi</th>
                            <th rowspan="2">Status Operasi</th>
                            <th colspan="2">Bahan Bakar yang Digunakan</th>
                        </tr>
                        <tr>
                            <th>Longitude</th>
                            <th>Latitude</th>
                            <th>Jenis</th>
                            <th>Satuan</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        include 'koneksi.php';
                        $query = "SELECT * FROM pembangkit";
                        $result = mysqli_query($conn, $query);

                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['nama_perusahaan']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['alamat']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['longitude']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['latitude']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['jenis_pembangkit']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['fungsi']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['kapasitas_terpasang']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['daya_mampu_netto']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['jumlah_unit']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['no_unit']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['tahun_operasi']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['status_operasi']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['bahan_bakar_jenis']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['bahan_bakar_satuan']) . "</td>";
                                echo "<td>
                                <a href='edit.php?id=" . $row['id'] . "' class='btn btn-sm btn-primary'>Edit</a>
                                <a href='hapus.php?id=" . $row['id'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Hapus data ini?\")'>Hapus</a>
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
        </div>
    </div>
</div>