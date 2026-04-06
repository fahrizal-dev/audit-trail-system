-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 06, 2026 at 09:07 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_audit`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_activity`
--

CREATE TABLE `tb_activity` (
  `id_activity` int(11) NOT NULL,
  `id_aplikasi` int(3) DEFAULT NULL,
  `modidate` datetime DEFAULT NULL,
  `user` varchar(100) DEFAULT NULL,
  `menu_fitur` varchar(200) DEFAULT NULL,
  `no_rm` varchar(10) DEFAULT NULL,
  `aksi` varchar(50) DEFAULT NULL,
  `hasil` varchar(50) DEFAULT NULL,
  `trx_id` varchar(100) DEFAULT NULL,
  `rawat` varchar(3) DEFAULT NULL,
  `ip_address` varchar(15) DEFAULT NULL,
  `ket` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Triggers `tb_activity`
--
DELIMITER $$
CREATE TRIGGER `UPDATE_ACT` AFTER INSERT ON `tb_activity` FOR EACH ROW update tb_user_app set last_update = now() where id_aplikasi = new.id_aplikasi
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `tb_admin`
--

CREATE TABLE `tb_admin` (
  `username` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `nama` varchar(200) DEFAULT NULL,
  `jabatan` varchar(200) DEFAULT NULL,
  `password` varchar(1000) DEFAULT NULL,
  `status_active` tinyint(1) DEFAULT 1,
  `modiby` varchar(50) DEFAULT NULL,
  `modidate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_admin`
--

INSERT INTO `tb_admin` (`username`, `email`, `nama`, `jabatan`, `password`, `status_active`, `modiby`, `modidate`) VALUES
('admin', 'admin@gmail.com', 'Isca Nyet', 'admin1', '$2y$10$hjjgOGMd6KxValF/8si/QuhDfTebPUKKhNMVh4Vo77AYN2bm.oLpS', 1, 'SYSTEM', '2025-12-12 14:10:35');

-- --------------------------------------------------------

--
-- Table structure for table `tb_log_api`
--

CREATE TABLE `tb_log_api` (
  `id_log` int(11) NOT NULL,
  `waktu_akses` datetime DEFAULT current_timestamp(),
  `ip_address` varchar(15) DEFAULT NULL,
  `metode` varchar(10) DEFAULT NULL,
  `request` longtext DEFAULT NULL,
  `response` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tb_token`
--

CREATE TABLE `tb_token` (
  `id_token` int(11) NOT NULL,
  `id_aplikasi` int(3) DEFAULT NULL,
  `modidate` datetime DEFAULT NULL,
  `token` varchar(1000) DEFAULT NULL,
  `use_date` datetime DEFAULT NULL,
  `exp_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tb_user_app`
--

CREATE TABLE `tb_user_app` (
  `id_aplikasi` int(3) NOT NULL,
  `NM_APLIKASI` varchar(50) NOT NULL,
  `user_name` varchar(20) NOT NULL,
  `password` varchar(1000) NOT NULL,
  `secret_key` varchar(1000) DEFAULT NULL,
  `status_active` tinyint(1) DEFAULT 1,
  `modiby` varchar(50) DEFAULT NULL,
  `modidate` datetime DEFAULT NULL,
  `IP_ADDRESS` varchar(15) NOT NULL,
  `last_update` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_user_app`
--

INSERT INTO `tb_user_app` (`id_aplikasi`, `NM_APLIKASI`, `user_name`, `password`, `secret_key`, `status_active`, `modiby`, `modidate`, `IP_ADDRESS`, `last_update`) VALUES
(2, 'SIDORRS', 'sidorrs_app', '5c2e8cf990c9b4853b7c3491182c20092dfb6b3cd10a1c9e39ce66e9254e777f', 'SIDORRS_SECRET_2026', 1, '2', '2026-04-06 10:05:50', '127.0.0.1', '2026-04-06 10:05:50'),
(3, 'Sistem Rapat', 'rapat_app', 'f6d4f3560959dc15d3764c843287bb19536845c1b080f03032d6aece7558839c', '3134cf0d0a236485d0a13c2aaadd5763', 1, '3', '2026-04-06 11:27:36', '::1', '2026-04-06 11:27:36');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_activity`
--
ALTER TABLE `tb_activity`
  ADD PRIMARY KEY (`id_activity`);

--
-- Indexes for table `tb_admin`
--
ALTER TABLE `tb_admin`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `tb_log_api`
--
ALTER TABLE `tb_log_api`
  ADD PRIMARY KEY (`id_log`);

--
-- Indexes for table `tb_token`
--
ALTER TABLE `tb_token`
  ADD PRIMARY KEY (`id_token`),
  ADD KEY `id_aplikasi` (`id_aplikasi`);

--
-- Indexes for table `tb_user_app`
--
ALTER TABLE `tb_user_app`
  ADD PRIMARY KEY (`id_aplikasi`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_activity`
--
ALTER TABLE `tb_activity`
  MODIFY `id_activity` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_log_api`
--
ALTER TABLE `tb_log_api`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_token`
--
ALTER TABLE `tb_token`
  MODIFY `id_token` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_token`
--
ALTER TABLE `tb_token`
  ADD CONSTRAINT `tb_token_ibfk_1` FOREIGN KEY (`id_aplikasi`) REFERENCES `tb_user_app` (`id_aplikasi`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
