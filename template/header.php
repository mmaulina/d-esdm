<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>D-ESDM</title>

    <!-- Bootstrap CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-dark px-3" style="background-color: #008B47;">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="assets/img/kalsel.png" alt="Logo Kalsel" class="me-2">
                <strong>Dinas Energi dan Sumber Daya Mineral</strong>
            </a>

            <!-- Tombol Toggle untuk Mobile -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Menu Navbar -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="drop2" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            &nbsp; <?php echo $_SESSION['username']; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="drop2">
                            <li>
                                <a href="?page=profil_perusahaan&id_user=<?php echo $_SESSION['id_user']; ?>" class="dropdown-item">
                                    <i class="ti ti-user fs-6"></i> Profil Saya
                                </a>
                            </li>
                            <li>
                                <a href="?page=password_edit&id_user=<?php echo $_SESSION['id_user']; ?>" class="dropdown-item">
                                    <i class="ti ti-key fs-6"></i> Ganti Password
                                </a>
                            </li>
                            <li>
                                <a href="?page=tampil_admin&id_user=<?php echo $_SESSION['id_user']; ?>" class="dropdown-item">
                                    <i class="ti ti-key fs-6"></i> Data Admin
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a href="login/logout.php" onclick="return confirm('Anda yakin ingin logout?')" class="dropdown-item text-danger">
                                    Logout
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>