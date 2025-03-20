-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 20, 2025 at 09:21 AM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 7.4.16

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
-- Table structure for table `laporan_bulanan`
--

CREATE TABLE `laporan_bulanan` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `bulan` enum('Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember') NOT NULL,
  `nama_perusahaan` varchar(200) NOT NULL,
  `volume_bb` varchar(200) NOT NULL,
  `produksi_sendiri` varchar(200) NOT NULL,
  `pemb_sumber_lain` varchar(200) NOT NULL,
  `susut_jaringan` varchar(200) NOT NULL,
  `penj_ke_pelanggan` varchar(200) NOT NULL,
  `penj_ke_pln` varchar(200) NOT NULL,
  `pemakaian_sendiri` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `laporan_semester`
--

CREATE TABLE `laporan_semester` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `nama_perusahaan` varchar(225) NOT NULL,
  `parameter` enum('SO2','HO2','TSP/DEBU','CO','kebisingan') NOT NULL,
  `buku_mutu` varchar(225) NOT NULL,
  `hasil` varchar(100) NOT NULL,
  `file_laporan` varchar(225) NOT NULL,
  `file_lhu` varchar(225) NOT NULL,
  `status` enum('diterima','ditolak','diajukan','') NOT NULL,
  `keterangan` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `laporan_semester`
--

INSERT INTO `laporan_semester` (`id`, `id_user`, `nama_perusahaan`, `parameter`, `buku_mutu`, `hasil`, `file_laporan`, `file_lhu`, `status`, `keterangan`) VALUES
(9, 1, 'irwan group', 'TSP/DEBU', 'aaaaaaaaa', 'aaaaaaaaa', 'uploads/1742217160_template_surat_rekomendasi_rbb2025.docx', 'uploads/1742217160_PortofolioMuhammadIrwanFirdaus1.pdf', 'diajukan', '-'),
(10, 1, 'irwan group', 'CO', 'aaaaaaaaa', 'baik', 'uploads/1742220771_PortofolioMuhammadIrwanFirdaus1_compressed.pdf', 'uploads/1742220771_Chapter1139.pdf', 'ditolak', 'admin lagi nggak mood'),
(11, 2, 'maya group', 'kebisingan', 'aaaaaaaaa', 'baik', 'uploads/1742379527_AplikasiManajemenAdministrasidanKeuanganSMKN1AmuntaiBerbasisWeb.docx', 'uploads/1742379527_AplikasiManajemenAdministrasidanKeuanganSMKN1AmuntaiBerbasisWeb.pdf', 'diajukan', '-');

-- --------------------------------------------------------

--
-- Table structure for table `pembangkit`
--

CREATE TABLE `pembangkit` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `nama_perusahaan` varchar(225) NOT NULL,
  `alamat` varchar(225) NOT NULL,
  `longitude` varchar(225) NOT NULL,
  `latitude` varchar(225) NOT NULL,
  `jenis_pembangkit` varchar(100) NOT NULL,
  `fungsi` varchar(100) NOT NULL,
  `kapasitas_terpasang` varchar(100) NOT NULL,
  `daya_mampu_netto` varchar(100) NOT NULL,
  `jumlah_unit` int(100) NOT NULL,
  `no_unit` varchar(100) NOT NULL,
  `tahun_operasi` int(11) NOT NULL,
  `status_operasi` varchar(100) NOT NULL,
  `bahan_bakar_jenis` varchar(100) NOT NULL,
  `bahan_bakar_satuan` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pembangkit`
--

INSERT INTO `pembangkit` (`id`, `id_user`, `nama_perusahaan`, `alamat`, `longitude`, `latitude`, `jenis_pembangkit`, `fungsi`, `kapasitas_terpasang`, `daya_mampu_netto`, `jumlah_unit`, `no_unit`, `tahun_operasi`, `status_operasi`, `bahan_bakar_jenis`, `bahan_bakar_satuan`) VALUES
(10, 2, 'maya group', 'Jl. Mistar Cokrokusumo Kelurahan Cempaka, Kecamatan Cempaka, No 21 (Seberang Kelurahan Cempaka)', '110.3091878', '-7.075730113', 'genset', 'darurat', '1.00', '0.58', 1, '1', 2029, 'operasi', 'solar', 'liter');

-- --------------------------------------------------------

--
-- Table structure for table `profil`
--

CREATE TABLE `profil` (
  `id_profil` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `nama_perusahaan` varchar(100) NOT NULL,
  `kabupaten` enum('Balangan','Banjar','Barito Kuala','Hulu Sungai Selatan','Hulu Sungai Tengah','Hulu Sungai Utara','Kotabaru','Tabalong','Tanah Bumbu','Tanah Laut','Tapin','Kota Banjarbaru','Kota Banjarmasin') NOT NULL,
  `alamat` varchar(200) NOT NULL,
  `jenis_usaha` varchar(100) NOT NULL,
  `no_telp_kantor` varchar(15) NOT NULL,
  `no_fax` varchar(20) NOT NULL,
  `tenaga_teknik` varchar(100) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `no_hp` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','umum') NOT NULL,
  `status` enum('diajukan','diverifikasi','ditolak') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `username`, `email`, `password`, `role`, `status`) VALUES
(4, 'admin', 'admin@gmail.com', '$2y$10$kOhOzPUvmsTDTA2BuKwGMun4IxZvwjVNonHA0K6ocRuJIx4CWoTBm', 'admin', 'diverifikasi'),
(5, 'irwan', 'irwanfirdaus508@gmail.com', '$2y$10$zmgD7Y1gcvhgey.N7VlBPeklVEEiCe2gqjDKE/XcuhrzigIT0dxAq', 'umum', 'diverifikasi');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `laporan_bulanan`
--
ALTER TABLE `laporan_bulanan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `laporan_semester`
--
ALTER TABLE `laporan_semester`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pembangkit`
--
ALTER TABLE `pembangkit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `profil`
--
ALTER TABLE `profil`
  ADD PRIMARY KEY (`id_profil`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `laporan_bulanan`
--
ALTER TABLE `laporan_bulanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `laporan_semester`
--
ALTER TABLE `laporan_semester`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `pembangkit`
--
ALTER TABLE `pembangkit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `profil`
--
ALTER TABLE `profil`
  MODIFY `id_profil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
