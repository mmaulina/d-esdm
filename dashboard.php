<?php
include "koneksi.php";

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query untuk menghitung jumlah perusahaan
$sql = "SELECT COUNT(*) AS total_perusahaan FROM profil";
$result = $conn->query($sql);

$total_perusahaan = 0; // Inisialisasi variabel

if ($result->num_rows > 0) {
    // Ambil data dari hasil query
    $row = $result->fetch_assoc();
    $total_perusahaan = $row['total_perusahaan'];
}

// Query untuk menghitung jumlah perusahaan di kota dan kabupaten lainnya
$kota_kabupaten = [
    'Kota Banjarbaru',
    'Kota Banjarmasin',
    'Balangan',
    'Banjar',
    'Barito Kuala',
    'Hulu Sungai Selatan',
    'Hulu Sungai Tengah',
    'Hulu Sungai Utara',
    'Kotabaru',
    'Tabalong',
    'Tanah Bumbu',
    'Tanah Laut',
    'Tapin'
];

$jumlah_per_kota = [];
$total_kota = 0; // Inisialisasi variabel untuk menghitung kota yang memiliki perusahaan

foreach ($kota_kabupaten as $kota) {
    $sql_kota = "SELECT COUNT(*) AS total FROM profil WHERE kabupaten = ?";
    $stmt = $conn->prepare($sql_kota);
    $stmt->bind_param("s", $kota);
    $stmt->execute();
    $result_kota = $stmt->get_result();
    $jumlah_per_kota[$kota] = 0; // Inisialisasi variabel

    if ($result_kota->num_rows > 0) {
        $row_kota = $result_kota->fetch_assoc();
        $jumlah_per_kota[$kota] = $row_kota['total'];

        // Jika ada perusahaan di kota tersebut, tambahkan ke total kota
        if ($row_kota['total'] > 0) {
            $total_kota++;
        }
    }
    $stmt->close();
}

// Tutup koneksi
$conn->close();
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
                        <h5 class="card-title">Total Apa Hayo wkwk</h5>
                        <p class="card-text"><?php echo $total_kota; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
