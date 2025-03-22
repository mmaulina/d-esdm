-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 22, 2025 at 05:10 AM
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
-- Table structure for table `djih`
--

CREATE TABLE `djih` (
  `id` int(11) NOT NULL,
  `jenis_konten` enum('gambar','file','link','kosong') NOT NULL,
  `konten` varchar(225) NOT NULL,
  `caption` text NOT NULL,
  `tanggal` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `djih`
--

INSERT INTO `djih` (`id`, `jenis_konten`, `konten`, `caption`, `tanggal`) VALUES
(3, 'file', 'uploads/1742616564_MuhammadIrwanFirdaus-TESTPRAKTEKIT2025.pdf', 'Info dari pusat format file terbaru', '2025-03-22 05:09:24');

-- --------------------------------------------------------

--
-- Table structure for table `djih_dilihat`
--

CREATE TABLE `djih_dilihat` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `konten_id` int(11) NOT NULL,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `djih_dilihat`
--

INSERT INTO `djih_dilihat` (`id`, `id_user`, `konten_id`, `tanggal`) VALUES
(54, 4, 1, '2025-03-22 03:53:47'),
(55, 4, 2, '2025-03-22 03:53:47'),
(57, 4, 1, '2025-03-22 03:54:24'),
(58, 4, 2, '2025-03-22 03:54:24'),
(60, 4, 3, '2025-03-22 04:09:25'),
(61, 4, 3, '2025-03-22 04:09:33'),
(62, 5, 3, '2025-03-22 04:09:46');

-- --------------------------------------------------------

--
-- Table structure for table `konten_dilihat`
--

CREATE TABLE `konten_dilihat` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `konten_id` int(11) NOT NULL,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `konten_dilihat`
--

INSERT INTO `konten_dilihat` (`id`, `id_user`, `konten_id`, `tanggal`) VALUES
(1, 4, 1, '2025-03-21 07:08:24'),
(2, 4, 2, '2025-03-21 07:08:24'),
(3, 4, 3, '2025-03-21 07:08:24'),
(4, 4, 1, '2025-03-21 07:08:29'),
(5, 4, 2, '2025-03-21 07:08:29'),
(6, 4, 3, '2025-03-21 07:08:29'),
(7, 5, 1, '2025-03-21 07:18:24'),
(8, 5, 2, '2025-03-21 07:18:24'),
(9, 5, 3, '2025-03-21 07:18:24'),
(10, 5, 1, '2025-03-21 07:18:32'),
(11, 5, 2, '2025-03-21 07:18:32'),
(12, 5, 3, '2025-03-21 07:18:32'),
(13, 0, 1, '2025-03-22 03:27:41'),
(14, 0, 2, '2025-03-22 03:27:41'),
(15, 0, 3, '2025-03-22 03:27:41'),
(16, 4, 1, '2025-03-22 03:27:51'),
(17, 4, 2, '2025-03-22 03:27:51'),
(18, 4, 3, '2025-03-22 03:27:51'),
(19, 4, 1, '2025-03-22 03:28:18'),
(20, 4, 2, '2025-03-22 03:28:18'),
(22, 4, 1, '2025-03-22 03:33:08'),
(23, 4, 2, '2025-03-22 03:33:08'),
(25, 4, 1, '2025-03-22 03:33:33'),
(26, 4, 2, '2025-03-22 03:33:33'),
(28, 4, 1, '2025-03-22 03:33:45'),
(29, 4, 2, '2025-03-22 03:33:45'),
(31, 4, 1, '2025-03-22 03:34:57'),
(32, 4, 2, '2025-03-22 03:34:57'),
(34, 4, 1, '2025-03-22 03:35:13'),
(35, 4, 2, '2025-03-22 03:35:13'),
(37, 4, 1, '2025-03-22 03:36:19'),
(38, 4, 2, '2025-03-22 03:36:19'),
(40, 4, 1, '2025-03-22 03:36:44'),
(41, 4, 2, '2025-03-22 03:36:44'),
(43, 4, 1, '2025-03-22 03:37:38'),
(44, 4, 2, '2025-03-22 03:37:38'),
(46, 4, 1, '2025-03-22 03:41:40'),
(47, 4, 2, '2025-03-22 03:41:40'),
(49, 4, 1, '2025-03-22 03:43:23'),
(50, 4, 2, '2025-03-22 03:43:23'),
(52, 4, 1, '2025-03-22 03:44:13'),
(53, 4, 2, '2025-03-22 03:44:13'),
(55, 4, 1, '2025-03-22 03:51:39'),
(56, 4, 2, '2025-03-22 03:51:39'),
(58, 4, 1, '2025-03-22 03:53:46'),
(59, 4, 2, '2025-03-22 03:53:46'),
(61, 5, 1, '2025-03-22 04:09:43'),
(62, 5, 2, '2025-03-22 04:09:43'),
(64, 4, 1, '2025-03-22 04:10:00'),
(65, 4, 2, '2025-03-22 04:10:00');

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
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `jenis_konten` enum('gambar','file','link','kosong') NOT NULL,
  `konten` varchar(225) NOT NULL,
  `caption` text NOT NULL,
  `tanggal` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `jenis_konten`, `konten`, `caption`, `tanggal`) VALUES
(1, 'gambar', 'uploads/1742537822_2a3066213824b688a253585469df73fd.jpg', 'adel jkt48 memilih graduation karena alasan kesehatan hal itu membuat banyak penggemar sedih', '2025-03-21 07:17:02'),
(2, 'file', 'uploads/1742538250_CvMuhammadIrwanFirdaus.pdf', 'ini contoh format cv yang terbaru', '2025-03-21 07:24:10');

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
-- Indexes for table `djih`
--
ALTER TABLE `djih`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `djih_dilihat`
--
ALTER TABLE `djih_dilihat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `konten_dilihat`
--
ALTER TABLE `konten_dilihat`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `news`
--
ALTER TABLE `news`
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
-- AUTO_INCREMENT for table `djih`
--
ALTER TABLE `djih`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `djih_dilihat`
--
ALTER TABLE `djih_dilihat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `konten_dilihat`
--
ALTER TABLE `konten_dilihat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

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
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
