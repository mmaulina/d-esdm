-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 19 Bulan Mei 2025 pada 02.25
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
-- Struktur dari tabel `laporan_semester`
--

CREATE TABLE `laporan_semester` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `nama_perusahaan` varchar(225) NOT NULL,
  `no_hp_pimpinan` varchar(15) NOT NULL,
  `tenaga_teknik` varchar(100) NOT NULL,
  `no_hp_teknik` varchar(15) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `no_hp` varchar(15) NOT NULL,
  `no_telp_kantor` varchar(15) NOT NULL,
  `baku_mutu_so2` varchar(225) NOT NULL,
  `hasil_so2` varchar(100) NOT NULL,
  `baku_mutu_ho2` varchar(225) NOT NULL,
  `hasil_ho2` varchar(100) NOT NULL,
  `baku_mutu_tsp` varchar(225) NOT NULL,
  `hasil_tsp` varchar(100) NOT NULL,
  `baku_mutu_co` varchar(225) NOT NULL,
  `hasil_co` varchar(100) NOT NULL,
  `baku_mutu_kebisingan` varchar(225) NOT NULL,
  `hasil_kebisingan` varchar(100) NOT NULL,
  `file_laporan` varchar(225) NOT NULL,
  `file_lhu` varchar(225) NOT NULL,
  `tahun` year(4) NOT NULL,
  `semester` varchar(30) NOT NULL,
  `status` enum('diterima','dikembalikan','diajukan') NOT NULL,
  `keterangan` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `laporan_semester`
--

INSERT INTO `laporan_semester` (`id`, `id_user`, `nama_perusahaan`, `no_hp_pimpinan`, `tenaga_teknik`, `no_hp_teknik`, `nama`, `no_hp`, `no_telp_kantor`, `baku_mutu_so2`, `hasil_so2`, `baku_mutu_ho2`, `hasil_ho2`, `baku_mutu_tsp`, `hasil_tsp`, `baku_mutu_co`, `hasil_co`, `baku_mutu_kebisingan`, `hasil_kebisingan`, `file_laporan`, `file_lhu`, `tahun`, `semester`, `status`, `keterangan`) VALUES
(28, 5, 'PT Energi Mandiri', '087535764768', 'Nama Tenaga Teknik Anda', '085654375689', 'MUHAMMAD IRWAN FIRDAUS', '086567896567', '088247342027', '0,26', '0,08', '0,21', '0,10', '0,23', '0,18', '55', '50', '10', '3,5', 'uploads/1747588518_Laporan_Bulanan_1747579893.xlsx', 'uploads/1747588518_SK-UJIAN-SKRIPSI-20100100561.docx', '2025', 'Semester I 2025', 'diterima', '-'),
(29, 5, 'PT Energi Mandiri', '087535764768', 'Nama Tenaga Teknik Anda', '085654375689', 'MUHAMMAD IRWAN FIRDAUS', '086567896567', '088247342027', '800', '540', '400', '285', '230', '160', '10.000', '7.200', '85', '79', 'uploads/1747609053_Laporan_Bulanan_1747579893.xlsx', 'uploads/1747609053_SK-UJIAN-SKRIPSI-20100100561.docx', '2025', 'Semester I 2025', 'dikembalikan', 'cek lagi');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `laporan_semester`
--
ALTER TABLE `laporan_semester`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `laporan_semester`
--
ALTER TABLE `laporan_semester`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
