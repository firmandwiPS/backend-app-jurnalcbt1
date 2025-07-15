-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 15, 2025 at 12:11 AM
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
-- Database: `toko-iki`
--

-- --------------------------------------------------------

--
-- Table structure for table `akun`
--

CREATE TABLE `akun` (
  `id_akun` int NOT NULL,
  `nama` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `level` varchar(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `akun`
--

INSERT INTO `akun` (`id_akun`, `nama`, `username`, `email`, `password`, `level`) VALUES
(18, 'admin', 'admin', 'Admin@gmail.com', '$2y$10$rAEf9i/gSH64uKefDhApdeIIC1xuGvBlvIRmt27CaugLgnWk.vvcm', '1'),
(22, 'kepsek', 'kepsek', 'kepsek@gmail.com', '$2y$10$iGnacp/ODFbYirkxBVYL7eY9PGoTg2NPIUNdEzL965sDTd8HxGL3G', '2'),
(23, 'guru', 'guru', 'guru@gmail.com', '$2y$10$5765t3ytUCXmDn9RAJb4N.TinESqc6B0DfFfapAIj7qokB4UU8wLe', '3'),
(24, 'ortu', 'ortu', 'ortu@gmail.com', '$2y$10$6ARSA7SZynZbedfvHYJ0teO.D2qfequoIkt8v0rMYryuawDR7xu7G', '4'),
(25, 'siwa', 'siswa', 'siswa@gmail.com', '$2y$10$HDvc0D0ubLKz7mN5ajvmSe0pmw2CqDSJGgUIKZa9EdGenuPrYI7QW', '5');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `akun`
--
ALTER TABLE `akun`
  ADD PRIMARY KEY (`id_akun`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `akun`
--
ALTER TABLE `akun`
  MODIFY `id_akun` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
