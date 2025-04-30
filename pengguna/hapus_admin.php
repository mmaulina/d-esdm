<?php
include 'koneksi.php'; // Pastikan koneksi sudah tersedia

// Pastikan ID ada di URL
if (!isset($_GET['id_user'])) {
    echo "<script>alert('ID pengguna tidak ditemukan!'); window.location='?page=pengguna';</script>";
    exit();
}

$id_user = $_GET['id_user'];

try {
    $db = new Database();
    $conn = $db->getConnection();
    // Periksa apakah pengguna ada dalam database
    $sql = "SELECT id_user FROM users WHERE id_user = :id_user";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() == 0) {
        echo "<script>alert('Pengguna tidak ditemukan!'); window.location='?page=pengguna';</script>";
        exit();
    }

    // Hapus pengguna berdasarkan ID
    $sql = "DELETE FROM users WHERE id_user = :id_user";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $_SESSION['hasil'] = true;
        $_SESSION['pesan'] = "Berhasil Hapus Data";
    } else {
        $_SESSION['hasil'] = false;
        $_SESSION['pesan'] = "Gagal Hapus Data";
    }

    echo "<meta http-equiv='refresh' content='0; url=?page=pengguna'>";
} catch (PDOException $e) {
    echo "<script>alert('Kesalahan: " . $e->getMessage() . "'); window.location='?page=pengguna';</script>";
}
?>
