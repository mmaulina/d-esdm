<?php
include 'koneksi.php'; // Pastikan koneksi sudah tersedia

// Pastikan ID ada di URL
if (!isset($_GET['id_user'])) {
    echo "<script>alert('ID pengguna tidak ditemukan!'); window.location='?page=pengguna';</script>";
    exit();
}

$id_user = $_GET['id_user'];

// Periksa apakah pengguna ada dalam database
$sql = "SELECT id_user FROM users WHERE id_user = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "<script>alert('Pengguna tidak ditemukan!'); window.location='?page=pengguna';</script>";
    exit();
}

// Hapus pengguna berdasarkan ID
$sql = "DELETE FROM users WHERE id_user = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_user);

if ($stmt->execute()) {
    echo "<script>alert('Pengguna berhasil dihapus!'); window.location='?page=pengguna';</script>";
} else {
    echo "<script>alert('Gagal menghapus pengguna!'); window.location='?page=pengguna';</script>";
}
?>
