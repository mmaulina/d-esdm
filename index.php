<?php
session_start();
if (!isset($_SESSION['id_user'])) {
    header('location:login/login.php');
}
include "koneksi.php"; 
include "template/header.php"; 
?>

<div class="container-fluid">
    <div class="row"> <!-- Mulai baris untuk sidebar dan konten -->
        
        <!-- Sidebar -->
        <?php include "template/sidebar.php"; ?>

        <!-- Konten utama -->
        <div class="col-md-9 col-lg-10 p-4"> <!-- Lebar konten agar sejajar dengan sidebar -->
            <?php
            $page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
            switch ($page) {
                case "profil_perusahaan": include "perusahaan/tampil.php"; break;
                case "tambah_profil": include "perusahaan/tambah_profil.php"; break;
                
                default: include "dashboard.php"; break;
            }
            ?>
        </div>

    </div> <!-- Tutup row -->
</div> <!-- Tutup container-fluid -->

<?php include "template/footer.php"; ?>
