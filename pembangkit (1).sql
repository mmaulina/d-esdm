-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 22, 2025 at 05:21 AM
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
-- Table structure for table `pembangkit`
--

CREATE TABLE `pembangkit` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `nama_perusahaan` varchar(225) NOT NULL,
  `kabupaten` enum('Balangan','Banjar','Barito Kuala','Hulu Sungai Selatan','Hulu Sungai Tengah','Hulu Sungai Utara','Kotabaru','Tabalong','Tanah Bumbu','Tanah Laut','Tapin','Kota Banjarbaru','Kota Banjarmasin') NOT NULL,
  `alamat` varchar(225) NOT NULL,
  `longitude` varchar(20) NOT NULL,
  `latitude` varchar(20) NOT NULL,
  `jenis_pembangkit` varchar(200) NOT NULL,
  `fungsi` enum('Utama','Darurat','Cadangan') NOT NULL,
  `kapasitas_terpasang` varchar(20) NOT NULL,
  `daya_mampu_netto` varchar(20) NOT NULL,
  `jumlah_unit` varchar(5) NOT NULL,
  `no_unit` varchar(20) NOT NULL,
  `tahun_operasi` varchar(4) NOT NULL,
  `status_operasi` enum('Beroperasi','Perbaikan','Rusak','Rusak Total') NOT NULL,
  `bahan_bakar_jenis` enum('Solar','Biomasa') NOT NULL,
  `bahan_bakar_satuan` enum('Liter','Ton') NOT NULL,
  `volume_bb` varchar(100) NOT NULL,
  `status` enum('diterima','diajukan','dikembalikan') NOT NULL,
  `keterangan` varchar(225) NOT NULL,
  `tahun` year(4) NOT NULL,
  `bulan` enum('Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pembangkit`
--

INSERT INTO `pembangkit` (`id`, `id_user`, `nama_perusahaan`, `kabupaten`, `alamat`, `longitude`, `latitude`, `jenis_pembangkit`, `fungsi`, `kapasitas_terpasang`, `daya_mampu_netto`, `jumlah_unit`, `no_unit`, `tahun_operasi`, `status_operasi`, `bahan_bakar_jenis`, `bahan_bakar_satuan`, `volume_bb`, `status`, `keterangan`, `tahun`, `bulan`) VALUES
(46, 5, 'PT Energi Mandiri', 'Kota Banjarbaru', 'Komp.Husindo Raya', '114°5\'12\"BT', '3°26\'12\"LS', 'PLTD', 'Utama', '1.259,8', '1.259,7', '2', '2', '2013', 'Perbaikan', 'Biomasa', 'Ton', '11.000', 'diterima', '', 2025, 'Januari'),
(47, 10, 'WASAKA CODE DIGITAL DEVELOPMENT', 'Kota Banjarbaru', 'Komp.Husindo Raya', '114°5\'12\"BT', '3°26\'12\"LS', 'PLTD', 'Darurat', '1.259,8', '1.259,7', '2', '1', '2012', 'Rusak', 'Solar', 'Liter', '11.000', 'dikembalikan', 'masaa', 2025, 'Januari'),
(49, 14, 'desain.in', 'Kota Banjarbaru', 'Landasan Ulin', '114°5\'12\"BT', '3°26\'12\"LS', 'PLTD', 'Utama', '1.259,8', '1.259,7', '1', '1', '2012', 'Rusak Total', 'Biomasa', 'Ton', '30.000', 'diterima', '', 2025, 'Januari');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pembangkit`
--
ALTER TABLE `pembangkit`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pembangkit`
--
ALTER TABLE `pembangkit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
