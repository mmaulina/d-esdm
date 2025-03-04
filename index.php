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
                case "kategori_read": include "kategori/view_data.php"; break;
                case "kategori_add": include "kategori/kategori_add.php"; break;
                case "kategori_edit": include "kategori/kategori_edit.php"; break;
                case "kategori_delete": include "kategori/kategori_delete.php"; break;
                case "peringkat_read": include "peringkat/view_data.php"; break;
                case "peringkat_add": include "peringkat/peringkat_add.php"; break;
                case "peringkat_edit": include "peringkat/peringkat_edit.php"; break;
                case "peringkat_delete": include "peringkat/peringkat_delete.php"; break;
                case "pengguna_add": include "pengguna/pengguna_add.php"; break;
                case "pengguna_read": include "pengguna/view_data.php"; break;
                case "verifikasi_akun": include "pengguna/proses_verifikasi.php"; break;
                case "prestasi_read": include "prestasi/prestasi_read.php"; break;
                case "prestasi_add": include "prestasi/prestasi_add.php"; break;
                case "verifikasi_prestasi": include "prestasi/proses_verifikasi.php"; break;
                default: include "dashboard.php"; break;
            }
            ?>
        </div>

    </div> <!-- Tutup row -->
</div> <!-- Tutup container-fluid -->

<?php include "template/footer.php"; ?>
