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

// Query untuk menghitung jumlah perusahaan di Kota Banjarbaru
$sql_banjarbaru = "SELECT COUNT(*) AS total_banjarbaru FROM profil WHERE kabupaten = 'Kota Banjarbaru'";
$result_banjarbaru = $conn->query($sql_banjarbaru);

$total_banjarbaru = 0; // Inisialisasi variabel

if ($result_banjarbaru->num_rows > 0) {
    // Ambil data dari hasil query
    $row_banjarbaru = $result_banjarbaru->fetch_assoc();
    $total_banjarbaru = $row_banjarbaru['total_banjarbaru'];
}

// Tutup koneksi
$conn->close();
?>
<main>
    <div class="col-md-6">
        <div class="card text-black mb-3" style="background-color: #FCDC2A;">
            <div class="card-body">
                <h5 class="card-title">Total Perusahaan</h5>
                <p class="card-text"><?php echo $total_perusahaan; ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card text-black mb-3" style="background-color: #6BB64A;">
            <div class="card-body">
                <h5 class="card-title">Kota Banjarbaru</h5>
                <p class="card-text"><?php echo $total_banjarbaru; ?></p>
            </div>
        </div>
    </div>
            <div class="col-md-6">
                <div class="card text-black mb-3" style="background-color: #6BB64A;">
                    <div class="card-body">
                        <h5 class="card-title">Kota Banjarmasin</h5>
                        <p class="card-text">10</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-black mb-3" style="background-color: #6BB64A;">
                    <div class="card-body">
                        <h5 class="card-title">Balangan</h5>
                        <p class="card-text">10</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-black mb-3" style="background-color: #6BB64A;">
                    <div class="card-body">
                        <h5 class="card-title">Banjar</h5>
                        <p class="card-text">10</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-black mb-3" style="background-color: #6BB64A;">
                    <div class="card-body">
                        <h5 class="card-title">Barito Kuala</h5>
                        <p class="card-text">10</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-black mb-3" style="background-color: #6BB64A;">
                    <div class="card-body">
                        <h5 class="card-title">Hulu Sungai Selatan</h5>
                        <p class="card-text">10</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-black mb-3" style="background-color: #6BB64A;">
                    <div class="card-body">
                        <h5 class="card-title">Hulu Sungai Tengah</h5>
                        <p class="card-text">10</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-black mb-3" style="background-color: #6BB64A;">
                    <div class="card-body">
                        <h5 class="card-title">Hulus Sungai Utara</h5>
                        <p class="card-text">10</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-black mb-3" style="background-color: #6BB64A;">
                    <div class="card-body">
                        <h5 class="card-title">Kotabaru</h5>
                        <p class="card-text">10</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-black mb-3" style="background-color: #6BB64A;">
                    <div class="card-body">
                        <h5 class="card-title">Tabalong</h5>
                        <p class="card-text">10</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-black mb-3" style="background-color: #6BB64A;">
                    <div class="card-body">
                        <h5 class="card-title">Tanah Bumbu</h5>
                        <p class="card-text">10</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-black mb-3" style="background-color: #6BB64A;">
                    <div class="card-body">
                        <h5 class="card-title">Tanah Laut</h5>
                        <p class="card-text">10</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-black mb-3" style="background-color: #6BB64A;">
                    <div class="card-body">
                        <h5 class="card-title">Tapin</h5>
                        <p class="card-text">10</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>