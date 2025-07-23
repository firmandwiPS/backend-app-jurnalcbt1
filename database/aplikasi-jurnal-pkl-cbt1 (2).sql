-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 23, 2025 at 10:30 AM
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
-- Table structure for table `biodata_siswa`
--

CREATE TABLE `biodata_siswa` (
  `nis` varchar(20) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `kelas` varchar(10) NOT NULL,
  `jurusan` varchar(50) DEFAULT NULL,
  `tempat_lahir` varchar(50) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat_rumah` text,
  `no_hp` varchar(15) DEFAULT NULL,
  `tempat_pkl` varchar(100) DEFAULT NULL,
  `alamat_pkl` text,
  `bidang_kerja` varchar(100) DEFAULT NULL,
  `pembimbing` varchar(100) DEFAULT NULL,
  `mulai_pkl` date DEFAULT NULL,
  `selesai_pkl` date DEFAULT NULL,
  `status_pkl` enum('berjalan','selesai') DEFAULT 'berjalan',
  `catatan_pkl` text,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `biodata_siswa`
--

INSERT INTO `biodata_siswa` (`nis`, `nama_lengkap`, `kelas`, `jurusan`, `tempat_lahir`, `tanggal_lahir`, `alamat_rumah`, `no_hp`, `tempat_pkl`, `alamat_pkl`, `bidang_kerja`, `pembimbing`, `mulai_pkl`, `selesai_pkl`, `status_pkl`, `catatan_pkl`, `foto`) VALUES
('222310266', 'Rizky Saputra', 'XII RPL', 'Rekayasa Perangkat Lunak', 'Bandung', '2006-04-15', 'Jl. Merdeka No. 10', '08123456789', 'PT. Teknologi Hebat', 'Jl. Industri No. 88', 'Software Development', 'Budi Santoso', '2025-01-01', '2025-06-30', 'berjalan', 'Anak ini sangat rajin.', 'http://10.0.2.2/backend-app-jurnalcbt1/foto/222310266.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `id` int NOT NULL,
  `nis` varchar(20) NOT NULL,
  `key_akses` varchar(100) NOT NULL,
  `level` varchar(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`id`, `nis`, `key_akses`, `level`) VALUES
(1, 'admin', 'admin', '1'),
(2, 'kepsek', 'kepsek', '2'),
(3, 'guru', 'guru', '3'),
(4, 'ortu', 'ortu', '4'),
(5, '222310266', '222310266', '5');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `biodata_siswa`
--
ALTER TABLE `biodata_siswa`
  ADD PRIMARY KEY (`nis`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nis` (`nis`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
