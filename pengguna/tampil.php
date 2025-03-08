<div class="container mt-4">
    <h3 class="text-center mb-3">Data Pengguna</h3>
    <hr>
    <div class="card shadow">
        <div class="card-body">
            <table class="table table-striped table-bordered">
                <thead class="table-dark text-white">
                    <tr>
                        <th>No.</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT id_user, username, email, role, status FROM users";
                    $result = $conn->query($sql);
                    $no = 1; // Inisialisasi nomor

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$no}</td>
                                    <td>" . htmlspecialchars($row['username']) . "</td>
                                    <td>" . htmlspecialchars($row['email']) . "</td>
                                    <td>" . htmlspecialchars($row['role']) . "</td>
                                    <td>" . htmlspecialchars($row['status']) . "</td>
                                    <td>
                                        <a href='edit_user.php?id=" . htmlspecialchars($row['id_user']) . "' class='btn btn-warning btn-sm'>Edit</a>
                                        <a href='hapus_user.php?id=" . htmlspecialchars($row['id_user']) . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Yakin ingin menghapus?\")'>Hapus</a>
                                    </td>
                                  </tr>";
                            $no++; // Menambah nomor setiap iterasi
                        }
                    } else {
                        echo "<tr><td colspan='5' class='text-center'>Tidak ada data pengguna</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>