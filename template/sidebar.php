<?php
$currentPage = $_GET['page'] ?? 'dashboard'; // Ambil halaman dari URL
?>

<aside class="col-md-3 col-lg-2 d-none d-md-block sidebar p-3 vh-100 d-flex flex-column">
    <ul class="nav flex-column flex-grow-1 mt-2">
        <li class="nav-item">
            <a class="nav-link <?= ($currentPage == 'dashboard') ? 'active' : ''; ?>" href="?page=dashboard">Home</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($currentPage == 'profil_perusahaan') ? 'active' : ''; ?>" href="?page=profil_perusahaan">Profile Perusahaan</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($currentPage == 'profil_admin') ? 'active' : ''; ?>" href="?page=profil_admin">Profile Perusahaan (A)</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($currentPage == 'pembangkit') ? 'active' : ''; ?>" href="?page=pembangkit">Isi Pembangkit</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($currentPage == 'pembangkit_admin') ? 'active' : ''; ?>" href="?page=pembangkit_admin">Data Pembangkit (A)</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($currentPage == 'laporan_semester') ? 'active' : ''; ?>" href="?page=laporan_semester">Laporan Persemester</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($currentPage == 'laporan_bulanan') ? 'active' : ''; ?>" href="?page=laporan_bulanan">Laporan Perbulan</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($currentPage == 'pengguna') ? 'active' : ''; ?>" href="?page=pengguna">Data Pengguna</a>
        </li>
    </ul>
</aside>