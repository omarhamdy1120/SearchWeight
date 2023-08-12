-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 15, 2023 at 12:39 PM
-- Server version: 8.0.33
-- PHP Version: 8.1.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `adamdesign_simple`
--

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `product_id` int NOT NULL DEFAULT '0',
  `weight` float NOT NULL DEFAULT '0',
  `McRetail` float NOT NULL,
  `ReturnRetail` varchar(255) NOT NULL DEFAULT '0',
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_id`, `weight`, `McRetail`, `ReturnRetail`) VALUES
(1, 1, 0.25, 80, 40),
(2, 2, 0.5, 80, 40),
(3, 3, 1, 80, 40),
(4, 4, 2.5, 165, 65),
(5, 5, 5, 300, 130),
(6, 6, 10, 600, 260),
(7, 7, 15.55, 917.45, 404.3),
(8, 8, 20, 1120, 520),
(9, 9, 31.1, 1741.6, 808.6),
(10, 10, 50, 2775, 1300),
(11, 11, 100, 5500, 2600),
(12, 12, 116.65, 2974.575, 1750),
(13, 13, 250, 7750, 3250),
(14, 14, 500, 12000, 6000),
(15, 15, 1000, 23000, 12000);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);


--
--
-- AUTO_INCREMENT for table `products`
--

ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
--
-- AUTO_INCREMENT for table `selected_sell`
--

