<?php
include 'koneksi.php'; // Pastikan koneksi sudah tersedia

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('ID tidak valid!'); window.location='?page=laporan_persemester';</script>";
    exit();
}

$id = intval($_GET['id']);

try {
    $db = new Database();
    $conn = $db->getConnection();

    // Ambil data file terlebih dahulu
    $sql = "SELECT file_lhu, file_laporan FROM laporan_semester WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() == 0) {
        echo "<script>alert('Data tidak ditemukan!'); window.location='?page=laporan_persemester';</script>";
        exit();
    }

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // Hapus file jika ada
    $filePaths = [$row['file_lhu'], $row['file_laporan']];
    foreach ($filePaths as $file) {
        if (!empty($file) && file_exists($file)) {
            unlink($file);
        }
    }

    // Hapus data dari database
    $sql = "DELETE FROM laporan_semester WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $_SESSION['hasil'] = true;
        $_SESSION['pesan'] = "Berhasil Hapus Data";
    } else {
        $_SESSION['hasil'] = false;
        $_SESSION['pesan'] = "Gagal Hapus Data";
    }

    echo "<meta http-equiv='refresh' content='0; url=?page=laporan_persemester'>";
} catch (PDOException $e) {
    echo "<script>alert('Kesalahan: " . $e->getMessage() . "'); window.location='?page=laporan_persemester';</script>";
}
?>
