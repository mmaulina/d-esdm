<?php
session_start(); // Mulai sesi jika belum dimulai
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="#">Dinas Energi dan Sumber Daya Mineral</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="perusahaan/tampil.php">Profile</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Settings</a></li>
                <?php if (isset($_SESSION['id_user'])): ?>
                    <li class="nav-item"><a class="nav-link text-white" href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link text-white" href="login/login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
