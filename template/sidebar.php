<?php
$currentPage = $_GET['page'] ?? 'dashboard'; // Ambil halaman dari URL
$id_user = $_SESSION['id_user']; // Pastikan ada sesi id_user

// Cek jumlah konten baru yang belum dilihat oleh user ini
$query = "SELECT COUNT(*) AS jumlah_baru FROM news WHERE id NOT IN 
          (SELECT konten_id FROM konten_dilihat WHERE id_user = :id_user)";
$stmt = $conn->prepare($query);
$stmt->execute(['id_user' => $id_user]);
$konten_baru = $stmt->fetch(PDO::FETCH_ASSOC)['jumlah_baru'];

$query_djih = "SELECT COUNT(*) AS jumlah_baru FROM djih WHERE id NOT IN 
          (SELECT konten_id FROM djih_dilihat WHERE id_user = :id_user)";
$stmt = $conn->prepare($query_djih);
$stmt->execute(['id_user' => $id_user]);
$konten_djih = $stmt->fetch(PDO::FETCH_ASSOC)['jumlah_baru'];
?>
<div class="container-fluid">
    <div class="row"> <!-- Mulai baris untuk sidebar dan konten -->
        <aside class="sidebar col-md-3 col-lg-2 sidebar p-3 vh-100 d-flex flex-column" id="sidebar">
            <div class="sidebar-header d-flex align-items-center gap-2 mb-4">
                <button class="btn btn-dark toggle-sidebar" id="toggleSidebar">
                    <i class="fas fa-bars"></i>
                </button>
            </div>

            <div class="user-card">
                <div class="d-flex align-items-center">
                    <div class="user-icon">
                        <i class="fas fa-user fs-5"></i>
                    </div>
                    <span class="user-name"><?php echo $_SESSION['username']; ?></span>
                </div>
            </div>

            <ul class="nav flex-column flex-grow-1 mt-2">
                <li class="nav-item">
                    <a class="nav-link <?= ($currentPage == 'dashboard') ? 'active' : ''; ?>" href="?page=dashboard">
                        <i class="fas fa-home me-2"></i>
                        <span class="sidebar-text">Beranda</span>
                        <?php if ($konten_baru > 0) : ?>
                            <span class="badge bg-danger ms-2"><?= $konten_baru; ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <?php if ($_SESSION['role'] == 'umum') { ?> <!-- hanya umum yang bisa mengakses menu ini -->
                    <li class="nav-item">
                        <a class="nav-link <?= ($currentPage == 'profil_perusahaan') ? 'active' : ''; ?>" href="?page=profil_perusahaan">
                            <i class="fas fa-building me-2"></i> <span class="sidebar-text">Profile Perusahaan</span>
                        </a>
                    </li>
                <?php } ?>
                <?php if ($_SESSION['role'] == 'superadmin'|| $_SESSION['role'] == 'admin'||$_SESSION['role'] == 'adminbulanan'||$_SESSION['role'] == 'adminsemester') { ?> 
                    <li class="nav-item">
                        <a class="nav-link <?= ($currentPage == 'profil_admin') ? 'active' : ''; ?>" href="?page=profil_admin">
                            <i class="fas fa-user-tie me-2"></i> <span class="sidebar-text">Profile Perusahaan</span>
                        </a>
                    </li>
                <?php } ?>
                <!-- <li class="nav-item">
                    <a class="nav-link <?= ($currentPage == 'pembangkit') ? 'active' : ''; ?>" href="?page=pembangkit">
                        <i class="fas fa-plug me-2"></i> <span class="sidebar-text">Data Pembangkit</span>
                    </a>
                </li> -->
                <?php if ( $_SESSION['role'] !== 'kementerian') { ?>
                <li class="nav-item">
                    <a class="nav-link collapsed" data-bs-toggle="collapse" href="#submenuPelaporan" role="button" aria-expanded="false" aria-controls="submenuPelaporan">
                        <i class="fas fa-file-alt me-2"></i> <span class="sidebar-text">Pelaporan</span>
                    </a>
                    <div class="collapse" id="submenuPelaporan">
                        <ul class="nav flex-column ms-3">
                        <?php if ( $_SESSION['role'] !== 'adminsemester') { ?> 
                            <li class="nav-item">
                                <a class="nav-link <?= ($currentPage == 'laporan_perbulan') ? 'active' : ''; ?>" href="?page=laporan_perbulan">
                                    <i class="fas fa-calendar-alt me-2"></i> <span class="sidebar-text">Pelaporan Bulanan</span>
                                </a>
                            </li>
                            <?php } ?>
                            <?php if ( $_SESSION['role'] !== 'adminbulanan') { ?>
                            <li class="nav-item">
                                <a class="nav-link <?= ($currentPage == 'laporan_persemester') ? 'active' : ''; ?>" href="?page=laporan_persemester">
                                    <i class="fas fa-calendar me-2"></i> <span class="sidebar-text">Pelaporan Semester</span>
                                </a>
                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                </li>
                <?php } ?>
                <li class="nav-item">
                    <a class="nav-link <?= ($currentPage == 'tampil_konten_djih') ? 'active' : ''; ?>" href="?page=tampil_konten_djih">
                        <i class="fas fa-earth-americas me-2"></i>
                        <span class="sidebar-text">JDIH</span>
                        <?php if ($konten_djih > 0) : ?>
                            <span class="badge bg-danger ms-2"><?= $konten_djih; ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($currentPage == 'kontak') ? 'active' : ''; ?>" href="?page=kontak">
                        <i class="fas fa-solid fa-address-book me-2"></i> <span class="sidebar-text">Kontak</span>
                    </a>
                </li>
                <?php if ($_SESSION['role'] == 'superadmin'||$_SESSION['role'] == 'admin'||$_SESSION['role'] == 'adminbulanan'||$_SESSION['role'] == 'adminsemester') { ?> <!-- hanya superadmin yang bisa mengakses menu ini -->
                    <li class="nav-item">
                        <a class="nav-link <?= ($currentPage == 'pengguna') ? 'active' : ''; ?>" href="?page=pengguna">
                            <i class="fas fa-users me-2"></i> <span class="sidebar-text">Data Pengguna</span>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </aside>

        <script>
            // Function to check window size and collapse sidebar if necessary
            function checkWindowSize() {
                var sidebar = document.getElementById('sidebar');
                if (window.innerWidth < 768) { // Adjust the threshold as needed
                    sidebar.classList.add('collapsed'); // Collapse the sidebar
                    localStorage.setItem('sidebarCollapsed', 'true'); // Update local storage
                } else {
                    // If the sidebar is not collapsed, ensure it is expanded
                    if (localStorage.getItem('sidebarCollapsed') !== 'true') {
                        sidebar.classList.remove('collapsed'); // Expand the sidebar
                    }
                }
            }

            // Check local storage for sidebar state on page load
            if (localStorage.getItem('sidebarCollapsed') === 'true') {
                document.getElementById('sidebar').classList.add('collapsed');
            }

            // Event listener for the toggle button
            document.getElementById('toggleSidebar').addEventListener('click', function() {
                var sidebar = document.getElementById('sidebar');
                sidebar.classList.toggle('collapsed'); // Toggle kelas collapsed

                // Update local storage
                localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
            });

            // Event listener for window resize
            window.addEventListener('resize', function() {
                checkWindowSize(); // Call the function to check window size on resize
            });

            // Initial check on page load
            checkWindowSize();
        </script>