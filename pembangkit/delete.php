<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include "koneksi.php";

// Pastikan pengguna sudah login
if (!isset($_SESSION['id_user'])) {
    echo "<script>alert('Anda harus login terlebih dahulu!'); window.location.href='login/login.php';</script>";
    exit();
}

if (isset($_GET['id_user'])) {
    $id_user = $_SESSION['id_user']; // ID user dari session
    $id_hapus = $_GET['id_user']; // ID user yang ingin dihapus

    // Pastikan pengguna hanya bisa menghapus profilnya sendiri
    if ($id_user != $id_hapus) {
        echo "<script>alert('Anda tidak memiliki izin untuk menghapus profil ini!'); window.location.href='?page=pembangkit';</script>";
        exit();
    }

    // Periksa apakah profil ada
    $sql_check = "SELECT id_user FROM pembangkit WHERE id_user = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("i", $id_hapus);
    $stmt_check->execute();
    $stmt_check->store_result();
    
    if ($stmt_check->num_rows > 0) {
        // Hapus profil
        $sql_delete = "DELETE FROM pembangkit WHERE id_user = ?";
        $stmt_delete = $conn->prepare($sql_delete);
        $stmt_delete->bind_param("i", $id_hapus);
        
        if ($stmt_delete->execute()) {
            echo "<script>alert('Profil berhasil dihapus!'); window.location.href='?page=pembangkit';</script>";
        } else {
            echo "<script>alert('Gagal menghapus profil. Silakan coba lagi.'); window.location.href='?page=pembangkit';</script>";
        }
        $stmt_delete->close();
    } else {
        echo "<script>alert('Profil tidak ditemukan!'); window.location.href='?page=pembangkit';</script>";
    }
    $stmt_check->close();
} else {
    echo "<script>alert('ID Profil tidak valid!'); window.location.href='?page=pembangkit';</script>";
}
$conn->close();
?>
