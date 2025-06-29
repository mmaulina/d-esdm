<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
?>
<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>E-WASDAL GATRIK</title>
  <link rel="icon" href="assets/img/kalsel.png" type="image/png">
  <!-- Bootstrap CSS -->
  <link href="assets/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
  .navbar {
    background: linear-gradient(90deg, #000000 0%, #1c1c1c 50%, #000000 100%);
    box-shadow: 0 4px 6px rgba(255, 255, 0, 0.2);
    border-bottom: 4px solid #ffd700;
    /* garis bawah kuning listrik */
  }

  .navbar-brand strong {
    font-size: 1.4rem;
    color: rgb(255, 255, 255);
    /* Kuning terang */
    text-shadow: 1px 1px 2px #fff200;
    /* Efek kilat */
  }

  .navbar-brand img {
    filter: drop-shadow(0 0 4px #ffeb3b);
    /* Efek neon ringan pada logo */
  }

  .navbar .nav-link {
    color: #f0f0f0 !important;
    /* Abu terang */
    font-weight: 500;
    transition: color 0.3s, text-shadow 0.3s;
  }

  .navbar .nav-link:hover {
    color: #fff700 !important;
    /* Kuning terang saat hover */
    text-shadow: 0 0 8px #fff200;
    /* Efek menyala */
  }

  .navbar .fa-bell,
  .navbar .fa-user {
    color: rgb(255, 255, 255) !important;
  }

  .badge.bg-danger {
    background-color: #ff3b3b !important;
    box-shadow: 0 0 5px #ff0000;
  }

  .alert {
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 16px;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .alert-success {
    background-color: #d4edda;
    color: #155724;
    border-left: 5px solid #28a745;
  }

  .alert-error {
    background-color: #f8d7da;
    color: #721c24;
    border-left: 5px solid #dc3545;
  }

  .alert i {
    font-size: 20px;
  }
</style>

<script>
  setTimeout(() => {
    const alertBox = document.querySelector('.alert');
    if (alertBox) {
      alertBox.style.transition = 'opacity 0.5s';
      alertBox.style.opacity = '0';
      setTimeout(() => alertBox.remove(), 500);
    }
  }, 3000);
</script>



<body>
  <nav class="navbar navbar-expand-lg navbar-dark px-3 w-100" style="background-color: #008B47;">
    <div class="container-fluid">
      <a class="navbar-brand d-flex align-items-center" href="#">
        <img src="assets/img/kalsel.png" alt="Logo Kalsel" class="me-2" style="max-height: 40px;">
        <strong>E-WASDAL GATRIK</strong>
      </a>

      <!-- Navbar Toggle untu mobile -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Menu Navbar -->
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto d-flex align-items-center"> <!-- Menjadikan elemen sejajar -->
          <!-- LONCENG NOTIFIKASI -->
          <li class="nav-item">
            <?php
            $id_user = $_SESSION['id_user']; // Pastikan ada sesi id_user
            $role = $_SESSION['role'];
            include 'koneksi.php';
            $database = new Database();
            $conn = $database->getConnection();

            // Query untuk menghitung jumlah laporan_bulanan yang berstatus 'diajukan'
            $jumlahLapBulananDiajukan = 0; // Default jika role adalah adminsemester

            if ($role != 'adminsemester') {
              // Query untuk menghitung jumlah laporan_bulanan yang berstatus 'diajukan'
              $queryLapBulanan = "SELECT COUNT(*) as total FROM laporan_bulanan WHERE status = 'diajukan'";
              $stmtLapBulanan = $conn->prepare($queryLapBulanan);
              $stmtLapBulanan->execute();
              $resultLapBulanan = $stmtLapBulanan->fetch(PDO::FETCH_ASSOC);
              $jumlahLapBulananDiajukan = $resultLapBulanan['total'];
            }
            $jumlahLapSemesterDiajukan = 0; // Default jika role adalah adminbulanan

            if ($role != 'adminbulanan') {
              // Query untuk menghitung jumlah laporan_semester yang berstatus 'diajukan'
              $queryLapSemester = "SELECT COUNT(*) as total FROM laporan_semester WHERE status = 'diajukan'";
              $stmtLapSemester = $conn->prepare($queryLapSemester);
              $stmtLapSemester->execute();
              $resultLapSemester = $stmtLapSemester->fetch(PDO::FETCH_ASSOC);
              $jumlahLapSemesterDiajukan = $resultLapSemester['total'];
            }


            // Query untuk menghitung jumlah pengguna yang berstatus 'diajukan'
            $queryPengguna = "SELECT COUNT(*) as total FROM users WHERE status = 'diajukan'";
            $stmtPengguna = $conn->prepare($queryPengguna);
            $stmtPengguna->execute();
            $resultPengguna = $stmtPengguna->fetch(PDO::FETCH_ASSOC);
            $jumlahPenggunaDiajukan = $resultPengguna['total'];

            $queryprofil = "SELECT COUNT(*) as total FROM profil WHERE status = 'diajukan'";
            $stmtprofil = $conn->prepare($queryprofil);
            $stmtprofil->execute();
            $resultprofil = $stmtprofil->fetch(PDO::FETCH_ASSOC);
            $jumlahprofilDiajukan = $resultprofil['total'];

            // Total notifikasi yang diajukan
            $totalNotifikasi = $jumlahLapBulananDiajukan + $jumlahLapSemesterDiajukan + $jumlahPenggunaDiajukan + $jumlahprofilDiajukan;
            ?>

            <?php if ($_SESSION['role'] == 'superadmin' || $_SESSION['role'] == 'admin' || $_SESSION['role'] == 'adminbulanan' || $_SESSION['role'] == 'adminsemester') { ?> <!-- hanya superadmin yang bisa mengakses menu ini -->
              <a href="?page=notifikasi" class="nav-link position-relative me-3">
                <i class="fas fa-bell fa-lg"></i>
                <?php if ($totalNotifikasi > 0): ?>
                  <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    <?= $totalNotifikasi; ?>
                    <span class="visually-hidden">unread messages</span>
                  </span>
                <?php endif; ?>
              </a>
            <?php } ?>
          </li>

          <!-- NAMA USER -->
          <li class="nav-item dropdown d-flex align-items-center"> <!-- Tambahkan d-flex align-items-center -->
            <!-- Dropdown User -->
            <a class="nav-link dropdown-toggle" href="#" id="drop2" role="button" data-bs-toggle="dropdown"
              aria-expanded="false">
              <i class="fas fa-user me-1"></i> <?= $_SESSION['username']; ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="drop2">
              <li>
                <a href="?page=pengguna_edit_admin&id_user=<?= $_SESSION['id_user']; ?>" class="dropdown-item">
                  <i class="fas fa-user-circle fs-6"></i> Profil Saya
                </a>
              </li>
              <li>
                <a href="?page=edit_password&id_user=<?= $_SESSION['id_user']; ?>" class="dropdown-item">
                  <i class="fas fa-key fs-6"></i> Ganti Password
                </a>
              </li>
              <li>
                <hr class="dropdown-divider">
              </li>
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