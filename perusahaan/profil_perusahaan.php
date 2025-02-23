<?php
include 'koneksi.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_perusahaan = $_POST['nama_perusahaan'];
    $kabupaten = $_POST['kabupaten'];
    $alamat = $_POST['alamat'];
    $tenaga_teknis = $_POST['tenaga_teknis'];
    $kontak_person = $_POST['kontak_person'];
    $nama_direktur = $_POST['nama_direktur'];
    $kontak_direktur = $_POST['kontak_direktur'];

    $sql = "INSERT INTO profil (nama_perusahaan, kabupaten, alamat, tenaga_teknis, kontak_person, nama_direktur, kontak_direktur) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $nama_perusahaan, $kabupaten, $alamat, $tenaga_teknis, $kontak_person, $nama_direktur, $kontak_direktur);
    
    if ($stmt->execute()) {
        echo "<script>alert('Profil perusahaan berhasil disimpan!'); window.location.href='profil_perusahaan.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Perusahaan</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Profil Perusahaan</h2>
        <form method="POST">
            <div class="form-group">
                <label>Nama Perusahaan</label>
                <input type="text" name="nama_perusahaan" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Kabupaten</label>
                <select name="kabupaten" class="form-control" required>
                    <option value="Banjarmasin">Banjarmasin</option>
                    <option value="Banjarbaru">Banjarbaru</option>
                    <option value="Banjar">Banjar</option>
                    <option value="Barito Kuala">Barito Kuala</option>
                    <option value="Tapin">Tapin</option>
                    <option value="Hulu Sungai Selatan">Hulu Sungai Selatan</option>
                    <option value="Hulu Sungai Tengah">Hulu Sungai Tengah</option>
                    <option value="Hulu Sungai Utara">Hulu Sungai Utara</option>
                    <option value="Tabalong">Tabalong</option>
                    <option value="Balangan">Balangan</option>
                    <option value="Kotabaru">Kotabaru</option>
                    <option value="Tanah Laut">Tanah Laut</option>
                    <option value="Tanah Bumbu">Tanah Bumbu</option>
                </select>
            </div>
            <div class="form-group">
                <label>Alamat</label>
                <input type="text" name="alamat" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Tenaga Teknis</label>
                <input type="text" name="tenaga_teknis" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Kontak Person</label>
                <input type="text" name="kontak_person" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Nama Direktur</label>
                <input type="text" name="nama_direktur" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Kontak Direktur</label>
                <input type="text" name="kontak_direktur" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</body>
</html>
