<?php
$currentPage = $_GET['page'] ?? 'dashboard'; // Ambil halaman dari URL
?>

<aside class="col-md-3 col-lg-2 d-none d-md-block sidebar p-3 vh-100 d-flex flex-column">
    <ul class="nav flex-column flex-grow-1">
        <li class="nav-item">
            <a class="nav-link <?= ($currentPage == 'dashboard') ? 'active' : ''; ?>" href="?page=dashboard">Home</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($currentPage == 'profil_perusahaan') ? 'active' : ''; ?>" href="?page=profil_perusahaan">Profile</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($currentPage == 'settings') ? 'active' : ''; ?>" href="?page=settings">Settings</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-danger" href="logout.php">Logout</a>
        </li>
    </ul>
</aside>