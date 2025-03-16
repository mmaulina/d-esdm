<?php
$currentPage = $_GET['page'] ?? 'dashboard'; // Ambil halaman dari URL
?>

<aside class="col-md-3 col-lg-2 d-none d-md-block sidebar p-3 vh-100 d-flex flex-column">
    <ul class="nav flex-column flex-grow-1 mt-2">
        <li class="nav-item">
            <a class="nav-link <?= ($currentPage == 'dashboard') ? 'active' : ''; ?>" href="?page=dashboard">Beranda</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($currentPage == 'profil_perusahaan') ? 'active' : ''; ?>" href="?page=profil_perusahaan">Profile Perusahaan</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($currentPage == 'profil_admin') ? 'active' : ''; ?>" href="?page=profil_admin">Profile Perusahaan (A)</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($currentPage == 'pembangkit') ? 'active' : ''; ?>" href="?page=pembangkit">Data Pembangkit</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($currentPage == 'pembangkit_admin') ? 'active' : ''; ?>" href="?page=pembangkit_admin">Data Pembangkit (A)</a>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-toggle="collapse" href="#submenuPelaporan" role="button" aria-expanded="false" aria-controls="submenuPelaporan">
                Pelaporan
            </a>
            <div class="collapse" id="submenuPelaporan">
                <ul class="nav flex-column ms-3">
                    <li class="nav-item">
                        <a class="nav-link <?= ($currentPage == 'laporan_perbulan') ? 'active' : ''; ?>" href="?page=laporan_perbulan">Laporan Perbulan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($currentPage == 'laporan_perbulan_admin') ? 'active' : ''; ?>" href="?page=laporan_perbulan_admin">Laporan Perbulan (A)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($currentPage == 'laporan_persemester') ? 'active' : ''; ?>" href="?page=laporan_persemester">Laporan Persemester</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($currentPage == 'laporan_persemester_admin') ? 'active' : ''; ?>" href="?page=laporan_persemester_admin">Laporan Persemester (A)</a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($currentPage == 'djih') ? 'active' : ''; ?>" href="?page=djih">DJIH</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($currentPage == 'pengguna') ? 'active' : ''; ?>" href="?page=pengguna">Data Pengguna</a>
        </li>
    </ul>
</aside>
