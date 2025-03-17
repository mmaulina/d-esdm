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
    // Periksa apakah data ada dalam database
    $sql = "SELECT id FROM laporan_semester WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    
    if ($stmt->rowCount() == 0) {
        echo "<script>alert('Data tidak ditemukan!'); window.location='?page=laporan_persemester';</script>";
        exit();
    }

    // Hapus data dari database
    $sql = "DELETE FROM laporan_semester WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        echo "<script>alert('Laporan berhasil dihapus!'); window.location='?page=laporan_persemester';</script>";
    } else {
        echo "<script>alert('Gagal menghapus Laporan!'); window.location='?page=laporan_persemester';</script>";
    }
} catch (PDOException $e) {
    echo "<script>alert('Kesalahan: " . $e->getMessage() . "'); window.location='?page=laporan_persemester';</script>";
}
?>
