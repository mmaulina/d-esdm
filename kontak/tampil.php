<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Pastikan pengguna sudah login
if (!isset($_SESSION['id_user'])) {
    echo "<script>alert('Anda harus login terlebih dahulu!'); window.location.href='login/login.php';</script>";
    exit();
}

$id_user = $_SESSION['id_user'];

$database = new Database();
$pdo = $database->getConnection();

// Ambil email & nomor HP dari id_user = 1
$sql = "SELECT email, no_hp FROM users WHERE id_user = 1";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$kontak = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-body">
            <h2>Kontak Admin</h2>
            <table class="table table-bordered">
                <tr>
                    <th>Email</th>
                    <td>
                        <?php 
                        if ($kontak && !empty($kontak['email'])) {
                            $email = htmlspecialchars($kontak['email']);
                            echo '<a href="mailto:' . $email . '" class="btn btn-success">
                                    <i class="fa-solid fa-envelope"></i> ' . $email . '
                                  </a>';
                        } else { 
                            echo "Email tidak ditemukan."; 
                        } 
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Nomor HP / WhatsApp</th>
                    <td>
                        <?php 
                        if ($kontak && !empty($kontak['no_hp'])) { 
                            // Format nomor HP agar sesuai dengan format internasional (tanpa angka 0 di awal)
                            $nomor_hp = preg_replace('/[^0-9]/', '', $kontak['no_hp']); // Hanya angka
                            if (substr($nomor_hp, 0, 1) == "0") {
                                $nomor_hp = "62" . substr($nomor_hp, 1); // Ganti "0" di awal dengan "62"
                            }

                            $wa_link = "https://wa.me/" . $nomor_hp;
                            echo '<a href="' . htmlspecialchars($wa_link) . '" target="_blank" class="btn btn-success">
                                    <i class="fa-brands fa-whatsapp"></i> ' . htmlspecialchars($kontak['no_hp']) . '
                                  </a>';
                        } else { 
                            echo "Nomor HP tidak ditemukan."; 
                        } 
                        ?>
                    </td>
                </tr>
            </table>

            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'superadmin') { ?> 
                <a href="?page=update_kontak" class="btn btn-warning">
                    <i class="fa-solid fa-pen-to-square"></i> Update Kontak
                </a>
            <?php } ?>
        </div>
    </div>
</div>

<!-- Tambahkan Font Awesome untuk ikon -->
