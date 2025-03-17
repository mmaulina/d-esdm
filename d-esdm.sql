-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 17, 2025 at 03:23 PM
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
(9, 1, 'irwan group', 'SO2', 'aaaaaaaaa', 'aaaaaaaaa', 'uploads/1742217160_template_surat_rekomendasi_rbb2025.docx', 'uploads/1742217160_PortofolioMuhammadIrwanFirdaus1.pdf', 'diajukan', '-'),
(10, 1, 'irwan group', 'CO', 'aaaaaaaaa', 'baik', 'uploads/1742220771_PortofolioMuhammadIrwanFirdaus1_compressed.pdf', 'uploads/1742220771_Chapter1139.pdf', 'ditolak', 'admin lagi nggak mood');

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
(1, 'admin', 'admin@gmail.com', 'admin', 'admin', 'diverifikasi'),
(2, 'irwan', 'irwanfirdaus508@gmail.com', 'irwan', 'admin', 'diverifikasi');

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for table `laporan_semester`
--
ALTER TABLE `laporan_semester`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `pembangkit`
--
ALTER TABLE `pembangkit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `profil`
--
ALTER TABLE `profil`
  MODIFY `id_profil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
