-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 26, 2025 at 01:37 PM
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
-- Database: `hotel`
--

-- --------------------------------------------------------

--
-- Table structure for table `hotel`
--

CREATE TABLE `hotel` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `kecamatan` varchar(100) DEFAULT NULL,
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL,
  `kategori` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hotel`
--

INSERT INTO `hotel` (`id`, `nama`, `alamat`, `kecamatan`, `latitude`, `longitude`, `kategori`) VALUES
(1, 'Sunlake Waterfront Resort & Convention', 'Jl. Danau Permai Raya Blok C1, Sunter Agung, Jakarta Utara', 'Tanjung Priok', -6.14656, 106.87934, '5★'),
(2, 'Discovery Ancol', 'Taman Impian Jaya, Jl. Lodan Timur No.7, Ancol, Kec. Pademangan, Jkt Utara', 'Pademangan', -6.12625, 106.8315, '4★'),
(3, 'ASTON Pluit Hotel & Residence', 'Jl. Pluit Selatan Raya No.1, RT.12/RW.9, Pluit, Kec. Penjaringan, Jkt Utara', 'Penjaringan', -6.12434, 106.79513, '4★'),
(4, 'Mercure Jakarta Pantai Indah Kapuk', 'Jl. Pantai Indah Kapuk, Kamal Muara, Kec. Penjaringan, Jkt Utara', 'Penjaringan', -6.10959, 106.74071, '4★'),
(5, 'Mercure Convention Center Ancol', 'Jl. Pantai Indah, Ancol, Jakarta Baycity, Jkt Utara', 'Pademangan', -6.12216, 106.83655, '4★'),
(6, 'HARRIS Hotel & Conventions Kelapa Gading', 'Jl. Boulevard Raya Blok M, Klp. Gading Tim., Kec. Klp. Gading, Jkt Utara', 'Kelapa Gading', -6.15846, 106.90953, '4★'),
(7, 'Ibis Styles Jakarta Mangga Dua Square', 'Jl. Gn. Sahari No.1, Ancol, Kec. Pademangan, Jakarta', 'Pademangan', -6.13887, 106.83223, '3★'),
(8, 'Ibis Styles Jakarta Sunter', 'Jl. Gaya Motor 1, Sungai Bambu, Sunter, Jakarta', 'Tanjung Priok', -6.14016, 106.88596, '3★'),
(9, 'Coins Hotel Jakarta', 'Jalan Sunter Agung Utara Raya Blok A No. 5B, RT.00, Sunter Agung, Kec. Tj. Priok, Jkt Utara', 'Tanjung Priok', -6.13841, 106.86033, '3★'),
(10, '101 URBAN Jakarta Kelapa Gading', 'Jl. Boulevard Bukit Gading Raya No.25, RT.15/RW.3, Klp. Gading Bar., Kec. Klp. Gading, Jkt Utara', 'Kelapa Gading', -6.16044, 106.8943, '3★'),
(11, 'Oakwood Apartments PIK Jakarta', 'RT.6/RW.2, Kamal Muara, Kec. Penjaringan, Jkt Utara, Daerah Khusus Ibukota Jakarta 14470', 'Penjaringan', -6.10216, 106.73896, '5★'),
(12, 'Putri Duyung Ancol', 'Jl. Lodan timur no.7 Taman impian jaya area Putri duyung resort, RT.6/RW.10, Ancol, Kec. Pademangan, Jkt Utara', 'Pademangan', -6.12166, 106.84096, '3★'),
(13, 'Whiz Prime Hotel Kelapa Gading', 'Ruko Inkopal, Komplek Jl. Boulevard Bar. Raya Blok B No.18, RT.2/RW.9, Klp. Gading Bar., Kec. Klp. Gading, Jkt Utara', 'Kelapa Gading', -6.15332, 106.89257, '3★'),
(14, 'd\'ARCICI Hotel ALUR LAUT', '8, Gg. Bete No.6, RT.6/RW.2, Rawabadak Sel., Kec. Koja, Jkt Utara', 'Koja', -6.106, 106.89841, '3★'),
(15, 'Lantai 6 Hotel', 'Kapuk Bisnis Park, Jl. Kapuk Raya No.28 5, RT.14/RW.12, Kapuk Muara, Kec. Penjaringan, Jkt Utara', 'Penjaringan', -6.12756, 106.7431, '2★'),
(16, 'JP Hotel Pluit', 'Jl. Pluit Raya No.35A, Penjaringan, Kec. Penjaringan, Jakarta, Daerah Khusus Ibukota Jakarta 14450', 'Penjaringan', -6.1267, 106.79738, '3★'),
(17, 'Holiday Inn Express Jakarta Pluit Citygate', 'Emporium Pluit Mall 10th Floor, Jl. Pluit Selatan Raya, RT.23/RW.8, Penjaringan, Kec. Penjaringan, Jakarta', 'Penjaringan', -6.12702, 106.79101, '3★'),
(18, 'RedDoorz Plus Near Ancol', 'Jl. RE Martadinata No.17, RT.6/RW.4, Ancol, Kec. Pademangan, Jkt Utara', 'Pademangan', -6.1253, 106.82523, '3★'),
(19, 'New Priok Indah Syariah Hotel', 'Jl. Raya Cilincing No.12 14, RT.2/RW.1, Lagoa, Kec. Koja, Jkt Utara, Daerah Khusus Ibukota Jakarta 14270', 'Koja', -6.1086, 106.91583, '2★'),
(20, 'Hotel Santika Kelapa Gading', 'Mahaka Square, Jl. Raya Kelapa Nias Blok HF3 No.17, RT.8/RW.6, Klp. Gading Bar., Kec. Klp. Gading, Jkt Utara', 'Kelapa Gading', -6.15018, 106.90272, '3★');

-- --------------------------------------------------------

--
-- Table structure for table `hotel_jakarta_utara`
--

CREATE TABLE `hotel_jakarta_utara` (
  `kecamatan` varchar(50) NOT NULL,
  `jml` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hotel_jakarta_utara`
--

INSERT INTO `hotel_jakarta_utara` (`kecamatan`, `jml`) VALUES
('Cilincing', 3),
('Kelapa Gading', 8),
('Koja', 1),
('Pademangan', 18),
('Penjaringan', 18),
('Tanjung Priok', 13);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `hotel`
--
ALTER TABLE `hotel`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hotel_jakarta_utara`
--
ALTER TABLE `hotel_jakarta_utara`
  ADD PRIMARY KEY (`kecamatan`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `hotel`
--
ALTER TABLE `hotel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
