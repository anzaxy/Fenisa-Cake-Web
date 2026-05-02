-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 18, 2025 at 07:59 AM
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
-- Database: `fenisa_cake`
--

-- --------------------------------------------------------

--
-- Table structure for table `cake`
--

CREATE TABLE `cake` (
  `id` int(11) NOT NULL,
  `nama_cake` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `harga` decimal(10,2) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `stok` int(11) DEFAULT 0,
  `rating` decimal(2,1) DEFAULT 0.0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cake`
--

INSERT INTO `cake` (`id`, `nama_cake`, `deskripsi`, `harga`, `kategori`, `stok`, `rating`) VALUES
(1, 'Chocolate Deluxe Birthday Cake', 'Cake coklat premium dengan lapisan ganache dan hiasan buttercream. Cocok untuk ulang tahun yang istimewa.', 350000.00, 'Birthday', 13, 4.8),
(2, 'Strawberry Dream Wedding Cake', 'Cake strawberry segar 3 tingkat dengan krim vanilla dan hiasan bunga. Sempurna untuk pernikahan impian.', 1250000.00, 'Wedding', 3, 4.9),
(3, 'Vanilla Anniversary Delight', 'Cake vanilla klasik dengan lapisan krim keju dan hiasan romantis. Ideal untuk perayaan anniversary.', 420000.00, 'Anniversary', 12, 4.7),
(4, 'Rainbow Cupcakes Set', 'Set 12 cupcakes warna-warni dengan berbagai rasa dan topping menarik. Favorit anak-anak!', 180000.00, 'Cupcake', 25, 4.6),
(5, 'Custom Unicorn Cake', 'Cake custom berbentuk unicorn dengan fondant warna-warni dan glitter. Bisa disesuaikan dengan keinginan.', 520000.00, 'Custom', 6, 4.9),
(6, 'Red Velvet Birthday Special', 'Cake red velvet lembut dengan cream cheese frosting dan hiasan coklat putih. Rasa yang tak terlupakan.', 380000.00, 'Birthday', 10, 4.8),
(7, 'Tiramisu Wedding Elegance', 'Cake tiramisu elegan 2 tingkat dengan lapisan mascarpone dan bubuk kakao. Kemewahan Italia untuk hari spesial.', 950000.00, 'Wedding', 4, 4.7),
(8, 'Lemon Cheesecake Anniversary', 'Cheesecake lemon segar dengan base graham cracker dan topping berry. Asam manis yang menyegarkan.', 285000.00, 'Anniversary', 17, 4.5),
(9, 'Chocolate Chip Cupcakes', 'Set 6 cupcakes coklat dengan chocolate chip dan buttercream vanilla. Kombinasi klasik yang selalu disukai.', 95000.00, 'Cupcake', 28, 4.4),
(10, 'Custom Princess Castle', 'Cake custom berbentuk kastil putri dengan menara dan hiasan fondant pink. Impian setiap putri kecil.', 680000.00, 'Custom', 4, 5.0),
(11, 'Black Forest Birthday', 'Cake black forest dengan cherry segar, whipped cream, dan serutan coklat. Klasik Jerman yang autentik.', 395000.00, 'Birthday', 8, 4.6),
(12, 'Naked Wedding Cake', 'Wedding cake bergaya rustic dengan lapisan tipis buttercream dan hiasan bunga segar. Trend terkini!', 850000.00, 'Wedding', 7, 4.8),
(13, 'Carrot Cake Anniversary', 'Cake wortel dengan kacang kenari dan cream cheese frosting. Sehat dan lezat untuk perayaan anniversary.', 340000.00, 'Anniversary', 14, 4.3),
(14, 'Mini Cupcakes Party Pack', 'Set 24 mini cupcakes dengan 6 varian rasa berbeda. Perfect untuk pesta besar!', 240000.00, 'Cupcake', 19, 4.5),
(15, 'Custom Superhero Theme', 'Cake custom dengan tema superhero favorit. Bisa disesuaikan dengan karakter yang diinginkan.', 450000.00, 'Custom', 8, 4.7);

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `id` int(11) NOT NULL,
  `id_order` varchar(50) NOT NULL,
  `nama_cake` varchar(100) NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `nama` varchar(20) NOT NULL,
  `kontak` varchar(13) NOT NULL,
  `alamat` text NOT NULL,
  `tanggal_order` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`id`, `id_order`, `nama_cake`, `harga`, `nama`, `kontak`, `alamat`, `tanggal_order`) VALUES
(1, 'ORD20250618070501280106729', 'Chocolate Chip Cupcakes', 95000.00, 'Marsya', '01234567898', 'Rajeg', '2025-06-18 05:05:01'),
(2, 'ORD20250618071738320580617', 'Strawberry Dream Wedding Cake', 1250000.00, 'Zahra', '0838975208112', 'Pasar Kemis', '2025-06-18 05:17:38');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cake`
--
ALTER TABLE `cake`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_order` (`id_order`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cake`
--
ALTER TABLE `cake`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
