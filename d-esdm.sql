-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 08 Mar 2025 pada 06.41
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
  `tenaga_teknik` varchar(100) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `no_hp` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `profil`
--

INSERT INTO `profil` (`id_profil`, `id_user`, `nama_perusahaan`, `kabupaten`, `alamat`, `jenis_usaha`, `no_telp_kantor`, `tenaga_teknik`, `nama`, `no_hp`, `email`) VALUES
(6, 1, 'irwan group', 'Tanah Bumbu', 'asam-asam', 'Tambang', '88247342027', 'irwan', 'MUHAMMAD IRWAN FIRDAUS', '88247342027', 'irwan@gmail.com');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','umum') NOT NULL,
  `status` enum('diajukan','diverifikasi','ditolak') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id_user`, `username`, `email`, `password`, `role`, `status`) VALUES
(1, 'admin', 'admin@gmail.com', 'admin', 'umum', 'diajukan');

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT untuk tabel `pembangkit`
--
ALTER TABLE `pembangkit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `profil`
--
ALTER TABLE `profil`
  MODIFY `id_profil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
