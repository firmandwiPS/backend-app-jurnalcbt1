-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 23, 2025 at 02:47 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `aplikasi-jurnal-pkl-cbt1`
--

-- --------------------------------------------------------

--
-- Table structure for table `berita`
--

CREATE TABLE `berita` (
  `id_berita` int NOT NULL,
  `judul_singkat` varchar(150) NOT NULL,
  `judul_lengkap` varchar(255) NOT NULL,
  `isi` text NOT NULL,
  `kategori` varchar(100) NOT NULL,
  `gambar` text,
  `tanggal` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `berita`
--

INSERT INTO `berita` (`id_berita`, `judul_singkat`, `judul_lengkap`, `isi`, `kategori`, `gambar`, `tanggal`) VALUES
(4, 'mkn', 'mkn', 'mkn', 'Tentang Sekolah', '[\"uploads/images_berita/berita_1755878023_0.jpg\",\"uploads/images_berita/berita_1755878023_1.jpg\"]', '2025-08-22 15:53:43'),
(5, 'wel', 'wl', 'well', 'Pengumuman', '[\"uploads/images_berita/berita_1755878422_0.jpg\"]', '2025-08-22 16:00:22');

-- --------------------------------------------------------

--
-- Table structure for table `biodata_pkl_siswa`
--

CREATE TABLE `biodata_pkl_siswa` (
  `id` int NOT NULL,
  `nis` varchar(20) NOT NULL,
  `tempat_pkl` varchar(100) NOT NULL,
  `mulai_pkl` date NOT NULL,
  `selesai_pkl` date NOT NULL,
  `pembimbing_perusahaan` varchar(100) NOT NULL,
  `no_hp_pembimbing_perusahaan` varchar(15) NOT NULL,
  `pembimbing_sekolah` varchar(100) NOT NULL,
  `no_hp_pembimbing_sekolah` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `biodata_pkl_siswa`
--

INSERT INTO `biodata_pkl_siswa` (`id`, `nis`, `tempat_pkl`, `mulai_pkl`, `selesai_pkl`, `pembimbing_perusahaan`, `no_hp_pembimbing_perusahaan`, `pembimbing_sekolah`, `no_hp_pembimbing_sekolah`) VALUES
(3, '222310266', 'PT Hanken Indonesia ', '2025-08-01', '2025-11-01', 'Pa Catur', '0825282862', 'Bu rindy ', '0825292588');

-- --------------------------------------------------------

--
-- Table structure for table `biodata_siswa`
--

CREATE TABLE `biodata_siswa` (
  `id` int NOT NULL,
  `nis` varchar(20) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `jenis_kelamin` varchar(10) NOT NULL,
  `kelas` varchar(20) NOT NULL,
  `jurusan` varchar(100) NOT NULL,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `biodata_siswa`
--

INSERT INTO `biodata_siswa` (`id`, `nis`, `nama_lengkap`, `jenis_kelamin`, `kelas`, `jurusan`, `foto`) VALUES
(4, '222310266', 'Firman Dwi Putra Setiawan ', 'Laki-laki', '12 Sija 2 ', 'Sistem informasi jaringan dan aplikasi ', '222310266.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `id` int NOT NULL,
  `nis` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `key_akses` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `level` varchar(2) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`id`, `nis`, `key_akses`, `level`) VALUES
(1, 'admin', 'admin', '1'),
(2, 'kepsek', 'kepsek', '2'),
(3, 'guru', 'guru', '3'),
(4, 'ortu', 'ortu', '4'),
(5, '222310266', '222310266', '5'),
(6, '222310277', '222310277', '5'),
(7, '222310288', '222310288', '5'),
(11, '222310260', '222310260', '5');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `berita`
--
ALTER TABLE `berita`
  ADD PRIMARY KEY (`id_berita`);

--
-- Indexes for table `biodata_pkl_siswa`
--
ALTER TABLE `biodata_pkl_siswa`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `biodata_siswa`
--
ALTER TABLE `biodata_siswa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nis` (`nis`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `berita`
--
ALTER TABLE `berita`
  MODIFY `id_berita` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `biodata_pkl_siswa`
--
ALTER TABLE `biodata_pkl_siswa`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `biodata_siswa`
--
ALTER TABLE `biodata_siswa`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
