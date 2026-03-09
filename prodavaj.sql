-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 09, 2026 at 01:10 AM
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
-- Database: `prodavaj`
--

-- --------------------------------------------------------

--
-- Table structure for table `listings`
--

CREATE TABLE `listings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `category` varchar(100) NOT NULL,
  `subcategory` varchar(100) DEFAULT NULL,
  `brand` varchar(50) NOT NULL,
  `model` varchar(50) NOT NULL,
  `version` varchar(50) DEFAULT NULL,
  `year` int(11) NOT NULL,
  `fuel` enum('Petrol','Diesel','Hybrid','Electric','LPG') NOT NULL,
  `transmission` enum('Manual','Automatic') NOT NULL,
  `mileage` varchar(20) DEFAULT NULL,
  `power` int(11) NOT NULL,
  `city` varchar(50) NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `currency` enum('MKD','EUR') NOT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`images`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `emission_class` varchar(20) DEFAULT NULL,
  `body_type` varchar(30) DEFAULT NULL,
  `color` varchar(20) DEFAULT NULL,
  `registration` varchar(20) DEFAULT NULL,
  `registration_until` date DEFAULT NULL,
  `is_premium` tinyint(1) DEFAULT 0,
  `premium_until` date DEFAULT NULL,
  `contact_phone` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `listings`
--

INSERT INTO `listings` (`id`, `user_id`, `title`, `description`, `category`, `subcategory`, `brand`, `model`, `version`, `year`, `fuel`, `transmission`, `mileage`, `power`, `city`, `price`, `currency`, `images`, `created_at`, `emission_class`, `body_type`, `color`, `registration`, `registration_until`, `is_premium`, `premium_until`, `contact_phone`) VALUES
(8, 1, 'maj', 'kkk', 'Моторни возила', 'Автомобили', 'BMW', '3 Series 316d', '', 5, 'Petrol', 'Manual', '22', 5, 'Скопје', 9.00, 'EUR', '[\"1769823301_1401.png\",\"1769823301_4232.png\",\"1769823301_7618.png\",\"1769823301_1305.png\",\"1769823301_3526.png\",\"1769823301_7776.png\",\"1769823301_8717.png\",\"1769823301_4207.png\"]', '2026-01-31 01:35:01', NULL, NULL, NULL, NULL, NULL, 1, '2026-02-27', NULL),
(9, 1, 'BMW 320GT', 'top kola', 'Моторни возила', 'Автомобили', 'BMW', '3-Series 320 GT', NULL, 2014, 'Diesel', 'Automatic', '150000-200000', 135, 'Велес', 13000.00, 'EUR', '[\"1770552805_1317.png\",\"1770552805_7138.png\",\"1770552805_9823.png\",\"1770552805_8354.png\",\"1770552805_4315.png\",\"1770552805_4403.png\",\"1770552805_9826.png\",\"1770552805_4421.png\"]', '2026-02-08 12:13:25', 'Euro 6', 'Седан', 'Сина', 'FOREIGN', '0000-00-00', 1, '2026-02-22', NULL),
(10, 1, '9', '9', 'Моторни возила', 'Автомобили', 'Abarth', '500 ', NULL, 2025, 'Diesel', 'Automatic', '350000-400000', 99, 'Битола', 9.00, 'EUR', '[\"1771266602_6154.png\"]', '2026-02-16 18:30:02', 'Euro 4', 'Комбе', 'Портокалова', 'MK', '0000-00-00', 1, '2026-03-13', '+38978944941');

-- --------------------------------------------------------

--
-- Table structure for table `listing_attributes`
--

CREATE TABLE `listing_attributes` (
  `id` int(11) NOT NULL,
  `listing_id` int(11) NOT NULL,
  `attr_key` varchar(100) NOT NULL,
  `attr_value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `balance` decimal(12,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `balance`) VALUES
(1, 'Gjoko', '$2y$10$npeJ1bz5HhUTRAQun/48I.ZK6BUXzI5dvs9XooM/4htTVznac2yNO', 7550.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `listings`
--
ALTER TABLE `listings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `listing_attributes`
--
ALTER TABLE `listing_attributes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_listing` (`listing_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `listings`
--
ALTER TABLE `listings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `listing_attributes`
--
ALTER TABLE `listing_attributes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `listings`
--
ALTER TABLE `listings`
  ADD CONSTRAINT `listings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `listing_attributes`
--
ALTER TABLE `listing_attributes`
  ADD CONSTRAINT `fk_listing` FOREIGN KEY (`listing_id`) REFERENCES `listings` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
