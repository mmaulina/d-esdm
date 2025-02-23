<?php
include '../koneksi.php';
// session_start();
// include '../koneksi.php';

// // Pastikan user sudah login
// if (!isset($_SESSION['user_id'])) {
//     header("Location: login/login.php");
//     exit();
// }

// $user_id = $_SESSION['user_id']; // Ambil ID pengguna dari sesi
// $sql = "SELECT * FROM profil WHERE user_id = '$user_id'"; // Filter berdasarkan user_id
// $result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Perusahaan</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">Dinas ESDM</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="profil_perusahaan.php">Profile</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Settings</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="login/login.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <h2>Profil Perusahaan</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Perusahaan</th>
                    <th>Kabupaten</th>
                    <th>Alamat</th>
                    <th>Tenaga Teknis</th>
                    <th>Kontak Person</th>
                    <th>Nama Direktur</th>
                    <th>Kontak Direktur</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM profil";
                $result = $conn->query($sql);
                $no = 1;
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $no++ . "</td>";
                    echo "<td>" . $row['nama_perusahaan'] . "</td>";
                    echo "<td>" . $row['kabupaten'] . "</td>";
                    echo "<td>" . $row['alamat'] . "</td>";
                    echo "<td>" . $row['tenaga_teknis'] . "</td>";
                    echo "<td>" . $row['kontak_person'] . "</td>";
                    echo "<td>" . $row['nama_direktur'] . "</td>";
                    echo "<td>" . $row['kontak_direktur'] . "</td>";
                    echo "<td>
                            <a href='edit_profil.php?id=" . $row['id'] . "' class='btn btn-warning btn-sm'>Edit</a>
                            <form method='POST' style='display:inline;' onsubmit='return confirm(\"Apakah Anda yakin ingin menghapus?\")'>
                                <input type='hidden' name='hapus_id' value='" . $row['id'] . "'>
                                <button type='submit' class='btn btn-danger btn-sm'>Hapus</button>
                            </form>
                          </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
