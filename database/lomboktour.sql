-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 24, 2026 at 08:28 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lomboktour`
--

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id` int(11) NOT NULL,
  `nama_kategori` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id`, `nama_kategori`) VALUES
(1, 'Alam'),
(2, 'Budaya'),
(3, 'Desa Wisata'),
(4, 'Gunung'),
(5, 'Kuliner'),
(6, 'Pantai'),
(7, 'Religi');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama_lengkap`, `username`, `password`) VALUES
(1, 'Administrator', 'admin', '$2y$10$8.D.g.d.g.d.g.d.g.d.g.d.g.d.g.d.g.d.g.d.g.d.g.d.g.d.g'),
(2, 'user1', 'user1', '$2y$10$bvkg01L.5dzTcVsPlRlm/OjtaLKGrRa7AGa4H/lKH8bMfFdjDBEky'),
(3, 'user2', 'user2', '$2y$10$avPj8Wg9yeOe.LdsiTqLQOgivG45cR7b7Eov8J9IMo9IVP2nyp90i');

-- --------------------------------------------------------

--
-- Table structure for table `wisata`
--

CREATE TABLE `wisata` (
  `id` int(11) NOT NULL,
  `kategori_id` int(11) NOT NULL,
  `nama_wisata` varchar(100) NOT NULL,
  `lokasi` varchar(150) NOT NULL,
  `total_pengunjung` int(11) DEFAULT 0,
  `deskripsi` text DEFAULT NULL,
  `harga_tiket` decimal(10,2) NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wisata`
--

INSERT INTO `wisata` (`id`, `kategori_id`, `nama_wisata`, `lokasi`, `total_pengunjung`, `deskripsi`, `harga_tiket`, `gambar`, `created_at`) VALUES
(1, 4, 'Gunung Rinjani', 'Pulau Lombok, Nusa Tenggara Barat (NTB).', 132322, 'Gunung Rinjani adalah gunung berapi aktif tertinggi kedua di Indonesia (3.726 mdpl) yang terletak di Lombok, NTB, terkenal dengan keindahan alamnya seperti Danau Segara Anak di kawahnya, flora dan fauna endemik, serta memiliki nilai spiritual bagi masyarakat Sasak. Menjadi destinasi pendakian populer, Rinjani menawarkan berbagai jalur dengan tantangan dan keunikan, namun kini dengan aturan baru yang lebih ketat terkait keselamatan, termasuk penggunaan asuransi dan pemandu melalui pembelian tiket online.', 250000.00, '1768839000_696dc4d9cdce8.jpg', '2026-01-19 16:10:00'),
(3, 3, 'Desa Sade (Rembitan)', 'Desa Rembitan, Kecamatan Pujut, Kabupaten Lombok Tengah, Nusa Tenggara Barat.', 150, 'Desa Sade di Rembitan, Lombok Tengah, adalah desa adat Sasak kuno yang melestarikan tradisi asli, terkenal dengan rumah adat \"Bale Tani\" berdinding anyaman bambu, atap jerami, dan lantai yang dilap dengan kotoran kerbau agar hangat serta bebas nyamuk. Desa ini merupakan destinasi ekowisata unggulan yang menawarkan pengalaman budaya autentik melalui kerajinan tenun (songket), tarian tradisional (Tari Peresean), dan pertunjukan musik Gendang Beleq, serta menjadi pusat kehidupan Suku Sasak asli di Lombok.', 15000.00, '1768841673_696dc610246ba.jpg', '2026-01-19 16:54:33'),
(4, 6, 'Pantai Kuta Lombok', 'Kabupaten Lombok Tengah, Provinsi Nusa Tenggara Barat.', 200, 'Pantai Kuta, Lombok adalah tempat wisata di Pulau Lombok. Pantai ini secara administratif berada di Kabupaten Lombok Tengah, Provinsi Nusa Tenggara Barat, Indonesia. Pantai dengan pasir berwarna putih seperti buliran merica ini terletak di Kawasan Ekonomi Khusus Mandalika di Desa Kuta.', 10000.00, '1768874289_unnamed[1].jpg', '2026-01-20 01:58:09'),
(5, 4, 'Savana Propok Lombok', 'Jl. Wisata Gn. Rinjani, Bebidas, Kec. Wanasaba, Kabupaten Lombok Timur.', 2, 'Bukit Kondo dan Savana Propok di Sembalun adalah destinasi wisata terbaru di Pulau Lombok yang tengah populer karena keindahan alamnya.', 10000.00, '1768876924_Savana-Propok[1].jpg', '2026-01-20 02:42:04');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `wisata`
--
ALTER TABLE `wisata`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kategori_id` (`kategori_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `wisata`
--
ALTER TABLE `wisata`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `wisata`
--
ALTER TABLE `wisata`
  ADD CONSTRAINT `wisata_ibfk_1` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
