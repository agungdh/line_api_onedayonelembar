-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 27, 2018 at 10:40 PM
-- Server version: 10.1.26-MariaDB
-- PHP Version: 5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `agungdhc_line_odol`
--

-- --------------------------------------------------------

--
-- Table structure for table `fix`
--

CREATE TABLE `fix` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `halaman` int(11) NOT NULL,
  `waktu` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `fix`
--

INSERT INTO `fix` (`id`, `id_user`, `halaman`, `waktu`) VALUES
(41, 26, 2, '2018-01-09 22:19:29'),
(42, 26, 3, '2018-01-10 22:23:17'),
(43, 26, 4, '2018-01-10 22:23:26'),
(44, 26, 5, '2018-01-11 06:10:04'),
(45, 26, 6, '2018-01-11 07:08:40'),
(46, 26, 20, '2018-01-11 08:44:01'),
(47, 26, 3, '2018-01-11 09:37:46'),
(48, 26, 7, '2018-01-11 10:53:17'),
(49, 26, 8, '2018-01-11 11:12:38'),
(50, 26, 10, '2018-01-12 06:15:18'),
(51, 26, 80, '2018-01-13 06:54:40'),
(52, 26, 82, '2018-01-14 06:59:19'),
(53, 26, 2, '2018-01-16 08:38:02'),
(54, 26, 100000, '2018-01-17 07:00:07'),
(55, 26, 2147483647, '2018-01-18 08:00:28'),
(56, 26, 1, '2018-01-18 23:00:16'),
(57, 26, 33, '2018-01-19 00:00:12'),
(58, 26, 51, '2018-01-20 05:00:35'),
(59, 26, 66, '2018-01-21 06:00:30'),
(60, 26, 9999, '2018-01-22 06:50:23');

-- --------------------------------------------------------

--
-- Table structure for table `sementara`
--

CREATE TABLE `sementara` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `halaman` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `id_line` varchar(255) NOT NULL,
  `waktu_daftar` datetime NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `id_line`, `waktu_daftar`, `admin`) VALUES
(26, 'U0d4965553ebeaf022e205e9056895a46', '2018-01-10 21:49:16', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `fix`
--
ALTER TABLE `fix`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `sementara`
--
ALTER TABLE `sementara`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_line` (`id_line`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `fix`
--
ALTER TABLE `fix`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `sementara`
--
ALTER TABLE `sementara`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `fix`
--
ALTER TABLE `fix`
  ADD CONSTRAINT `fix_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`);

--
-- Constraints for table `sementara`
--
ALTER TABLE `sementara`
  ADD CONSTRAINT `sementara_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
