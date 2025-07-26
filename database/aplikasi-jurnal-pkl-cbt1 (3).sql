-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 25, 2025 at 02:45 PM
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
-- Table structure for table `biodata_pkl_siswa`
--

CREATE TABLE `biodata_pkl_siswa` (
  `id` int NOT NULL,
  `nis` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `tempat_pkl` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `alamat_pkl` text COLLATE utf8mb4_general_ci,
  `bidang_kerja` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pembimbing` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `mulai_pkl` date DEFAULT NULL,
  `selesai_pkl` date DEFAULT NULL,
  `status_pkl` enum('berjalan','selesai') COLLATE utf8mb4_general_ci DEFAULT 'berjalan',
  `catatan_pkl` text COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `biodata_pkl_siswa`
--

INSERT INTO `biodata_pkl_siswa` (`id`, `nis`, `tempat_pkl`, `alamat_pkl`, `bidang_kerja`, `pembimbing`, `mulai_pkl`, `selesai_pkl`, `status_pkl`, `catatan_pkl`) VALUES
(1, '222310266', 'PT. Teknologi Hebat', 'Jl. Industri No. 88', 'Software Development', 'Budi Santoso', '2025-01-01', '2025-06-30', 'berjalan', 'Siswa sangat aktif.'),
(2, '222310277', 'CV. Komputer Maju', 'Jl. Telekom No. 55', 'Network Engineer', 'Siti Rahma', '2025-02-01', '2025-07-31', 'berjalan', 'Perlu lebih disiplin.'),
(3, '222310288', 'Studio Kreatif', 'Jl. Studio No. 9', 'Desain Grafis', 'Anton Wijaya', '2025-01-15', '2025-07-15', 'selesai', 'Hasil desain bagus.');

-- --------------------------------------------------------

--
-- Table structure for table `biodata_siswa`
--

CREATE TABLE `biodata_siswa` (
  `nis` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `nama_lengkap` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `kelas` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `jurusan` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `tempat_lahir` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat_rumah` text COLLATE utf8mb4_general_ci,
  `no_hp` varchar(15) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `biodata_siswa`
--

INSERT INTO `biodata_siswa` (`nis`, `nama_lengkap`, `kelas`, `jurusan`, `tempat_lahir`, `tanggal_lahir`, `alamat_rumah`, `no_hp`) VALUES
('222310266', 'Rizky Saputra', 'XII RPL', 'Rekayasa Perangkat Lunak', 'Bandung', '2006-04-15', 'Jl. Merdeka No. 10', '08123456789'),
('222310277', 'Ayu Lestari', 'XII TKJ', 'Teknik Komputer dan Jaringan', 'Jakarta', '2006-08-20', 'Jl. Kembang No. 5', '08129876543'),
('222310288', 'Dimas Pratama', 'XII MM', 'Multimedia', 'Surabaya', '2006-07-02', 'Jl. Mawar No. 15', '08998765432');

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
(7, '222310288', '222310288', '5');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `biodata_pkl_siswa`
--
ALTER TABLE `biodata_pkl_siswa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nis` (`nis`);

--
-- Indexes for table `biodata_siswa`
--
ALTER TABLE `biodata_siswa`
  ADD PRIMARY KEY (`nis`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `biodata_pkl_siswa`
--
ALTER TABLE `biodata_pkl_siswa`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `biodata_pkl_siswa`
--
ALTER TABLE `biodata_pkl_siswa`
  ADD CONSTRAINT `biodata_pkl_siswa_ibfk_1` FOREIGN KEY (`nis`) REFERENCES `biodata_siswa` (`nis`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
