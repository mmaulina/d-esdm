<div class="container mt-4">
    <h3 class="text-center mb-3">Data Profil Perusahaan</h3>
    <hr>
    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive" style="max-height: 500px; overflow-x: auto; overflow-y: auto;">
                <table class="table table-bordered" style="min-width: 1200px; white-space: nowrap;">
                    <thead class="table-dark text-center align-middle">
                        <tr>
                            <th rowspan="2">No.</th>
                            <th rowspan="2">Nama Perusahaan</th>
                            <th rowspan="2">Kabupaten/Kota</th>
                            <th rowspan="2">Alamat</th>
                            <th rowspan="2">Jenis Usaha</th>
                            <th rowspan="2">Nomor Telepon Kantor</th>
                            <th rowspan="2">Tenaga Teknik</th>
                            <th colspan="3">Kontak Person</th>
                            <th rowspan="2">Aksi</th>
                        </tr>
                        <tr>
                            <th>Nama</th>
                            <th>No. HP</th>
                            <th>Email</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        include 'koneksi.php';
                        $query = "SELECT * FROM profil ORDER BY id_profil ASC";
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
                                echo "<td>" . htmlspecialchars($row['tenaga_teknik']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['no_hp']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                echo "<td>
                                <a href='edit.php?id=" . $row['id_profil'] . "' class='btn btn-sm btn-primary'>Edit</a>
                                <a href='hapus.php?id=" . $row['id_profil'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Hapus data ini?\")'>Hapus</a>
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