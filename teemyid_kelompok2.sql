-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 30, 2026 at 10:20 PM
-- Server version: 10.6.17-MariaDB-cll-lve
-- PHP Version: 8.4.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `teemyid_kelompok2`
--

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `username` varchar(100) NOT NULL,
  `password` varchar(200) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `alamat` text NOT NULL,
  `kota` varchar(50) NOT NULL,
  `tlp` varchar(100) NOT NULL,
  `level` varchar(50) NOT NULL,
  `tipe` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`username`, `password`, `nama`, `alamat`, `kota`, `tlp`, `level`, `tipe`, `status`) VALUES
('admin', '$2y$12$Dp0gOa/b0mheXE5nK1m16OhdRDrDwZmh2LwG4a4eU2MRG07aQeJEO', 'admin', 'polines', 'Semarang', '081234567890', 'Admin', '', 'Aktif'),
('bendahara', '$2y$12$rbSt9BwRAOayDTDp9Ukyke.GYJAMOqZSg.cn7H5jJrVAPkqA3C0ku', 'Bendahara', 'Srondol', 'Semarang', '081234567890', 'Bendahara', '', 'Aktif'),
('irfan', '$2y$12$V1Bnq2gtmJFt/Lbb8aE5s.1x3Y8mcr6xBGeHuta82VUpVJlksq9R2', 'irfan', 'srondol kulon', 'Semarang', '081234567890', 'Warga', 'Kos', 'Aktif'),
('paktani', '$2y$12$SRcMCbAJ/YRzmB1dGIIt5.G4zFqc5Bgt.R03kHpWZ2D6Yjm1uVQW.', 'paktani', 'ceper', 'Semarang', '081234567890', 'Warga', 'RT', 'Aktif'),
('petugas', '$2y$12$oczBPTLeyPZXFxcyEPerOeRm92WxELLmk3MHagU2zgSNjfU0UXELu', 'Petugas', 'Banyumanik', 'Semarang', '081234567890', 'Petugas', '', 'Aktif'),
('rafa', '$2y$12$bBbu/3B82MNok6TZnjoYiOo2uAwy4I0dCstJfosUSbZBrJZIvvl7i', 'rafa', 'banyumanik', 'Semarang', '08129181991818', 'Warga', 'RT', 'Aktif'),
('warga', '$2y$12$PmlIejozEARlIC5PT1U16u8BXtJKw.ud4VfqKSpUZhNvQlKTmPFpi', 'Warga', 'Gg. Ceper Sari', 'Semarang', '081234567890', 'Warga', 'Kos', 'Aktif');

-- --------------------------------------------------------

--
-- Table structure for table `pemakaian`
--

CREATE TABLE `pemakaian` (
  `no` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `meter_awal` smallint(6) NOT NULL,
  `meter_akhir` smallint(6) NOT NULL,
  `pemakaian` smallint(6) NOT NULL,
  `tgl` date NOT NULL,
  `waktu` time NOT NULL,
  `kd_tarif` varchar(10) NOT NULL,
  `tagihan` mediumint(9) NOT NULL,
  `status` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `pemakaian`
--

INSERT INTO `pemakaian` (`no`, `username`, `meter_awal`, `meter_akhir`, `pemakaian`, `tgl`, `waktu`, `kd_tarif`, `tagihan`, `status`) VALUES
(25, 'irfan', 0, 12, 12, '2026-01-20', '08:23:27', 'T2', 60000, 'Lunas'),
(26, 'irfan', 12, 15, 3, '2026-02-18', '11:13:46', 'T2', 15000, 'Lunas'),
(27, 'rafa', 0, 10, 10, '2026-01-23', '10:55:06', 'T1', 70000, 'Lunas'),
(28, 'paktani', 0, 18, 18, '2026-01-21', '08:55:25', 'T1', 126000, 'Lunas'),
(30, 'irfan', 15, 35, 20, '2026-03-16', '09:57:21', 'T2', 100000, 'Lunas'),
(31, 'paktani', 18, 24, 6, '2026-02-15', '06:27:59', 'T1', 42000, 'Lunas'),
(32, 'rafa', 10, 25, 15, '2026-02-09', '07:19:09', 'T1', 105000, 'Lunas'),
(34, 'paktani', 24, 36, 12, '2026-03-08', '13:27:27', 'T1', 84000, 'Lunas'),
(35, 'rafa', 25, 41, 16, '2026-03-09', '11:27:37', 'T1', 112000, 'Lunas'),
(38, 'irfan', 35, 45, 10, '2026-04-22', '07:07:58', 'T2', 50000, 'Lunas'),
(39, 'paktani', 36, 51, 15, '2026-04-13', '14:44:06', 'T1', 105000, 'Lunas'),
(40, 'rafa', 41, 67, 26, '2026-04-11', '12:36:17', 'T1', 182000, 'Lunas'),
(42, 'paktani', 51, 65, 14, '2026-05-13', '13:37:51', 'T1', 98000, 'Lunas'),
(43, 'rafa', 67, 77, 10, '2026-05-10', '16:48:04', 'T1', 70000, 'Belum Lunas'),
(45, 'warga', 0, 20, 20, '2026-01-20', '07:43:05', 'T2', 100000, 'Lunas'),
(46, 'warga', 20, 36, 16, '2026-02-07', '11:44:02', 'T2', 80000, 'Lunas'),
(47, 'warga', 36, 51, 15, '2026-03-17', '10:45:10', 'T2', 75000, 'Lunas'),
(48, 'warga', 51, 67, 16, '2026-04-20', '12:46:22', 'T2', 80000, 'Lunas'),
(51, 'irfan', 45, 53, 8, '2026-05-28', '09:38:09', 'T2', 40000, 'Lunas'),
(53, 'warga', 67, 98, 31, '2026-05-12', '10:13:36', 'T2', 155000, 'Lunas'),
(54, 'paktani', 65, 79, 14, '2026-06-10', '09:22:28', 'T1', 98000, 'Lunas');

-- --------------------------------------------------------

--
-- Table structure for table `tarif`
--

CREATE TABLE `tarif` (
  `kd_tarif` varchar(10) NOT NULL,
  `tarif` smallint(6) NOT NULL,
  `tipe` varchar(10) NOT NULL,
  `status` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tarif`
--

INSERT INTO `tarif` (`kd_tarif`, `tarif`, `tipe`, `status`) VALUES
('T1', 7000, 'RT', 'Aktif'),
('T2', 5000, 'Kos', 'Aktif'),
('T3', 7500, 'RT', 'Non-Aktif');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pemakaian`
--
ALTER TABLE `pemakaian`
  ADD PRIMARY KEY (`no`);

--
-- Indexes for table `tarif`
--
ALTER TABLE `tarif`
  ADD PRIMARY KEY (`kd_tarif`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pemakaian`
--
ALTER TABLE `pemakaian`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
