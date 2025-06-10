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
    $sql = "SELECT id FROM parameter WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    
    if ($stmt->rowCount() == 0) {
        echo "<script>alert('Data tidak ditemukan!'); window.location='?page=laporan_persemester';</script>";
        exit();
    }

    // Hapus data dari database
    $sql = "DELETE FROM parameter WHERE id = :id";
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
