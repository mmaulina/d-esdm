<?php
session_start();
if (!isset($_SESSION['id_user'])) {
    header('location:login/login.php');
}
include "koneksi.php"; 
include "template/header.php"; 
?>
<style>
    .row {
    display: flex; /* Menggunakan Flexbox untuk baris */
}

.sidebar {
    position: sticky; /* Membuat sidebar tetap di posisi saat scroll */
    top: 0; /* Menjaga sidebar tetap di atas saat scroll */
    height: 100vh; /* Mengatur tinggi sidebar sesuai dengan tinggi viewport */
    overflow-y: auto; /* Menambahkan scroll jika konten lebih tinggi dari viewport */
}

.main-content {
    flex: 1; /* Membuat konten utama mengambil sisa ruang */
    padding: 20px; /* Menambahkan padding untuk konten */
}
</style>
<div class="container-fluid">
    <div class="row"> <!-- Mulai baris untuk sidebar dan konten -->
        
        <!-- Sidebar -->
        <?php include "template/sidebar.php"; ?>

        <!-- Konten utama -->
        <div class="col-md-9 col-lg-10 main-content"> <!-- Lebar konten agar sejajar dengan sidebar -->
            <?php
            $page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
            switch ($page) {
                // perusahaan
                case "profil_perusahaan": include "perusahaan/tampil.php"; break;
                case "profil_admin": include "perusahaan/tampil_admin.php"; break;
                case "tambah_profil": include "perusahaan/tambah_profil.php"; break;
                case "update_profil": include "perusahaan/update_profil.php"; break;
                case "delete_profil": include "perusahaan/delete_profil.php"; break;

                // data pembangkit dan data teknis pembangkit
                case "pembangkit_admin": include "pembangkit/tampil_admin.php"; break;

                // default saat login berhasil
                default: include "dashboard.php"; break;
            }
            ?>
        </div>

    </div> <!-- Tutup row -->
</div> <!-- Tutup container-fluid -->

<?php include "template/footer.php"; ?>