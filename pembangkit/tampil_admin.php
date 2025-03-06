<div class="container mt-4">
    <h3 class="text-center mb-3">Data Pembangkit dan Data Teknis Pembangkit</h3>
    <hr>
    <div class="table-responsive" style="max-height: 500px; overflow-x: auto; overflow-y: auto;">
        <table class="table table-bordered">
            <thead class="table-dark text-center align-middle">
                <tr>
                    <th colspan="3">Data Pembangkit</th>
                    <th colspan="10">Data Teknis Pembangkit</th>
                    <th rowspan="3">Aksi</th>
                </tr>
                <tr>
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

            <!-- INI BLM KU ATUR, TOLONG YA WAN WKWK -->
            <tbody>
                <?php
                $query = "SELECT * FROM pembangkit";
                $result = mysqli_query($conn, $query);

                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['nama'] . "</td>";
                    echo "<td>" . $row['jenis'] . "</td>";
                    echo "<td>" . $row['kapasitas'] . "</td>";
                    echo "<td>" . $row['lokasi'] . "</td>";
                    echo "<td>
                                <a href='edit.php?id=" . $row['id'] . "' class='btn btn-sm btn-primary'>Edit</a>
                                <a href='hapus.php?id=" . $row['id'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Hapus data ini?\")'>Hapus</a>
                              </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>