<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>D-ESDM</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid"> <!-- Ubah ke container-fluid agar responsif -->
            <a class="navbar-brand" href="#">Dinas Energi dan Sumber Daya Mineral</a>
            
            <!-- Tombol Toggle untuk Mobile -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Menu Navbar -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="drop2" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="ti ti-user-circle" style="font-size: 24px;"></i> &nbsp; <?php echo $_SESSION['username']; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="drop2">
                            <li>
                                <a href="?page=pengguna_edit&id_user=<?php echo $_SESSION['id_user']; ?>" class="dropdown-item">
                                    <i class="ti ti-user fs-6"></i> Profil Saya
                                </a>
                            </li>
                            <li>
                                <a href="?page=password_edit&id_user=<?php echo $_SESSION['id_user']; ?>" class="dropdown-item">
                                    <i class="ti ti-key fs-6"></i> Ganti Password
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
</body>

</html>
