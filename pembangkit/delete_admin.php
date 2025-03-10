<?php
include 'koneksi.php'; // Pastikan koneksi sudah tersedia

$id = $_GET['id'];

// Periksa apakah pengguna ada dalam database
$sql = "SELECT id FROM pembangkit WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "<script>alert('Pengguna tidak ditemukan!'); window.location='?page=pembangkit_admin';</script>";
    exit();
}

// Hapus pengguna berdasarkan ID
$sql = "DELETE FROM pembangkit WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "<script>alert('Pembangkit berhasil dihapus!'); window.location='?page=pembangkit_admin';</script>";
} else {
    echo "<script>alert('Gagal menghapus pembangkit!'); window.location='?page=pembangkit_admin';</script>";
}
?>
