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
                        require 'koneksi.php';
                        
                        try {
                            $db = new Database();
                            $conn = $db->getConnection();
                            $query = "SELECT * FROM pembangkit";
                            $stmt = $conn->prepare($query);
                            $stmt->execute();
                            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            if (count($result) > 0) {
                                foreach ($result as $row) {
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
                                        <a href='?page=pembangkit_edit_admin&id=" . $row['id'] . "' class='btn btn-sm btn-primary'>Edit</a>
                                        <button class='btn btn-sm btn-danger' onclick='confirmDelete(" . $row['id'] . ")'>Hapus</button>
                                    </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='15' class='text-center'>Data tidak ditemukan</td></tr>";
                            }
                        } catch (PDOException $e) {
                            echo "<tr><td colspan='15' class='text-center'>Gagal mengambil data: " . $e->getMessage() . "</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus data ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                <a href="#" id="confirmDeleteButton" class="btn btn-danger">Yes</a>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
    function confirmDelete(id) {
        document.getElementById('confirmDeleteButton').href = '?page=pembangkit_hapus_admin&id=' + id;
        var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    }
</script>