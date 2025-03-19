<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIP2 GATRIK</title>

    <!-- Bootstrap CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <style>
        body {
            padding-top: 60px; /* Untuk mencegah konten tertutup navbar */
        }
        .navbar {
            width: 100%;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1030;
        }
        .nav-item .fa-bell {
            font-size: 1.2rem; /* Sesuaikan ukuran lonceng */
        }
        .nav-item .badge {
            font-size: 0.7rem; /* Ukuran badge notifikasi */
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark px-3 w-100" style="background-color: #008B47;">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="assets/img/kalsel.png" alt="Logo Kalsel" class="me-2" style="max-height: 40px;">
                <strong>SIP2 GATRIK</strong>
            </a>

            <!-- Navbar Toggle untu mobile -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Menu Navbar -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto d-flex align-items-center"> <!-- Menjadikan elemen sejajar -->
                    <li class="nav-item">
                        <?php
                        include 'koneksi.php';
                        $database = new Database();
                        $conn = $database->getConnection();

                        $query = "SELECT COUNT(*) as total FROM laporan_semester WHERE status = 'diajukan'";
                        $stmt = $conn->prepare($query);
                        $stmt->execute();
                        $result = $stmt->fetch(PDO::FETCH_ASSOC);
                        $jumlahLaporanDiajukan = $result['total'];
                        ?>
                        <a href="?page=notifikasi" class="nav-link position-relative me-3">
                            <i class="fas fa-bell fa-lg"></i>
                            <?php if ($jumlahLaporanDiajukan > 0): ?>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    <?= $jumlahLaporanDiajukan; ?>
                                    <span class="visually-hidden">unread messages</span>
                                </span>
                            <?php endif; ?>
                        </a>
                    </li>
                    
                    <li class="nav-item dropdown d-flex align-items-center"> <!-- Tambahkan d-flex align-items-center -->
                    <!-- Dropdown User -->
                    <a class="nav-link dropdown-toggle" href="#" id="drop2" role="button" data-bs-toggle="dropdown" 
                        aria-expanded="false">
                        <i class="fas fa-user me-1"></i> <?= $_SESSION['username']; ?>
                    </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="drop2">
                            <li>
                                <a href="?page=profil_perusahaan&id_user=<?= $_SESSION['id_user']; ?>" class="dropdown-item">
                                    <i class="fas fa-user-circle fs-6"></i> Profil Saya
                                </a>
                            </li>
                            <li>
                                <a href="?page=password_edit&id_user=<?= $_SESSION['id_user']; ?>" class="dropdown-item">
                                    <i class="fas fa-key fs-6"></i> Ganti Password
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a href="login/logout.php" onclick="return confirm('Anda yakin ingin logout?')" 
                                    class="dropdown-item text-danger">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Bootstrap JS -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
