<?php
include "koneksi.php";

try {
    $db = new Database();
    $conn = $db->getConnection();
    // Query untuk menghitung jumlah perusahaan
    $sql = "SELECT COUNT(*) AS total_perusahaan FROM profil";
    $stmt = $conn->query($sql);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $total_perusahaan = $row['total_perusahaan'] ?? 0;

    // Query untuk menghitung jumlah perusahaan di kota dan kabupaten lainnya
    $kota_kabupaten = [
        'Kota Banjarbaru', 'Kota Banjarmasin', 'Balangan', 'Banjar', 'Barito Kuala',
        'Hulu Sungai Selatan', 'Hulu Sungai Tengah', 'Hulu Sungai Utara', 'Kotabaru',
        'Tabalong', 'Tanah Bumbu', 'Tanah Laut', 'Tapin'
    ];

    $jumlah_per_kota = [];
    $total_kota = 0;

    $sql_kota = "SELECT COUNT(*) AS total FROM profil WHERE kabupaten = ?";
    $stmt_kota = $conn->prepare($sql_kota);

    foreach ($kota_kabupaten as $kota) {
        $stmt_kota->execute([$kota]);
        $row_kota = $stmt_kota->fetch(PDO::FETCH_ASSOC);
        $jumlah_per_kota[$kota] = $row_kota['total'] ?? 0;

        if ($row_kota['total'] > 0) {
            $total_kota++;
        }
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
<main>
    <div class="container">
        <!-- Tambahan Welcome Text -->
        <div class="row">
            <div class="col-12">
                <h2 class="text-center mt-4">Welcome to Dashboard</h2>
                <hr>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card text-black mb-3" style="background-color: #FCDC2A;">
                    <div class="card-body">
                        <h5 class="card-title">Total Perusahaan</h5>
                        <p class="card-text"><?php echo $total_perusahaan; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-black mb-3" style="background-color: #008B47;">
                    <div class="card-body">
                        <h5 class="card-title">Total Kota dengan Perusahaan</h5>
                        <p class="card-text"><?php echo $total_kota; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
