-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 10, 2025 at 02:38 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_uts`
--

-- --------------------------------------------------------

--
-- Table structure for table `tabel_vera`
--

CREATE TABLE `tabel_vera` (
  `id` int(11) NOT NULL,
  `brand` varchar(50) NOT NULL,
  `model` varchar(50) NOT NULL,
  `year` year(4) NOT NULL,
  `price` int(11) NOT NULL,
  `transmission` enum('manual','automatic',''',''','') NOT NULL,
  `photo` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tabel_vera`
--

INSERT INTO `tabel_vera` (`id`, `brand`, `model`, `year`, `price`, `transmission`, `photo`) VALUES
(3, 'mobil', 'brio', '2021', 800000000, 'manual', '85ca11d8f3a5889cc672be6555dc203a.jpg'),
(4, 'mobil', 'agya', '2022', 800000000, 'manual', 'ee87e9b7136250be26e1a14981f1b0f9.jpg'),
(5, 'honda', 'xenia', '2022', 300000000, 'manual', 'vera.jpg'),
(6, 'mobil', 'avanza', '2023', 500000000, 'manual', '1715b3bac1ce6f0068a3f0bf4af86df5.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tabel_vera`
--
ALTER TABLE `tabel_vera`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tabel_vera`
--
ALTER TABLE `tabel_vera`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
