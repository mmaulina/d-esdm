<?php
// localhost/simtawa-app
    session_start();
    if(!isset($_SESSION['user_id'])){ //cek session id apakah belum di set
        header('location:login/login.php'); //kalau belum, halaman diarahkan ke login_view.php
    }
    include "koneksi.php"; //memanggil file koneksi
    include "template/sidebar.php"; //memanggil file sidebar
    include "template/header.php"; //memanggil file header
    $page = $_GET['page']; //mengambil nilai dari url pada variabel page
    switch($page){
        // kategori_prestasi
        case "kategori_read": include "kategori/view_data.php"; break;
        case "kategori_add": include "kategori/kategori_add.php"; break;
        case "kategori_edit": include "kategori/kategori_edit.php"; break;
        case "kategori_delete": include "kategori/kategori_delete.php"; break;
        // peringkat
        case "peringkat_read": include "peringkat/view_data.php"; break;
        case "peringkat_add": include "peringkat/peringkat_add.php"; break;
        case "peringkat_edit": include "peringkat/peringkat_edit.php"; break;
        case "peringkat_delete": include "peringkat/peringkat_delete.php"; break;
        // pengguna
        case "pengguna_add": include "pengguna/pengguna_add.php"; break;
        case "pengguna_read": include "pengguna/view_data.php"; break;
        case "verifikasi_akun": include "pengguna/proses_verifikasi.php"; break;
        // prestasi
        case "prestasi_read": include "prestasi/prestasi_read.php"; break;
        case "prestasi_add": include "prestasi/prestasi_add.php"; break;
        case "verifikasi_prestasi": include "prestasi/proses_verifikasi.php"; break;

    }
    include "template/footer.php";
?>