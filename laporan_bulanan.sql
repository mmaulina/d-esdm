-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 30 Apr 2025 pada 12.47
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
  `kabupaten` enum('Balangan','Banjar','Barito Kuala','Hulu Sungai Selatan','Hulu Sungai Tengah','Hulu Sungai Utara','Kotabaru','Tabalong','Tanah Bumbu','Tanah Laut','Tapin','Kota Banjarbaru','Kota Banjarmasin') NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `latitude` varchar(20) NOT NULL,
  `longitude` varchar(20) NOT NULL,
  `jenis_pembangkit` varchar(200) NOT NULL,
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
  `pemakaian_sendiri` varchar(200) NOT NULL,
  `status` enum('diajukan','diterima','ditolak') NOT NULL,
  `keterangan` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `laporan_bulanan`
--

INSERT INTO `laporan_bulanan` (`id`, `id_user`, `tahun`, `bulan`, `nama_perusahaan`, `kabupaten`, `alamat`, `latitude`, `longitude`, `jenis_pembangkit`, `fungsi`, `kapasitas_terpasang`, `daya_mampu_netto`, `jumlah_unit`, `no_unit`, `tahun_operasi`, `status_operasi`, `bahan_bakar_jenis`, `bahan_bakar_satuan`, `volume_bb`, `produksi_sendiri`, `pemb_sumber_lain`, `susut_jaringan`, `penj_ke_pelanggan`, `penj_ke_pln`, `pemakaian_sendiri`, `status`, `keterangan`) VALUES
(3, 1, '2025', 'April', 'PT Energi Mandiri', 'Kota Banjarbaru', 'Jl. Mistar Cokrokusumo Kelurahan Cempaka, Kecamatan Cempaka, No 21 (Seberang Kelurahan Cempaka)', '3°26\'43', '114°50\'21', '1', 'Utama', '1.00', '0.58', '1', '1', '2000', 'Beroperasi', 'Solar', 'Liter', '2.342,2', '2.341,4', '1.231,5', '1.231,3', '1.132,3', '1.231,2', '19.2', 'diterima', '-'),
(4, 5, '2025', 'April', 'Perusahaan', 'Kota Banjarmasin', 'Jl. Mistar Cokrokusumo Kelurahan Cempaka, Kecamatan Cempaka, No 21 (Seberang Kelurahan Cempaka)', '3°26\'43', '114°50\'21', '-', 'Utama', '1.00', '0.58', '1', '1', '2002', 'Beroperasi', 'Solar', 'Liter', '2.342,2', '2.341,4', '1.231,5', '1.231,3', '1.132,3', '1.231,2', '1.342,2', 'diterima', '-');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
