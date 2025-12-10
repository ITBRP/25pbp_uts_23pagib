-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Waktu pembuatan: 10 Des 2025 pada 02.24
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_alfeb`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `uts_alfeb`
--

CREATE TABLE `uts_alfeb` (
  `id` int(11) NOT NULL,
  `brand` varchar(50) NOT NULL,
  `model` varchar(50) NOT NULL,
  `year` year(4) NOT NULL,
  `price` int(11) NOT NULL,
  `transmission` enum('Manual','Automatic',',') NOT NULL,
  `photo` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `uts_alfeb`
--

INSERT INTO `uts_alfeb` (`id`, `brand`, `model`, `year`, `price`, `transmission`, `photo`) VALUES
(6, 'honda', 'audi', '2021', 2147483647, 'Manual', 'b008f530acb526b6fcd69668ece2acae.jpg'),
(7, 'mobil', 'bmw', '2023', 2147483647, 'Automatic', 'alfeb.jpg'),
(9, 'honda', 'bentley', '2021', 2147483647, 'Manual', '9f6764bf4156c31ec04e0bd737588432.jpg'),
(10, 'honda', 'buggati', '2021', 2147483647, 'Manual', 'd4d86e2347149d91a708f72bc8e6e0d6.jpg');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `uts_alfeb`
--
ALTER TABLE `uts_alfeb`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `uts_alfeb`
--
ALTER TABLE `uts_alfeb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
