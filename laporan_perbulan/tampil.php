<div class="container mt-4">
    <h3 class="text-center mb-3">Laporan Bulanan</h3>
    <hr>
    <div class="card shadow">
        <div class="card-body">
            <div class="mb-3">
                <a href="?page=tambah_laporan" class="btn btn-primary">Tambah Data</a>
            </div>
            <div class="table-responsive" style="max-height: 500px; overflow-x: auto; overflow-y: auto;">
                <table class="table table-bordered" style="min-width: 1200px; white-space: nowrap;">
                    <thead class="table-dark text-center align-middle">
                        <tr>
                            <th rowspan="2">No.</th>
                            <th rowspan="2">Bulan</th>
                            <th rowspan="2">Nama Perusahaan</th>
                            <th rowspan="2">Volume Bahan Bakar</th>
                            <th colspan="2">Produksi Listrik</th>
                            <th rowspan="2">Susut Jaringan (bila ada) (kWh)</th>
                            <th colspan="3">Konsumsi Listrik</th>
                            <th rowspan="2">Aksi</th>
                        </tr>
                        <tr>
                            <th>Produksi Sendiri (kWh)</th>
                            <th>Pembelian Sumber Lain (bila ada) (kWh)</th>
                            <th>Penjualan ke Pelanggan (bila ada) (kWh)</th>
                            <th>Penjualan ke PLN (bila ada) (kWh)</th>
                            <th>Pemakaian Sendiri (kWh)</th>
                        </tr>
                    </thead>

                    <!-- TOLONG PERBAIKI INI YA WAN -->
                    <tbody>
                        <?php
                        include 'koneksi.php';
                        $query = "SELECT * FROM lapor_bulan ORDER BY id_profil ASC";
                        $result = mysqli_query($conn, $query);

                        if (mysqli_num_rows($result) > 0) {
                            $no = 1;
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>" . $no . "</td>";
                                echo "<td>" . htmlspecialchars($row['nama_perusahaan']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['kabupaten']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['alamat']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['jenis_usaha']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['no_telp_kantor']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['no_fax']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['tenaga_teknik']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['no_hp']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                echo "<td>
                                
                                <a href='?page=update_profil&id_profil=" . htmlspecialchars($row['id_profil']) . "' class='btn btn-warning btn-sm'>Edit</a>
                                <a href='?page=delete_profil&id_profil=" . htmlspecialchars($row['id_profil']) . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Yakin ingin menghapus data ini?\")'>Hapus</a>
                              </td>";
                                echo "</tr>";
                                $no++;
                            }
                        } else {
                            echo "<tr><td colspan='11' class='text-center'>Data tidak ditemukan</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>