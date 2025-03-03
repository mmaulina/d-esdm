<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>D-ESDM</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">Dinas Energi dan Sumber Daya Mineral</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 16px;">
                            <i class="ti ti-user-circle" style="font-size: 24px;"></i> &nbsp; <?php echo $_SESSION['nama']; ?>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                            <div class="message-body">
                                <a href="?page=pengguna_edit&id_user=<?php echo $_SESSION['id_user']; ?>" class="d-flex align-items-center gap-2 dropdown-item">
                                    <i class="ti ti-user fs-6"></i>
                                    <p class="mb-0 fs-3">Profil Saya</p>
                                </a>
                                <a href="?page=password_edit&id_user=<?php echo $_SESSION['id_user']; ?>" class="d-flex align-items-center gap-2 dropdown-item">
                                    <i class="ti ti-user fs-6"></i>
                                    <p class="mb-0 fs-3">Ganti Password</p>
                                </a>
                                <!-- <a href="?page=anggota_edit" class="d-flex align-items-center gap-2 dropdown-item">
                                    <i class="ti ti-address-book fs-6"></i>
                                    <p class="mb-0 fs-3">Informasi Pribadi</p>
                                </a> -->
                                <a href="login/logout.php" onclick="return confirm('Anda yakin ingin logout?')" class="btn btn-outline-primary mx-3 mt-2 d-block">Logout</a>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>