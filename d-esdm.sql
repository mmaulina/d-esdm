-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 26 Mar 2025 pada 07.09
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
-- Struktur dari tabel `djih`
--

CREATE TABLE `djih` (
  `id` int(11) NOT NULL,
  `jenis_konten` enum('gambar','file','link','kosong') NOT NULL,
  `konten` varchar(225) NOT NULL,
  `caption` text NOT NULL,
  `tanggal` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `djih`
--

INSERT INTO `djih` (`id`, `jenis_konten`, `konten`, `caption`, `tanggal`) VALUES
(3, 'file', 'uploads/1742616564_MuhammadIrwanFirdaus-TESTPRAKTEKIT2025.pdf', 'Info dari pusat format file terbaru', '2025-03-22 05:09:24');

-- --------------------------------------------------------

--
-- Struktur dari tabel `djih_dilihat`
--

CREATE TABLE `djih_dilihat` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `konten_id` int(11) NOT NULL,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `djih_dilihat`
--

INSERT INTO `djih_dilihat` (`id`, `id_user`, `konten_id`, `tanggal`) VALUES
(54, 4, 1, '2025-03-22 03:53:47'),
(55, 4, 2, '2025-03-22 03:53:47'),
(57, 4, 1, '2025-03-22 03:54:24'),
(58, 4, 2, '2025-03-22 03:54:24'),
(60, 4, 3, '2025-03-22 04:09:25'),
(61, 4, 3, '2025-03-22 04:09:33'),
(62, 5, 3, '2025-03-22 04:09:46'),
(63, 4, 3, '2025-03-22 14:15:12'),
(64, 4, 3, '2025-03-22 14:15:17'),
(65, 4, 3, '2025-03-22 15:49:59'),
(66, 4, 3, '2025-03-23 03:25:35'),
(67, 4, 3, '2025-03-23 04:05:02'),
(68, 6, 3, '2025-03-23 06:54:02'),
(69, 6, 3, '2025-03-23 06:54:04'),
(70, 4, 3, '2025-03-24 10:26:04'),
(71, 4, 3, '2025-03-24 10:29:10'),
(72, 4, 3, '2025-03-24 11:59:47'),
(73, 4, 3, '2025-03-24 12:00:07'),
(74, 6, 3, '2025-03-24 12:07:55'),
(75, 6, 3, '2025-03-24 12:15:03'),
(76, 1, 3, '2025-03-24 14:05:45'),
(77, 1, 3, '2025-03-24 14:28:33'),
(78, 1, 3, '2025-03-24 14:39:06');

-- --------------------------------------------------------

--
-- Struktur dari tabel `konten_dilihat`
--

CREATE TABLE `konten_dilihat` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `konten_id` int(11) NOT NULL,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `konten_dilihat`
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
(65, 4, 2, '2025-03-22 04:10:00'),
(67, 4, 1, '2025-03-22 14:10:06'),
(68, 4, 2, '2025-03-22 14:10:06'),
(70, 4, 1, '2025-03-22 14:14:45'),
(71, 4, 2, '2025-03-22 14:14:45'),
(73, 4, 1, '2025-03-22 14:15:30'),
(74, 4, 2, '2025-03-22 14:15:30'),
(76, 4, 1, '2025-03-22 14:15:43'),
(77, 4, 2, '2025-03-22 14:15:43'),
(79, 0, 1, '2025-03-22 14:21:23'),
(80, 0, 2, '2025-03-22 14:21:23'),
(82, 4, 1, '2025-03-22 14:21:31'),
(83, 4, 2, '2025-03-22 14:21:31'),
(85, 4, 1, '2025-03-22 15:12:45'),
(86, 4, 2, '2025-03-22 15:12:45'),
(88, 4, 1, '2025-03-22 15:22:53'),
(89, 4, 2, '2025-03-22 15:22:53'),
(91, 4, 1, '2025-03-22 15:22:54'),
(92, 4, 2, '2025-03-22 15:22:54'),
(94, 4, 1, '2025-03-22 16:06:20'),
(95, 4, 2, '2025-03-22 16:06:20'),
(97, 4, 1, '2025-03-22 16:06:38'),
(98, 4, 2, '2025-03-22 16:06:38'),
(100, 4, 1, '2025-03-22 16:17:59'),
(101, 4, 2, '2025-03-22 16:17:59'),
(103, 4, 1, '2025-03-22 16:21:16'),
(104, 4, 2, '2025-03-22 16:21:16'),
(105, 0, 1, '2025-03-23 03:25:22'),
(106, 0, 2, '2025-03-23 03:25:22'),
(108, 4, 1, '2025-03-23 03:25:30'),
(109, 4, 2, '2025-03-23 03:25:30'),
(111, 4, 1, '2025-03-23 03:31:52'),
(112, 4, 2, '2025-03-23 03:31:52'),
(114, 0, 1, '2025-03-23 04:54:36'),
(115, 0, 2, '2025-03-23 04:54:36'),
(117, 4, 1, '2025-03-23 04:54:44'),
(118, 4, 2, '2025-03-23 04:54:44'),
(120, 4, 1, '2025-03-23 06:39:10'),
(121, 4, 2, '2025-03-23 06:39:10'),
(123, 4, 1, '2025-03-23 06:39:58'),
(124, 4, 2, '2025-03-23 06:39:58'),
(126, 4, 1, '2025-03-23 06:40:02'),
(127, 4, 2, '2025-03-23 06:40:02'),
(129, 4, 1, '2025-03-23 06:40:27'),
(130, 4, 2, '2025-03-23 06:40:27'),
(132, 4, 1, '2025-03-23 06:40:36'),
(133, 4, 2, '2025-03-23 06:40:36'),
(135, 6, 1, '2025-03-23 06:53:51'),
(136, 6, 2, '2025-03-23 06:53:51'),
(138, 6, 1, '2025-03-23 06:53:59'),
(139, 6, 2, '2025-03-23 06:53:59'),
(141, 6, 1, '2025-03-23 06:57:19'),
(142, 6, 2, '2025-03-23 06:57:19'),
(144, 6, 1, '2025-03-23 06:57:24'),
(145, 6, 2, '2025-03-23 06:57:24'),
(147, 6, 1, '2025-03-23 07:21:13'),
(148, 6, 2, '2025-03-23 07:21:13'),
(150, 4, 1, '2025-03-23 07:23:03'),
(151, 4, 2, '2025-03-23 07:23:03'),
(153, 4, 1, '2025-03-23 07:23:08'),
(154, 4, 2, '2025-03-23 07:23:08'),
(156, 0, 1, '2025-03-23 08:18:05'),
(157, 0, 2, '2025-03-23 08:18:05'),
(159, 4, 1, '2025-03-23 08:18:12'),
(160, 4, 2, '2025-03-23 08:18:12'),
(162, 4, 1, '2025-03-23 08:19:21'),
(163, 4, 2, '2025-03-23 08:19:21'),
(165, 6, 1, '2025-03-23 09:09:50'),
(166, 6, 2, '2025-03-23 09:09:50'),
(168, 6, 1, '2025-03-23 09:13:52'),
(169, 6, 2, '2025-03-23 09:13:52'),
(170, 0, 1, '2025-03-24 09:35:04'),
(171, 0, 2, '2025-03-24 09:35:04'),
(173, 4, 1, '2025-03-24 09:49:11'),
(174, 4, 2, '2025-03-24 09:49:11'),
(176, 4, 1, '2025-03-24 10:25:54'),
(177, 4, 2, '2025-03-24 10:25:54'),
(179, 4, 1, '2025-03-24 10:26:10'),
(180, 4, 2, '2025-03-24 10:26:10'),
(182, 4, 1, '2025-03-24 12:00:21'),
(183, 4, 2, '2025-03-24 12:00:21'),
(185, 4, 1, '2025-03-24 12:02:42'),
(186, 4, 2, '2025-03-24 12:02:42'),
(188, 4, 1, '2025-03-24 12:05:38'),
(189, 4, 2, '2025-03-24 12:05:38'),
(191, 6, 1, '2025-03-24 12:05:54'),
(192, 6, 2, '2025-03-24 12:05:54'),
(194, 4, 1, '2025-03-24 12:08:08'),
(195, 4, 2, '2025-03-24 12:08:08'),
(197, 6, 1, '2025-03-24 12:14:20'),
(198, 6, 2, '2025-03-24 12:14:20'),
(200, 0, 1, '2025-03-24 12:15:54'),
(201, 0, 2, '2025-03-24 12:15:54'),
(203, 6, 1, '2025-03-24 12:15:59'),
(204, 6, 2, '2025-03-24 12:15:59'),
(206, 6, 1, '2025-03-24 13:49:46'),
(207, 6, 2, '2025-03-24 13:49:46'),
(209, 6, 1, '2025-03-24 13:52:27'),
(210, 6, 2, '2025-03-24 13:52:27'),
(212, 1, 1, '2025-03-24 14:05:35'),
(213, 1, 2, '2025-03-24 14:05:35'),
(215, 1, 1, '2025-03-24 14:05:40'),
(216, 1, 2, '2025-03-24 14:05:40'),
(218, 1, 1, '2025-03-24 14:28:16'),
(219, 1, 2, '2025-03-24 14:28:16'),
(221, 1, 1, '2025-03-24 14:30:41'),
(222, 1, 2, '2025-03-24 14:30:41'),
(224, 1, 1, '2025-03-24 14:39:00'),
(225, 1, 2, '2025-03-24 14:39:00'),
(227, 1, 1, '2025-03-24 14:42:10'),
(228, 1, 2, '2025-03-24 14:42:10'),
(230, 1, 1, '2025-03-24 15:19:11'),
(231, 1, 2, '2025-03-24 15:19:11'),
(233, 1, 1, '2025-03-24 15:19:19'),
(234, 1, 2, '2025-03-24 15:19:19'),
(236, 0, 1, '2025-03-24 16:01:09'),
(237, 0, 2, '2025-03-24 16:01:09'),
(239, 1, 1, '2025-03-24 16:01:15'),
(240, 1, 2, '2025-03-24 16:01:15'),
(242, 1, 1, '2025-03-24 16:01:28'),
(243, 1, 2, '2025-03-24 16:01:28'),
(245, 1, 1, '2025-03-24 16:01:30'),
(246, 1, 2, '2025-03-24 16:01:30'),
(248, 1, 1, '2025-03-24 16:04:04'),
(249, 1, 2, '2025-03-24 16:04:04'),
(251, 1, 1, '2025-03-24 16:05:02'),
(252, 1, 2, '2025-03-24 16:05:02'),
(254, 1, 1, '2025-03-24 16:32:02'),
(255, 1, 2, '2025-03-24 16:32:02'),
(257, 1, 1, '2025-03-24 16:46:43'),
(258, 1, 2, '2025-03-24 16:46:43'),
(260, 1, 1, '2025-03-24 16:57:40'),
(261, 1, 2, '2025-03-24 16:57:40'),
(263, 1, 1, '2025-03-24 17:00:52'),
(264, 1, 2, '2025-03-24 17:00:52'),
(266, 1, 1, '2025-03-24 17:04:16'),
(267, 1, 2, '2025-03-24 17:04:16'),
(269, 1, 1, '2025-03-24 17:04:21'),
(270, 1, 2, '2025-03-24 17:04:21'),
(272, 1, 1, '2025-03-24 17:08:27'),
(273, 1, 2, '2025-03-24 17:08:27'),
(274, 0, 1, '2025-03-26 05:46:25'),
(275, 0, 2, '2025-03-26 05:46:25'),
(277, 1, 1, '2025-03-26 05:47:27'),
(278, 1, 2, '2025-03-26 05:47:27'),
(280, 1, 1, '2025-03-26 05:47:43'),
(281, 1, 2, '2025-03-26 05:47:43');

-- --------------------------------------------------------

--
-- Struktur dari tabel `laporan_bulanan`
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `laporan_semester`
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
  `status` enum('diterima','ditolak','diajukan') NOT NULL,
  `keterangan` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `laporan_semester`
--

INSERT INTO `laporan_semester` (`id`, `id_user`, `nama_perusahaan`, `parameter`, `buku_mutu`, `hasil`, `file_laporan`, `file_lhu`, `status`, `keterangan`) VALUES
(9, 1, 'irwan group', 'TSP/DEBU', 'aaaaaaaaa', 'aaaaaaaaa', 'uploads/1742217160_template_surat_rekomendasi_rbb2025.docx', 'uploads/1742217160_PortofolioMuhammadIrwanFirdaus1.pdf', 'diajukan', '-'),
(10, 1, 'irwan group', 'CO', 'aaaaaaaaa', 'baik', 'uploads/1742220771_PortofolioMuhammadIrwanFirdaus1_compressed.pdf', 'uploads/1742220771_Chapter1139.pdf', 'ditolak', 'admin lagi nggak mood'),
(11, 2, 'maya group', 'kebisingan', 'aaaaaaaaa', 'baik', 'uploads/1742379527_AplikasiManajemenAdministrasidanKeuanganSMKN1AmuntaiBerbasisWeb.docx', 'uploads/1742379527_AplikasiManajemenAdministrasidanKeuanganSMKN1AmuntaiBerbasisWeb.pdf', 'diterima', '-'),
(12, 6, 'desain.ln', 'SO2', 'apaaja', 'yagitulah', 'uploads/1742712917_LPJANNIVERSARY4THKALSEL.pdf', 'uploads/1742712917_LPJANNIVERSARY4THKALSEL.pdf', 'ditolak', 'gpp');

-- --------------------------------------------------------

--
-- Struktur dari tabel `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `jenis_konten` enum('gambar','file','link','kosong') NOT NULL,
  `konten` varchar(225) NOT NULL,
  `caption` text NOT NULL,
  `tanggal` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `news`
--

INSERT INTO `news` (`id`, `jenis_konten`, `konten`, `caption`, `tanggal`) VALUES
(1, 'gambar', 'uploads/1742537822_2a3066213824b688a253585469df73fd.jpg', 'adel jkt48 memilih graduation karena alasan kesehatan hal itu membuat banyak penggemar sedih', '2025-03-21 07:17:02'),
(2, 'file', 'uploads/1742538250_CvMuhammadIrwanFirdaus.pdf', 'ini contoh format cv yang terbaru', '2025-03-21 07:24:10');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembangkit`
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pembangkit`
--

INSERT INTO `pembangkit` (`id`, `id_user`, `nama_perusahaan`, `alamat`, `longitude`, `latitude`, `jenis_pembangkit`, `fungsi`, `kapasitas_terpasang`, `daya_mampu_netto`, `jumlah_unit`, `no_unit`, `tahun_operasi`, `status_operasi`, `bahan_bakar_jenis`, `bahan_bakar_satuan`) VALUES
(10, 2, 'maya group', 'Jl. Mistar Cokrokusumo Kelurahan Cempaka, Kecamatan Cempaka, No 21 (Seberang Kelurahan Cempaka)', '110.3091878', '-7.075730113', 'genset', 'darurat', '1.00', '0.58', 1, '1', 2029, 'operasi', 'solar', 'liter');

-- --------------------------------------------------------

--
-- Struktur dari tabel `profil`
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `profil`
--

INSERT INTO `profil` (`id_profil`, `id_user`, `nama_perusahaan`, `kabupaten`, `alamat`, `jenis_usaha`, `no_telp_kantor`, `no_fax`, `tenaga_teknik`, `nama`, `no_hp`, `email`) VALUES
(8, 6, 'desain.ln', 'Kota Banjarbaru', 'Jl. Trikora, Komp. Griya Pesona Bhayangkara, Kel. Guntung Manggis, Kec. Landasan Ulin, Kota Banjarbaru.', 'Jasa', '08115128607', '0', 'Maya', 'Maya Maulina', '08115128607', 'mayamaulina16@gmail.com');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `no_hp` varchar(15) NOT NULL,
  `role` enum('admin','umum') NOT NULL,
  `status` enum('diajukan','diverifikasi','ditolak') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id_user`, `username`, `email`, `password`, `no_hp`, `role`, `status`) VALUES
