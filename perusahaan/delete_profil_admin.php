<?php
include 'koneksi.php'; // Pastikan koneksi sudah tersedia

// Pastikan ID ada di URL
if (!isset($_GET['id_user'])) {
    echo "<script>alert('ID pengguna tidak ditemukan!'); window.location='?page=pengguna';</script>";
    exit();
}

$id_profil = $_GET['id_profil'];

// Periksa apakah pengguna ada dalam database
$sql = "SELECT id_profil FROM profil WHERE id_profil = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_profil);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "<script>alert('Pengguna tidak ditemukan!'); window.location='?page=profil_admin';</script>";
    exit();
}

// Hapus pengguna berdasarkan ID
$sql = "DELETE FROM profil WHERE id_profil = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_profil);

if ($stmt->execute()) {
    echo "<script>alert('Pengguna berhasil dihapus!'); window.location='?page=profil_admin';</script>";
} else {
    echo "<script>alert('Gagal menghapus pengguna!'); window.location='?page=profil_admin';</script>";
}
?>
