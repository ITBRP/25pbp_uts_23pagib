-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 10, 2025 at 02:24 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_yuni`
--

-- --------------------------------------------------------

--
-- Table structure for table `uts_yuni`
--

CREATE TABLE `uts_yuni` (
  `id` int(11) NOT NULL,
  `brand` varchar(50) NOT NULL,
  `model` varchar(50) NOT NULL,
  `year` year(4) NOT NULL,
  `price` int(11) NOT NULL,
  `transmission` enum('Manual','Automatic','","') NOT NULL,
  `photo` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `uts_yuni`
--

INSERT INTO `uts_yuni` (`id`, `brand`, `model`, `year`, `price`, `transmission`, `photo`) VALUES
(7, 'honda', 'hyundai', '2023', 2147483647, 'Manual', 'f405d62da429bee1a13e06f0ea8aa4e3.jpg'),
(10, 'honda', 'tesla', '2022', 2147483647, 'Manual', '7a1e7e681607da7d65ee9c2eb00b72d8.jpg'),
(11, 'mobil', 'ferrari', '2025', 2147483647, 'Automatic', 'yuni.jpg'),
(13, 'honda', 'audi', '2024', 2147483647, 'Manual', '71277fdd0ba3b8c1286df30a511ef608.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `uts_yuni`
--
ALTER TABLE `uts_yuni`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `uts_yuni`
--
ALTER TABLE `uts_yuni`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
