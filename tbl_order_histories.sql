-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 26, 2020 at 05:45 AM
-- Server version: 10.3.16-MariaDB
-- PHP Version: 7.3.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `medicalbrows_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_order_histories`
--

CREATE TABLE `tbl_order_histories` (
  `id` int(10) UNSIGNED NOT NULL,
  `clinic_id` int(11) DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `rank_id` int(11) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `menu_id` int(11) DEFAULT NULL,
  `interviewer_id` int(11) DEFAULT NULL,
  `interview_start` time DEFAULT NULL,
  `interview_end` time DEFAULT NULL,
  `amount` float DEFAULT NULL,
  `discount` float DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `order_route` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `staff_choosed` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rank_schedule_id` int(11) DEFAULT NULL,
  `order_type` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_deleted` tinyint(4) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `order_date` date DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_serial_id` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_order_histories`
--
ALTER TABLE `tbl_order_histories`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_order_histories`
--
ALTER TABLE `tbl_order_histories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
