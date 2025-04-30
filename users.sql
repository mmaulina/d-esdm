-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 30, 2025 at 03:23 PM
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
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `no_hp` varchar(15) NOT NULL,
  `role` enum('superadmin','admin','umum','adminbulanan','adminsemester','kementerian') NOT NULL,
  `status` enum('diajukan','diverifikasi','ditolak') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `username`, `email`, `password`, `no_hp`, `role`, `status`) VALUES
(1, 'superadmin', 'admin@gmail.com', '$2y$10$C3vLGa6azn7MTl7gBw/PVuSBhFf/yPxaLtFIF.dU8yoBoRpJcGF.m', '081234567890', 'superadmin', 'diverifikasi'),
(5, 'umum', 'irwanfirdaus508@gmail.com', '$2y$10$IfKG0EFwC/grk2sIy8fFzOKQnTgT51tXY5wdJ4IZZVZo5BNVU43HC', '', 'umum', 'diverifikasi'),
(9, 'admin', 'yanda@gmail.com', '$2y$10$ue.Tc/.YrGfz0IIi69BuDenBIpBL8.eYEuAmXoy6ulXx4OXRNL8/y', '', 'admin', 'diverifikasi'),
(10, 'umum2', 'umum2@gmail.com', '$2y$10$VXbtfcHWz.AYyjG1g1kh/eQIgbEFGyRYUkljqCO7DH1ZeOZUO.qKC', '082716152', 'umum', 'diverifikasi');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
