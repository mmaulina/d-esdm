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
        flex-wrap: nowrap; /* Mencegah baris membungkus */
    }

    .sidebar {
        width: 250px; /* Lebar sidebar */
        height: 100vh; /* Tinggi sidebar sesuai dengan tinggi viewport */
        position: sticky; /* Membuat sidebar tetap di posisi saat scroll */
        top: 0; /* Menjaga sidebar tetap di atas saat scroll */
        overflow-y: auto; /* Menambahkan scroll jika konten lebih tinggi dari viewport */
    }

    .main-content {
        flex: 1; /* Membuat konten utama mengambil sisa ruang */
        padding: 20px; /* Menambahkan padding untuk konten */
        background-color: #f8f9fa; /* Warna latar belakang konten */
        overflow-y: auto; /* Menambahkan scroll jika konten lebih tinggi dari viewport */
    }

    @media (max-width: 768px) {
        .sidebar {
            width: 100%; /* Sidebar penuh lebar pada layar kecil */
            height: auto; /* Tinggi otomatis pada layar kecil */
            position: relative; /* Mengubah posisi pada layar kecil */
        }
    }
</style>
<link rel="stylesheet" href="assets/fa/css/all.min.css">
<link rel="stylesheet" href="assets/css/bootstraps.min.css">
<script src="assets/js/bootstrap.min.js"></script>
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
                case "update_profil_admin": include "perusahaan/update_profil_admin.php"; break;
                case "delete_profil": include "perusahaan/delete_profil.php"; break;
                case "delete_profil_admin": include "perusahaan/delete_profil_admin.php"; break;

                // data pembangkit dan data teknis pembangkit
                case "pembangkit_admin": include "pembangkit/tampil_admin.php"; break;
                case "pembangkit": include "pembangkit/tampil.php"; break;
                case "pembangkit_tambah": include "pembangkit/tambah.php"; break;
                case "pembangkit_edit": include "pembangkit/update.php"; break;
                case "pembangkit_edit_admin": include "pembangkit/update_admin.php"; break;
                case "pembangkit_hapus_admin": include "pembangkit/delete_admin.php"; break;
                case "pembangkit_hapus": include "pembangkit/delete.php"; break;

                // laporan perbulan
                case "laporan_perbulan": include "laporan_perbulan/tampil.php"; break;
                case "tambah_laporan_perbulan": include "laporan_perbulan/tambah_laporan.php"; break;
                
                // laporan persemester
                case "laporan_persemester": include "laporan_persemester/tampil.php"; break;
                case "tambah_laporan_persemester": include "laporan_persemester/tambah_laporan.php"; break;
                case "edit_laporan_persemester": include "laporan_persemester/edit_laporan.php"; break;
                case "hapus_laporan_persemester": include "laporan_persemester/hapus_laporan.php"; break;
                case "laporan_persemester_admin": include "laporan_persemester/tampil_admin.php"; break;

                // pengguna
                case "pengguna": include "pengguna/tampil.php"; break;
                case "pengguna_tambah_admin": include "pengguna/tambah_admin.php"; break;
                case "pengguna_edit_admin": include "pengguna/edit_admin.php"; break;
                case "pengguna_hapus_admin": include "pengguna/hapus_admin.php"; break;

                //notiifikasi
                case "notifikasi": include "notif.php"; break;


                // default saat login berhasil
                default: include "dashboard.php"; break;
            }
            ?>
        </div>

    </div> <!-- Tutup row -->
</div> <!-- Tutup container-fluid -->

<?php include "template/footer.php"; ?>