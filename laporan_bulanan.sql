-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 25 Apr 2025 pada 07.43
-- Versi server: 10.4.28-MariaDB
-- Versi PHP: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `d-esdm`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `laporan_bulanan`
--

CREATE TABLE `laporan_bulanan` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `tahun` year(4) NOT NULL,
  `bulan` enum('Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember') NOT NULL,
  `nama_perusahaan` varchar(200) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `latitude` varchar(20) NOT NULL,
  `longitude` varchar(20) NOT NULL,
  `jenis_pembangkit` int(100) NOT NULL,
  `fungsi` enum('Utama','Darurat','Cadangan') NOT NULL,
  `kapasitas_terpasang` varchar(20) NOT NULL,
  `daya_mampu_netto` varchar(20) NOT NULL,
  `jumlah_unit` varchar(5) NOT NULL,
  `no_unit` varchar(20) NOT NULL,
  `tahun_operasi` varchar(4) NOT NULL,
  `status_operasi` enum('Beroperasi','Maintenance/Perbaikan','Rusak') NOT NULL,
  `bahan_bakar_jenis` enum('Solar','Biomasa') NOT NULL,
  `bahan_bakar_satuan` enum('Liter','Ton') NOT NULL,
  `volume_bb` varchar(200) NOT NULL,
  `produksi_sendiri` varchar(200) NOT NULL,
  `pemb_sumber_lain` varchar(200) NOT NULL,
  `susut_jaringan` varchar(200) NOT NULL,
  `penj_ke_pelanggan` varchar(200) NOT NULL,
  `penj_ke_pln` varchar(200) NOT NULL,
  `pemakaian_sendiri` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `laporan_bulanan`
--

INSERT INTO `laporan_bulanan` (`id`, `id_user`, `tahun`, `bulan`, `nama_perusahaan`, `alamat`, `latitude`, `longitude`, `jenis_pembangkit`, `fungsi`, `kapasitas_terpasang`, `daya_mampu_netto`, `jumlah_unit`, `no_unit`, `tahun_operasi`, `status_operasi`, `bahan_bakar_jenis`, `bahan_bakar_satuan`, `volume_bb`, `produksi_sendiri`, `pemb_sumber_lain`, `susut_jaringan`, `penj_ke_pelanggan`, `penj_ke_pln`, `pemakaian_sendiri`) VALUES
(3, 1, '0000', 'April', 'PT Energi Mandiri', '', '', '', 0, 'Utama', '', '', '', '', '', 'Beroperasi', 'Solar', 'Liter', '1200', '75000', '5000', '800', '60000', '10000', '9200'),
(4, 5, '0000', 'April', 'Perusahaan', '', '', '', 0, 'Utama', '', '', '', '', '', 'Beroperasi', 'Solar', 'Liter', '-', '-', '-', '-', '-', '-', '-');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `laporan_bulanan`
--
ALTER TABLE `laporan_bulanan`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `laporan_bulanan`
--
ALTER TABLE `laporan_bulanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