(1, 'admin', 'admin@gmail.com', '$2y$10$kOhOzPUvmsTDTA2BuKwGMun4IxZvwjVNonHA0K6ocRuJIx4CWoTBm', '081234567890', 'admin', 'diverifikasi'),
(5, 'irwan', 'irwanfirdaus508@gmail.com', '$2y$10$zmgD7Y1gcvhgey.N7VlBPeklVEEiCe2gqjDKE/XcuhrzigIT0dxAq', '', 'umum', 'diverifikasi'),
(6, 'mmaulina', 'mayamaulina16@gmail.com', '$2y$10$Ura5rWA7w7PgA3R1PNdZSuMsDPxRHDEqE24SjpcT6A7iYcHc.mYr2', '', 'umum', 'diverifikasi'),
(7, 'adminmaya', 'mayamaulina16@gmail.com', '$2y$10$HgwEcj1jKVK9FEj0RH9l1efzj0QcW8OrhMQ5X8pDdPCGJz7xQsNW2', '', 'admin', 'diajukan');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `djih`
--
ALTER TABLE `djih`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `djih_dilihat`
--
ALTER TABLE `djih_dilihat`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `konten_dilihat`
--
ALTER TABLE `konten_dilihat`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `laporan_bulanan`
--
ALTER TABLE `laporan_bulanan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `laporan_semester`
--
ALTER TABLE `laporan_semester`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pembangkit`
--
ALTER TABLE `pembangkit`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `profil`
--
ALTER TABLE `profil`
  ADD PRIMARY KEY (`id_profil`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `djih`
--
ALTER TABLE `djih`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `djih_dilihat`
--
ALTER TABLE `djih_dilihat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT untuk tabel `konten_dilihat`
--
ALTER TABLE `konten_dilihat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=283;

--
-- AUTO_INCREMENT untuk tabel `laporan_bulanan`
--
ALTER TABLE `laporan_bulanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `laporan_semester`
--
ALTER TABLE `laporan_semester`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `pembangkit`
--
ALTER TABLE `pembangkit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `profil`
--
ALTER TABLE `profil`
  MODIFY `id_profil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
